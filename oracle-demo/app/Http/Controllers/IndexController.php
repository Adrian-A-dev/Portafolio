<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        $contador = [
            'annios' => (date('Y') - 2012),
            'sucursales' => Sucursal::where('deleted', 'N')->where('estado', 'Y')->count(),
            'reservas' => Reserva::where('id_reserva', '>',  0)->count(),
            'clientes' => Cliente::where('deleted', 'N')->count(),
        ];
        $tipo_servicios = TipoServicio::where('deleted', 'N')->where('estado', 'Y')->orderby('tipo_servicio')->get();

        foreach ($tipo_servicios as $key => $tipo) {
            $tipo->servicios = Servicio::where('deleted', 'N')->where('estado', 'Y')->where('id_tipo_servicio', $tipo->id_tipo_servicio)->get();
        }

        $departamentos = Departamento::select('departamento.*')
            ->join('estado_departamento as e', 'departamento.id_estado_departamento', '=', 'e.id_estado_departamento', 'left')
            ->where('departamento.deleted', 'N')
            ->where('e.mostrar_web', 'Y')
            ->get();
        $usuario = Usuarios::where('id_usuario', 1)->get();
        $title = 'Inicio';
        return view('landing.index', compact(
            'usuario',
            'title',
            'departamentos',
            'contador',
            'tipo_servicios'
        ));
    }


    public function departamentos()
    {

        $sucursales = Sucursal::where('deleted', 'N')->where('estado', 'Y')->get();
        foreach ($sucursales as $sucursal) {
            $nombre_region = strUpper($sucursal->comuna->region->nombre);
            $nombre_region = str_replace(' ', '_', $nombre_region);
            $sucursales_arr[$nombre_region] = [];
        }
        foreach ($sucursales as $sucursal) {
            $departamentos = Departamento::select('departamento.*')
                ->join('estado_departamento as e', 'departamento.id_estado_departamento', '=', 'e.id_estado_departamento', 'left')
                ->where('departamento.deleted', 'N')
                ->where('e.mostrar_web', 'Y')
                ->where('departamento.id_sucursal', $sucursal->id_sucursal)
                ->get();
                $nombre_region = strUpper($sucursal->comuna->region->nombre);
                $nombre_region = str_replace(' ', '_', $nombre_region);
                if(count($departamentos) > 0){
                    foreach($departamentos as $d){
                        $sucursales_arr[$nombre_region][] = ($d);
                    }
                   
                }
               
          
        }
        
        $title = 'Departamentos';
        return view('landing.departamentos', compact(
            'title',
            'sucursales_arr'
        ));


    }



    public function contacto()
    {
        $title = 'Contacto';
        return view('landing.contacto', compact('title'));
    }

    public function contacto_form(Request $request)
    {
        if ($validate = $this->validateFieldContact($request->all())) {
            $resp = [
                'type' => 'error',
                'msg' => implode(", ", $validate),
            ];
        } else {
            $recipient = EMAIL_RECEPTOR;
            // Set the email subject.
            $subject = "Nuevo Mensaje de $request->name - Formulario Contacto " . NOMBRE_APP;

            // Build the email content.
            $email_content = "Nombre: $request->name\n";
            $email_content .= "Asunto: $request->asunto\n";
            $email_content .= "Email: $request->email\n\n";
            $email_content .= "Mensaje:\n$request->message\n";

            // Build the email headers.
            $email_headers = "From: $request->name <$request->email>";

            // Send the email.
            if (mail($recipient, $subject, $email_content, $email_headers)) {
                $resp = [
                    'type' => 'success',
                    'msg' => 'Su mensaje ha sido enviado. Nos pondremos en contacto a la brevedad :)',
                ];
            } else {
                $resp = [
                    'type' => 'error',
                    'msg' => "Oops! Ha Ocurrido un Error al enviar mensaje. Intente Nuevamente.",
                ];
            }
        }
        echo json_encode($resp);
    }

    protected function validateFieldContact($data)
    {

        $error = [];
        $error_obligatorio = [];
        $error_final = [];
        $error_flag = false;
        if (empty(trim($data['name']))) {
            $error_flag = true;
            $error_obligatorio['name'] = 'Nombre';
        } elseif (validateText(trim($data['name']))) {
            $error_flag = true;
            $error['name'] = 'Nombre debe tener mínimo 3 caracteres';
        }
        if (empty(trim($data['asunto']))) {
            $error_flag = true;
            $error_obligatorio['asunto'] = 'Asunto';
        } elseif (validateText(trim($data['asunto']))) {
            $error_flag = true;
            $error['asunto'] = 'Asunto debe tener mínimo 3 caracteres';
        }
        if (empty(trim($data['email']))) {
            $error_flag = true;
            $error_obligatorio['email'] = 'Correo electrónico';
        } elseif (validateEmail(trim($data['email']))) {
            $error_flag = true;
            $error['email'] = 'Correo electrónico con formato inválido';
        }
        if (empty(trim($data['message']))) {
            $error_flag = true;
            $error_obligatorio['message'] = 'Mensaje';
        } elseif (validateText(trim($data['message']))) {
            $error_flag = true;
            $error['message'] = 'Mensaje debe tener mínimo 3 caracteres';
        }
        if (!empty($error_obligatorio)) {
            $errores = implode(", ", $error_obligatorio);
            if (count($error_obligatorio) > 1) {
                $errores = $errores . ' Son Obligatorios';
            } else {
                $errores = $errores . ' Es Obligatorio';
            }
            array_push($error_final, $errores);
        }
        if (!empty($error)) {
            $errores2 = implode(", ", $error);
            array_push($error_final, $errores2);
        }
        if ($error_flag) {
            return $error_final;
        } else {
            return false;
        }
    }
}
