<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Multa;
use App\Models\TipoMulta;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class MultaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        $multas = Multa::where('deleted', 'N')->get();
        $tipo_multa = TipoMulta::where('deleted', 'N')->get();
        $title = 'Gestión de Multas';
        $title_list = 'Listado de Multas';
        $btn_nuevo = 'Nueva Multa';
        $url_btn_nuevo = '/dashboard/multas/nueva';
        $side_multas = true;
        $side_multas_list = true;
        return view('admin.modulos.multas.multas_listado', compact(
            'title',
            'title_list',
            'side_multas',
            'side_multas_list',
            'multas',
            'btn_nuevo',
            'url_btn_nuevo',
            'tipo_multa',
        ));
    }

    public function create()
    {
        $title = 'Nueva Multa';
        $action = '/dashboard/multas/nueva';
        $url_volver_listado = '/dashboard/multas';
        $volver_listado = 'Gestión de Multas';
        $title_form = 'Formulario de Registro de Multa';
        $side_multas = true;
        $side_multas_new = true;
        $tipo_multas = TipoMulta::where('deleted', 'N')->where('estado', 'Y')->get();
        return view('admin.modulos.multas.multas_nuevo', compact(
            'title',
            'side_multas',
            'side_multas_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'tipo_multas',
        ));
    }

    public function store(Request $request)
    {
        if (isset($request->form) && $request->form == 1) {
            #Valida campor de formulario
            $this->validate($request, [
                'multa' => 'required|min:3|max:100',
                'tipo_multa' => 'required',
                'valor' => 'required',
                'estado' => 'required',
            ], [
                'multa.required' => ' Multa Requerida',
                'multa.min' => ' Multa debe tener Mínimo 3 Caracteres',
                'multa.max' => ' Multa debe tener Máximo 100 Caracteres',
                'tipo_multa.required' => ' Tipo de Multa Requerida',
                'valor.required' => ' Valor de Multa Requerido',
            ]);

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE
            $valida_nombre = DB::select("SELECT * from MULTA where UPPER(multa) = '" . strUpper($request->multa) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Multa ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            
            #Validar monto no menor a 0
            if(limpiaMoneda($request->valor)<1){
                return back()->with(['danger_message' => 'Valor debe ser mayor a $0'])->with(['danger_message_title' => 'Error de Validación'])->withInput();    
            }

            #LOGICA DE GUARDADO DE TIPO MULTA
            $multaAdd = new Multa();
            $multaAdd->multa = $request->multa;
            $multaAdd->id_tipo_multa = $request->tipo_multa;
            $multaAdd->monto = limpiaMoneda($request->valor);
            $multaAdd->estado = $request->estado == 1 ? 'Y' : 'N';

            #LOGICA DE GUARDADO DE MULTA
            if ($multaAdd->save()) {
                return redirect('/dashboard/multas')->with(['success_message' => 'Se ha Registrado La Multa Correctamente'])->with(['success_message_title' => 'Gestión de Multas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }

        $multa = Multa::where('id_multa', $id)->where('deleted', 'N')->first();
        if (empty($multa)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }
        $tipo_multas = TipoMulta::where('estado', 'Y')->where('deleted', 'N')->get();
        // $tipo_multas = TipoMulta::where('deleted', 'N')->get();
        // pre_die($tipo_multas);
        $title = 'Editar Multa';
        $title_form = 'Formulario de Edición de Multa';
        $action = '/dashboard/multas/' . $id . '/editar';
        $url_volver_listado = '/dashboard/multas';
        $volver_listado = 'Gestión de Multas';
        $side_multas = true;
        $side_multas_list = true;
        return view('admin.modulos.multas.multas_editar', compact(
            'title',
            'title_form',
            'side_multas',
            'side_multas_list',
            'multa',
            'action',
            'url_volver_listado',
            'volver_listado',
            'tipo_multas',
        ));
    }


    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }

        $multaUpdated = Multa::where('id_multa', $id)->where('deleted', 'N')->first();
        if (empty($multaUpdated)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }
        if (isset($request->form) && $request->form == 1) {
            
            #Valida campor de formulario
            $this->validate($request, [
                'multa' => 'required|min:3|max:100',
                'tipo_multa' => 'required',
                'valor' => 'required',
                'estado' => 'required',
            ], [
                'multa.required' => ' Multa Requerida',
                'multa.min' => ' Multa debe tener Mínimo 3 Caracteres',
                'multa.max' => ' Multa debe tener Máximo 100 Caracteres',
                'tipo_multa.required' => ' Tipo de Multa Requerida',
                'valor.required' => ' Valor de Multa Requerido',
            ]);
            
            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from MULTA where UPPER(multa) = '" . strUpper($request->multa) . "' AND deleted = 'N' AND id_multa != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Multa ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }

            #Validar monto no menor a 0
            if(limpiaMoneda($request->valor)<1){
                return back()->with(['danger_message' => 'Valor debe ser mayor a $0'])->with(['danger_message_title' => 'Error de Validación'])->withInput();    
            }

            #LOGICA DE MODIFICADO DE MULTA
            $multaUpdated->multa = $request->multa;
            $multaUpdated->id_tipo_multa = $request->tipo_multa;
            $multaUpdated->monto = limpiaMoneda($request->valor);
            $multaUpdated->estado = $request->estado == 1 ? 'Y' : 'N';

            if ($multaUpdated->save()) {
                return redirect('/dashboard/multas')->with(['success_message' => 'Se ha Modificado La Multa Correctamente'])->with(['success_message_title' => 'Gestión de Multas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }

        } else {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function delete_multa($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }

        $multa = Multa::where('id_multa', $id)->where('deleted', 'N')->first();
        if (empty($multa)) {
            return redirect('/dashboard/multas')->with(['danger_message' => 'Multa No existe o fue Eliminada'])->with(['danger_message_title' => 'Multa no encontrada']);
        }

        if (!empty($multa)) {
            $multa->deleted =  'Y';
            $multa->deleted_at = ordenar_fechaHoraServidor();
            if ($multa->save()) {
                return redirect('/dashboard/multas')->with(['success_message' => 'Se ha Eliminada Multa Correctamente'])->with(['success_message_title' => 'Gestión de Multas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Multa no existe o fue Eliminada'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $multa = Multa::where('id_multa', $id)->where('deleted', 'N')->first();
                if (empty($multa)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $multa->deleted =  'Y';
                    $multa->deleted_at = ahoraServidor();
                    if ($multa->save()) {
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
