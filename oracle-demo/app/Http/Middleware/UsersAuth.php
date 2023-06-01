<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UsersAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()){
            if(auth()->user()->deleted == 'Y'){
                auth()->logout();
                return redirect('/')->with(['success_message' => 'Cuenta Eliminada Correctamente'])->with(['success_message_title' => 'Cuenta Eliminada']);
            }
           
            if(auth()->user()->flg_cambio_password == 'Y'){
                
                return redirect('/cambio-contrasena')->with(['warning_message' => 'Realice el cambio de contraseña para continuar'])->with(['warning_message_title' => 'Cambio de Contraseña Obligatorio']);
            }
            return $next($request);
        }else{
            if (strpos($request->path(), '/reserva') !== false) {
                return redirect('/login');
            }else{
                return redirect('/login-admin');
            }
        }
    }
}
