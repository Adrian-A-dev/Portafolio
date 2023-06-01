<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comuna;
use App\Models\Region;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
        
    }

    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $sucursales = Sucursal::select('sucursal.*', 'c.comuna')
            ->join('comuna as c', 'sucursal.comuna_id_comuna', '=', 'c.id_comuna', 'left')
            ->where('sucursal.deleted', 'N')
            ->get();
        $title = 'Gestión de Sucursales';
        $title_list = 'Listado de Sucursales';
        $btn_nuevo = 'Nueva Sucursal';
        $url_btn_nuevo = '/dashboard/sucursales/nueva';
        $side_sucursales = true;
        $side_sucursales_list = true;
        return view('admin.modulos.sucursales.sucursales_listado', compact(
            'title',
            'title_list',
            'side_sucursales',
            'side_sucursales_list',
            'sucursales',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {

        $title = 'Nueva Sucursal';
        $action = '/dashboard/sucursales/nueva';
        $url_volver_listado = '/dashboard/sucursales';
        $volver_listado = 'Gestión de Sucursales';
        $title_form = 'Formulario de Registro de Sucursal';
        $regiones = Region::orderBy('id_region')->get();
        $comunas = Comuna::orderBy('comuna')->get();
        $side_sucursales = true;
        $side_sucursales_new = true;
        return view('admin.modulos.sucursales.sucursales_nuevo', compact(
            'title',
            'side_sucursales',
            'side_sucursales_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'regiones',
            'comunas'
        ));
    }

    public function store(Request $request)
    {
        if (isset($request->form) && $request->form == 1) {

            $this->validate($request, [
                'sucursal' => 'required|min:3|max:100',
                'comuna' => 'required',
            ], [
                'sucursal.required' => ' Sucursal Requerida',
                'sucursal.min' => ' Sucursal debe tener Mínimo 3 Caracteres',
                'sucursal.max' => ' Sucursal debe tener Máximo 100 Caracteres',
                'comuna.required' => ' Comuna Requerida',
            ]);

            $valida_sucursal = DB::select("SELECT * from sucursal where UPPER(sucursal) = '" . strUpper($request->sucursal) . "' AND deleted = 'N'");
            if (!empty($valida_sucursal)) {
                return back()->with(['danger_message' => 'Nombre de Sucursal ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }

            $sucursal = new Sucursal();
            $sucursal->sucursal =  strUpper($request->sucursal);
            $sucursal->comuna_id_comuna = $request->comuna;
            if ($sucursal->save()) {

                return redirect('/dashboard/sucursales')->with(['success_message' => 'Se ha Registrado la Sucursal Correctamente'])->with(['success_message_title' => 'Gestión de Sucursales']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Sucursal. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Sucursal no encontrada']);
        }
        $sucursal = Sucursal::where('id_sucursal', $id)->where('deleted', 'N')->first();
        if (empty($sucursal)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Sucursal no encontrada']);
        }

        $title = 'Editar Sucursal';
        $title_form = 'Formulario de Edición de Sucursal';
        $action = '/dashboard/sucursales/' . $id . '/editar';
        $url_volver_listado = '/dashboard/sucursales';
        $volver_listado = 'Gestión de Sucursales';
        $side_sucursales = true;
        $side_sucursales_list = true;
        $regiones = Region::orderBy('id_region')->get();
        if (!empty($sucursal->comuna->region_id_region)) {
            $comunas = Comuna::where('region_id_region', $sucursal->comuna->region_id_region)->orderBy('comuna')->get();
        } else {
            $comunas = [];
        }

        return view('admin.modulos.sucursales.sucursales_editar', compact(
            'title',
            'title_form',
            'side_sucursales',
            'side_sucursales_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'sucursal',
            'regiones',
            'comunas'
        ));
    }

    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Sucursal no encontrada']);
        }

        $sucursalUpdated = Sucursal::where('id_sucursal', $id)->where('deleted', 'N')->first();
        if (empty($sucursalUpdated)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Sucursal no encontrada']);
        }

        //Validar formulario de Editar - POST Method
        $this->validate($request, [
            'sucursal' => 'required|min:3|max:100',
            'comuna' => 'required',
            'estado' => 'required',
        ], [
            'sucursal.required' => ' Sucursal Requerida',
            'sucursal.min' => ' Sucursal debe tener Mínimo 3 Caracteres',
            'sucursal.max' => ' Sucursal debe tener Máximo 100 Caracteres',
            'comuna.required' => ' Comuna Requerida',
            'estado.required' => ' Estado Requerido',
        ]);
        $valida_nombre = DB::select("SELECT * from sucursal where UPPER(sucursal) = '" . strUpper($request->sucursal) . "' AND deleted = 'N' AND id_sucursal != $id");
        if (!empty($valida_nombre)) {
            return back()->with(['danger_message' => 'Nombre de Sucursal ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
        }

        $sucursalUpdated->sucursal = $request->sucursal;
        $sucursalUpdated->comuna_id_comuna = $request->comuna;
        $sucursalUpdated->estado = $request->estado == 1 ? 'Y' : 'N';
        $sucursalUpdated->updated_at = ordenar_fechaHoraServidor();

        if ($sucursalUpdated->save()) {
            return redirect('/dashboard/sucursales')->with(['success_message' => 'Se ha Registrado la Sucursal Correctamente'])->with(['success_message_title' => 'Gestión de Sucursales']);
        } else {
            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Sucursal. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
        }
    }
    public function delete_sucursal($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Registro no encontrado']);
        }
        $sucursal = Sucursal::where('id_sucursal', $id)->where('deleted', 'N')->first();
        if (empty($sucursal)) {
            return redirect('/dashboard/sucursales')->with(['danger_message' => 'Sucursal No existe o fue Eliminada'])->with(['danger_message_title' => 'Registro no encontrado']);
        }
        $sucursal->deleted =  'Y';
        $sucursal->deleted_at = ahoraServidor();
        if ($sucursal->save()) {
            return redirect('/dashboard/sucursales')->with(['success_message' => 'Se ha Eliminado La Sucursal Correctamente'])->with(['success_message_title' => 'Gestión de Sucursales']);
        } else {
            return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Sucursal. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $sucursal = Sucursal::where('id_sucursal', $id)->where('deleted', 'N')->first();
                if (empty($sucursal)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $sucursal->deleted =  'Y';
                    $sucursal->deleted_at = ahoraServidor();
                    if ($sucursal->save()) {
                        echo 'ok';
                    } else {
                        echo 'Ocurrió un problema al Eliminar. Intente Nuevamente';
                    }
                }
            }
        } else {
            echo 'No se ha indicado ID';
        }
    }
}
