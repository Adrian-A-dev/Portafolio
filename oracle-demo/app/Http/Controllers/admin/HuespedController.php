<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Huesped;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HuespedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $huespedes = Huesped::where('deleted', 'N')->get();
        $title = 'Gestión de Huéspedes';
        $title_list = 'Listado de Huéspedes';
        $btn_nuevo = 'Nuevo Huésped';
        $url_btn_nuevo = '/dashboard/huespedes/nuevo';
        $side_huespedes = true;
        return view('admin.modulos.huespedes.huespedes_listado', compact(
            'title',
            'title_list',
            'side_huespedes',
            'huespedes',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

   
}
