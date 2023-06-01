<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\IsRoot;

class TipoServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        $this->middleware('isRoot');
        
        #todos los campos de tipo_servicio y el NOMBRE de COMUNA 
        $tipo_servicios = TipoServicio::where('deleted', 'N')->get();
        $title = 'Gestión de Tipo de Servicios';
        $title_list = 'Listado de Tipo de Servicios';
        $btn_nuevo = 'Nuevo Tipo de Servicios';
        $url_btn_nuevo = '/dashboard/tipo-servicios/nuevo';
        $side_tipo_servicios = true;
        $side_tipo_servicios_list = true;
        return view('admin.modulos.tipo_servicios.tipo_servicios_listado', compact(
            'title',
            'title_list',
            'side_tipo_servicios',
            'side_tipo_servicios_list',
            'tipo_servicios',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {
        
        $title = 'Nuevo Tipo de Servicio';
        $action = '/dashboard/tipo-servicios/nuevo';
        $url_volver_listado = '/dashboard/tipo-servicios';
        $volver_listado = 'Gestión de Tipo de Servicios';
        $title_form = 'Formulario de Registro de Tipo de Servicio';
        $side_tipo_servicios = true;
        $side_tipo_servicios_new = true;
        return view('admin.modulos.tipo_servicios.tipo_servicios_nuevo', compact(
            'title',
            'side_tipo_servicios',
            'side_tipo_servicios_new',

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
                'nombre' => 'required|min:3|max:100',
                'estado' => 'required',
                'lugares' => 'required',
                'horarios' => 'required',
                'transportista' => 'required',
            ], [
                'nombre.required' => 'Nombre de Cargo Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'estado.required' => 'Estado Requerido',
                'lugares.required' => 'Mostrar Campo de Lugares en Servicio Requerido',
                'horarios.required' => 'Mostrar Campo de Horarios en Servicio Requerido',
                'transportista.required' => 'Mostrar Campo de Transportista en Servicio Requerido',
            ]);
            $valida_nombre = DB::select("SELECT * from TIPO_SERVICIO where UPPER(tipo_servicio) = '" . strUpper($request->nombre) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de  Tipo de Servicio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE CARGO
            $tipo_servicio = new TipoServicio();
            $tipo_servicio->tipo_servicio = strUpper($request->nombre);
            $tipo_servicio->estado = $request->estado == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_lugar = $request->lugares == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_horario = $request->horarios == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_transportista = $request->transportista == 1 ? 'Y' : 'N';
            if ($tipo_servicio->save()) {
                return redirect('/dashboard/tipo-servicios')->with(['success_message' => 'Se ha Registrado El Tipo de Servicio Correctamente'])->with(['success_message_title' => 'Gestión de  Tipo de Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear  Tipo de Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function edit($id)
    {
        
        
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }

        $tipo_servicio = TipoServicio::where('id_tipo_servicio', $id)->where('deleted', 'N')->first();
        if (empty($tipo_servicio)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }
        $title = 'Editar Tipo de Servicio';
        $action = '/dashboard/tipo-servicios/' . $id . '/editar';
        $url_volver_listado = '/dashboard/tipo-servicios';
        $volver_listado = 'Gestión de Tipo de Servicios';
        $side_tipo_servicios = true;
        $side_tipo_servicios_list = true;
        $title_form = 'Formulario de Edición de Tipo de Servicio';
        return view('admin.modulos.tipo_servicios.tipo_servicios_editar', compact(
            'title',
            'side_tipo_servicios',
            'side_tipo_servicios_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'tipo_servicio'
        ));
    }


    public function update($id, Request $request)
    {
        
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }

        $tipo_servicio = TipoServicio::where('id_tipo_servicio', $id)->where('deleted', 'N')->first();
        if (empty($tipo_servicio)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }
        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'estado' => 'required',
                'lugares' => 'required',
                'horarios' => 'required',
                'transportista' => 'required',
            ], [
                'nombre.required' => 'Nombre de Tipo Servicio Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'estado.required' => 'Estado Requerido',
                'lugares.required' => 'Mostrar Campo de Lugares en Servicio Requerido',
                'horarios.required' => 'Mostrar Campo de Horarios en Servicio Requerido',
                'transportista.required' => 'Mostrar Campo de Transportista en Servicio Requerido',
            ]);
            $valida_nombre = DB::select("SELECT * from TIPO_SERVICIO where UPPER(tipo_servicio) = '" . strUpper($request->nombre) . "' AND deleted = 'N' AND id_tipo_servicio != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de  Tipo de Servicio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE CARGO
            $tipo_servicio->tipo_servicio = strUpper($request->nombre);
            $tipo_servicio->estado = $request->estado == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_lugar = $request->lugares == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_horario = $request->horarios == 1 ? 'Y' : 'N';
            $tipo_servicio->flg_transportista = $request->transportista == 1 ? 'Y' : 'N';
            
            if ($tipo_servicio->save()) {
                return redirect('/dashboard/tipo-servicios')->with(['success_message' => 'Se ha Modificado el Tipo de Servicio Correctamente'])->with(['success_message_title' => 'Gestión de Tipo de Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Tipo de Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function delete_tipo_servicio($id)
    {
        
        if (!is_numeric($id)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }

        $tipo_servicio = TipoServicio::where('id_tipo_servicio', $id)->where('deleted', 'N')->first();
        if (empty($tipo_servicio)) {
            return redirect('/dashboard/tipo-servicios')->with(['danger_message' => 'Tipo Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Tipo Servicio no encontrado']);
        }

        if (!empty($tipo_servicio)) {
            $tipo_servicio->deleted =  'Y';
            $tipo_servicio->deleted_at = ahoraServidor();
            if ($tipo_servicio->save()) {
                return redirect('/dashboard/tipo-servicios')->with(['success_message' => 'Se ha Eliminado Tipo Servicio Correctamente'])->with(['success_message_title' => 'Gestión de Tipo Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Tipo Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Tipo Servicio no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }


    public function getTipoServicio(Request $request){
        if(!empty($request->tipo_servicio)){
            $id_tipo_servicio = $request->tipo_servicio;
            if (is_numeric($id_tipo_servicio)) {
                if ($id_tipo_servicio < 0) {
                    echo 'error';
                } else {
                    $tipo_servicio = TipoServicio::where('id_tipo_servicio', $id_tipo_servicio)->where('deleted', 'N')->first();
                    if (!empty($tipo_servicio)) {
                        echo json_encode($tipo_servicio);
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

    public function deleted(Request $request)
    {
        
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $tipo_servicio = TipoServicio::where('id_tipo_servicio', $id)->where('deleted', 'N')->first();
                if (empty($tipo_servicio)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $tipo_servicio->deleted =  'Y';
                    $tipo_servicio->deleted_at = ahoraServidor();
                    if ($tipo_servicio->save()) {
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
