<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
        $this->middleware('isRoot');
    }
    public function index()
    {

        #$usuario = Usuarios::select('*') ->join('empleado as e', 'usuario.id_usuario', '=', 'e.id_usuario')->where('e.id_usuario', auth()->user()->id_usuario)->first();
        // $empleado = Empleado::find(1);
        // pre_die($empleado->usuario->email);
        $usuarios = Usuarios::select('usuario.*', 'e.nombre', 'e.apellido', 'r.rol')
            ->join('empleado as e', 'usuario.id_usuario', '=', 'e.id_usuario', 'left')
            ->join('rol as r', 'usuario.id_rol', '=', 'r.id_rol', 'left')
            ->where('usuario.deleted', 'N')
            ->where('usuario.id_rol', '!=', 3)
            ->get();
        $title = 'Gestión de Usuarios';
        $title_list = 'Listado de Usuarios';
        $btn_nuevo = 'Nuevo Usuario';
        $url_btn_nuevo = '/dashboard/usuarios/nuevo';
        $side_usuarios = true;
        $side_usuarios_list = true;
        return view('admin.modulos.usuarios.usuarios_listado', compact(
            'title',
            'title_list',
            'btn_nuevo',
            'url_btn_nuevo',
            'side_usuarios',
            'side_usuarios_list',
            'usuarios'
        ));
    }

    public function create()
    {
        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $title = 'Nuevo Usuario';
        $action = '/dashboard/usuarios/nuevo';
        $url_volver_listado = '/dashboard/usuarios';
        $volver_listado = 'Gestión de Usuarios';
        $title_form = 'Formulario de Registro de Usuario';
        $side_usuarios = true;
        $side_usuarios_new = true;
        return view('admin.modulos.usuarios.usuarios_nuevo', compact(
            'title',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'side_usuarios',
            'side_usuarios_new',
            'action',
            'roles'
        ));
    }


    public function store(Request $request)
    {
        if (isset($request->user_form) && $request->user_form == 1) {
            $this->validate($request, [
                'rut' => 'required|min:11|max:13',
                'nombres' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'email' => 'required',
                'rol' => 'required',
            ], [
                'rut.required' => 'Rut Requerido',
                'rut.min' => 'Rut debe tener Mínimo 11 Caracteres',
                'rut.max' => 'Rut debe tener Máximo 13 Caracteres',
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'apellidos.required' => 'Apellidos Requerido',
                'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
                'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
                'email.required' => 'Correo electrónico Requerido',
                'rol.required' => 'Rol Requerido',
            ]);
            $email = strLower($request->email);
            $rut = trim($request->rut);
            $rut = str_replace('.', '', $rut);
            $rutSinDV = substr(str_replace('-', '', $rut), 0, -1);
            if (!validateRut($rut)) {
                return back()->with(['danger_message' => 'Rut posee formato inválido'])->with(['danger_message_title' => 'Error de Validación']);
            }
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

            $usuario = new Usuarios();
            $usuario->username =  $email;
            $usuario->password = bcrypt($rutSinDV);
            $usuario->email =  $email;
            $usuario->id_rol = $request->rol;
            $usuario->flg_cambio_password = 'Y';
            $usuario->save();
            if ($usuario->id_usuario > 0) {
                $empleado = new Empleado();
                $empleado->nombre = $request->nombres;
                $empleado->apellido = $request->apellidos;
                $empleado->email =  $email;
                $empleado->dni =  $rut;
                $empleado->celular = $request->celular;
                $empleado->id_usuario =  $usuario->id_usuario;
                $empleado->save();
                return redirect('/dashboard/usuarios')->with(['success_message' => 'Se ha Registrado su Usuario Correctamente. Se ha enviado indicaciones de inicio de sesión a correo registrado'])->with(['success_message_title' => 'Gestión de Usuarios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Usuario. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }
        $usuario = Usuarios::where('id_usuario', $id)->where('deleted', 'N')->first();
        if (empty($usuario)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }

        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $title = 'Editar Usuario';
        $action = '/dashboard/usuarios/' . $id . '/editar';
        $url_volver_listado = '/dashboard/usuarios';
        $volver_listado = 'Gestión de Usuarios';
        $title_form = 'Formulario de Edición de Usuario';
        $side_usuarios = true;
        $side_usuarios_list = true;
        return view('admin.modulos.usuarios.usuarios_editar', compact(
            'title',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'side_usuarios',
            'side_usuarios_list',
            'action',
            'roles',
            'usuario'
        ));
    }

    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }
        $usuario = Usuarios::where('id_usuario', $id)->where('deleted', 'N')->first();
        if (empty($usuario)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }
        if (isset($request->user_form) && $request->user_form == 1) {
            $this->validate($request, [
                'nombres' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'email' => 'required',
                'rol' => 'required',
                'estado' => 'required',
            ], [
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'apellidos.required' => 'Apellidos Requerido',
                'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
                'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
                'email.required' => 'Correo electrónico Requerido',
                'rol.required' => 'Rol Requerido',
                'estado.required' => 'Estado Requerido',
            ]);
            $email = strLower($request->email);

            $valida_email = Usuarios::where('username', $email)->where('id_rol', '!=', 3)->where('id_usuario', '!=', $usuario->id_usuario)->where('deleted', 'N')->first();
            if (!empty($valida_email)) {
                return back()->with(['danger_message' => 'Correo electrónico ya está asociado a un usuario'])->with(['danger_message_title' => 'Error de Validación']);
            }
            switch ($request->rol) {
                case '3':
                    return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
                    break;
                case '1':
                    if (auth()->user()->id_rol != 1) {
                        return back()->with(['danger_message' => 'Rol No existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
                    }
                    break;
                default:
                    break;
            }
            if (auth()->user()->id_usuario == $id && $request->estado !=  1) {
                return back()->with(['danger_message' => 'No puedes deshabilitar tu propia cuenta'])->with(['danger_message_title' => 'Error de Validación']);
            }

            $empleado = Empleado::where('id_usuario', $usuario->id_usuario)->first();
            if (!empty($empleado)) {
                $usuario->email =  $email;
                $usuario->id_rol = $request->rol;
                $usuario->estado = $request->estado == 1 ? 'Y' : 'N';
                $usuario->updated_at = ahoraServidor();
                $usuario->save();

                $empleado->nombre = $request->nombres;
                $empleado->apellido = $request->apellidos;
                $empleado->celular = $request->celular;
                $empleado->email =  $email;
                $empleado->updated_at = ahoraServidor();
                $empleado->save();
                return redirect('/dashboard/usuarios')->with(['success_message' => 'Se ha Modificado su Usuario Correctamente.'])->with(['success_message_title' => 'Usuario Modificado']);
            } else {
                return back()->with(['danger_message' => 'Empleado asociado a Usuario no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
            }
        }

        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $title = 'Editar Usuario';
        $action = '/dashboard/usuarios/' . $id . '/editar';
        $url_volver_listado = '/dashboard/usuarios';
        $volver_listado = 'Gestión de Usuarios';
        $title_form = 'Formulario de Edición de Usuario';
        $side_usuarios = true;
        $side_usuarios_list = true;
        return view('admin.modulos.usuarios.usuarios_editar', compact(
            'title',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'side_usuarios',
            'side_usuarios_list',
            'action',
            'roles',
            'usuario'
        ));
    }

    public function delete_user($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }
        $usuario = Usuarios::where('id_usuario', $id)->where('deleted', 'N')->first();
        if (empty($usuario)) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuario No existe o fue Eliminado'])->with(['danger_message_title' => 'Usuario no encontrado']);
        }
        if ($usuario->id_rol == 1 && auth()->user()->id_rol != 1) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'Usuarios Root No pueden ser eliminados'])->with(['danger_message_title' => 'Error de Validación']);
        }

        if (auth()->user()->id_usuario == $id) {
            return redirect('/dashboard/usuarios')->with(['danger_message' => 'No puedes eliminar tu propia cuenta'])->with(['danger_message_title' => 'Error de Validación']);
        }


        $empleado = Empleado::where('id_usuario', $usuario->id_usuario)->first();
        if (!empty($empleado)) {
            $usuario->deleted =  'Y';
            $usuario->deleted_at = ahoraServidor();
            $usuario->save();

            $empleado->deleted =  'Y';
            $empleado->deleted_at = ahoraServidor();
            $empleado->save();
            return redirect('/dashboard/usuarios')->with(['success_message' => 'El usuario ha sido eliminado correctamente'])->with(['success_message_title' => 'Usuario Eliminado Correctamente']);
        } else {
            return back()->with(['danger_message' => 'Empleado asociado a Usuario no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }
}
