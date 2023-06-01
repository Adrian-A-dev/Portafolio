<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Comuna;
use App\Models\Empleado;
use App\Models\Region;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function miPerfil()
    {
        $empleado = Empleado::where('deleted', 'N')->where('id_empleado', auth()->user()->empleado[0]->id_empleado)->first();
        $title = 'Mi Perfil';
        return view('admin.perfil_admin', compact(
            'title',
            'empleado',
        ));
    }

    public function miPerfilUpdate(Request $request)
    {
        $this->validate($request, [
            'nombres' => 'required|min:3|max:100',
            'apellidos' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
        ], [
            'nombres.required' => 'Nombres Requerido',
            'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
            'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
            'apellidos.required' => 'Apellidos Requerido',
            'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
            'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
            'email.required' => 'Correo electrónico Requerido',
            'email.required' => 'Correo electrónico con Formato Inválido',
            'email.max' => 'Correo electrónico debe tener Máximo 100 Caracteres',
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
        $empleado = Empleado::where('deleted', 'N')->where('id_empleado', auth()->user()->empleado[0]->id_empleado)->first();
        $empleado->dni =  !empty($rut) ? $rut : null;
        $empleado->nombre = $request->nombres;
        $empleado->apellido = $request->apellidos;
        $empleado->fecha_nacimiento =  !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
        $empleado->email = strLower($request->email);
        $empleado->celular = !empty($request->celular) ? $request->celular : null;
        $empleado->updated_at =  ahoraServidor();
        if ($empleado->save()) {
            $usuario = Usuarios::find(auth()->user()->id_usuario);
            $usuario->email = strLower($request->email);
            $usuario->updated_at =  ahoraServidor();
            $usuario->save();
            return redirect('/dashboard/mi-perfil')->with(['success_message' => 'Perfil Actualizado Correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return back()->with(['danger_message' => 'Ha Ocurrido un error al Asignar Huéspedes a Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }

    public function changePassword()
    {
        $title = 'Cambiar Contraseña';
        return view('admin.password_admin', compact(
            'title',
        ));
    }

    public function changePasswordUpdate(Request $request)
    {
        $usuario = Usuarios::find(auth()->user()->id_usuario);
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:4',
            'password_confirm' => 'required|min:4',

        ], [
            'old_password.required' => 'Contraseña Actual Requerida',
            'password.required' => 'Nueva Contraseña Requerida',
            'password.min' => 'Nueva Contraseña debe tener Mínimo 4 Caracteres',
            'password_confirm.required' => 'Confirmar Contraseña Requerida',
            'password_confirm.min' => 'Contraseña debe tener Mínimo 4 Caracteres',
        ]);
        if ($request->password != $request->password_confirm) {
            return back()->with(['danger_message' => 'Contraseñas deben ser Indenticas'])->with(['danger_message_title' => 'Error de Validación']);
        }
        if (Hash::check($request->old_password, $usuario->password)) {
            $usuario->password = bcrypt($request->password);
            $usuario->updated_at =  ahoraServidor();
            if ($usuario->save()) {
                return redirect('/dashboard/mi-perfil/cambiar-contrasena')->with(['success_message' => 'Contraseña Actualizada Correctamente'])->with(['success_message_title' => 'Gestión de Perfil']);
            } else {
                return back()->with(['danger_message' => 'Ha Ocurrido un error al modificar Contraseña. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }else{
            return back()->with(['danger_message' => 'Contraseña actual es Incorrecta'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }
}
