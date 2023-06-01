<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\MovimientoCaja;
use App\Models\PasoReserva;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\ServicioReserva;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use App\Models\Accesorio;
use App\Models\DepartamentoAccesorio;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use PhpParser\Node\Expr\Empty_;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
    }

    public function reserva($url_departamento)
    {
        if (!empty(auth()->user()->id_reserva) && auth()->user()->id_paso_reserva < 4) {
            return  redirect('/reserva/' . sha1(auth()->user()->id_reserva))->with(['danger_message' => 'Usted ya posee una Reserva en Curso. Termine esta antes de empezar otra'])->with(['danger_message_title' => 'Gestión de Reserva']);
        }
        $departamento = Departamento::where('url_departamento', $url_departamento)->where('deleted', 'N')->first();

        if (empty($departamento)) {
            return  redirect('/')->with(['danger_message' => 'Departamento no existe o Desahabilitado'])->with(['danger_message_title' => 'Departamento No Encontrado']);
        }
        if ($departamento->estado_departamento->permite_reservar == 'N') {
            return  redirect('/')->with(['danger_message' => 'Departamento no puede ser Reservado debido a que fue deshabilitado por el Administrador'])->with(['danger_message_title' => 'Departamento Deshabilitado']);
        }
        $accesorios = $this->getAccesorios($departamento->id_departamento);
        $fechas = $this->getFechaOcupadas($departamento->id_departamento);
        $title = 'Nueva Reserva';
        $action = '/departamentos/' . $departamento->url_departamento . '/reservar';
        return view('landing.reserva.reserva_nueva', compact(
            'title',
            'action',
            'departamento',
            'fechas',
            'accesorios'
        ));
    }





    public function storeReserva($url_departamento, Request $request)
    {
        $departamento = Departamento::where('url_departamento', $url_departamento)->where('deleted', 'N')->first();

        if (empty($departamento)) {
            return   back()->with(['danger_message' => 'Departamento no existe o Desahabilitado'])->with(['danger_message_title' => 'Departamento No Encontrado'])->withInput();
        }
        if ($departamento->estado_departamento->permite_reservar == 'N') {
            return   back()->with(['danger_message' => 'Departamento no puede ser Reservado debido a que fue deshabilitado por el Administrador'])->with(['danger_message_title' => 'Departamento Deshabilitado'])->withInput();
        }
        $this->validate($request, [
            'date_picker_llegada' => 'required|min:10',
            'date_picker_salida' => 'required|min:10',
            'nombre' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',

        ], [
            'date_picker_llegada.required' => 'Fecha Llegada Requerida',
            'date_picker_llegada.min' => 'Formato de Fecha Llegada Inválida',
            'date_picker_salida.required' => 'Fecha Salida Requerida',
            'date_picker_salida.min' => 'Formato de Fecha Salida Inválida',
            'nombre.required' => 'Nombre Requerido',
            'nombre.min' => 'Nombre debe tener Mínimo 3 Caracteres',
            'nombre.max' => 'Nombre debe tener Máximo 100 Caracteres',
            'email.required' => 'Correo electrónico Requerido',
            'email.email' => 'Formato de email Inválido',
            'email.max' => 'email debe tener Máximo 100 Caracteres',

        ]);

        if (auth()->user()->id_rol != 3) {
            return back()->with(['danger_message' => 'Lo sentimos. La Reserva de Departamentos es solo para Usuarios de Tipo Clientes'])->with(['danger_message_title' => 'Usuario no permitido'])->withInput();
        }

        $reserva = new Reserva();
        $reserva->inicio_reserva = !empty($request->date_picker_llegada) ? ordenar_fechaServidor($request->date_picker_llegada) : null;
        $reserva->final_reserva = !empty($request->date_picker_salida) ? ordenar_fechaServidor($request->date_picker_salida) : null;
        $reserva->id_cliente = auth()->user()->cliente[0]->id_cliente;
        $reserva->id_departamento = $departamento->id_departamento;
        $reserva->numero_huespedes = !empty($departamento->cantidad_huespedes) ? $departamento->cantidad_huespedes : 1;
        $reserva->nombre_reserva = $request->nombre;
        $reserva->email_reserva = strLower($request->email);
        $reserva->id_estado_reserva = 1;
        $reserva->id_paso_reserva = 2;

        $reserva->save();
        if ($reserva->id_reserva > 0) {
            $usuario = Usuarios::find(auth()->user()->id_usuario);
            $usuario->id_reserva = $reserva->id_reserva;
            $usuario->save();
            return redirect('reserva/' . sha1($reserva->id_reserva))->with(['success_message' => 'Se ha Iniciado Reserva Correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {

            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }


    public function reservaProcesoEdit($id_reserva)
    {
        $valida = $this->validaPasos($id_reserva);
        if ($valida->type == 'error') {
            return redirect("$valida->url")->with(['danger_message' => "$valida->msg"])->with(['danger_message_title' => "$valida->title"]);
        } else {
            $reserva_id = $valida->msg;
        }

        $reserva = Reserva::find($reserva_id);
        $departamento = Departamento::find($reserva->id_departamento);
        $servicios_comidas = [];
        $servicios_transporte = [];
        $servicios_tours = [];
        $servicios_reserva = [];


        switch ($reserva->id_paso_reserva) {
            case '1':
                $url_paso = '/paso-uno';
                $title = 'Selección de Fechas Reserva';
                break;
            case '2':
                $title = 'Contratación de Servicios';
                $url_paso = '/paso-dos';
                $servicios_comidas = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 41)->get();
                $servicios_transporte = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 1)->get();
                $servicios_tours = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 21)->get();
                $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
                break;
            case '3':
                $title = 'Pago de Reserva';
                $url_paso = '/paso-tres';
                $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
                break;
            case '4':
                return redirect('/reserva/' . $reserva->id_reserva . '/ver-detalle');
                break;
            default:
                $url_paso = '';
                break;
        }

        $dias_reserva =  diasEntreFechas($reserva->inicio_reserva, $reserva->final_reserva);
        $dias_reserva = $dias_reserva == 0 ? $dias_reserva = 1 : $dias_reserva;
        $dias_reserva_valor = $dias_reserva * $departamento->valor_arriendo;

        $action = '/reserva/' . $id_reserva . $url_paso;
        $fechas = $this->getFechaOcupadas($departamento->id_departamento, $reserva->id_reserva);

        return view('landing.reserva.reserva_paso_' . $reserva->id_paso_reserva, compact(
            'title',
            'action',
            'reserva',
            'dias_reserva',
            'dias_reserva_valor',
            'departamento',
            'servicios_comidas',
            'servicios_transporte',
            'servicios_tours',
            'servicios_reserva',
            'fechas',
        ));
    }



    public function reservaProcesoUpdatePaso1($id_reserva, Request $request)
    {
        $valida = $this->validaPasos($id_reserva);
        if ($valida->type == 'error') {
            return redirect("$valida->url")->with(['danger_message' => "$valida->msg"])->with(['danger_message_title' => "$valida->title"]);
        } else {
            $reserva_id = $valida->msg;
        }
        $reserva = Reserva::find($reserva_id);
        $departamento = Departamento::find($reserva->id_departamento);
        $this->validate($request, [
            'date_picker_llegada' => 'required|min:10',
            'date_picker_salida' => 'required|min:10',
            'nombre' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',

        ], [
            'date_picker_llegada.required' => 'Fecha Llegada Requerida',
            'date_picker_llegada.min' => 'Formato de Fecha Llegada Inválida',
            'date_picker_salida.required' => 'Fecha Salida Requerida',
            'date_picker_salida.min' => 'Formato de Fecha Salida Inválida',
            'nombre.required' => 'Nombre Requerido',
            'nombre.min' => 'Nombre debe tener Mínimo 3 Caracteres',
            'nombre.max' => 'Nombre debe tener Máximo 100 Caracteres',
            'email.required' => 'Correo electrónico Requerido',
            'email.email' => 'Formato de email Inválido',
            'email.max' => 'email debe tener Máximo 100 Caracteres',
        ]);

        if (auth()->user()->id_rol != 3) {
            return back()->with(['danger_message' => 'Lo sentimos. La Reserva de Departamentos es solo para Usuarios de Tipo Clientes'])->with(['danger_message_title' => 'Usuario no permitido'])->withInput();
        }
        $fechas = $this->getFechaOcupadas($departamento->id_departamento, $reserva->id_reserva);
        $fechas_reservadas = $fechas['fechas'];
        $reserva->inicio_reserva = !empty($request->date_picker_llegada) ? ordenar_fechaServidor($request->date_picker_llegada) : null;
        if (in_array($reserva->inicio_reserva, $fechas_reservadas)) {
            return  back()->with(['danger_message' => 'La fecha de Inicio Indicada ya no está disponible. Corrija e intente de nuevo'])->with(['danger_message_title' => 'Fecha de Inicio ya Reservada'])->withInput();
        }
        $reserva->final_reserva = !empty($request->date_picker_salida) ? ordenar_fechaServidor($request->date_picker_salida) : null;
        if (in_array($reserva->final_reserva, $fechas_reservadas)) {
            return  back()->with(['danger_message' => 'La fecha de Fin Indicada ya no está disponible. Corrija e intente de nuevo'])->with(['danger_message_title' => 'Fecha de Fin ya Reservada'])->withInput();
        }
        $reserva->numero_huespedes = !empty($departamento->cantidad_huespedes) ? $departamento->cantidad_huespedes : 1;
        $reserva->id_paso_reserva = 2;
        $reserva->nombre_reserva = $request->nombre;
        $reserva->email_reserva = strLower($request->email);
        $usuario = Usuarios::find(auth()->user()->id_usuario);
        $usuario->id_paso_reserva = 2;
        $usuario->save();
        if ($reserva->save()) {
            return redirect('reserva/' . sha1($reserva->id_reserva))->with(['success_message' => 'Se ha modificado Reserva Correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }



    public function reservaProcesoUpdatePaso2($id_reserva, Request $request)
    {

        $valida = $this->validaPasos($id_reserva);
        if ($valida->type == 'error') {
            return redirect("$valida->url")->with(['danger_message' => "$valida->msg"])->with(['danger_message_title' => "$valida->title"]);
        } else {
            $reserva_id = $valida->msg;
        }
        $reserva = Reserva::find($reserva_id);

        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
        if ($servicios_reserva->count() > 0) {
            foreach ($servicios_reserva as $s) {
                $servicio_val = Servicio::find($s->id_servicio);
                if (!empty($servicio_val)) {
                    $servicio_val->cantidad = $servicio_val->cantidad + (int)$s->cantidad;
                    $servicio_val->save();
                }
                $servicio_reserva_deleted = ServicioReserva::where('id', $s->id)->delete();
            }
        }

        if (!empty($request->servicios_comidas)) {
            foreach ($request->servicios_comidas as $key) {
                $servicio_comida_val = Servicio::where('id_servicio', $key)->where('deleted', 'N')->where('estado', 'Y')->first();
                if (!empty($servicio_comida_val)) {
                    $cantidad_servicio_comida = $request->cantidad[$key];
                    $cantidad_servicio_comida = (int)$cantidad_servicio_comida;
                    if ($cantidad_servicio_comida > 0 && $servicio_comida_val->cantidad >= $cantidad_servicio_comida) {
                        $servicio_comida = new ServicioReserva();
                        $servicio_comida->id_servicio = $servicio_comida_val->id_servicio;
                        $servicio_comida->id_reserva = $reserva->id_reserva;
                        $servicio_comida->precio = $servicio_comida_val->precio;
                        $servicio_comida->cantidad = $cantidad_servicio_comida;
                        if ($servicio_comida->save()) {
                            $servicio_comida_val->cantidad = $servicio_comida_val->cantidad - $cantidad_servicio_comida;
                            $servicio_comida_val->save();
                        }
                    }
                }
            }
        }

        if (!empty($request->servicios_transporte)) {
            foreach ($request->servicios_transporte as $key) {
                $servicios_transporte_val = Servicio::where('id_servicio', $key)->where('deleted', 'N')->where('estado', 'Y')->first();
                if (!empty($servicios_transporte_val)) {
                    $cantidad_servicios_transporte = $request->cantidad[$key];
                    $cantidad_servicios_transporte = (int)$cantidad_servicios_transporte;
                    if ($cantidad_servicios_transporte > 0 && $servicios_transporte_val->cantidad >= $cantidad_servicios_transporte) {
                        $servicios_transporte = new ServicioReserva();
                        $servicios_transporte->id_servicio = $servicios_transporte_val->id_servicio;
                        $servicios_transporte->id_reserva = $reserva->id_reserva;
                        $servicios_transporte->precio = $servicios_transporte_val->precio;
                        $servicios_transporte->cantidad = $cantidad_servicios_transporte;
                        if ($servicios_transporte->save()) {
                            $servicios_transporte_val->cantidad = $servicios_transporte_val->cantidad - $cantidad_servicios_transporte;
                            $servicios_transporte_val->save();
                        }
                    }
                }
            }
        }

        if (!empty($request->servicios_tours)) {
            foreach ($request->servicios_tours as $key) {
                $servicios_tours_val = Servicio::where('id_servicio', $key)->where('deleted', 'N')->where('estado', 'Y')->first();
                if (!empty($servicios_tours_val)) {
                    $cantidad_servicios_tours = $request->cantidad[$key];
                    $cantidad_servicios_tours = (int)$cantidad_servicios_tours;
                    if ($cantidad_servicios_tours > 0 && $servicios_tours_val->cantidad >= $cantidad_servicios_tours) {
                        $servicios_tours = new ServicioReserva();
                        $servicios_tours->id_servicio = $servicios_tours_val->id_servicio;
                        $servicios_tours->id_reserva = $reserva->id_reserva;
                        $servicios_tours->precio = $servicios_tours_val->precio;
                        $servicios_tours->cantidad = $cantidad_servicios_tours;
                        if ($servicios_tours->save()) {
                            $servicios_tours_val->cantidad = $servicios_tours_val->cantidad - $cantidad_servicios_tours;
                            $servicios_tours_val->save();
                        }
                    }
                }
            }
        }


        $reserva->id_paso_reserva = 3;
        $usuario = Usuarios::find(auth()->user()->id_usuario);
        $usuario->id_paso_reserva = 3;
        $usuario->save();
        if ($reserva->save()) {
            return redirect('reserva/' . sha1($reserva->id_reserva))->with(['success_message' => 'Se ha modificado Reserva Correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }
    public function reservaProcesoUpdatePaso3($id_reserva, Request $request)
    {
        $valida = $this->validaPasos($id_reserva);
        if ($valida->type == 'error') {
            return redirect("$valida->url")->with(['danger_message' => "$valida->msg"])->with(['danger_message_title' => "$valida->title"]);
        } else {
            $reserva_id = $valida->msg;
        }
        $reserva = Reserva::find($reserva_id);
        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
        $departamento = Departamento::find($reserva->id_departamento);
        # SE OBTIENEN DÍAS DE RESERVAS
        $dias_reserva =  diasEntreFechas($reserva->inicio_reserva, $reserva->final_reserva);
        $dias_reserva = $dias_reserva == 0 ?  1 : $dias_reserva;
        $dias_reserva_valor = $dias_reserva * $departamento->valor_arriendo;

        #SE OBTIENEN SERVICIOS Y COSTOS DE SERVICIOS
        $sum_total_servicios = 0;
        $cantidad = 0;
        foreach ($servicios_reserva as $s) {
            $cantidad = $cantidad + ((int)$s->cantidad);
            $sum_total_servicios = (int)$s->precio *  (int)$s->cantidad;
        }

        $total_servicio = $sum_total_servicios;

        $total_reserva = $dias_reserva_valor + $total_servicio;
        $diferencia_pago = 0;
        #SE OBTIENE PAGO REALIZADO (TOTAL/PARCIAL)
        if ($request->tipo_pago == 2) {
            $total_pagado = round($total_reserva / 2);
            $diferencia_pago = round($total_reserva - $total_pagado);
        } else {
            $total_pagado = $total_reserva;
        }


        #SE OBTIENEN DATOS DEL CLIENTE PARA ENVÍO DE PDF
        $cliente  = Cliente::find($reserva->id_cliente);
        if (empty($reserva->nombre_reserva)) {
            $reserva->nombre_reserva = $cliente->nombre . ' ' . $cliente->apellido;
            $nombre_cliente = $cliente->nombre . ' ' . $cliente->apellido;
        } else {
            $nombre_cliente = $reserva->nombre_reserva;
        }

        if (empty($reserva->email_reserva)) {
            $reserva->email_reserva = $cliente->email;
            $email_cliente = $cliente->email;
        } else {
            $email_cliente = $reserva->email_reserva;
        }



        $reserva->total_reserva = $total_reserva;
        $reserva->total_pagado = $total_pagado;
        $reserva->diferencia_pago = $diferencia_pago;
        $reserva->id_paso_reserva = 4;
        $reserva->cantidad_noches = $dias_reserva;
        $reserva->valor_noche = $departamento->valor_arriendo;
        if ($reserva->save()) {
            #se genera movimiento
            $movimiento = new MovimientoCaja();
            $movimiento->origen = 'RESERVA';
            $movimiento->total = $total_pagado;
            $movimiento->nro_reserva = $reserva_id;
            $movimiento->id_tipo_movimiento_caja = 1;
            $movimiento->id_usuario = auth()->user()->id_usuario;
            $movimiento->save();
            $usuario = Usuarios::find(auth()->user()->id_usuario);
            $usuario->id_paso_reserva = 4;
            $usuario->save();
            $this->downloadPdf($reserva->id_reserva, 'send');
            return redirect('reserva/' . ($reserva->id_reserva) . '/ver-detalle')->with(['success_message' => 'Se ha realizado el pago de su Reserva Correctamente'])->with(['success_message_title' => 'Reserva Completada']);
        } else {
            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Procesar el Pago Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error en Pago'])->withInput();
        }
    }

    public function reservaProcesoPaso4($id_reserva)
    { #ver detalle
        if (!is_numeric($id_reserva) || $id_reserva < 1) {
            return redirect("/reserva/$id_reserva");
        }
        $reserva = Reserva::find($id_reserva);
        if (empty($reserva)) {
            return redirect("/")->with(['danger_message' => "Reserva no existe o fue eliminada"])->with(['danger_message_title' => "Reserva No encontrada"]);
        } elseif ($reserva->id_paso_reserva != 4) {
            $url_paso = $reserva->id_paso_reserva == 1 ? '/paso-uno' : ($reserva->id_paso_reserva == 1 ? '/paso-dos' : '/paso-tres');
            return redirect('/reserva/' . sha1($id_reserva))->with(['danger_message' => "Reserva aún no ha sido finalizada. Corrija e intente de nuevo"])->with(['danger_message_title' => "Gestión de Reservas"]);
        } else {
            $title = 'Detalle de Reserva N°' . $id_reserva;
            $departamento = Departamento::find($reserva->id_departamento);
            $actualizar_reserva = false;

            if (empty($reserva->cantidad_noches) || $reserva->cantidad_noches < 1) {
                $cantidad_noches =  diasEntreFechas($reserva->inicio_reserva, $reserva->final_reserva); #CANTIDAD DE NOCHES
                $cantidad_noches = $cantidad_noches == 0 ? 1 : $cantidad_noches;
                $reserva->cantidad_noches = $cantidad_noches;
                $actualizar_reserva = true;
            } else {
                $cantidad_noches = $reserva->cantidad_noches;
                $cantidad_noches = $cantidad_noches == 0 ? 1 : $cantidad_noches;
            }

            if (empty($reserva->valor_noche) || $reserva->valor_noche < 1) {
                $reserva->valor_noche = $departamento->valor_arriendo;
                $valor_total_noches = $cantidad_noches * $departamento->valor_arriendo;
                $valor_noche = $departamento->valor_arriendo;
                $actualizar_reserva = true;
            } else {
                $valor_total_noches = $reserva->valor_noche * $cantidad_noches;
                $valor_noche = $reserva->valor_noche;
            }


            if ($actualizar_reserva) {
                $reserva->save();
            }
            $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
            $total_servicios = 0;
            $total_servicios_cantidad = 0;
            foreach ($servicios_reserva as $s) {
                $total_servicios = $total_servicios + ($s->precio * $s->cantidad);
                $total_servicios_cantidad = $total_servicios_cantidad + $s->cantidad;
            }
            return view('landing.reserva.reserva_paso_4', compact(
                'title',
                'reserva',
                'departamento',
                'cantidad_noches',
                'valor_total_noches',
                'valor_noche',
                'servicios_reserva',
                'total_servicios',
                'total_servicios_cantidad',
            ));
        }
    }

    public function volverPasoReserva($id_reserva)
    {

        $valida = $this->validaPasos($id_reserva);
        if ($valida->type == 'error') {
            return redirect("$valida->url")->with(['danger_message' => "$valida->msg"])->with(['danger_message_title' => "$valida->title"]);
        } else {
            $reserva_id = $valida->msg;
        }

        $reserva = Reserva::find($reserva_id);
        $estado_reserva = $reserva->id_paso_reserva;
        if ($estado_reserva == 1) {

            return   back()->with(['danger_message' => 'Reserva ya está en paso n° 1'])->with(['danger_message_title' => 'Gestión de Reservas']);
        } elseif ($estado_reserva == 4) {

            return   redirect('/')->with(['danger_message' => 'Reserva ya ha sido procesada. Revise su listado de Reservas para obtener más información'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        $estado_reserva = $estado_reserva - 1;
        $paso_reserva = PasoReserva::find($estado_reserva);
        $reserva->id_paso_reserva = $estado_reserva;
        $usuario = Usuarios::find(auth()->user()->id_usuario);
        $usuario->id_paso_reserva = $estado_reserva;
        $usuario->save();
        if ($reserva->save()) {
            return redirect('reserva/' . sha1($reserva->id_reserva))->with(['success_message' => 'Se ha devuelta a ' . $paso_reserva->paso_reserva])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return   back()->with(['danger_message' => 'Reserva no se pudo actualizar'])->with(['danger_message_title' => 'Error Interno']);
        }
    }


    public function validaPasos($id_reserva)
    {

        if (empty(auth()->user()->id_reserva)) {
            $resp = [
                'type' => 'error',
                'url' => '/',
                'msg' => 'Usted no posee una Reserva en Curso',
                'title' => 'Reserva No encontrada',
            ];
        } else {
            $reserva_usuario = sha1(auth()->user()->id_reserva);
            if ($id_reserva != $reserva_usuario) {
                $resp = [
                    'type' => 'error',
                    'url' => '/',
                    'msg' => 'Reserva buscada no corresponde a la Reserva en Curso',
                    'title' => 'Reserva No encontrada',
                ];
            } else {
                $reserva = Reserva::find(auth()->user()->id_reserva);
                if (empty($reserva)) {
                    $resp = [
                        'type' => 'error',
                        'url' => '/',
                        'msg' => 'Reserva no existe o fue eliminada',
                        'title' => 'Reserva No encontrada',
                    ];
                } elseif ($reserva->id_estado_reserva != 1) {
                    $resp = [
                        'type' => 'error',
                        'url' => '/',
                        'msg' => 'Reserva ya ha sido procesada. Revise su listado de Reservas para obtener más información',
                        'title' => 'Gestión de Reservas',
                    ];
                } else {
                    $departamento = Departamento::where('id_departamento', $reserva->id_departamento)->where('deleted', 'N')->first();
                    if (empty($departamento)) {
                        $resp = [
                            'type' => 'error',
                            'url' => '/',
                            'msg' => 'Departamento no existe o Desahabilitado',
                            'title' => 'Departamento No Encontrado',
                        ];
                    } elseif ($departamento->estado_departamento->permite_reservar == 'N') {
                        $resp = [
                            'type' => 'error',
                            'url' => '/',
                            'msg' => 'Departamento no puede ser Reservado debido a que fue deshabilitado por el Administrador',
                            'title' => 'Departamento Deshabilitado',
                        ];
                    } else {
                        $resp = [
                            'type' => 'success',
                            'msg' => $reserva->id_reserva
                        ];
                    }
                }
            }
        }
        $resp = (object)$resp;
        return $resp;
    }





    function getFechaOcupadas($id_departamento, $id_reserva = 0)
    {
        if ($id_reserva == 0) {
            $reservas = Reserva::where('id_departamento', $id_departamento)->where('id_estado_reserva', '<', 6)->get();
        } else {
            $reservas = Reserva::where('id_departamento', $id_departamento)->where('id_estado_reserva', '<', 6)->where('id_reserva', '!=', $id_reserva)->get();
        }

        $fechas = [];
        foreach ($reservas as $reserva) {
            $fecha_desde = date("Y-m-d", strtotime($reserva->inicio_reserva));
            $fecha_hasta = date("Y-m-d", strtotime($reserva->final_reserva));
            for ($i = $fecha_desde; $i <= $fecha_hasta; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {
                $fechas[] = $i;
            }
        }
        $fecha_texto = "";
        foreach ($fechas as $f) {
            $f = ordenar_fechaHumano($f);
            $fecha_texto .= "'$f',";
        }
        if (!empty($fecha_texto)) {
            $fecha_texto = substr($fecha_texto, 0, -1);
        }
        $data = [
            'fechas' => ($fechas),
            'fecha_texto' => $fecha_texto,
        ];
        return $data;
    }

    public function downloadPdf($id_reserva, $accion = 'download')
    {
        if (!is_numeric($id_reserva) || $id_reserva < 1) {
            return back()->with(['danger_message' => 'Reserva no existe o fue eliminada'])->with(['danger_message_title' => 'Reserva No encontrada']);
        }
        $reserva = Reserva::where('id_reserva', $id_reserva)->where('id_cliente', auth()->user()->cliente[0]->id_cliente)->first();
        if (empty($reserva)) {
            return back()->with(['danger_message' => 'Reserva no existe o fue eliminada'])->with(['danger_message_title' => 'Reserva No encontrada']);
        }
        if ($reserva->id_paso_reserva != 4) {
            return back()->with(['danger_message' => 'Complete su reserva para descargar comprobante'])->with(['danger_message_title' => 'Reserva Incompleta']);
        }
        $departamento = Departamento::find($reserva->id_departamento);
        $actualizar_reserva = false;
        if (empty($reserva->cantidad_noches) || $reserva->cantidad_noches < 1) {
            $cantidad_noches =  diasEntreFechas($reserva->inicio_reserva, $reserva->final_reserva); #CANTIDAD DE NOCHES
            $cantidad_noches = $cantidad_noches == 0 ? 1 : $cantidad_noches;
            $reserva->cantidad_noches = $cantidad_noches;
            $actualizar_reserva = true;
        } else {
            $cantidad_noches = $reserva->cantidad_noches;
            $cantidad_noches = $cantidad_noches == 0 ? 1 : $cantidad_noches;
        }

        if (empty($reserva->valor_noche) || $reserva->valor_noche < 1) {
            $reserva->valor_noche = $departamento->valor_arriendo;
            $valor_total_noches = $cantidad_noches * $departamento->valor_arriendo;
            $valor_noche = $departamento->valor_arriendo;
            $actualizar_reserva = true;
        } else {
            $valor_total_noches = $reserva->valor_noche * $cantidad_noches;
            $valor_noche = $reserva->valor_noche;
        }


        $cliente  = Cliente::find($reserva->id_cliente);
        if (empty($reserva->nombre_reserva)) {
            $reserva->nombre_reserva = $cliente->nombre . ' ' . $cliente->apellido;
            $nombre_cliente = $cliente->nombre . ' ' . $cliente->apellido;
            $actualizar_reserva = true;
        } else {
            $nombre_cliente = $reserva->nombre_reserva;
        }

        if (empty($reserva->email_reserva)) {
            $reserva->email_reserva = $cliente->email;
            $email_cliente = $cliente->email;
            $actualizar_reserva = true;
        } else {
            $email_cliente = $reserva->email_reserva;
        }
        if ($actualizar_reserva) {
            $reserva->save();
        }
        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
        $total_servicios = 0;
        $total_servicios_cantidad = 0;
        foreach ($servicios_reserva as $s) {
            $total_servicios = $total_servicios + ($s->precio * $s->cantidad);
            $total_servicios_cantidad = $total_servicios_cantidad + $s->cantidad;
        }
        $data = [
            'reserva' => $reserva,
            'departamento' => $departamento,
            'cantidad_noches' => $cantidad_noches,
            'valor_total_noches' => $valor_total_noches,
            'valor_noche' => $valor_noche,
            'servicios_reserva' => $servicios_reserva,
            'total_servicios' => $total_servicios,
            'total_servicios_cantidad' => $total_servicios_cantidad,
        ];
        $pdf = \PDF::loadView('landing.reserva.pdf_reserva', $data);
        if ($accion == 'send') {
            $data_email = [
                'numero_reserva' => $reserva->id_reserva,
                'nombre_cliente' => $nombre_cliente,
            ];
            #PRIMER PARAMETRO ES EL TEMPLATE
            #SEGUNDO PARAMETRO SON LAS VARIABLES A UTILIZAR EN EL TEMPLATE
            #TERCER PARAMETRO (FUNCTION) ES LA VARIABLE CON LA QUE SE LLAMARÁN LAS FUNCIONES DE ENVÍO
            #CUARTO PARAMETRO (USE) SON LAS VARIABLES QUE SE PODRÁN USAR DENTRO DEL ENVÍO
            Mail::send('emails/confirmacion_reserva', $data_email, function ($mail) use ($pdf, $reserva, $email_cliente) {
                $mail->from('contacto@designwebirg.com', 'Turismo Real');
                $mail->subject('CONFIRMACIÓN DE RESERVA N°' . $reserva->id_reserva);
                $mail->to("$email_cliente");
                $mail->attachData($pdf->output(), 'reserva_' . sha1($reserva->id_reserva) . '.pdf');
            });
        } else {
            return $pdf->download('reserva_' . sha1($reserva->id_reserva) . '.pdf');
            // return $pdf->stream('reserva_' . sha1($reserva->id_reserva) . '.pdf');
            // return view('landing.reserva.pdf_reserva', compact(
            //     'reserva',
            //     'departamento',
            //     'cantidad_noches',
            //     'valor_total_noches',
            //     'valor_noche',
            //     'servicios_reserva',
            //     'total_servicios',
            //     'total_servicios_cantidad',
            // ));
        }
    }

    public function cancelarReserva(Request $request)
    {
        // $request->id_cancel = '82';
        if (!empty($request->id_cancel)) {
            $id = $request->id_cancel;
            $reserva = Reserva::find($id);
            if (empty($reserva)) {
                return back()->with(['danger_message' => 'La Reserva no existe o fue Cancelada'])->with(['danger_message_title' => 'Reserva No encontrada']);
            } else {
                if ($reserva->id_estado_reserva == 6) {
                    return back()->with(['danger_message' => 'La Reserva ya fue Cancelada'])->with(['danger_message_title' => 'Reserva No encontrada']);
                } elseif ($reserva->id_estado_reserva != 6 && $reserva->id_estado_reserva >= 3) {
                    return back()->with(['danger_message' => 'La Reserva ya fue Cancelada'])->with(['danger_message_title' => 'Reserva No encontrada']);
                } else {
                    $cliente = Cliente::find($reserva->id_cliente);
                    if ($reserva->id_paso_reserva == 4) {
                        #se genera movimiento
                        $movimiento = new MovimientoCaja();
                        $movimiento->origen = 'RESERVA';
                        $movimiento->total = $reserva->total_pagado;
                        $movimiento->nro_reserva = $reserva->id_reserva;
                        $movimiento->id_tipo_movimiento_caja = 2;
                        $movimiento->id_usuario = auth()->user()->id_usuario;
                        $movimiento->save();
                       
                    }
                    $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
                    if ($servicios_reserva->count() > 0) {
                        foreach ($servicios_reserva as $s) {
                            $servicio_val = Servicio::find($s->id_servicio);
                            if (!empty($servicio_val)) {
                                $servicio_val->cantidad = $servicio_val->cantidad + (int)$s->cantidad;
                                $servicio_val->save();
                            }
                        }
                    }
                    $reserva->id_estado_reserva = 6;
                    $reserva->cancel_at = ahoraServidor();;
                    $reserva->id_usuario_cancel = auth()->user()->id_usuario;
                    if($reserva->save()){
                        $usuario = Usuarios::find($cliente->usuario->id_usuario);
                        $usuario->id_paso_reserva = null;
                        $usuario->id_reserva = null;
                        $usuario->save();
                        return redirect('/')->with(['success_message' => 'Reserva cancelada correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
                    }else{
                        return back()->with(['danger_message' => 'Ocurrió un error al cancelar reserva'])->with(['danger_message_title' => 'Error al Cancelar']);
                    }
                }
            }
        } else {
            return back()->with(['danger_message' => 'No se encuentra ID de Reserva'])->with(['danger_message_title' => 'Error al Cancelar']);
        }
    }

    public function getAccesorios($id_departamento){
        #SE OBTIENEN LOS ACCESORIOS ASOCIADOS AL DEPARTAMENTO
        $getAccesoriosDepartamento = DepartamentoAccesorio::where('id_departamento', $id_departamento)->get();
        $accesorios_departamento = []; # SE GENERA VARIABLE PARA ALMACENAR ID DEL ACCESORIO
        foreach ($getAccesoriosDepartamento as $key => $ad) {
            array_push($accesorios_departamento, $ad->id_accesorio);
        };

        #SE EXTRAEN ACCESORIOS DISPONIBLES
        $getAccesorios = Accesorio::where('deleted', 'N')->where('estado', 'Y')->orderBy('accesorio')->get();
        $accesorios = []; # SE GENERA VARIABLE PARA GUARDAR DATA DE LOS ACCESORIOS 
        foreach ($getAccesorios as  $a) { # SE RECORREN LAS ACCESORIOS
            #SE COMPARA ID_ACCESORIO CON EL ID_ACCESORIO DEL DEPARTAMENTO 
            if (in_array($a->id_accesorio, $accesorios_departamento)) { #SI ID DEL ACCESORIO ESTÁ EN EL ARRAY DE ACCESORIOS DEL DEPARTAMENTO ENTONCES SE MARCA CON CHECKED 1
                $b = [
                    'id_accesorio' => $a->id_accesorio,
                    'accesorio' => $a->accesorio,
                    'checked' => 1,
                    'icon' => 'fa fa-check',
                    'icon_color' => 'text-success',
                ];
            } else { #SI ID DEL ACCESORIO NO ESTÁ EN EL ARRAY DE LAS ACCESORIOS DEL DEPARTAMENTO ENTONCES SE MARCA CON CHECKED 0
                $b = [
                    'id_accesorio' => $a->id_accesorio,
                    'accesorio' => $a->accesorio,
                    'checked' => 0,
                    'icon' => 'fa fa-times',
                    'icon_color' => 'text-muted',
                ];
            }
            $accesorios[$a->id_accesorio] = $b; # SE GUARDA ARRAY DE DATA EN VARIABLE DE ACCESORIOS
        }

        return $accesorios;
    }
}
