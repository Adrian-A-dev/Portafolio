<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\DepartamentoAccesorio;
use App\Models\HuespedReserva;
use App\Models\MovimientoCaja;
use App\Models\Multa;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\ServicioReserva;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $reservas = Reserva::all();
        foreach ($reservas as $r) {
            $r->cantidad =  HuespedReserva::where('id_reserva', $r->id_reserva)->where('id_huesped', '!=', null)->count();
        }
        $title = 'Gestión de Reservas';
        $title_list = 'Listado de Reservas';
        $btn_nuevo = 'Nueva Reserva';
        $url_btn_nuevo = '/dashboard/reservas/nueva';
        $side_reservas = true;
        return view('admin.modulos.reservas.reservas_listado', compact(
            'title',
            'title_list',
            'side_reservas',
            'reservas',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function generarCheckIn($id_reserva, Request $request)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::where('id_reserva', $id_reserva)->first();

        if (empty($reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        $cantidad =  HuespedReserva::where('id_reserva', $reserva->id_reserva)->where('id_huesped', '!=', null)->count();
        if ($cantidad == 0) {
            return back()->with(['danger_message' => 'Reserva no posee Huéspedes asignados'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        if (isset($request->form) && $request->form == 1) {
            $condiciones = $request->accesorio;
            $crea = false;
            if (!empty($reserva->id_check_in)) {
                $check_in = CheckIn::find($reserva->id_check_in);
                if (empty($check_in)) {
                    $crea = true;
                }
            } else {
                $crea = true;
            }
            if ($crea) {
                $check_in = new CheckIn();
            }
            $check_in->fecha_checkin = ahoraServidor();
            $check_in->id_empleado = auth()->user()->empleado[0]->id_empleado;
            $check_in->condiciones = $condiciones > 0 ? json_encode($condiciones) : null;
            if ($check_in->save()) {
                $reserva->id_estado_reserva = 3;
                $reserva->id_check_in = $check_in->id_check_in;
                if ($reserva->save()) {
                    return redirect('/dashboard/reservas')->with(['success_message' => 'Se han Generado CheckIn Correctamente.'])->with(['success_message_title' => 'Gestión de Reservas']);
                } else {
                    return  back()->with(['danger_message' => 'Ha Ocurrido un error al Generar CheckIn de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                }
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Generar CheckIn de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
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
        $action = '/dashboard/reservas/' . $id_reserva . '/check_in';
        $action_pago = '/dashboard/reservas/' . $id_reserva . '/pago_check_in';
        $url_volver_listado = '/dashboard/reservas';
        $volver_listado = 'Gestión de Reservas';
        $side_reservas = true;
        return view('admin.modulos.reservas.check_in', compact(
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
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        $reserva = Reserva::find($id_reserva);
        if (empty($reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        if ($reserva->diferencia_pago == 0) {
            return redirect('/dashboard/reservas/' . $id_reserva . '/check_in')->with(['danger_message' => 'Reserva no posee monto pendiente a pagar'])->with(['danger_message_title' => 'No posee Pago Pendiente']);
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
            return redirect('/dashboard/reservas/' . $id_reserva . '/check_in')->with(['success_message' => 'Se han Generado Pago de Diferencia Correctamente.'])->with(['success_message_title' => 'Pago Realiazado']);
        } else {
            return redirect('/dashboard/reservas/' . $id_reserva . '/check_in')->with(['danger_message' => 'Ha ocurrido un error al generar Pago. Recargue e intente de nuevo'])->with(['danger_message_title' => 'Error en Pago ']);
        }
    }


    public function downloadPdf($id_reserva)
    {
        if (!is_numeric($id_reserva) || $id_reserva < 1) {
            return back()->with(['danger_message' => 'Reserva no existe o fue eliminada'])->with(['danger_message_title' => 'Reserva No encontrada']);
        }
        $reserva = Reserva::where('id_reserva', $id_reserva)->first();
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

        return $pdf->stream('reserva_' . sha1($reserva->id_reserva) . '.pdf');
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

    public function cancelarReserva(Request $request)
    {
        // $request->id_cancel = '85';
        if (!empty($request->id_cancel)) {
            $id = $request->id_cancel;
            $reserva = Reserva::find($id);
            if (empty($reserva)) {
                echo 'Reserva no existe o fue Cancelada';
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
        } else {
            echo 'No se ha indicado ID';
        }
    }


    public function generarCheckOut($id_reserva)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        $reserva = Reserva::where('id_reserva', $id_reserva)->first();
        if (empty($reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        if ($reserva->id_estado_reserva == 6) {
            return back()->with(['danger_message' => 'La Reserva está cancelada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        } elseif ($reserva->id_estado_reserva < 4) {
            return back()->with(['danger_message' => 'La Reserva debe tener Check-In Confirmado'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        $crea = false;
        if (empty($reserva->id_check_out)) {
            $crea = true;
        } else {
            $check_out = CheckOut::find($reserva->id_check_out);
            if (empty($check_out)) {
                $crea = true;
            }
        }

        if ($crea) {
            $check_out = new CheckOut();
            $check_out->fecha_checkout = ahoraServidor();
            $check_out->id_empleado = auth()->user()->empleado[0]->id_empleado;
            if ($check_out->save()) {
                $reserva->id_estado_reserva = 5;
                $reserva->id_check_out = $check_out->id_check_out;
                $reserva->save();
            }
        }




        $multas_check_out = [];
        $multas_check_out2 = [];

        if (!empty($check_out->observacion_multa)) {
            $multas_arr = json_decode($check_out->observacion_multa);

            foreach ($multas_arr as $key => $k) {
                array_push($multas_check_out, $key);
                $multas_check_out2[$key] = $k;
            }
        }


        #SE EXTRAEN MULTAS DISPONIBLES
        $getMultas = Multa::where('deleted', 'N')->where('estado', 'Y')->orderBy('multa')->get();
        $multas = []; # SE GENERA VARIABLE PARA GUARDAR DATA DE LAS MULTAS 
        foreach ($getMultas as  $m) { # SE RECORREN LAS MULTAS
            #SE COMPARA ID_MULTA CON EL ID_MULTA DEL CHECK_OUT 
            if (in_array($m->id_multa, $multas_check_out)) { #SI ID DE LA MULTA ESTÁ EN EL ARRAY DE MULTA DEL CHECK_OUT ENTONCES SE MARCA CON CHECKED 1
                $b = [
                    'id_multa' => $m->id_multa,
                    'multa' => $multas_check_out2[$m->id_multa]->monto,
                    'monto' => $m->monto,
                    'checked' => 1,
                ];
            } else { #SI ID LA MULTA NO ESTÁ EN EL ARRAY DE LAS MULTA DEL CHECK_OUT ENTONCES SE MARCA CON CHECKED 0
                $b = [
                    'id_multa' => $m->id_multa,
                    'multa' => $m->multa,
                    'monto' => $m->monto,
                    'checked' => 0,
                ];
            }
            $multas[$m->id_multa] = $b; # SE GUARDA ARRAY DE DATA EN VARIABLE DE MULTAS
        }
        $servicios_reserva = ServicioReserva::where('id_reserva', $reserva->id_reserva)->get();

        $total_servicios = 0;
        $total_servicios_cantidad = 0;
        foreach ($servicios_reserva as $s) {
            $total_servicios = $total_servicios + ($s->precio * $s->cantidad);
            $total_servicios_cantidad = $total_servicios_cantidad + $s->cantidad;
        }





        $title = 'Check-Out Reserva N°' . $id_reserva;
        $title_form = 'Formulario de Check-Out';
        $action = '/dashboard/reservas/' . $id_reserva . '/check_out';
        $action_pago = '/dashboard/reservas/' . $id_reserva . '/pago_check_out';
        $url_volver_listado = '/dashboard/reservas';
        $volver_listado = 'Gestión de Reservas';
        $side_reservas = true;
        return view('admin.modulos.reservas.check_out', compact(
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
            'multas'
        ));
    }



    public function generarCheckOutPost($id_reserva, Request $request)
    {
        if (!is_numeric($id_reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe o aun no ha sido procesada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        $reserva = Reserva::where('id_reserva', $id_reserva)->first();
        if (empty($reserva)) {
            return redirect('/dashboard/reservas')->with(['danger_message' => 'Reserva no existe'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }
        if ($reserva->id_estado_reserva == 6) {
            return back()->with(['danger_message' => 'La Reserva está cancelada'])->with(['danger_message_title' => 'Gestión de Reservas']);
        } elseif ($reserva->id_estado_reserva < 4) {
            return back()->with(['danger_message' => 'La Reserva debe tener Check-In Confirmado'])->with(['danger_message_title' => 'Gestión de Reservas']);
        }

        if (isset($request->form) && $request->form == 1) {
            $check_out = CheckOut::find($reserva->id_check_out);

            if ($request->otra_multa == 'Y') {
                $check_out->flg_problema_adicional = 'Y';
                $check_out->observacion_adicional = $request->observacion_adicional;
                $check_out->total_adicional = !empty($request->total_adicional) ? limpiaMoneda($request->total_adicional) : 0;
            } else {
                $check_out->flg_problema_adicional = 'N';
                $check_out->observacion_adicional = null;
                $check_out->total_adicional = null;
            }

            $multas = isset($request->multa) ? $request->multa : [];
            $multas_json = [];
            if (!empty($multas)) {
                foreach ($multas as $multa) {
                    $multa = Multa::find($multa);
                    if (!empty($multa)) {
                        $m = [
                            'id_multa' => $multa->id_multa,
                            'monto' => $multa->monto
                        ];
                        $multas_json[$multa->id_multa] = $m;
                    }
                }
                $check_out->flg_posee_multa = 'Y';
                $check_out->observacion_multa = json_encode($multas_json);
                $check_out->total_multa = !empty($request->total_multa) ? limpiaMoneda($request->total_multa) : 0;
            } else {
                $check_out->flg_posee_multa = 'N';
                $check_out->observacion_multa = null;
                $check_out->total_multa = null;
            }
            $check_out->updated_at = ahoraServidor();

            if ($check_out->save()) {
                if ($request->accion == 1) {
                    return back()->with(['success_message' => 'CheckOut Modificado Correctamente'])->with(['success_message_title' => 'Modificación Correcta']);
                } else {
                    $genera_movimiento = false;
                    $check_out_val = CheckOut::find($reserva->id_check_out);
                    $multa_tt = 0;
                    $adicional_tt = 0;
                    $diferencia = (int)$reserva->diferencia_pago;
                    if ($check_out_val->flg_problema_adicional == 'Y') {
                        $adicional_tt = (int)$check_out_val->total_adicional;
                    }
                    if ($check_out_val->flg_posee_multa == 'Y') {
                        $multa_tt = (int)$check_out_val->total_multa;
                    }
                    $total_paga_out =  $diferencia + $multa_tt + $adicional_tt;
                    if ($total_paga_out > 0) {
                        $reserva->diferencia_pago = 0;
                        $reserva->total_pagado = $reserva->total_pagado + $total_paga_out;
                        $genera_movimiento = true;
                    }
                    $reserva->id_estado_reserva = 7;
                    $reserva->updated_at = ahoraServidor();
                    if ($reserva->save()) {
                        if ($genera_movimiento) {
                            $movimiento = new MovimientoCaja();
                            $movimiento->origen = 'CHECK-OUT';
                            $movimiento->total = $total_paga_out;
                            $movimiento->nro_reserva = $id_reserva;
                            $movimiento->id_tipo_movimiento_caja = 1;
                            $movimiento->id_usuario = auth()->user()->id_usuario;
                            $movimiento->save();
                        }
                        return redirect('/dashboard/reservas')->with(['success_message' => 'Se han Finalizado CheckOut Correctamente.'])->with(['success_message_title' => 'Gestión de Reservas']);
                    } else {
                        return  back()->with(['danger_message' => 'Ha Ocurrido un error al Finalizar CheckOut de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                    }
                }
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar CheckOut de Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error al Modificar'])->withInput();
            }
        }
    }
}
