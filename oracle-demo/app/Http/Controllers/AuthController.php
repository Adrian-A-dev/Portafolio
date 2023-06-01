<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Cliente;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }
    public function changePassword(Request $request)
    {
        if(!auth()->check()){
            return redirect('/');
        }
        if(auth()->user()->flg_cambio_password == 'N' && auth()->user()->id_rol == 3){
            return redirect('/gestion-reservas');
        }elseif(auth()->user()->flg_cambio_password == 'N' && auth()->user()->id_rol != 3){
            return redirect('/dashboard');
        }
        
        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'password' => 'required|min:4',
                'password_confirm' => 'required|min:4',

            ], [
                'password.required' => 'Contraseña Requerida',
                'password.min' => 'Contraseña debe tener Mínimo 4 Caracteres',
                'password_confirm.required' => 'Confirmar Contraseña Requerida',
                'password_confirm.min' => 'Contraseña debe tener Mínimo 4 Caracteres',
            ]);
            if ($request->password != $request->password_confirm) {
                return back()->with(['danger_message' => 'Contraseñas deben ser Indenticas'])->with(['danger_message_title' => 'Error de Validación']);
            }
            $usuario = Usuarios::find(auth()->user()->id_usuario);
            $usuario->password = bcrypt($request->password);
            $usuario->updated_at =  ahoraServidor();
            $usuario->flg_cambio_password =  'N';
            if ($usuario->save()) {
                if($usuario->id_rol == 3){
                    return redirect('/gestion-reservas')->with(['success_message' => 'Contraseña Actualizada Correctamente'])->with(['success_message_title' => 'Contraseña Actualizada']);
                }else{
                    return redirect('/dashboard')->with(['success_message' => 'Contraseña Actualizada Correctamente'])->with(['success_message_title' => 'Contraseña Actualizada']);
                }
            } else {
                return back()->with(['danger_message' => 'Ha Ocurrido un error al modificar Contraseña. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }

        if (isset($request->login_form) && $request->login_form == 1) {
            $email = strLower($request->email);
            $usuario = Usuarios::where('username', $email)->where('id_rol', 3)->where('deleted', 'N')->first();
            if (!empty($usuario)) {
                if (auth()->attempt(['username' => $request->email, 'password' => $request->password, 'id_rol' => 3, 'deleted' => 'N']) == false) {
                    return back()->with(['danger_message' => 'Usuario y/o Contraseña Incorrectos'])->with(['danger_message_title' => 'Error de Validación']);
                } else {
                    $usuario->ultimo_acceso = ahoraServidor();
                    $usuario->save();
                    return redirect('/')->with(['success_message' => 'Bienvenido'])->with(['success_message_title' => 'Sesión Iniciada Correctamente']);
                }
            } else {
                return back()->with(['danger_message' => 'Usuario y/o Contraseña Incorrectos'])->with(['danger_message_title' => 'Error de Validación']);
            }
        }
        $action = '/cambio-contrasena';
        $title = 'Cambio de Contraseña Obligatorio';
        $registro_btn = false;
        return view('landing.change_password_required', compact('action', 'title', 'registro_btn'));
    }

 
}
