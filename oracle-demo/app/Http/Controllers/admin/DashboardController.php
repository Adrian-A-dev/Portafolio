<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comuna;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }

    public function index()
    {
        if (auth()->user()->id_rol == 3) {
            return back()->with(['danger_message' => 'Cliente no posee permisos necesarios'])->with(['danger_message_title' => 'Error de Validación']);
        }
        #$usuario = Usuarios::select('*') ->join('empleado as e', 'usuario.id_usuario', '=', 'e.id_usuario')->where('e.id_usuario', auth()->user()->id_usuario)->first();
        // $empleado = Empleado::find(1);
        // pre_die($empleado->usuario->email);
        $side_dashboard = true;
        $title = 'Dashboard';
        return view('admin.dashboard', compact('title',
         'side_dashboard'));
    }

    public function getComunas(Request $request) #PROCESOS NUEVOS PARA INDEX 2022-03-09
    {
        if(!empty($request->region)){
            $id_region = $request->region;
            $html2 = '<option value="" selected>Seleccione Comuna..</option>';
            if (is_numeric($id_region)) {
                if ($id_region < 0) {
                    echo 'error';
                } else {
                    $comunas = Comuna::where(['region_id_region' => $id_region])->orderby('comuna')->get();
                    if (!empty($comunas)) {
                        foreach ($comunas as $value) {
                            $html2 .= "<option value='$value->id_comuna'>$value->comuna </option>";
                        }
                        echo ($html2);
                    } else {
                        echo 'error';
                    }
                }
            } else {
                echo 'error';
            }
        }else{
            echo 'error';
        }
        
    }
}