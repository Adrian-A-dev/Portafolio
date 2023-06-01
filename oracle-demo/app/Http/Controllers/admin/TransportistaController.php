<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transportista;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class TransportistaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $transportistas = Transportista::where('deleted', 'N')->get();
        $title = 'Gestión de Transportistas';
        $title_list = 'Listado de Transportistas';
        $btn_nuevo = 'Nuevo Transportista';
        $url_btn_nuevo = '/dashboard/transportistas/nuevo';
        $side_transportistas = true;
        $side_transportistas_list = true;
        return view('admin.modulos.transportistas.transportistas_listado', compact(
            'title',
            'title_list',
            'side_transportistas',
            'side_transportistas_list',
            'transportistas',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {

        $title = 'Nuevo Transportista';
        $action = '/dashboard/transportistas/nuevo';
        $url_volver_listado = '/dashboard/transportistas';
        $volver_listado = 'Gestión de Transportistas';
        $title_form = 'Formulario de Registro de Transportista';
        $side_transportistas = true;
        $side_transportistas_new = true;
        return view('admin.modulos.transportistas.transportistas_nuevo', compact(
            'title',
            'side_transportistas',
            'side_transportistas_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form'
        ));
    }

    public function store(Request $request)
    {

        if (isset($request->form) && $request->form == 1) {

            $this->validate($request, [
                'rut' => 'required|min:11|max:13',
                'nombres' => 'required|min:3|max:100',
                'vehiculo' => 'required|min:3|max:100',
                'patente' => 'required|min:8|max:9',
            ], [
                'rut.required' => 'Rut Requerido',
                'rut.min' => 'Rut debe tener Mínimo 11 Caracteres',
                'rut.max' => 'Rut debe tener Máximo 13 Caracteres',
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'vehiculo.required' => 'Nombre Vehículo Requerido',
                'vehiculo.min' => 'Nombre Vehículo debe tener Mínimo 3 Caracteres',
                'vehiculo.max' => 'Nombre Vehículo debe tener Máximo 100 Caracteres',
                'patente.required' => 'Patente Requerida',
                'patente.min' => 'Patente debe tener Mínimo 8 Caracteres',
                'patente.max' => 'Patente debe tener Máximo 9 Caracteres',
            ]);

            $rut = trim($request->rut);
            $rut = str_replace('.', '', $rut);
            if (!validateRut($rut)) {
                return back()->with(['danger_message' => 'Rut posee formato inválido'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $valida_rut = DB::select("SELECT * from TRANSPORTISTA where UPPER(dni) = '" . strUpper($rut) . "' AND deleted = 'N'");
            if (!empty($valida_rut)) {
                return back()->with(['danger_message' => 'Rut de Transportista ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE TRANSPORTISTA
            $transportista = new Transportista();
            $transportista->dni = $rut;
            $transportista->nombre = strUpper($request->nombres);
            $transportista->vehiculo = strUpper($request->vehiculo);
            $transportista->patente = strUpper($request->patente);
            if ($transportista->save()) {
                return redirect('/dashboard/transportistas')->with(['success_message' => 'Se ha Registrado El Transportista Correctamente'])->with(['success_message_title' => 'Gestión de Transportistas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Transportista. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }

        $transportista = Transportista::where('id_transportista', $id)->where('deleted', 'N')->first();
        if (empty($transportista)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }


        $title = 'Editar Transportista';
        $title_form = 'Formulario de Edición de Transportista';
        $action = '/dashboard/transportistas/' . $id . '/editar';
        $url_volver_listado = '/dashboard/transportistas';
        $volver_listado = 'Gestión de Transportistas';
        // $comunas = Comuna::all();
        $side_transportistas = true;
        $side_transportistas_list = true;
        return view('admin.modulos.transportistas.transportistas_editar', compact(
            'title',
            'title_form',
            'side_transportistas',
            'side_transportistas_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'transportista'
        ));
    }

    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }

        $transportista = Transportista::where('id_transportista', $id)->where('deleted', 'N')->first();
        if (empty($transportista)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }
        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'rut' => 'required|min:11|max:13',
                'nombres' => 'required|min:3|max:100',
                'vehiculo' => 'required|min:3|max:100',
                'patente' => 'required|min:8|max:9',
            ], [
                'rut.required' => 'Rut Requerido',
                'rut.min' => 'Rut debe tener Mínimo 11 Caracteres',
                'rut.max' => 'Rut debe tener Máximo 13 Caracteres',
                'nombres.required' => 'Nombres Requerido',
                'nombres.min' => 'Nombres debe tener Mínimo 3 Caracteres',
                'nombres.max' => 'Nombres debe tener Máximo 100 Caracteres',
                'vehiculo.required' => 'Nombre Vehículo Requerido',
                'vehiculo.min' => 'Nombre Vehículo debe tener Mínimo 3 Caracteres',
                'vehiculo.max' => 'Nombre Vehículo debe tener Máximo 100 Caracteres',
                'patente.required' => 'Patente Requerida',
                'patente.min' => 'Patente debe tener Mínimo 8 Caracteres',
                'patente.max' => 'Patente debe tener Máximo 9 Caracteres',
            ]);

            $rut = trim($request->rut);
            $rut = str_replace('.', '', $rut);
            if (!validateRut($rut)) {
                return back()->with(['danger_message' => 'Rut posee formato inválido'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $valida_rut = DB::select("SELECT * from TRANSPORTISTA where UPPER(dni) = '" . strUpper($rut) . "' AND deleted = 'N' AND id_transportista != $id");
            if (!empty($valida_rut)) {
                return back()->with(['danger_message' => 'Rut de Transportista ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE MODIFICADO DE TRANSPORTISTA
            $transportista->dni = $rut;
            $transportista->nombre = strUpper($request->nombres);
            $transportista->vehiculo = strUpper($request->vehiculo);
            $transportista->patente = strUpper($request->patente);
            $transportista->estado = $request->estado == 1 ? 'Y' : 'N';
            $transportista->updated_at = ahoraServidor();
            if ($transportista->save()) {
                return redirect('/dashboard/transportistas')->with(['success_message' => 'Se ha Modificado el Transportista Correctamente'])->with(['success_message_title' => 'Gestión de Transportistas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Transportista. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function delete_transportista($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }

        $transportista = Transportista::where('id_transportista', $id)->where('deleted', 'N')->first();
        if (empty($transportista)) {
            return redirect('/dashboard/transportistas')->with(['danger_message' => 'Transportista No existe o fue Eliminado'])->with(['danger_message_title' => 'Transportista no encontrado']);
        }

        if (!empty($transportista)) {
            $transportista->deleted =  'Y';
            $transportista->deleted_at = ahoraServidor();
            if ($transportista->save()) {
                return redirect('/dashboard/transportistas')->with(['success_message' => 'Se ha Eliminado Transportista Correctamente'])->with(['success_message_title' => 'Gestión de Transportistas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Transportista. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Transportista no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $transportista = Transportista::where('id_transportista', $id)->where('deleted', 'N')->first();
                if (empty($transportista)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $transportista->deleted =  'Y';
                    $transportista->deleted_at = ahoraServidor();
                    if ($transportista->save()) {
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
