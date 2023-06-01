<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class AccesorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }


    public function index()
    {
        $accesorios = Accesorio::where('deleted', 'N')->orderby('accesorio')->get();
        $title = 'Gestión de Accesorios';
        $title_list = 'Listado de Accesorios';
        $btn_nuevo = 'Nuevo Accesorio';
        $url_btn_nuevo = '/dashboard/accesorios/nuevo';
        $side_accesorios = true;
        $side_accesorios_list = true;
        return view('admin.modulos.accesorios.accesorios_listado', compact(
            'title',
            'title_list',
            'side_accesorios',
            'side_accesorios_list',
            'btn_nuevo',
            'url_btn_nuevo',
            'accesorios'
        ));
    }


    public function create()
    {
        $title = 'Nuevo Accesorio';
        $action = '/dashboard/accesorios/nuevo';
        $url_volver_listado = '/dashboard/accesorios';
        $volver_listado = 'Gestión de Accesorios';
        $title_form = 'Formulario de Registro de Accesorios';
        $side_accesorios = true;
        $side_accesorios_new = true;

        return view('admin.modulos.accesorios.accesorios_nuevo', compact(
            'title',
            'side_accesorios',
            'side_accesorios_new',
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
                'accesorio' => 'required|min:3|max:100',
                'estado' => 'required',
            ], [
                'accesorio.required' => ' Nombre de Accesorio Requerido',
                'accesorio.min' => ' Nombre de Accesorio debe tener Mínimo 3 Caracteres',
                'accesorio.max' => ' Nombre de Accesorio debe tener Máximo 100 Caracteres',
                'estado.required' => ' Estado Requerido',
            ]);
            $valida_accesorio = DB::select("SELECT * from ACCESORIO where UPPER(ACCESORIO) = '" . strUpper($request->accesorio) . "' AND deleted = 'N'");
            if (!empty($valida_accesorio)) {
                return back()->with(['danger_message' => 'Nombre de Accesorio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $accesorio = $request->accesorio;
            $estado = $request->estado == 1 ? 'Y' : 'N';
            $accesorioAdd = new Accesorio();
            $accesorioAdd->accesorio = $accesorio;
            $accesorioAdd->estado = $estado;
            $accesorioAdd->save();
            if ($accesorioAdd->id_accesorio > 0) {
                return redirect('/dashboard/accesorios')->with(['success_message' => 'Se ha Registrado el Accesorio Correctamente'])->with(['success_message_title' => 'Gestión de Accesorios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Accesorio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }
        // select * from accesorio where id_accesorio = 1 AND deleted = 'N';
        $accesorio = Accesorio::where('id_accesorio', $id)->where('deleted', 'N')->first();
        if (empty($accesorio)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }

        $title = 'Editar Accesorio';
        $action = '/dashboard/accesorios/' . $id . '/editar';
        $url_volver_listado = '/dashboard/accesorios';
        $volver_listado = 'Gestión de Accesorios';
        $title_form = 'Formulario de Edición de Accesorios';
        $side_accesorios = true;
        $side_accesorios_list = true;
        return view('admin.modulos.accesorios.accesorios_editar', compact(
            'title',
            'side_accesorios',
            'side_accesorios_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'accesorio'
        ));
    }
    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }

        $accesorio = Accesorio::where('id_accesorio', $id)->where('deleted', 'N')->first();
        if (empty($accesorio)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }

        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'accesorio' => 'required|min:3|max:100',
                'estado' => 'required',
            ], [
                'accesorio.required' => ' Nombre de Accesorio Requerido',
                'accesorio.min' => ' Nombre de Accesorio debe tener Mínimo 3 Caracteres',
                'accesorio.max' => ' Nombre de Accesorio debe tener Máximo 100 Caracteres',
                'estado.required' => ' Estado Requerido',
            ]);

            $valida_accesorio = DB::select("SELECT * from ACCESORIO where UPPER(ACCESORIO) = '" . strUpper($request->accesorio) . "' AND deleted = 'N' AND id_accesorio != $id");
            if (!empty($valida_accesorio)) {
                return back()->with(['danger_message' => 'Nombre de Accesorio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $accesorio = $request->accesorio;
            $estado = $request->estado == 1 ? 'Y' : 'N';

            $accesorioUpdated = Accesorio::find($id);
            $accesorioUpdated->accesorio = $accesorio;
            $accesorioUpdated->estado = $estado;
            $accesorioUpdated->updated_at = ahoraServidor();
            if ($accesorioUpdated->save()) {
                return redirect('/dashboard/accesorios')->with(['success_message' => 'Se ha Modificado el Accesorio Correctamente'])->with(['success_message_title' => 'Gestión de Accesorios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Accesorio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function delete_accesorio($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }
        $accesorio = Accesorio::where('id_accesorio', $id)->where('deleted', 'N')->first();
        if (empty($accesorio)) {
            return redirect('/dashboard/accesorios')->with(['danger_message' => 'Accesorio No existe o fue eliminado'])->with(['danger_message_title' => 'Accesorio no encontrado']);
        }

        if (!empty($accesorio)) {
            $accesorio->deleted =  'Y';
            $accesorio->deleted_at = ahoraServidor();
            if ($accesorio->save()) {
                return redirect('/dashboard/accesorios')->with(['success_message' => 'Se ha Eliminado el Accesorio Correctamente'])->with(['success_message_title' => 'Gestión de Accesorios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Accesorio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Accesorio no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $accesorio = Accesorio::where('id_accesorio', $id)->where('deleted', 'N')->first();
                if (empty($accesorio)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $accesorio->deleted =  'Y';
                    $accesorio->deleted_at = ahoraServidor();
                    if ($accesorio->save()) {
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
