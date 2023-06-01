<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Cliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function registro(Request $request)
    {
        if (isset($request->register_form) && $request->register_form == 1) {
            $this->validate($request, [
                'nombres' => 'required|min:3|max:100',
                'apellidos' => 'required|min:3|max:100',
                'email' => 'required',
                'password' => 'required|min:4',
                'password_confirm' => 'required|min:4',

            ], [
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'apellidos.required' => 'Apellidos Requerido',
                'apellidos.min' => 'Apellidos debe tener Mínimo 3 Caracteres',
                'apellidos.max' => 'Apellidos debe tener Máximo 100 Caracteres',
                'email.required' => 'Correo electrónico Requerido',
                'password.required' => 'Contraseña Requerida',
                'password.min' => 'Contraseña debe tener Mínimo 4 Caracteres',
                'password_confirm.required' => 'Confirmar Contraseña Requerida',
                'password_confirm.min' => 'Contraseña debe tener Mínimo 4 Caracteres',

            ]);
            if ($request->password != $request->password_confirm) {
                return back()->with(['danger_message' => 'Contraseñas deben ser Indenticas'])->with(['danger_message_title' => 'Error de Validación']);
            }

            $email = strLower($request->email);
            $valida_email = Usuarios::where('email', $email)->where('id_rol', 3)->first();

            if (!empty($valida_email)) {
                return back()->with(['danger_message' => 'Correo electrónico ya existe en nuestros registros'])->with(['danger_message_title' => 'Error de Validación']);
            }

            $usuario = new Usuarios();

            $usuario->username =  $email;
            $usuario->password = bcrypt($request->password);
            $usuario->email =  $email;
            $usuario->id_rol = 3;
            $usuario->save();
            if ($usuario->id_usuario > 0) {
                $cliente = new Cliente();
                $cliente->nombre = $request->nombres;
                $cliente->apellido = $request->apellidos;
                $cliente->email =  $email;
                $cliente->id_usuario =  $usuario->id_usuario;
                $cliente->save();
                $nombre = $request->nombre .' '. $request->apellido;
                Mail::send('emails/creacion_cuenta_cliente', ['nombre'=> $nombre, 'email' => $email], function ($mail) use ($email) {
                    $mail->from('contacto@designwebirg.com', 'Turismo Real');
                    $mail->subject('CREACIÓN DE CUENTA - '.NOMBRE_APP );
                    $mail->to("$email");
                });
                return redirect('/login')->with(['success_message' => 'Se ha Registrado su Usuario Correctamente. Ya puede Iniciar Sesión'])->with(['success_message_title' => 'Usuario Registrado Correctamente']);
            } else {
                return redirect('/registro')->with(['danger_message' => 'Ha Ocurrido un error al Crear Usuario. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        }
        $action = '/registro';
        $title = 'Registro de Clientes';
        return view('landing.register', compact(
            'action',
            'title'
        ));
    }
}
