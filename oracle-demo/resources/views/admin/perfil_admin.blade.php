@extends('layout.admin.layout_admin')
@section('contenido-admin')

<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4><?= isset($title) ? $title  : '' ?></h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/gestion-reservas">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Edición de Mi Perfil' ?></h4>
            <hr>
        </div>
        <div class="p-4">
            <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                {{ csrf_field() }}
                <h6>Información de Usuario</h6>
                <hr>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Fecha Creación</label>
                            <input class="form-control" disabled value="{{ !empty($empleado->created_at) ? ordenar_fechaHoraMinutoHumano($empleado->created_at) : 'Sin información'}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usuario">Usuario <span class="text-danger">*</span></label>
                            <input type="text" name="usuario" id="usuario" class="form-control" value="{{ auth()->user()->username }}" disabled>
                            <small id="invalid_usuario" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <h6>Información de Personal</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ingrese Nombres..." value="{{ old('nombres') != null ? old('nombres') : (!empty($empleado->nombre) ? $empleado->nombre : '')  }}">
                            <small id="invalid_nombres" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ingrese Apellidos..." value="{{ old('apellidos') != null ? old('apellidos') : (!empty($empleado->apellido) ? $empleado->apellido : '')  }}">
                            <small id="invalid_apellidos" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rut">Rut <span class="text-danger"></span></label>
                            <input type="text" name="rut" id="rut" class="form-control" placeholder="Ingrese Rut..." value="{{ old('rut') != null ? old('rut') : (!empty($empleado->dni) ? formateaRut($empleado->dni) : '')  }}" maxlength="12">
                            <small id="invalid_rut" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Ingrese Correo electrónico..." value="{{ old('email') != null ? old('email') : (!empty($empleado->email) ? $empleado->email : '')  }}">
                            <small id="invalid_email" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="tel" name="celular" id="celular" class="form-control" placeholder="Ingrese Celular..." minlength="12" maxlength="12" value="{{ old('celular') != null ? old('celular') : (!empty($empleado->celular) ? $empleado->celular : '')  }}">
                            <small id="invalid_celular" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha Nacimiento</label>
                            <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" readonly placeholder="Seleccione Fecha Nacimiento..." value="{{old('fecha_nacimiento') != null ? old('fecha_nacimiento') : (!empty($empleado->fecha_nacimiento) ? ordenar_fechaHumano($empleado->fecha_nacimiento) : '')}}">
                            <small id="invalid_fecha_nacimiento" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Modificar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@section('js_content')
@include('./generalJS')
<script src="<?= asset(ASSETS_JS) ?>/validate_rut.js"></script>
<script>
    $(document).ready(function() {
        $('#fecha_nacimiento').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(1910, 0, 1),
            maxDate: new Date(),
        });
        validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        validaCampos($('#rut').val(), 'rut', 'rut', false);
        validaCampos($('#celular').val(), 'celular', 'celular', false);
        validaCorreo($('#email').val(), 'email');
        $('#nombres').keyup(function() {
            validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        });
        $('#apellidos').keyup(function() {
            validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        });
        $('#rut').keyup(function() {
            validaCampos($('#rut').val(), 'rut', 'rut', false);
        });
        $('#celular').keyup(function() {
            let cel = checkNumero($('#celular').val());
            $('#celular').val(cel)
            validaCampos($('#celular').val(), 'celular', 'celular', false);
        });
        $('#email').keyup(function() {
            validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido')
        });

    });

    $("#btn_submit").click(function() {
       
        let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        let apellidos = validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        let rut = validaCampos($('#rut').val(), 'rut', 'rut', false);
        let celular = validaCampos($('#celular').val(), 'celular', 'celular', false);
        let email = validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido');

        if (nombres == 1 && apellidos == 1 && rut == 1 && celular == 1 && email == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>
@endsection


@endsection