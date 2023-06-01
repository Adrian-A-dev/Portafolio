<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;
use App\Models\Cargo;
use App\Models\Empleado;
use App\Models\EmpleadoSucursal;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmpleadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }


    public function index()
    {
        $empleados = Empleado::where('deleted', 'N')->orderby('nombre')->get();
        $title = 'Gestión de Empleados';
        $title_list = 'Listado de Empleados';
        $btn_nuevo = 'Nuevo Empleado';
        $url_btn_nuevo = '/dashboard/empleados/nuevo';
        $side_empleados = true;
        $side_empleados_list = true;
        return view('admin.modulos.empleados.empleados_listado', compact(
            'title',
            'title_list',
            'side_empleados',
            'side_empleados_list',
            'btn_nuevo',
            'url_btn_nuevo',
            'empleados'
        ));
    }


    public function create()
    {
        $title = 'Nuevo Empleado';
        $action = '/dashboard/empleados/nuevo';
        $url_volver_listado = '/dashboard/empleados';
        $volver_listado = 'Gestión de Empleados';
        $title_form = 'Formulario de Registro de Empleado';
        $side_empleados = true;
        $side_empleados_new = true;
        $cargos = Cargo::where('deleted', 'N')->where('estado', 'Y')->orderby('cargo')->get();
        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $sucursales = Sucursal::where('deleted', 'N')->where('estado', 'Y')->orderBy('sucursal')->get();

        return view('admin.modulos.empleados.empleados_nuevo', compact(
            'title',
            'side_empleados',
            'side_empleados_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'cargos',
            'roles',
            'sucursales'

        ));
    }

    public function store(Request $request)
    {

        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'nombres' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'cargo' => 'required',
                'email' => 'required|email|max:100',
                'rol' => 'required',
                'sucursal' => 'required',
            ], [
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'apellidos.required' => 'Apellidos Requerido',
                'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
                'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
                'cargo.required' => 'Cargo Requerido',
                'email.required' => 'Correo electrónico Requerido',
                'email.required' => 'Correo electrónico con Formato Inválido',
                'email.max' => 'Correo electrónico debe tener Máximo 100 Caracteres',
                'rol.required' => 'Rol Requerido',
                'sucursal.required' => 'Sucursal Requerida',
            ]);
            $rut = '';
            if (!empty($request->rut)) {
                $this->validate($request, [
                    'rut' => 'min:11|max:13',
                ], [
                    'rut.min' => 'Rut debe tener Mínimo 11 Caracteres',
                    'rut.max' => 'Rut debe tener Máximo 13 Caracteres',
                ]);
                $rut = trim($request->rut);
                $rut = str_replace('.', '', $rut);
                if (!validateRut($rut)) {
                    return back()->with(['danger_message' => 'Rut posee formato inválido'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                }
            }
            $email = strLower($request->email);
            $valida_email = Usuarios::where('username', $email)->where('id_rol', '!=', 3)->where('deleted', 'N')->first();
            if (!empty($valida_email)) {
                return back()->with(['danger_message' => 'Correo electrónico ya está asociado a un usuario'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            switch ($request->rol) {
                case '3':
                    return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                case '1':
                    if (auth()->user()->id_rol != 1) {
                        return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                    }
                    break;
                default:
                    break;
            }
            if (count($request->sucursal) < 1) {
                return back()->with(['danger_message' => 'No existe Sucursal Seleccionada'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            } else {
                $sucursales = $request->sucursal;
            }


            $usuario = new Usuarios();
            $usuario->username =  $email;
            $usuario->password = bcrypt(12345);
            $usuario->email =  $email;
            $usuario->id_rol = $request->rol;
            $usuario->flg_cambio_password = 'Y';
            $usuario->save();
            if ($usuario->id_usuario > 0) {
                $empleado = new Empleado();
                $empleado->dni =  !empty($rut) ? $rut : null;
                $empleado->nombre = $request->nombres;
                $empleado->apellido = $request->apellidos;
                $empleado->fecha_nacimiento =  !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
                $empleado->email =  $email;
                $empleado->celular = !empty($request->celular) ? $request->celular : null;
                $empleado->id_cargo = $request->cargo;
                $empleado->id_usuario =  $usuario->id_usuario;
                $empleado->save();
                if ($empleado->id_empleado > 0) {
                    foreach ($sucursales as $s) {
                        $emplado_sucursal = new EmpleadoSucursal();
                        $emplado_sucursal->id_empleado = $empleado->id_empleado;
                        $emplado_sucursal->id_sucursal = $s;
                        $emplado_sucursal->save();
                    }
                   
                    $nombre = $request->nombres .' '. $request->apellidos;
                    Mail::send('emails/creacion_cuenta_empleado', ['nombre'=> $nombre, 'email' => $email], function ($mail) use ($email) {
                        $mail->from('contacto@designwebirg.com', 'Turismo Real');
                        $mail->subject('CREACIÓN DE CUENTA - '.NOMBRE_APP );
                        $mail->to("$email");
                    });
                    return redirect('/dashboard/empleados')->with(['success_message' => 'Se ha Registrado su Empleado Correctamente. Se envían indicaciones de inicio de sesión a correo: ' . $email])->with(['success_message_title' => 'Gestión de Empleados']);
                } else {
                    $usuario_deleted = Usuarios::find($usuario->id_usuario);
                    $usuario_deleted->delete();
                    return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Empleado. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                }
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Usuario. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }

        $empleado = Empleado::where('id_empleado', $id)->where('deleted', 'N')->first();
        if (empty($empleado)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }
        #SE OBTIENEN LAS SUCURSALES ASOCIADAS AL EMPLEADO
        $getEmpladoSucursales = EmpleadoSucursal::where('id_empleado', $empleado->id_empleado)->get();
        $sucursales_empleado = []; # SE GENERA VARIABLE PARA ALMACENAR ID DE LA SUCURSAL
        foreach ($getEmpladoSucursales as $key => $es) {
            array_push($sucursales_empleado, $es->id_sucursal);
        };

        #SE EXTRAEN SUCURSALES DISPONIBLES
        $getSucursales = Sucursal::where('deleted', 'N')->where('estado', 'Y')->orderBy('sucursal')->get();
        $sucursales = []; # SE GENERA VARIABLE PARA GUARDAR DATA DE LAS SUCURSALES 
        foreach ($getSucursales as  $s) { # SE RECORREN LAS SUCURSALES
            #SE COMPARA ID_SUCURSAL CON EL ID_SUCURSAL DEL EMPLEADO 
            if (in_array($s->id_sucursal, $sucursales_empleado)) { #SI ID DE LA SUCURSAL ESTÁ EN EL ARRAY DE LAS SUCURSALES DEL EMPLEADO ENTONCES SE MARCA CON CHECKED 1
                $a = [
                    'id_sucursal' => $s->id_sucursal,
                    'sucursal' => $s->sucursal,
                    'checked' => 1,
                ];
            } else { #SI ID DE LA SUCURSAL NO ESTÁ EN EL ARRAY DE LAS SUCURSALES DEL EMPLEADO ENTONCES SE MARCA CON CHECKED 0
                $a = [
                    'id_sucursal' => $s->id_sucursal,
                    'sucursal' => $s->sucursal,
                    'checked' => 0,
                ];
            }
            $sucursales[$s->id_sucursal] = $a; # SE GUARDA ARRAY DE DATA EN VARIABLE DE SUCURSALES
        }

        $cargos = Cargo::where('deleted', 'N')->where('estado', 'Y')->orderby('cargo')->get();
        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $title = 'Editar Empleado';
        $action = '/dashboard/empleados/' . $id . '/editar';
        $url_volver_listado = '/dashboard/empleados';
        $volver_listado = 'Gestión de Empleados';
        $title_form = 'Formulario de Edición de Empleado';
        $side_empleados = true;
        $side_empleados_list = true;
        return view('admin.modulos.empleados.empleados_editar', compact(
            'title',
            'side_empleados',
            'side_empleados_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'empleado',
            'sucursales',
            'cargos',
            'roles',
        ));
    }

    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }

        $empleado = Empleado::where('id_empleado', $id)->where('deleted', 'N')->first();
        if (empty($empleado)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }
        if (isset($request->form) && $request->form == 1) {

            if (isset($request->form) && $request->form == 1) {
                $this->validate($request, [
                    'nombres' => 'required|min:3|max:100',
                    'apellidos' => 'required|min:3|max:100',
                    'cargo' => 'required',
                    'email' => 'required|email|max:100',
                    'rol' => 'required',
                    'sucursal' => 'required',
                ], [
                    'nombres.required' => 'Nombres Requerido',
                    'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                    'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                    'apellidos.required' => 'Apellidos Requerido',
                    'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
                    'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
                    'cargo.required' => 'Cargo Requerido',
                    'email.required' => 'Correo electrónico Requerido',
                    'email.required' => 'Correo electrónico con Formato Inválido',
                    'email.max' => 'Correo electrónico debe tener Máximo 100 Caracteres',
                    'rol.required' => 'Rol Requerido',
                    'sucursal.required' => 'Sucursal Requerida',
                ]);
                $rut = '';
                if (!empty($request->rut)) {
                    $this->validate($request, [
                        'rut' => 'min:11|max:13',
                    ], [
                        'rut.min' => 'Rut debe tener Mínimo 11 Caracteres',
                        'rut.max' => 'Rut debe tener Máximo 13 Caracteres',
                    ]);
                    $rut = trim($request->rut);
                    $rut = str_replace('.', '', $rut);
                    if (!validateRut($rut)) {
                        return back()->with(['danger_message' => 'Rut posee formato inválido'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                    }
                }
                $email = strLower($request->email);
                $valida_email = Usuarios::where('username', $email)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('id_usuario', '!=', $empleado->id_usuario)->first();
                if (!empty($valida_email)) {
                    return back()->with(['danger_message' => 'Correo electrónico ya está asociado a un usuario'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                }
                switch ($request->rol) {
                    case '3':
                        return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                    case '1':
                        if (auth()->user()->id_rol != 1) {
                            return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                        }
                        break;
                    default:
                        break;
                }
                if (count($request->sucursal) < 1) {
                    return back()->with(['danger_message' => 'No existe Sucursal Seleccionada'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
                } else {
                    $sucursales = $request->sucursal;
                }

               
  

            
                $usuario = Usuarios::find($empleado->id_usuario);
                $usuario->estado = $request->estado == 1 ? 'Y' : 'N';
                $usuario->email =  $email;
                $usuario->id_rol = $request->rol;
                $usuario->updated_at =  ahoraServidor();
                if ($usuario->save()) {
                    $empleado->dni =  !empty($rut) ? $rut : null;
                    $empleado->nombre = $request->nombres;
                    $empleado->apellido = $request->apellidos;
                    $empleado->fecha_nacimiento =  !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
                    $empleado->email =  $email;
                    $empleado->celular = !empty($request->celular) ? $request->celular : null;
                    $empleado->id_cargo = $request->cargo;
                    $empleado->id_usuario =  $usuario->id_usuario;
                    $empleado->estado = $request->estado == 1 ? 'Y' : 'N';
                    $empleado->updated_at =  ahoraServidor();

                    if ($empleado->save()) {
                        if (count(EmpleadoSucursal::where('id_empleado', $empleado->id_empleado)->get()) > 0) {
                            $eliminar_sucursales_empleado = EmpleadoSucursal::where('id_empleado', $empleado->id_empleado)->delete();
                            if ($eliminar_sucursales_empleado) {
                                foreach ($sucursales as $s) {
                                    $emplado_sucursal = new EmpleadoSucursal();
                                    $emplado_sucursal->id_empleado = $empleado->id_empleado;
                                    $emplado_sucursal->id_sucursal = $s;
                                    $emplado_sucursal->save();
                                }
                                return redirect('/dashboard/empleados')->with(['success_message' => 'Se ha Modificado Empleado Correctamente'])->with(['success_message_title' => 'Gestión de Empleados']);
                            } else {
                                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Sucursales de Empleado. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                            }
                        } else {
                            foreach ($sucursales as $s) {
                                $emplado_sucursal = new EmpleadoSucursal();
                                $emplado_sucursal->id_empleado = $empleado->id_empleado;
                                $emplado_sucursal->id_sucursal = $s;
                                $emplado_sucursal->save();
                            }
                            return redirect('/dashboard/empleados')->with(['success_message' => 'Se ha Modificado Empleado Correctamente'])->with(['success_message_title' => 'Gestión de Empleados']);
                        }
                    } else {
                        return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Empleado. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                    }
                } else {
                    return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Usuario. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                }
            } else {
                return redirect('/dashboard/empleados')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
            }
        }
    }
    public function delete_empleado($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }
        $empleado = Empleado::where('id_empleado', $id)->where('deleted', 'N')->first();
        if (empty($empleado)) {
            return redirect('/dashboard/empleados')->with(['danger_message' => 'Empleado No existe o fue Eliminado'])->with(['danger_message_title' => 'Empleado no encontrado']);
        }
        if (!empty($empleado->usuario->id_rol)) {
            if ($empleado->usuario->id_rol == 1 && auth()->user()->id_rol != 1) {
                return redirect('/dashboard/empleados')->with(['danger_message' => 'Usuarios Root No pueden ser eliminados'])->with(['danger_message_title' => 'Error de Validación']);
            }

            if (auth()->user()->id_usuario == $empleado->id_usuario) {
                return redirect('/dashboard/empleados')->with(['danger_message' => 'No puedes eliminar tu propia cuenta'])->with(['danger_message_title' => 'Error de Validación']);
            }
            $usuario = Usuarios::where('id_usuario', $empleado->id_usuario)->where('deleted', 'N')->first();
            $usuario->deleted =  'Y';
            $usuario->deleted_at = ahoraServidor();
            $usuario->save();
        }
        if (!empty($empleado)) {
            $empleado->deleted =  'Y';
            $empleado->deleted_at = ahoraServidor();
            $empleado->save();
            return redirect('/dashboard/empleados')->with(['success_message' => 'El Empleado ha sido eliminado correctamente'])->with(['success_message_title' => 'Empleado Eliminado Correctamente']);
        } else {
            return back()->with(['danger_message' => 'Empleado asociado a Usuario no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        $detele_bool = true;
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $empleado = Empleado::where('id_empleado', $id)->where('deleted', 'N')->first();
                if (empty($empleado)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    if (!empty($empleado->usuario->id_rol)) {
                        if (auth()->user()->id_usuario == $empleado->id_usuario) {
                            echo 'No puedes eliminar tu propia cuenta';
                            $detele_bool = false;
                        } elseif ($empleado->usuario->id_rol == 1 && auth()->user()->id_rol != 1) {
                            echo 'Usuarios Root No pueden ser eliminados';
                            $detele_bool = false;
                        }
                    }
                    if ($detele_bool) {
                        $usuario = Usuarios::where('id_usuario', $empleado->id_usuario)->where('deleted', 'N')->first();
                        $usuario->deleted =  'Y';
                        $usuario->deleted_at = ahoraServidor();
                        if ($usuario->save()) {
                            $empleado->deleted =  'Y';
                            $empleado->deleted_at = ahoraServidor();
                            if ($empleado->save()) {
                                echo 'ok';
                            } else {
                                echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                            }
                        } else {
                            echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                        }
                    }
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }
}
