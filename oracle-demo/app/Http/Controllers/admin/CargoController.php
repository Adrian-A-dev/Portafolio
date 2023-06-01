<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class CargoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }


    public function index()
    {
        $cargos = Cargo::where('deleted', 'N')->orderby('cargo')->get();
        $title = 'Gestión de Cargos';
        $title_list = 'Listado de Cargos';
        $btn_nuevo = 'Nuevo Cargo';
        $url_btn_nuevo = '/dashboard/cargos/nuevo';
        $side_cargos = true;
        $side_cargos_list = true;
        return view('admin.modulos.cargos.cargos_listado', compact(
            'title',
            'title_list',
            'side_cargos',
            'side_cargos_list',
            'btn_nuevo',
            'url_btn_nuevo',
            'cargos'
        ));
    }


    public function create()
    {

        $title = 'Nuevo Cargo';
        $action = '/dashboard/cargos/nuevo';
        $url_volver_listado = '/dashboard/cargos';
        $volver_listado = 'Gestión de Cargos';
        $title_form = 'Formulario de Registro de Cargo';
        $side_cargos = true;
        $side_cargos_new = true;

        return view('admin.modulos.cargos.cargos_nuevo', compact(
            'title',
            'side_cargos',
            'side_cargos_new',
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
            ], [
                'nombre.required' => 'Nombre de Cargo Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'estado.required' => 'Estado Requerido',
            ]);
            $valida_nombre = DB::select("SELECT * from CARGO where UPPER(cargo) = '" . strUpper($request->nombre) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Cargo ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE CARGO
            $cargo = new cargo();
            $cargo->cargo = strUpper($request->nombre);
            $cargo->estado = $request->estado == 1 ? 'Y' : 'N';
            if ($cargo->save()) {
                return redirect('/dashboard/cargos')->with(['success_message' => 'Se ha Registrado El Cargo Correctamente'])->with(['success_message_title' => 'Gestión de Cargos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Cargo. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }

        } else {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }

       
    }

    public function edit($id)
    {

        if (!is_numeric($id)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }

        $cargo = Cargo::where('id_cargo', $id)->where('deleted', 'N')->first();
        if (empty($cargo)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }
        $title = 'Editar Cargo';
        $action = '/dashboard/cargos/' . $id . '/editar';
        $url_volver_listado = '/dashboard/cargos';
        $volver_listado = 'Gestión de Cargos';
        $title_form = 'Formulario de Edición de Cargo';
        $side_cargos = true;
        $side_cargos_list = true;
        return view('admin.modulos.cargos.cargos_editar', compact(
            'title',
            'side_cargos',
            'side_cargos_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'cargo'
        ));
    }


    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }

        $cargo = Cargo::where('id_cargo', $id)->where('deleted', 'N')->first();
        if (empty($cargo)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }

        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'estado' => 'required',
            ], [
                'nombre.required' => 'Nombre de Cargo Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'estado.required' => 'Estado Requerido',
            ]);
            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from CARGO where UPPER(cargo) = '" . strUpper($request->nombre) . "' AND deleted = 'N' AND id_cargo != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Cargo ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE MODIFICADO DE CARGO
            $cargo->cargo = strUpper($request->nombre);
            $cargo->estado = $request->estado == 1 ? 'Y' : 'N';
            if ($cargo->save()) {
                return redirect('/dashboard/cargos')->with(['success_message' => 'Se ha Modificado el Cargo Correctamente'])->with(['success_message_title' => 'Gestión de Cargos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Cargo. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }

        } else {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function delete_cargo($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }

        $cargo = Cargo::where('id_cargo', $id)->where('deleted', 'N')->first();
        if (empty($cargo)) {
            return redirect('/dashboard/cargos')->with(['danger_message' => 'Cargo no existe o fue Eliminado'])->with(['danger_message_title' => 'Cargo no encontrado']);
        }

        if (!empty($cargo)) {
            $cargo->deleted =  'Y';
            $cargo->deleted_at = ahoraServidor();
            if ($cargo->save()) {
                return redirect('/dashboard/cargos')->with(['success_message' => 'Se ha Eliminado Cargo Correctamente'])->with(['success_message_title' => 'Gestión de Cargos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Cargo. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Cargo no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $cargo = Cargo::where('id_cargo', $id)->where('deleted', 'N')->first();
                if (empty($cargo)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $cargo->deleted =  'Y';
                    $cargo->deleted_at = ahoraServidor();
                    if ($cargo->save()) {
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
