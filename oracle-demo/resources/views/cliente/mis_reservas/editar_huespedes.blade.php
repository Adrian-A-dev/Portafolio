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
        <div class="col-lg-2 col-md-1"></div>
        <div class="col-lg-8 col-md-10">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                        {{ csrf_field() }}
                        <div class="alert alert-primary text-center" role="alert">
                            <b><i class="fa fa-info-circle"></i> ATENCIÓN:</b><br> <span>El correo electrónico debe ser válido para que el usuario pueda recibir los pasos de activación de cuenta</span></b>
                        </div>
                        <hr>
                        <input type="hidden" name="form" id="form" value="1">
                        <?php $i=1; while ($i <= $reserva->numero_huespedes): ?>
                            <h6>Información de Huésped N°<?=$i?></h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rut">Rut <span class="text-danger"></span></label>
                                    <input type="text" name="rut[<?=$i?>]" id="rut" class="form-control" placeholder="Ingrese Rut..." value="{{old('rut')}}" maxlength="12">
                                    <small id="invalid_rut" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres[<?=$i?>]" id="nombres" class="form-control" placeholder="Ingrese Nombres..." value="{{old('nombres')}}">
                                    <small id="invalid_nombres" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellidos[<?=$i?>]" id="apellidos" class="form-control" placeholder="Ingrese Apellidos..." value="{{old('apellidos')}}">
                                    <small id="invalid_apellidos" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="celular">Celular</label>
                                    <input type="tel" name="celular[<?=$i?>]" id="celular" class="form-control" placeholder="Ingrese Celular..." minlength="12" maxlength="12" value="{{ old('celular') }}">
                                    <small id="invalid_celular" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                    <input type="text" name="fecha_nacimiento[<?=$i?>]" id="fecha_nacimiento" class="form-control fecha_nacimiento_input" readonly placeholder="Seleccione Fecha Nacimiento..." value="{{ old('fecha_nacimiento') }}">
                                    <small id="invalid_fecha_nacimiento" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                            <?php ($i++); endwhile?>

                        
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
        $('.fecha_nacimiento_input').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(1910, 0, 1),
            maxDate: new Date(),
        });
        // validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        // validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        // validaCampos($('#rut').val(), 'rut', 'rut', false);
        // validaCampos($('#cargo').val(), 'cargo', 'select', true);
        // validaCampos($('#celular').val(), 'celular', 'celular', false);

        // validaCorreo($('#email').val(), 'email');
        // validaCampos($('#rol').val(), 'rol', 'select', true);
        // cuentaCheckbox(1, '');

        // $('#nombres').keyup(function() {
        //     validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        // });
        // $('#apellidos').keyup(function() {
        //     validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        // });
        // $('#rut').keyup(function() {
        //     validaCampos($('#rut').val(), 'rut', 'rut', false);
        // });
        // $('#cargo').change(function() {
        //     validaCampos($('#cargo').val(), 'cargo', 'select', true, 'Seleccione Cargo');
        // });
        // $('#celular').keyup(function() {
        //     let cel = checkNumero($('#celular').val());
        //     $('#celular').val(cel)
        //     validaCampos($('#celular').val(), 'celular', 'celular', false);
        // });
        // $('#email').keyup(function() {
        //     validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido')
        // });
        // $('#rol').change(function() {
        //     validaCampos($('#rol').val(), 'rol', 'select', true, 'Seleccione Rol');
        // });

    });

    $("#btn_submit").click(function() {
        // let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        // let apellidos = validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        // let rut = validaCampos($('#rut').val(), 'rut', 'rut', false);
        // let cargo = validaCampos($('#cargo').val(), 'cargo', 'select', true, 'Seleccione Cargo');
        // let celular = validaCampos($('#celular').val(), 'celular', 'celular', false);
        // let email = validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido');
        // let rol = validaCampos($('#rol').val(), 'rol', 'select', true, 'Seleccione Rol');
        // let sucursal = cuentaCheckbox(1, '');
        // if (nombres == 1 && apellidos == 1 && rut == 1 && cargo == 1 && celular == 1 && email == 1 && rol == 1 && sucursal == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        // } else {
        //     toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        // }
    })
</script>



@endsection
@endsection