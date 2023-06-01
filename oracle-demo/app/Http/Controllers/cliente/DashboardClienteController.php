<?php

namespace App\Http\Controllers\cliente;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;
use App\Models\CheckIn;
use App\Models\Cliente;
use App\Models\Comuna;
use App\Models\DepartamentoAccesorio;
use App\Models\Huesped;
use App\Models\HuespedReserva;
use App\Models\MovimientoCaja;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\ServicioReserva;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('isClient');
    }


    public function index()
    {
        if (auth()->user()->id_rol != 3) {
            return back()->with(['danger_message' => 'Cliente no posee permisos necesarios'])->with(['danger_message_title' => 'Error de Validación']);
        }
        $reservas = Reserva::where('id_cliente', auth()->user()->cliente[0]->id_cliente)->get();
        $reservas_totales = $reservas->count();
        $reservas_gastos_totales = 0;
        $reservas_devoluciones_totales = 0;
        foreach ($reservas as $reserva) {
            $movimientos = MovimientoCaja::where('nro_reserva', $reserva->id_reserva)->get();
            foreach ($movimientos as $m) {
                if ($m->id_tipo_movimiento_caja == 1) {
                    $reservas_gastos_totales = $reservas_gastos_totales + $m->total;
                } else {
                    $reservas_devoluciones_totales = $reservas_devoluciones_totales + $m->total;
                }
            }
        }
        $huespedes_totales = Huesped::where('id_cliente', auth()->user()->cliente[0]->id_cliente)->count();
        $side_dashboard = true;
        $title = 'Dashboard';
        return view('cliente.dashboard', compact(
            'title',
            'reservas_totales',
            'reservas_gastos_totales',
            'reservas_devoluciones_totales',
            'huespedes_totales',
            'side_dashboard'
        ));
    }

    public function listado()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $reservas = Reserva::where('id_cliente', auth()->user()->cliente[0]->id_cliente)->get();
        foreach ($reservas as $r) {
            $r->cantidad =  HuespedReserva::where('id_reserva', $r->id_reserva)->where('id_huesped', '!=', null)->count();
        }
        $title = 'Mis Reservas';
        $title_list = 'Listado de Reservas';
        $side_reservas = true;
        return view('cliente.mis_reservas.mis_reservas_listado', compact(
            'title',
            'title_list',
            'side_reservas',
            'reservas',
        ));
    }


    public function cancelarReserva(Request $request)
    {
        // $request->id_cancel = '85';
        if (!empty($request->id_cancel)) {
            $id = $request->id_cancel;
            $reserva = Reserva::find($id);
            if (empty($reserva)) {
                echo 'Reserva no existe o fue Cancelada';
            } else {
                $fecha_actual = date('Y-m-d');
                $dias = diasEntreFechas($fecha_actual, $reserva->inicio_reserva);
                if ($dias < 2) {
                    echo 'La Reserva solo puede ser Cancelada 48 antes de la FECHA DE LLEGADA';
                } else {
                    if ($reserva->id_estado_reserva == 6) {
                        echo 'Reserva ya fue Cancelada';
                    } elseif ($reserva->id_estado_reserva != 6 && $reserva->id_estado_reserva >= 3) {
                        echo 'Reserva procesadas no pueden ser canceladas';
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
                        if ($reserva->save()) {
                            $usuario = Usuarios::find($cliente->usuario->id_usuario);
                            $usuario->id_paso_reserva = null;
                            $usuario->id_reserva = null;
                            $usuario->save();
                            echo 'ok';
                        } else {
                            echo 'No se ha podido cancelar reserva';
                        }
                    }
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }

    public function generarCheckIn($id_reserva, Request $request)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::where('id_reserva', $id_reserva)->where('id_estado_reserva',  3)->first();

        if (empty($reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o ya ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        if (isset($request->form) && $request->form == 1) {


            $check_in = CheckIn::find($reserva->id_check_in);
            $check_in->updated_at = ahoraServidor();
            if ($check_in->save()) {
                $reserva->id_estado_reserva = 4;
                if ($reserva->save()) {
                    return redirect('/gestion-reservas/reservas')->with(['success_message' => 'Se ha Confirmado CheckIn Correctamente.'])->with(['success_message_title' => 'Gestión de Reservas']);
                } else {
                    return  back()->with(['danger_message' => 'Ha Ocurrido un error al Confirmar CheckIn de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                }
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Confirmar CheckIn de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }
        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();
        $huespedes = HuespedReserva::where('id_reserva', $id_reserva)->get();
        $total_servicios = 0;
        $total_servicios_cantidad = 0;
        foreach ($servicios_reserva as $s) {
            $total_servicios = $total_servicios + ($s->precio * $s->cantidad);
            $total_servicios_cantidad = $total_servicios_cantidad + $s->cantidad;
        }



        #SE OBTIENEN LOS ACCESORIOS ASOCIADOS AL DEPARTAMENTO
        $getAccesoriosDepartamento = DepartamentoAccesorio::where('id_departamento', $reserva->id_departamento)->get();
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
                ];
                $accesorios[$a->id_accesorio] = $b; # SE GUARDA ARRAY DE DATA EN VARIABLE DE ACCESORIOS
            }
        }
      
        $condiciones = $accesorios;
        if (!empty($reserva->id_check_in)) {
            $checkIn = CheckIn::find($reserva->id_check_in);
            if (!empty($checkIn) && !empty($checkIn->condiciones)) {
                $condiciones_check = json_decode($checkIn->condiciones);
                $condiciones = [];
                foreach ($accesorios as $accesorio) {
                    $accesorio = (object)$accesorio;
                    if (in_array($accesorio->id_accesorio, $condiciones_check)) { #SI ID DEL ACCESORIO ESTÁ EN EL ARRAY DE ACCESORIOS DEL DEPARTAMENTO ENTONCES SE MARCA CON CHECKED 1
                        $c = [
                            'id_accesorio' => $accesorio->id_accesorio,
                            'accesorio' => $accesorio->accesorio,
                            'checked' => 1,
                        ];
                    } else {
                        $c = [
                            'id_accesorio' => $accesorio->id_accesorio,
                            'accesorio' => $accesorio->accesorio,
                            'checked' => 0,
                        ];
                    }
                    $condiciones[$accesorio->id_accesorio] = $c; # SE GUARDA ARRAY DE DATA EN VARIABLE DE ACCESORIOS
                }
            }
        }


        $title = 'Check-In Reserva N°' . $id_reserva;
        $title_form = 'Formulario de Check-In';
        $action = '/gestion-reservas/reservas/' . $id_reserva . '/check_in';
        $action_pago = '/gestion-reservas/reservas/' . $id_reserva . '/pago_check_in';
        $url_volver_listado = '/gestion-reservas/reservas';
        $volver_listado = 'Mis Reservas';
        $side_reservas = true;
        return view('cliente.mis_reservas.check_in_cliente', compact(
            'title',
            'title_form',
            'side_reservas',
            'action',
            'action_pago',
            'url_volver_listado',
            'volver_listado',
            'reserva',
            'servicios_reserva',
            'total_servicios',
            'total_servicios_cantidad',
            'huespedes',
            'condiciones'
        ));
    }

    public function generarCheckInPago($id_reserva, Request $request)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::find($id_reserva);
        if (empty($reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        if ($reserva->diferencia_pago == 0) {
            return redirect('/gestion-reservas/reservas/' . $id_reserva . '/check_in')->with(['danger_message' => 'Reserva no posee monto pendiente a pagar'])->with(['danger_message_title' => 'No posee Pago Pendiente']);
        }

        $total_pagado = $reserva->total_pagado + $reserva->diferencia_pago;
        $reserva->total_pagado = $total_pagado;
        $reserva->diferencia_pago = 0;
        if ($reserva->save()) {
            #se genera movimiento
            $movimiento = new MovimientoCaja();
            $movimiento->origen = 'CHECK-IN';
            $movimiento->total = $total_pagado;
            $movimiento->nro_reserva = $id_reserva;
            $movimiento->id_tipo_movimiento_caja = 1;
            $movimiento->id_usuario = auth()->user()->id_usuario;
            $movimiento->save();
            return redirect('/gestion-reservas/reservas/' . $id_reserva . '/check_in')->with(['success_message' => 'Se han Generado Pago de Diferencia Correctamente.'])->with(['success_message_title' => 'Pago Realiazado']);
        } else {
            return redirect('/gestion-reservas/reservas/' . $id_reserva . '/check_in')->with(['danger_message' => 'Ha ocurrido un error al generar Pago. Recargue e intente de nuevo'])->with(['danger_message_title' => 'Error en Pago ']);
        }
    }



    public function createServices($id_reserva)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::where('id_reserva', $id_reserva)->where('id_estado_reserva', '<', 5)->first();

        if (empty($reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o ya ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $servicios_comidas = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 41)->get();
        $servicios_transporte = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 1)->get();
        $servicios_tours = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', 21)->get();
        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();

        $cantidad_huespedes = $reserva->numero_huespedes;



       
        $title = 'Reserva N°' . $id_reserva;
        $title_form = 'Contratación de Servicios';
        $action = '/gestion-reservas/reservas/' . $id_reserva . '/servicios-extras';
        $url_volver_listado = '/gestion-reservas/reservas';
        $volver_listado = 'Mis Reservas';
        $side_reservas = true;
        return view('cliente.mis_reservas.servicios_extras', compact(
            'title',
            'title_form',
            'side_reservas',
            'action',
            'url_volver_listado',
            'volver_listado',
            'reserva',
            'servicios_comidas',
            'servicios_transporte',
            'servicios_tours',
            'servicios_reserva',
            'cantidad_huespedes'
        ));
    }

    public function storeServices($id_reserva, Request $request)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::where('id_reserva', $id_reserva)->where('id_estado_reserva', '<', 5)->first();

        if (empty($reserva)) {
            return redirect('/gestion-reservas/reservas')->with(['danger_message' => 'Reserva no existe o ya ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        if(empty($request->servicios_comidas) && empty($request->servicios_transporte) && empty($request->servicios_tours)){
            return back()->with(['danger_message' => 'Debe seleccionar la menos 1 Servicio para Continuar'])->with(['danger_message_title' => 'Error de validación '])->withInput();
        }

        $valor_servicios_contratados = 0;
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
                            $valor_servicios_contratados = $valor_servicios_contratados + ($servicio_comida_val->precio * $cantidad_servicio_comida);
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
                            $valor_servicios_contratados = $valor_servicios_contratados + ($servicios_transporte_val->precio * $cantidad_servicios_transporte);
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
                            $valor_servicios_contratados = $valor_servicios_contratados + ($servicios_tours_val->precio * $cantidad_servicios_tours);
                            $servicios_tours_val->cantidad = $servicios_tours_val->cantidad - $cantidad_servicios_tours;
                            $servicios_tours_val->save();
                        }
                    }
                }
            }
        }


       
        $reserva->total_reserva = $reserva->total_reserva + $valor_servicios_contratados;
        $reserva->diferencia_pago = $reserva->diferencia_pago + $valor_servicios_contratados;
        if ($reserva->save()) {
            return redirect('/gestion-reservas/reservas')->with(['success_message' => 'Servicios contratados Correctamente.'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error interno '])->withInput();
        }
    }
}
