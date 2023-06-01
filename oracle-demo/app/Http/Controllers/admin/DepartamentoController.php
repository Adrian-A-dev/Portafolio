<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;
use App\Models\Departamento;
use App\Models\DepartamentoAccesorio;
use App\Models\Empleado;
use App\Models\EstadoDepartamento;
use App\Models\Sucursal;


use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {

        $departamentos = Departamento::where('deleted', 'N')->get();
        foreach ($departamentos as $key => $departamento) {
            $departamento->cantidad_accesorios = DepartamentoAccesorio::where('id_departamento', $departamento->id_departamento)->count();
        }
        $title = 'Gestión de Departamentos';
        $title_list = 'Listado de Departamentos';
        $btn_nuevo = 'Nuevo Departamento';
        $url_btn_nuevo = '/dashboard/departamentos/nuevo';
        $side_departamentos = true;
        $side_departamentos_list = true;
        return view('admin.modulos.departamentos.departamentos_listado', compact(
            'title',
            'title_list',
            'side_departamentos',
            'side_departamentos_list',
            'departamentos',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {
        $anfitriones = Empleado::select('empleado.id_empleado', 'empleado.nombre', 'empleado.apellido')
            ->join('usuario as u', 'empleado.id_usuario', '=', 'u.id_usuario', 'left')
            ->where('empleado.deleted', 'N')
            ->where('empleado.estado', 'Y')
            ->where('u.id_rol', '4')
            ->get();
        $title = 'Nuevo Departamento';
        $action = '/dashboard/departamentos/nuevo';
        $url_volver_listado = '/dashboard/departamentos';
        $volver_listado = 'Gestión de Departamentos';
        $title_form = 'Formulario de Registro de Departamento';
        $estados_departamento = EstadoDepartamento::orderBy('estado_departamento')->get();
        $sucursales = Sucursal::where('deleted', 'N')->where('estado', 'Y')->orderBy('sucursal')->get();
        $side_departamentos = true;
        $side_departamentos_new = true;
        return view('admin.modulos.departamentos.departamentos_nuevo', compact(
            'title',
            'side_departamentos',
            'side_departamentos_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'estados_departamento',
            'sucursales',
            'anfitriones',
        ));
    }

    public function store(Request $request)
    {
        if (isset($request->form) && $request->form == 1) {

            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'sucursal' => 'required',
                'anfitrion' => 'required',
                'cantidad_huespedes' => 'required',
                'precio_costo' => 'required',
                'precio_arriendo' => 'required',
                'estado' => 'required',
                'nuevo' => 'required',
                'destacado' => 'required',
                'descripcion_corta' => 'required|min:3|max:255',

            ], [
                'nombre.required' => 'Nombre de Departamento Requerido',
                'nombre.min' => 'Nombre de Departamento debe tener Mínimo 3 Caracteres',
                'nombre.max' => 'Nombre de Departamento debe tener Máximo 150 Caracteres',
                'sucursal.required' => 'Sucursal Requerida',
                'anfitrion.required' => 'Anfitrión Requerido',
                'cantidad_huespedes.required' => 'Cantidad Huespedes Requerida',
                'precio_costo.required' => 'Precio Costo Requerido',
                'precio_arriendo.required' => 'Precio Arriendo Requerido',
                'estado.required' => 'Estado de Departamento Requerido',
                'nuevo.required' => '¿Es Nuevo? Requerido',
                'destacado.required' => '¿Es Destacado? Requerido',
                'descripcion_corta.required' => 'Descripción Corta Requerido',
                'descripcion_corta.min' => 'Descripción Corta debe tener Mínimo 3 Caracteres',
                'descripcion_corta.max' => 'Descripción Corta debe tener Máximo 255 Caracteres',


            ]);
            if (!empty($request->descripcion)) {
                $this->validate($request, [
                    'descripcion' => 'min:3'
                ], [
                    'descripcion.min' => 'Descripción  debe tener Mínimo 3 Caracteres',
                ]);
            }

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from DEPARTAMENTO where UPPER(departamento) = '" . strUpper($request->nombre) . "' AND deleted = 'N'");


            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Departamento ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $url = getUrl($request->nombre);
            $valida_url = DB::select("SELECT * from DEPARTAMENTO where lower(url_departamento) = '" . $url . "' AND deleted = 'N'");
            if (!empty($valida_url)) {
                $url = $url . '-' . count($valida_url);
            }

            #LOGICA DE GUARDADO DE DEPARTAMENTO
            $departamento = new Departamento();
            $departamento->departamento = strUpper($request->nombre);
            $departamento->precio_costo = limpiaMoneda($request->precio_costo) > 0 ? limpiaMoneda($request->precio_costo) : 0;
            $departamento->valor_arriendo = limpiaMoneda($request->precio_arriendo) > 0 ? limpiaMoneda($request->precio_arriendo) : 0;
            $departamento->id_estado_departamento = strUpper($request->estado);
            $departamento->id_empleado = !empty($request->anfitrion) ? $request->anfitrion : '';
            $departamento->flg_nuevo = $request->nuevo == 1 ? 'Y' : 'N';
            $departamento->flg_destacado = $request->destacado == 1 ? 'Y' : 'N';
            $departamento->id_sucursal = !empty($request->sucursal) ? $request->sucursal : '';
            $departamento->cantidad_huespedes = limpiaMoneda($request->cantidad_huespedes) > 0 ? limpiaMoneda($request->cantidad_huespedes) : 1;
            $departamento->url_departamento = $url;
            $departamento->descripcion_corta = !empty($request->descripcion_corta) ? $request->descripcion_corta : '';
            $departamento->descripcion_general = !empty($request->descripcion) ? $request->descripcion : '';
            if ($departamento->save()) {
                return redirect('/dashboard/departamentos')->with(['success_message' => 'Se ha Registrado El Departamento Correctamente'])->with(['success_message_title' => 'Gestión de Departamentos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Departamento. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método'])->withInput();
        }
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
        if (empty($departamento)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $anfitriones = Empleado::select('empleado.id_empleado', 'empleado.nombre', 'empleado.apellido')
            ->join('usuario as u', 'empleado.id_usuario', '=', 'u.id_usuario', 'left')
            ->where('empleado.deleted', 'N')
            ->where('empleado.estado', 'Y')
            ->where('u.id_rol', '4')
            ->get();
        $estados_departamento = EstadoDepartamento::orderBy('estado_departamento')->get();
        $sucursales = Sucursal::where('deleted', 'N')->where('estado', 'Y')->orderBy('sucursal')->get();

        $title = 'Editar Departamento';
        $title_form = 'Formulario de Edición de Departamento';
        $action = '/dashboard/departamentos/' . $id . '/editar';
        $url_volver_listado = '/dashboard/departamentos';
        $volver_listado = 'Gestión de Departamentos';
        $side_departamentos = true;
        $side_departamentos_list = true;
        return view('admin.modulos.departamentos.departamentos_editar', compact(
            'title',
            'title_form',
            'side_departamentos',
            'side_departamentos_list',
            'departamento',
            'action',
            'url_volver_listado',
            'volver_listado',
            'estados_departamento',
            'sucursales',
            'anfitriones',
        ));
    }

    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
        if (empty($departamento)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }
        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'sucursal' => 'required',
                'anfitrion' => 'required',
                'cantidad_huespedes' => 'required',
                'precio_costo' => 'required',
                'precio_arriendo' => 'required',
                'estado' => 'required',
                'nuevo' => 'required',
                'destacado' => 'required',
                'descripcion_corta' => 'required|min:3|max:255',

            ], [
                'nombre.required' => 'Nombre de Departamento Requerido',
                'nombre.min' => 'Nombre de Departamento debe tener Mínimo 3 Caracteres',
                'nombre.max' => 'Nombre de Departamento debe tener Máximo 150 Caracteres',
                'sucursal.required' => 'Sucursal Requerida',
                'anfitrion.required' => 'Anfitrión Requerido',
                'cantidad_huespedes.required' => 'Cantidad Huespedes Requerida',
                'precio_costo.required' => 'Precio Costo Requerido',
                'precio_arriendo.required' => 'Precio Arriendo Requerido',
                'estado.required' => 'Estado de Departamento Requerido',
                'nuevo.required' => '¿Es Nuevo? Requerido',
                'destacado.required' => '¿Es Destacado? Requerido',
                'descripcion_corta.required' => 'Descripción Corta Requerido',
                'descripcion_corta.min' => 'Descripción Corta debe tener Mínimo 3 Caracteres',
                'descripcion_corta.max' => 'Descripción Corta debe tener Máximo 255 Caracteres',


            ]);
            if (!empty($request->descripcion)) {
                $this->validate($request, [
                    'descripcion' => 'min:3'
                ], [
                    'descripcion.min' => 'Descripción  debe tener Mínimo 3 Caracteres',
                ]);
            }

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from DEPARTAMENTO where UPPER(departamento) = '" . strUpper($request->nombre) . "' AND deleted = 'N' AND id_departamento != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Departamento ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
            $url = getUrl($request->nombre);
            $valida_url = DB::select("SELECT * from DEPARTAMENTO where lower(url_departamento) = '" . $url . "' AND deleted = 'N'  AND id_departamento != $id");
            if (!empty($valida_url)) {
                $url = $url . '-' . count($valida_url);
            }

            #LOGICA DE GUARDADO DE DEPARTAMENTO
            $departamento->departamento = strUpper($request->nombre);
            $departamento->precio_costo = limpiaMoneda($request->precio_costo) > 0 ? limpiaMoneda($request->precio_costo) : 0;
            $departamento->valor_arriendo = limpiaMoneda($request->precio_arriendo) > 0 ? limpiaMoneda($request->precio_arriendo) : 0;
            $departamento->id_estado_departamento = strUpper($request->estado);
            $departamento->id_empleado = !empty($request->anfitrion) ? $request->anfitrion : '';
            $departamento->flg_nuevo = $request->nuevo == 1 ? 'Y' : 'N';
            $departamento->flg_destacado = $request->destacado == 1 ? 'Y' : 'N';
            $departamento->id_sucursal = !empty($request->sucursal) ? $request->sucursal : '';
            $departamento->cantidad_huespedes = limpiaMoneda($request->cantidad_huespedes) > 0 ? limpiaMoneda($request->cantidad_huespedes) : 1;
            if ($departamento->url_departamento != $url) {
                $departamento->url_departamento = $url;
            }
            $departamento->descripcion_corta = !empty($request->descripcion_corta) ? $request->descripcion_corta : '';
            $departamento->descripcion_general = !empty($request->descripcion) ? $request->descripcion : '';
            if ($departamento->save()) {
                return redirect('/dashboard/departamentos')->with(['success_message' => 'Se ha Modificado el Departamento Correctamente'])->with(['success_message_title' => 'Gestión de Departamentos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Departamento. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function editAccesorios($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
        if (empty($departamento)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        #SE OBTIENEN LOS ACCESORIOS ASOCIADOS AL DEPARTAMENTO
        $getAccesoriosDepartamento = DepartamentoAccesorio::where('id_departamento', $departamento->id_departamento)->get();
        $accesorios_departamento = []; # SE GENERA VARIABLE PARA ALMACENAR ID DEL ACCESORIO
        foreach ($getAccesoriosDepartamento as $key => $ad) {
            array_push($accesorios_departamento, $ad->id_accesorio);
        };

        #SE EXTRAEN ACCESORIOS DISPONIBLES
        $getAccesorios = Accesorio::where('deleted', 'N')->where('estado', 'Y')->orderBy('accesorio')->get();
        $accesorios = []; # SE GENERA VARIABLE PARA GUARDAR DATA DE LOS ACCESORIOS 
        foreach ($getAccesorios as  $a) { # SE RECORREN LAS ACCESORIOS
            #SE COMPARA ID_ACCESORIO CON EL ID_ACCESORIO DEL DEPARTAMENTO 
            if (in_array($a->id_accesorio, $accesorios_departamento)) { #SI ID DEL ACCESORIO ESTÁ EN EL ARRAY DE ACCESORIOS DEL DEPARTAMENTO ENTONCES SE MARCA CON CHECKED 1
                $b = [
                    'id_accesorio' => $a->id_accesorio,
                    'accesorio' => $a->accesorio,
                    'checked' => 1,
                ];
            } else { #SI ID DEL ACCESORIO NO ESTÁ EN EL ARRAY DE LAS ACCESORIOS DEL DEPARTAMENTO ENTONCES SE MARCA CON CHECKED 0
                $b = [
                    'id_accesorio' => $a->id_accesorio,
                    'accesorio' => $a->accesorio,
                    'checked' => 0,
                ];
            }
            $accesorios[$a->id_accesorio] = $b; # SE GUARDA ARRAY DE DATA EN VARIABLE DE ACCESORIOS
        }

        $title = 'Asignación de Accesorios ';
        $title_form = 'Formulario de Asignación de Accesorios';
        $action = '/dashboard/departamentos/' . $id . '/asignar-accesorios';
        $url_volver_listado = '/dashboard/departamentos';
        $volver_listado = 'Gestión de Departamentos';
        $side_departamentos = true;
        $side_departamentos_list = true;
        return view('admin.modulos.departamentos.departamentos_asignar_accesorios', compact(
            'title',
            'title_form',
            'side_departamentos',
            'side_departamentos_list',
            'departamento',
            'accesorios',
            'action',
            'url_volver_listado',
            'volver_listado',

        ));
    }

    public function updateAccesorios($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
        if (empty($departamento)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }
        if (isset($request->form) && $request->form == 1) {
            if (count($request->accesorio) < 1) {
                return back()->with(['danger_message' => 'No existe Accesorio Seleccionado'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            } else {
                $accesorios = $request->accesorio;
            }
            if (count(DepartamentoAccesorio::where('id_departamento', $departamento->id_departamento)->get()) > 0) {
                $eliminar_accesorios = DepartamentoAccesorio::where('id_departamento', $departamento->id_departamento)->delete();
                if ($eliminar_accesorios) {
                    foreach ($accesorios as $a) {
                        $accesorio_departamento = new DepartamentoAccesorio();
                        $accesorio_departamento->id_departamento = $departamento->id_departamento;
                        $accesorio_departamento->id_accesorio = $a;
                        $accesorio_departamento->save();
                    }
                    return redirect('/dashboard/departamentos')->with(['success_message' => 'Se ha realizado Asignación de Accessorios Correctamente'])->with(['success_message_title' => 'Gestión de Departamentos']);
                } else {
                    return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Accessorios de Departamento. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
                }
            } else {
                foreach ($accesorios as $a) {
                    $accesorio_departamento = new DepartamentoAccesorio();
                    $accesorio_departamento->id_departamento = $departamento->id_departamento;
                    $accesorio_departamento->id_accesorio = $a;
                    $accesorio_departamento->save();
                }
                return redirect('/dashboard/departamentos')->with(['success_message' => 'Se ha realizado Asignación de Accessorios Correctamente'])->with(['success_message_title' => 'Gestión de Departamentos']);
            }
        } else {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function delete_departamento($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }

        $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
        if (empty($departamento)) {
            return redirect('/dashboard/departamentos')->with(['danger_message' => 'Departamento No existe o fue Eliminado'])->with(['danger_message_title' => 'Departamento no encontrada']);
        }
        if (!empty($departamento)) {
            $departamento->deleted =  'Y';
            $departamento->deleted_at = ahoraServidor();
            if ($departamento->save()) {
                return redirect('/dashboard/departamentos')->with(['success_message' => 'Se ha Eliminado el Departamento Correctamente'])->with(['success_message_title' => 'Gestión de Departamentos']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Departamento. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Departamento no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $departamento = Departamento::where('id_departamento', $id)->where('deleted', 'N')->first();
                if (empty($departamento)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $departamento->deleted =  'Y';
                    $departamento->deleted_at = ahoraServidor();
                    if ($departamento->save()) {
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
