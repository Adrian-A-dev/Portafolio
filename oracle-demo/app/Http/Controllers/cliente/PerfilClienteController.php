<?php

namespace App\Http\Controllers\cliente;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Comuna;
use App\Models\Region;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class PerfilClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('isClient');
    }
    public function miPerfil()
    {
        $cliente = Cliente::where('deleted', 'N')->where('id_cliente', auth()->user()->cliente[0]->id_cliente)->first();
    
        $title = 'Mi Perfil';
        $regiones = Region::orderBy('id_region')->get();
        if(!empty($cliente->id_comuna)){
            $comunas = Comuna::where('region_id_region', $cliente->comuna->region_id_region)->orderBy('comuna')->get();
        }else{
            $comunas = [];
        }
        return view('cliente.perfil_cliente', compact(
            'title',
            'cliente',
            'regiones',
            'comunas',
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
        $cliente = Cliente::where('deleted', 'N')->where('id_cliente', auth()->user()->cliente[0]->id_cliente)->first();
        $cliente->dni =  !empty($rut) ? $rut : null;
        $cliente->nombre = $request->nombres;
        $cliente->apellido = $request->apellidos;
        $cliente->fecha_nacimiento =  !empty($request->fecha_nacimiento) ? ordenar_fechaServidor($request->fecha_nacimiento) : null;
        $cliente->email = strLower($request->email);
        $cliente->celular = !empty($request->celular) ? $request->celular : null;
        $cliente->id_comuna = !empty($request->comuna) ? $request->comuna : null;
        $cliente->direccion = !empty($request->direccion) ? $request->direccion : null;
        $cliente->updated_at =  ahoraServidor();
        if ($cliente->save()) {
            $usuario = Usuarios::find(auth()->user()->id_usuario);
            $usuario->email = strLower($request->email);
            $usuario->updated_at =  ahoraServidor();
            $usuario->save();
            return redirect('/gestion-reservas/mi-perfil')->with(['success_message' => 'Perfil Actualizado Correctamente'])->with(['success_message_title' => 'Gestión de Reservas']);
        } else {
            return back()->with(['danger_message' => 'Ha Ocurrido un error al Asignar Huéspedes a Reserva. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }

    public function changePassword()
    {
        $title = 'Cambiar Contraseña';
        return view('cliente.password_cliente', compact(
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
                return redirect('/gestion-reservas/mi-perfil/cambiar-contrasena')->with(['success_message' => 'Contraseña Actualizada Correctamente'])->with(['success_message_title' => 'Gestión de Perfil']);
            } else {
                return back()->with(['danger_message' => 'Ha Ocurrido un error al modificar Contraseña. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }else{
            return back()->with(['danger_message' => 'Contraseña actual es Incorrecta'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleteAccount(Request $request)
    {
        $usuario = Usuarios::find(auth()->user()->id_usuario);
        $cliente = Cliente::where('id_usuario', $usuario->id_usuario)->first();
        $deteled = false;
        if(!empty($cliente)){
            $cliente->deleted = 'Y';
            $cliente->deleted_at =  ahoraServidor();
            if($cliente->save()){
                $deteled = true;
            }
        }else{
            $deteled = true;
        }
        if($deteled){
            $usuario->deleted = 'Y';
            $usuario->deleted_at =  ahoraServidor();
            if ($usuario->save()) {
                return redirect('/gestion-reservas/mi-perfil')->with(['success_message' => 'Cuenta Eliminada Correctamente'])->with(['success_message_title' => 'Cuenta Eliminada']);
            } else {
                return back()->with(['danger_message' => 'Ha Ocurrido un error al eliminar Cuenta. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }
        
   
    }
    
}
