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
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item">
                            <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>">
                                <?= isset($volver_listado) ? $volver_listado : 'Listado' ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Simple Datatable start -->
    <div class="row">
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-10 col-md-10">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                        {{ csrf_field() }}

                        <input type="hidden" name="form" id="form" value="1">
                        <h6>Información de Empleado</h6>
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
                                    <label for="cargo">Cargo <span class="text-danger">*</span></label>
                                    <select name="cargo" id="cargo" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @if(!empty($cargos))
                                        @foreach($cargos as $cargo)
                                        <option value="{{$cargo->id_cargo}}" <?= old('cargo') != null &&  old('cargo') == $cargo->id_cargo ? 'selected' : ($empleado->id_cargo == $cargo->id_cargo ? 'selected' : '') ?>>{{strUpper($cargo->cargo)}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_cargo" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Ingrese Correo electrónico..." value="{{ old('email') != null ? old('email') : (!empty($empleado->email) ? $empleado->email : '')  }}">
                                    <small id="invalid_email" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="1" <?= old('estado') == '1' ? 'selected' : ($empleado->estado == 'Y' ? 'selected' : '') ?>>Activo</option>
                                        <option value="0" <?= old('estado') == '0' ? 'selected' : ($empleado->estado == 'N' ? 'selected' : '') ?>>Inactivo</option>
                                    </select>
                                    <small id="invalid_estado" class="text-danger"></small>
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
                        <h6>Información de Usuario</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Usuario <span class="text-danger"></span></label>
                                    <input type="text" disabled class="form-control" placeholder="Usuario" value="{{ !empty($empleado->usuario->username) ? $empleado->usuario->username : 'SIN INFORMACIÓN' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rol">Rol <span class="text-danger">*</span></label>
                                    <select name="rol" id="rol" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @if(!empty($roles))
                                        @foreach($roles as $rol)
                                        <option value="{{$rol->id_rol}}" <?= old('rol') !== null && old('rol') == $rol->id_rol ? 'selected' : ($empleado->usuario->id_rol == $rol->id_rol ? 'selected' : '') ?>>{{strUpper($rol->rol)}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_rol" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Último acceso <span class="text-danger"></span></label>
                                    <input type="text" disabled class="form-control" placeholder="Fecha de Último acceso" value="{{ !empty($empleado->usuario->ultimo_acceso) ? ordenar_fechaHoraHumano($empleado->usuario->ultimo_acceso) : 'SIN INFORMACIÓN' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12  border">
                                <h5 class="card-header bg-dark text-white  text-center">Sucursales de
                                    Usuario <span class="text-danger">*</span></h5>
                                <br>
                                @if (!empty($sucursales))
                                <div id="sucursales_div">
                                    <h6 id="msg_chk" hidden class="alert alert-danger text-center"><b><i class="fa fa-info-circle"></i>
                                            ATENCIÓN:</b> Seleccione una o más sucursales para asociarlas al usuario
                                    </h6>
                                    <small id="invalid_sucursal" class="text-danger"></small>
                                    <div class="row d-flex justify-content-start p-4 text-center" id="div_chk_sucursales">
                                        @foreach ($sucursales as $sucursal)
                                        <?php $sucursal = (object)$sucursal; ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <input type="checkbox" onchange="cuentaCheckbox(1, '')" class="checkbox" id="sucursal_{{$sucursal->id_sucursal}}" name="sucursal[]" {{ $sucursal->checked == '1' ? 'checked' : ''}} value="{{$sucursal->id_sucursal}}" />
                                                <label for="sucursal_{{$sucursal->id_sucursal}}" class="ml-2">{{$sucursal->sucursal}}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <h6 id="msg_vacio" {{empty($sucursales) ? '' : 'hidden'}} class="alert alert-danger text-center"><b><i class="fa fa-exclamation-triangle"></i>
                                        ATENCIÓN:</b> No existen sucursales asociadas a su empresa
                                    <br>
                                    <a href="/dashboard/sucursales/nueva" class="mt-2 btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Ir a Crear Nueva Sucursal</a>
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <hr>
                                <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                                <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
        validaCampos($('#cargo').val(), 'cargo', 'select', true);
        validaCampos($('#celular').val(), 'celular', 'celular', false);

        validaCorreo($('#email').val(), 'email');
        validaCampos($('#rol').val(), 'rol', 'select', true);
        cuentaCheckbox(1, '');

        $('#nombres').keyup(function() {
            validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        });
        $('#apellidos').keyup(function() {
            validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        });
        $('#rut').keyup(function() {
            validaCampos($('#rut').val(), 'rut', 'rut', false);
        });
        $('#cargo').change(function() {
            validaCampos($('#cargo').val(), 'cargo', 'select', true, 'Seleccione Cargo');
        });
        $('#celular').keyup(function() {
            let cel = checkNumero($('#celular').val());
            $('#celular').val(cel)
            validaCampos($('#celular').val(), 'celular', 'celular', false);
        });
        $('#email').keyup(function() {
            validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido')
        });
        $('#rol').change(function() {
            validaCampos($('#rol').val(), 'rol', 'select', true, 'Seleccione Rol');
        });

    });

    $("#btn_submit").click(function() {
        let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        let apellidos = validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        let rut = validaCampos($('#rut').val(), 'rut', 'rut', false);
        let cargo = validaCampos($('#cargo').val(), 'cargo', 'select', true, 'Seleccione Cargo');
        let celular = validaCampos($('#celular').val(), 'celular', 'celular', false);
        let email = validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido');
        let rol = validaCampos($('#rol').val(), 'rol', 'select', true, 'Seleccione Rol');
        let sucursal = cuentaCheckbox(1, '');
        if (nombres == 1 && apellidos == 1 && rut == 1 && cargo == 1 && celular == 1 && email == 1 && rol == 1 && sucursal == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>



@endsection
@endsection