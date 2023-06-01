<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TipoMulta;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class TipoMultaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        $tipo_multas = TipoMulta::where('deleted', 'N')->get();
        $title = 'Gestión de Tipo de Multas';
        $title_list = 'Listado de Tipo de Multas';
        $btn_nuevo = 'Nuevo Tipo de Multa';
        $url_btn_nuevo = '/dashboard/tipo-multas/nuevo';
        $side_tipo_multas = true;
        $side_tipo_multas_list = true;
        return view('admin.modulos.tipo_multas.tipo_multas_listado', compact(
            'title',
            'title_list',
            'side_tipo_multas',
            'side_tipo_multas_list',
            'tipo_multas',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {

        $title = 'Nuevo Tipo de Multa';
        $action = '/dashboard/tipo-multas/nuevo';
        $url_volver_listado = '/dashboard/tipo-multas';
        $volver_listado =  'Gestión de Tipo de Multas';
        $title_form = 'Formulario de Registro de Tipo de Multa';
        $side_tipo_multas = true;
        $side_tipo_multas_new = true;
        $tipo_multas = TipoMulta::where('deleted', 'N')->where('estado', 'Y')->get();
        return view('admin.modulos.tipo_multas.tipo_multas_nuevo', compact(
            'title',
            'side_tipo_multas',
            'side_tipo_multas_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'tipo_multas'
        ));
    }

    public function store(Request $request)
    {
        if (isset($request->form) && $request->form == 1) {
            
            $this->validate($request, [
                'tipo_multa' => 'required|min:3|max:100',
                'estado' => 'required',
            ], [
                'tipo_multa.required' => ' Tipo de Multa Requerido',
                'tipo_multa.min' => ' Tipo de Multa debe tener Mínimo 3 Caracteres',
                'tipo_multa.max' => ' Tipo de Multa debe tener Máximo 100 Caracteres',
                'estado.required' => ' Estado Requerida',
            ]);
            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from TIPO_MULTA where UPPER(tipo_multa) = '" . strUpper($request->tipo_multa) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Tipo Multa ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE TIPO MULTA
            $tipo_multaAdd = new TipoMulta();
            $tipo_multaAdd->tipo_multa = $request->tipo_multa;
            $tipo_multaAdd->estado = $request->estado == 1 ? 'Y' : 'N';

            if ($tipo_multaAdd->save()) {
                return redirect('/dashboard/tipo-multas')->with(['success_message' => 'Se ha Registrado El Tipo de Multa Correctamente'])->with(['success_message_title' => 'Gestión de Tipo de Multas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Tipo de Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }

        } else {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }

        $tipo_multa = TipoMulta::where('id_tipo_multa', $id)->where('deleted', 'N')->first();
        if (empty($tipo_multa)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }

        $title = 'Editar Tipo de Multa';
        $title_form = 'Formulario de Edición de Tipo de Multa';
        $action = '/dashboard/tipo-multas/' . $id . '/editar';
        $url_volver_listado = '/dashboard/tipo-multas';
        $volver_listado = 'Gestión de Tipo de Multas';
        $side_tipo_multas = true;
        $side_tipo_multas_list = true;

        return view('admin.modulos.tipo_multas.tipo_multas_editar', compact(
            'title',
            'title_form',
            'side_tipo_multas',
            'side_tipo_multas_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'tipo_multa',
        ));
    }
    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }

        $tipo_multa = TipoMulta::where('id_tipo_multa', $id)->where('deleted', 'N')->first();
        if (empty($tipo_multa)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }
        if (isset($request->form) && $request->form == 1) {
            
            #Validar formulario de Editar - POST Method
            $this->validate($request, [
                'tipo_multa' => 'required|min:3|max:100',
                'estado' => 'required',
            ], [
                'tipo_multa.required' => ' Tipo de Multa Requerido',
                'tipo_multa.min' => ' Tipo de Multa debe tener Mínimo 3 Caracteres',
                'tipo_multa.max' => ' Tipo de Multa debe tener Máximo 100 Caracteres',
                'estado.required' => ' Estado Requerida',
            ]);

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE
            $valida_nombre = DB::select("SELECT * from TIPO_MULTA where UPPER(tipo_multa) = '" . strUpper($request->tipo_multa) . "' AND deleted = 'N' AND id_tipo_multa != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Tipo Multa ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }

            #LOGICA DE MODIFICADO DE TIPO MULTA
            $TipoMultaUpdated = TipoMulta::find($id);
            $TipoMultaUpdated->tipo_multa = $request->tipo_multa;
            $TipoMultaUpdated->estado = $request->estado == 1 ? 'Y' : 'N';
            $TipoMultaUpdated->updated_at = ordenar_fechaHoraServidor();
            $TipoMultaUpdated->save();

            if ($TipoMultaUpdated->save()) {
                return redirect('/dashboard/tipo-multas')->with(['success_message' => 'Se ha Modificado el Tipo de Multa Correctamente'])->with(['success_message_title' => 'Gestión de Tipo de Multas']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Tipo de Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }

        } else {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function delete_tipo_multa($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }

        $tipo_multa = TipoMulta::where('id_tipo_multa', $id)->where('deleted', 'N')->first();
        if (empty($tipo_multa)) {
            return redirect('/dashboard/tipo-multas')->with(['danger_message' => 'Tipo Multa No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Multa no encontrada']);
        }

        if (!empty($tipo_multa)) {
            $tipo_multa->deleted =  'Y';
            $tipo_multa->deleted_at = ordenar_fechaHoraServidor();
            if ($tipo_multa->save()) {
                return redirect('/dashboard/tipo-multas')->with(['success_message' => 'Se ha Eliminado Tipo Multa Correctamente'])->with(['success_message_title' => 'Gestión de Tipo Multa']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Tipo Multa. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Tipo Multa no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $tipo_multa = TipoMulta::where('id_tipo_multa', $id)->where('deleted', 'N')->first();
                if (empty($tipo_multa)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $tipo_multa->deleted =  'Y';
                    $tipo_multa->deleted_at = ahoraServidor();
                    if ($tipo_multa->save()) {
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
