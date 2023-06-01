<?php

namespace App\Http\Controllers\cliente;

use App\Http\Controllers\Controller;
use App\Models\Huesped;
use App\Models\HuespedReserva;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HuespedClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('isClient');
    }
    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $huespedes = Huesped::where('deleted', 'N')->where('id_cliente', auth()->user()->cliente[0]->id_cliente)->get();
        foreach ($huespedes as $h) {
            $h->cantidad = HuespedReserva::where('id_huesped', $h->id_huesped)->count();
        }
        $title = 'Mis Huéspedes';
        $title_list = 'Listado de Huéspedes';
        $side_huespedes = true;
        return view('cliente.mis_huespedes.mis_huespedes_listado', compact(
            'title',
            'title_list',
            'side_huespedes',
            'huespedes',
        ));
    }

    public function asignar_huespedesCreate($id_reserva)
    {
        $title = 'Asignación de Huéspedes';
        $action = '/gestion-reservas/reservas/' . $id_reserva . '/asingar_huespedes';
        $url_volver_listado = '/gestion-reservas/reservas';
        $volver_listado = 'Mis Reservas';
        $title_list = 'Asignación de Huéspedes';
        $side_reservas = true;
        $reserva = Reserva::where('id_reserva', $id_reserva)->where('id_estado_reserva', '<', 3)->first();


        $huespedes = HuespedReserva::where('id_reserva', $id_reserva)->get();
        $diferencia = $reserva->numero_huespedes - $huespedes->count();
        if ($huespedes->count() == $reserva->numero_huespedes) {
        } else {
            for ($i = 1; $i <= $diferencia; $i++) {
                $huesped = new HuespedReserva();
                $huesped->id_reserva = $reserva->id_reserva;
                $huesped->save();
            }
            $huespedes = HuespedReserva::where('id_reserva', $id_reserva)->get();
        }
        return view('cliente.mis_reservas.asingar_huespedes', compact(
            'title',
            'side_reservas',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_list',
            'reserva',
            'huespedes',
        ));
    }


    public function store(Request $request)
    {
        if (!empty($request->id) && $request->id > 0) {
            $create = false;
            $msg = '';
            $huespedReserva = HuespedReserva::find($request->id);
            $rut = '';
            if (!empty($request->rut)) {
                $rut = trim($request->rut);
                $rut = str_replace('.', '', $rut);
                if (!validateRut($rut)) {
                    $rut = '';
                }
            }
            if (!empty($huespedReserva)) {
                if ($huespedReserva->id_huesped > 0) {
                    $getHuesped = Huesped::where('id_huesped', $huespedReserva->id_huesped)->where('deleted', 'N')->first();
                    if (!empty($getHuesped)) {
                        $getHuesped->nombre = $request->nombres;
                        $getHuesped->apellido = $request->apellidos;
                        $getHuesped->dni = $rut;
                        $getHuesped->fecha_nacimiento = !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
                        $getHuesped->celular = !empty($request->celular) ? ($request->celular) : null;
                        $getHuesped->email = strLower($request->email);
                        $getHuesped->updated_at = ahoraServidor();
                        if ($getHuesped->save()) {
                            $msg = 'ok';
                        } else {
                            $msg = 'Error al modificar Huésped';
                        }
                    } else {
                        $create = true;
                    }
                } else {
                    if (!empty($rut)) {
                        $getHuesped = Huesped::where('dni', $rut)->where('deleted', 'N')->where('id_cliente', $huespedReserva->reserva->id_cliente)->first();
                        if (empty($getHuesped)) {
                            $create = true;
                        } else {
                            $getHuesped->nombre = $request->nombres;
                            $getHuesped->apellido = $request->apellidos;
                            $getHuesped->dni = $rut;
                            $getHuesped->fecha_nacimiento = !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
                            $getHuesped->celular = !empty($request->celular) ? ($request->celular) : null;
                            $getHuesped->email = strLower($request->email);
                            $getHuesped->updated_at = ahoraServidor();
                            if ($getHuesped->save()) {
                                $huespedReserva->id_huesped = $getHuesped->id_huesped;
                                $huespedReserva->updated_at = ahoraServidor();
                                $huespedReserva->save();
                                $msg = 'ok';
                            } else {
                                $msg = 'Error al modificar Huésped';
                            }
                        }
                    } else {
                        $create = true;
                    }
                }
                if ($create) {
                    $huesped = new Huesped();
                    $huesped->nombre = $request->nombres;
                    $huesped->apellido = $request->apellidos;
                    $huesped->dni = $rut;
                    $huesped->fecha_nacimiento = !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
                    $huesped->celular = !empty($request->celular) ? ($request->celular) : null;
                    $huesped->email = strLower($request->email);
                    $huesped->id_cliente = $huespedReserva->reserva->id_cliente;
                    if ($huesped->save()) {
                        $huespedReserva->id_huesped = $huesped->id_huesped;
                        $huespedReserva->updated_at = ahoraServidor();
                        $huespedReserva->save();
                        $msg = 'ok';
                    } else {
                        $msg = 'Error al Crear Huésped';
                    }
                }
            } else {
                $msg = 'Registro No existe o fue eliminado';
            }

            $cantidad =  HuespedReserva::where('id_reserva', $huespedReserva->id_reserva)->where('id_huesped', '!=', null)->count();
            if ($cantidad > 0) {
                $reserva = Reserva::find($huespedReserva->id_reserva);
                if (!empty($reserva)) {
                    $reserva->id_estado_reserva = 2;
                    $reserva->save();
                }
            }
        } else {
            $msg = 'ID de Registro No existe o fue eliminado';
        }
        echo $msg;
    }
    public function getHuesped(Request $request)
    {
        if (!empty($request->id) && $request->id > 0) {
            $huespedReserva = HuespedReserva::find($request->id);
            if (!empty($huespedReserva)) {
                $huesped = [
                    'nombres' => '',
                    'apellidos' => '',
                    'rut' => '',
                    'email' => '',
                    'celular' => '',
                    'fecha_nacimiento' => '',
                ];
                if ($huespedReserva->id_huesped > 0) {
                    $getHuesped = Huesped::find($huespedReserva->id_huesped);
                    if (!empty($getHuesped)) {
                        $huesped = [
                            'nombres' => ($getHuesped->nombre),
                            'apellidos' => ($getHuesped->apellido),
                            'rut' => $getHuesped->dni,
                            'email' => $getHuesped->email,
                            'celular' => $getHuesped->celular,
                            'fecha_nacimiento' => !empty($getHuesped->fecha_nacimiento) ? ordenar_fechaHumano($getHuesped->fecha_nacimiento) : '',
                        ];
                    }
                }
                $msg = [
                    'tipo' => 'success',
                    'msg' => $huesped
                ];
            } else {
                $msg = [
                    'tipo' => 'error',
                    'msg' => 'Registro No existe o fue eliminado'
                ];
            }
        } else {
            $msg = [
                'tipo' => 'error',
                'msg' => 'Registro No existe o fue eliminado'
            ];
        }
        echo json_encode($msg);
    }


    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            $huesped = Huesped::where('id_huesped', $id)->where('deleted', 'N')->first();
            if (empty($huesped)) {
                echo 'Registro no existe o fue eliminado';
            } else {
                $huesped->deleted =  'Y';
                $huesped->deleted_at = ahoraServidor();
                if ($huesped->save()) {
                    echo 'ok';
                } else {
                    echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }

    public function remove(Request $request)
    {
        if (!empty($request->id_quitar)) {
            $id = $request->id_quitar;
            $huespedReserva = HuespedReserva::find($id);
            if (empty($huespedReserva)) {
                echo 'Registro no existe o fue eliminado';
            } else {
                $huespedReserva->id_huesped =  null;
                $huespedReserva->updated_at = ahoraServidor();
                if ($huespedReserva->save()) {
                    $cantidad =  HuespedReserva::where('id_reserva', $huespedReserva->id_reserva)->where('id_huesped', '!=', null)->count();
                    if ($cantidad == 0) {
                        $reserva = Reserva::find($huespedReserva->id_reserva);
                        if (!empty($reserva)) {
                            $reserva->id_estado_reserva = 1;
                            $reserva->save();
                        }
                    }
                    echo 'ok';
                } else {
                    echo 'Ocurrió un problema al quitar. Intente Nuevamente';
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }
}
