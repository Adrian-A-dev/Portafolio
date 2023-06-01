<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
        $this->middleware('isRoot');

    }


    public function index()
    {

        if (auth()->user()->id_rol == 1) {
            $roles = Rol::where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        } else {
            $roles = Rol::where('id_rol', '!=', 1)->where('id_rol', '!=', 3)->where('deleted', 'N')->where('estado', 'Y')->orderBy('rol', 'asc')->get();
        }
        $title = 'Gestión de Roles';
        $title_list = 'Listado de Roles';
        $btn_nuevo = 'Nuevo Rol';
        $url_btn_nuevo = '/dashboard/roles/nuevo';
        $side_roles = true;
        $side_roles_list = true;
        return view('admin.modulos.roles.roles_listado', compact(
            'title',
            'title_list',
            'side_roles',
            'side_roles_list',
            'btn_nuevo',
            'url_btn_nuevo',
            'roles'
        ));
    }


    public function create()
    {

        $title = 'Nuevo Rol';
        $action = '/dashboard/roles/nuevo';
        $url_volver_listado = '/dashboard/roles';
        $volver_listado = 'Gestión de Roles';
        $title_form = 'Formulario de Registro de Rol';
        $side_roles = true;
        $side_roles_new = true;
        return view('admin.modulos.roles.roles_nuevo', compact(
            'title',
            'side_roles',
            'side_roles_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form'
        ));
    }

    public function store(Request $request)
    {

        if (isset($request->form) && $request->form == 1) {
            #FALTA VALIDAR tomar de ejemplo
            // $this->validate($request, [
            //     'sucursal' => 'required|min:3|max:100',
            //     'comuna' => 'required',
            // ], [
            //     'sucursal.required' => ' Sucursal Requerida',
            //     'sucursal.min' => ' Sucursal debe tener Mínimo 3 Caracteres',
            //     'sucursal.max' => ' Sucursal debe tener Máximo 100 Caracteres',
            //     'comuna.required' => ' Comuna Requerida',
            // ]);
            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from Rol where UPPER(servicio) = '" . strUpper($request->servicio) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Rol ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE GUARDADO DE SERVICIO
            // $Rol = new Servicio();
            // $rol-> = .....
            // if ($rol->save()) {
            //     return redirect('/dashboard/roles')->with(['success_message' => 'Se ha Registrado El Rol Correctamente'])->with(['success_message_title' => 'Gestión de Roles']);
            // } else {
            //     return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            // }

        } else {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }

       
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }

        $rol = Rol::where('id_rol', $id)->where('deleted', 'N')->first();
        if (empty($rol)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }


        $title = 'Editar Rol';
        $action = '/dashboard/roles/' . $id . '/editar';
        $url_volver_listado = '/dashboard/roles';
        $volver_listado = 'Gestión de Roles';
        $title_form = 'Formulario de Edición de Rol';
        $side_roles = true;
        $side_roles_list = true;
        return view('admin.modulos.roles.roles_editar', compact(
            'title',
            'side_roles',
            'side_roles_list',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'rol'
        ));
    }


    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }

        $rol = Rol::where('id_rol', $id)->where('deleted', 'N')->first();
        if (empty($rol)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }
        if (isset($request->form) && $request->form == 1) {
            #FALTA VALIDAR tomar de ejemplo
            // $this->validate($request, [
            //     'sucursal' => 'required|min:3|max:100',
            //     'comuna' => 'required',
            // ], [
            //     'sucursal.required' => ' Sucursal Requerida',
            //     'sucursal.min' => ' Sucursal debe tener Mínimo 3 Caracteres',
            //     'sucursal.max' => ' Sucursal debe tener Máximo 100 Caracteres',
            //     'comuna.required' => ' Comuna Requerida',
            // ]);
            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from ROL where UPPER(rol) = '" . strUpper($request->rol) . "' AND deleted = 'N' AND id_rol != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Rol ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            #LOGICA DE MODIFICADO DE ROL
            // $rol-> = .....
            // if ($rol->save()) {
            //     return redirect('/dashboard/roles')->with(['success_message' => 'Se ha Modificado el Rol Correctamente'])->with(['success_message_title' => 'Gestión de Roles']);
            // } else {
            //     return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Rol. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            // }

        } else {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function delete_rol($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }

        $rol = Rol::where('id_rol', $id)->where('deleted', 'N')->first();
        if (empty($rol)) {
            return redirect('/dashboard/roles')->with(['danger_message' => 'Rol No existe o fue Eliminado'])->with(['danger_message_title' => 'Rol no encontrado']);
        }

        if (!empty($rol)) {
            $rol->deleted =  'Y';
            $rol->deleted_at = ahoraServidor();
            if ($rol->save()) {
                return redirect('/dashboard/roles')->with(['success_message' => 'Se ha Eliminado Rol Correctamente'])->with(['success_message_title' => 'Gestión de Roles']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Rol. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Rol no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }
}
