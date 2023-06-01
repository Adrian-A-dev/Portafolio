<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Cliente;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {

        if (isset($request->register_form) && $request->register_form == 1) {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',

            ], [
                'email.required' => 'Correo electrónico Requerido',
                'password.required' => 'Contraseña Requerida',
            ]);
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


        // pre_die( auth()->user()->email);
        $action = '/login';
        $title = 'Acceso a Clientes';
        $registro_btn = true;
        return view('landing.login', compact('action', 'title', 'registro_btn'));
    }

    public function login_admin(Request $request)
    {
        if (isset($request->register_form) && $request->register_form == 1) {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',

            ], [
                'email.required' => 'Correo electrónico Requerido',
                'password.required' => 'Contraseña Requerida',
            ]);
        }
        if (isset($request->login_form) && $request->login_form == 1) {
            $email = strLower($request->email);
            $usuario = Usuarios::where('username', $email)->where('id_rol', '!=', 3)->first();

            if (!empty($usuario)) {
                if (auth()->attempt(['username' => $request->email, 'password' => $request->password, 'id_rol' => $usuario->id_rol]) == false) {
                    return back()->with(['danger_message' => 'Usuario y/o Contraseña Incorrectos'])->with(['danger_message_title' => 'Error de Validación']);
                } else {
                    $usuario->ultimo_acceso = ahoraServidor();
                    $usuario->save();
                    return redirect('/dashboard')->with(['success_message' => 'Bienvenido ' . $usuario->empleado[0]->nombre])->with(['success_message_title' => 'Sesión Iniciada Correctamente']);
                }
            } else {
                return back()->with(['danger_message' => 'Usuario y/o Contraseña Incorrectos'])->with(['danger_message_title' => 'Error de Validación']);
            }
        }
        $action = '/login-admin';
        $title = 'Inicio de Sesión';
        $registro_btn = false;
        return view('landing.login', compact('action', 'title',  'registro_btn'));
    }
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/')->with(['success_message' => 'Hazta Luego'])->with(['success_message_title' => 'Sesión Finalizada Correctamente']);
    }
}
