<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\TipoServicio;
use App\Models\Transportista;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.users')->except('logout');
        $this->middleware('notClient');
    }
    public function index()
    {
        #todos los campos de sucursal y el NOMBRE de COMUNA 
        $servicios = Servicio::where('deleted', 'N')->get();
        $title = 'Gestión de Servicios';
        $title_list = 'Listado de Servicios';
        $btn_nuevo = 'Nuevo Servicio';
        $url_btn_nuevo = '/dashboard/servicios/nuevo';
        $side_servicios = true;
        $side_servicios_list = true;
        return view('admin.modulos.servicios.servicios_listado', compact(
            'title',
            'title_list',
            'side_servicios',
            'side_servicios_list',
            'servicios',
            'btn_nuevo',
            'url_btn_nuevo'
        ));
    }

    public function create()
    {

        $title = 'Nuevo Servicio';
        $action = '/dashboard/servicios/nuevo';
        $url_volver_listado = '/dashboard/servicios';
        $volver_listado = 'Gestión de Servicios';
        $title_form = 'Formulario de Registro de Servicio';
        $tipo_servicios = TipoServicio::where('deleted', 'N')->where('estado', 'Y')->get();
        $side_servicios = true;
        $side_servicios_new = true;
        $transportistas = Transportista::where('deleted', 'N')->where('estado', 'Y')->get();
        return view('admin.modulos.servicios.servicios_nuevo', compact(
            'title',
            'side_servicios',
            'side_servicios_new',
            'action',
            'url_volver_listado',
            'volver_listado',
            'title_form',
            'tipo_servicios',
            'transportistas'
        ));
    }

    public function store(Request $request)
    {

        if (isset($request->form) && $request->form == 1) {

            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'tipo_servicio' => 'required',
                'precio' => 'required',
                'cantidad' => 'required',
                'estado' => 'required',
                'descripcion_corta' => 'required|min:3|max:255',

            ], [
                'nombre.required' => 'Nombre de Servicio Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'tipo_servicio.required' => 'Tipo Servicio Requerido',
                'estado.required' => 'Estado Requerido',
                'precio.required' => 'Precio de Servicio Requerido',
                'cantidad.required' => 'Cantidad Disponible Requerida',
                'descripcion_corta.required' => 'Descripción Corta Requerida',
                'descripcion_corta.min' => ' Descripción Corta  debe tener Mínimo 3 Caracteres',
                'descripcion_corta.max' => ' Descripción Corta  debe tener Máximo 255 Caracteres',

            ]);

            if ($request->val_lugares == 1) {
                $this->validate($request, [
                    'origen' => 'required|min:3|max:100',
                    'destino' => 'required|min:3|max:100',
                ], [
                    'origen.required' => 'Lugar de Origen Requerido',
                    'origen.min' => ' Lugar de Origen debe tener Mínimo 3 Caracteres',
                    'origen.max' => ' Lugar de Origen debe tener Máximo 100 Caracteres',
                    'destino.required' => 'Lugar de Destino Requerido',
                    'destino.min' => ' Lugar de Destino debe tener Mínimo 3 Caracteres',
                    'destino.max' => ' Lugar de Destino debe tener Máximo 100 Caracteres',
                ]);
            }
            if ($request->val_horarios == 1) {
                $this->validate($request, [
                    'hora_inicio' => 'required|min:3|max:100',
                    'hora_fin' => 'required|min:3|max:100',
                ], [
                    'origen.required' => 'Hora de Inicio Requerida',
                    'destino.required' =>  'Hora de Fin Requerida',

                ]);
            }
            if ($request->val_transportista == 1) {
                $this->validate($request, [
                    'transportista' => 'required'
                ], [
                    'transportista.required' => 'Transportista Requerido',

                ]);
            }

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from SERVICIO where UPPER(servicio) = '" . strUpper($request->nombre) . "' AND deleted = 'N'");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Servicio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }

            #LOGICA DE GUARDADO DE SERVICIO
            $servicio = new Servicio();
            $servicio->servicio = strUpper($request->nombre);
            $servicio->precio = limpiaMoneda($request->precio);
            $servicio->cantidad = limpiaMoneda($request->cantidad);
            $servicio->estado = $request->estado == 1 ? 'Y' : 'N';
            $servicio->id_tipo_servicio = $request->tipo_servicio;
            if ($request->val_lugares == 1) {
                $servicio->lugar_origen = $request->origen;
                $servicio->lugar_destino = $request->destino;
            }
            if ($request->val_horarios == 1) {
                $servicio->fecha_inicio = !empty($request->fecha_inicio) ? ordenar_fechaServidor($request->fecha_inicio) : '';
                $servicio->fecha_fin = !empty($request->fecha_fin) ? ordenar_fechaServidor($request->fecha_fin) : '';
                $servicio->hora_inicio = $request->hora_inicio;
                $servicio->hora_fin = $request->hora_fin;
            }
            if ($request->val_transportista == 1) {
                $servicio->id_transportista = $request->transportista;
            }
            $servicio->flg_lugar = $request->val_lugares == 1 ? 'Y' : 'N';
            $servicio->flg_horario = $request->val_horarios == 1 ? 'Y' : 'N';
            $servicio->flg_transportista = $request->val_transportista == 1 ? 'Y' : 'N';
            $servicio->descripcion_corta = $request->descripcion_corta;
            if ($servicio->save()) {
                return redirect('/dashboard/servicios')->with(['success_message' => 'Se ha Registrado El Servicio Correctamente'])->with(['success_message_title' => 'Gestión de Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Crear Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }


    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }

        $servicio = Servicio::where('id_servicio', $id)->where('deleted', 'N')->first();
        if (empty($servicio)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }


        $title = 'Editar Servicio';
        $title_form = 'Formulario de Edición de Servicio';
        $action = '/dashboard/servicios/' . $id . '/editar';
        $url_volver_listado = '/dashboard/servicios';
        $volver_listado = 'Gestión de Servicios';
        $side_servicios = true;
        $side_servicios_list = true;
        $tipo_servicios = TipoServicio::where('deleted', 'N')->where('estado', 'Y')->get();
        $transportistas = Transportista::where('deleted', 'N')->where('estado', 'Y')->get();
        
        return view('admin.modulos.servicios.servicios_editar', compact(
            'title',
            'title_form',
            'side_servicios',
            'side_servicios_list',
            'tipo_servicios',
            'action',
            'url_volver_listado',
            'volver_listado',
            'servicio',
            'transportistas'
        ));
    }



    public function update($id, Request $request)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }

        $servicio = Servicio::where('id_servicio', $id)->where('deleted', 'N')->first();
        if (empty($servicio)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }
        if (isset($request->form) && $request->form == 1) {
            $this->validate($request, [
                'nombre' => 'required|min:3|max:100',
                'tipo_servicio' => 'required',
                'precio' => 'required',
                'cantidad' => 'required',
                'estado' => 'required',
                'descripcion_corta' => 'required|min:3|max:255',

            ], [
                'nombre.required' => 'Nombre de Servicio Requerido',
                'nombre.min' => ' Nombre debe tener Mínimo 3 Caracteres',
                'nombre.max' => ' Nombre debe tener Máximo 100 Caracteres',
                'tipo_servicio.required' => 'Tipo Servicio Requerido',
                'estado.required' => 'Estado Requerido',
                'precio.required' => 'Precio de Servicio Requerido',
                'cantidad.required' => 'Cantidad Disponible Requerida',
                'descripcion_corta.required' => 'Descripción Corta Requerida',
                'descripcion_corta.min' => ' Descripción Corta  debe tener Mínimo 3 Caracteres',
                'descripcion_corta.max' => ' Descripción Corta  debe tener Máximo 255 Caracteres',

            ]);

            if ($request->val_lugares == 1) {
                $this->validate($request, [
                    'origen' => 'required|min:3|max:100',
                    'destino' => 'required|min:3|max:100',
                ], [
                    'origen.required' => 'Lugar de Origen Requerido',
                    'origen.min' => ' Lugar de Origen debe tener Mínimo 3 Caracteres',
                    'origen.max' => ' Lugar de Origen debe tener Máximo 100 Caracteres',
                    'destino.required' => 'Lugar de Destino Requerido',
                    'destino.min' => ' Lugar de Destino debe tener Mínimo 3 Caracteres',
                    'destino.max' => ' Lugar de Destino debe tener Máximo 100 Caracteres',
                ]);
            }
            if ($request->val_horarios == 1) {
                $this->validate($request, [
                    'hora_inicio' => 'required|min:3|max:100',
                    'hora_fin' => 'required|min:3|max:100',
                ], [
                    'origen.required' => 'Hora de Inicio Requerida',
                    'destino.required' =>  'Hora de Fin Requerida',

                ]);
            }
            if ($request->val_transportista == 1) {
                $this->validate($request, [
                    'transportista' => 'required'
                ], [
                    'transportista.required' => 'Transportista Requerido',

                ]);
            }

            #VALIDACIÓN PARA EVITAR DUPLICIDAD DE NOMBRE ok!
            $valida_nombre = DB::select("SELECT * from SERVICIO where UPPER(servicio) = '" . strUpper($request->nombre) . "' AND deleted = 'N' AND id_servicio != $id");
            if (!empty($valida_nombre)) {
                return back()->with(['danger_message' => 'Nombre de Servicio ya existe'])->with(['danger_message_title' => 'Error de Validación'])->withInput();
            }
           
            #LOGICA DE MODIFICADO DE Servicio
            $servicio->servicio = strUpper($request->nombre);
            $servicio->precio = limpiaMoneda($request->precio);
            $servicio->cantidad = limpiaMoneda($request->cantidad);
            $servicio->estado = $request->estado == 1 ? 'Y' : 'N';
            $servicio->id_tipo_servicio = $request->tipo_servicio;
            #GUARDADO SEGÚN VALIDACIÓN LUGAR
            $servicio->lugar_origen = $request->val_lugares == 1 ? $request->origen : '';
            $servicio->lugar_destino = $request->val_lugares == 1 ? $request->destino : '';
            #GUARDADO SEGÚN VALIDACIÓN HORARIO
            $servicio->fecha_inicio = $request->val_horarios == 1 ?  (!empty($request->fecha_inicio) ? ordenar_fechaServidor($request->fecha_inicio) : '') : '';
            $servicio->fecha_fin = $request->val_horarios == 1 ?  (!empty($request->fecha_fin) ? ordenar_fechaServidor($request->fecha_fin) : '') : '';
            $servicio->hora_inicio = $request->val_horarios == 1 ?  $request->hora_inicio : '';
            $servicio->hora_fin = $request->val_horarios == 1 ?  $request->hora_fin : '';
            #GUARDADO SEGÚN VALIDACIÓN TRANSPORTISTA
            $servicio->id_transportista = $request->val_transportista == 1 ? $request->transportista : null;
            $servicio->flg_lugar = $request->val_lugares == 1 ? 'Y' : 'N';
            $servicio->flg_horario = $request->val_horarios == 1 ? 'Y' : 'N';
            $servicio->flg_transportista = $request->val_transportista == 1 ? 'Y' : 'N';
            $servicio->descripcion_corta = $request->descripcion_corta;
            if ($servicio->save()) {
                return redirect('/dashboard/servicios')->with(['success_message' => 'Se ha Modificado el  Servicio Correctamente'])->with(['success_message_title' => 'Gestión de Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Modificar Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno'])->withInput();
            }
        } else {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Error'])->with(['danger_message_title' => 'Error de Método']);
        }
    }

    public function delete_servicio($id)
    {
        if (!is_numeric($id)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }

        $servicio = Servicio::where('id_servicio', $id)->where('deleted', 'N')->first();
        if (empty($servicio)) {
            return redirect('/dashboard/servicios')->with(['danger_message' => 'Servicio No existe o fue Eliminado'])->with(['danger_message_title' => 'Servicio no encontrado']);
        }

        if (!empty($servicio)) {
            $servicio->deleted =  'Y';
            $servicio->deleted_at = ahoraServidor();
            if ($servicio->save()) {
                return redirect('/dashboard/servicios')->with(['success_message' => 'Se ha Eliminado Servicio Correctamente'])->with(['success_message_title' => 'Gestión de Servicios']);
            } else {
                return  back()->with(['danger_message' => 'Ha Ocurrido un error al Eliminar Servicio. Intente Nuevamente'])->with(['danger_message_title' => 'Error Interno']);
            }
        } else {
            return back()->with(['danger_message' => 'Servicio no existe o fue eliminado'])->with(['danger_message_title' => 'Error de Validación']);
        }
    }

    public function deleted(Request $request)
    {
        if (!empty($request->id_delete)) {
            $id = $request->id_delete;
            if (auth()->user()->id_rol > 2) {
                echo 'Su Usuario no tiene los permisos necesarios';
            } else {
                $servicio = Servicio::where('id_servicio', $id)->where('deleted', 'N')->first();
                if (empty($servicio)) {
                    echo 'Registro no existe o fue eliminado';
                } else {
                    $servicio->deleted =  'Y';
                    $servicio->deleted_at = ahoraServidor();
                    if ($servicio->save()) {
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
