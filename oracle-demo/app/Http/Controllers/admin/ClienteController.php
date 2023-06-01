<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        $clientes = Cliente::select('cliente.*', 'c.comuna')
            ->join('comuna as c', 'cliente.id_comuna', '=', 'c.id_comuna', 'left')
            ->where('cliente.deleted', 'N')
            ->get();
        foreach ($clientes as $cl) {
            $cl->reservas = Reserva::where('id_cliente', $cl->id_cliente)->count();
        }
        $title = 'Gestión de Clientes';
        $title_list = 'Listado de Clientes';
        $btn_nuevo = 'Nuevo Cliente';
        $url_btn_nuevo = '/dashboard/clientes/nuevo';
        $side_clientes = true;
        return view('admin.modulos.clientes.clientes_listado', compact(
            'title',
            'title_list',
            'side_clientes',
            'clientes',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function activar($id_cliente)
    {
        $cliente = Cliente::where('id_cliente', $id_cliente)->where('deleted', 'N')->first();
        if(!empty($cliente)){
            $cliente->estado  = 'Y';
            $cliente->updated_at = ahoraServidor();
            if($cliente->save()){
                return redirect('/dashboard/clientes')->with(['success_message' => 'Cliente Activado Correctamente.'])->with(['success_message_title' => 'Gestión de Clientes']);
            }else{
                return  redirect('/dashboard/clientes')->with(['danger_message' => 'Ha Ocurrido un error al modificar Cliente. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }else{
            return  redirect('/dashboard/clientes')->with(['danger_message' => 'Cliente no existe o fue eliminado'])->with(['danger_message_title' => 'Gestión de Clientes']);
        }
    }

    public function desactivar($id_cliente)
    {
        $cliente = Cliente::where('id_cliente', $id_cliente)->where('deleted', 'N')->first();
        if(!empty($cliente)){
            $cliente->estado  = 'N';
            $cliente->updated_at = ahoraServidor();
            if($cliente->save()){
                return redirect('/dashboard/clientes')->with(['success_message' => 'Cliente Activado Correctamente.'])->with(['success_message_title' => 'Gestión de Clientes']);
            }else{
                return  redirect('/dashboard/clientes')->with(['danger_message' => 'Ha Ocurrido un error al modificar Cliente. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        }else{
            return  redirect('/dashboard/clientes')->with(['danger_message' => 'Cliente no existe o fue eliminado'])->with(['danger_message_title' => 'Gestión de Clientes']);
        }
    }
    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $cliente = Cliente::where('id_cliente', $id)->where('deleted', 'N')->first();
                if (empty($cliente)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $deleted = false;
                    $usuario = Usuario::where('id_usuario', $cliente->id_usuario)->where('deleted', 'N')->first();
                    if (!empty($usuario)) {
                        $usuario->deleted =  'Y';
                        $usuario->deleted_at = ahoraServidor();
                        if($usuario->save()){
                            $deleted = true;
                        }
                    }else{
                        $deleted = true;
                    }
                    if($deleted){
                        $cliente->deleted =  'Y';
                        $cliente->deleted_at = ahoraServidor();
                        if ($cliente->save()) {
                            echo 'ok';
                        } else {
                            echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                        }
                    }else{
                        echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                    }
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }
}
