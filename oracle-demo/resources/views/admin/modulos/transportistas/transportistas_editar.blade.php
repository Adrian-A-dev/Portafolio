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

                        <input type="hidden" name="form" id="form" value="1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rut">Rut <span class="text-danger">*</span></label>
                                    <input type="text" name="rut" id="rut" class="form-control" placeholder="Ingrese Rut..." value="{{old('rut') != null ? old('rut') : (!empty($transportista->dni) ? formateaRut($transportista->dni) : ''  ) }}" maxlength="12">
                                    <small id="invalid_rut" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ingrese Nombre Completo..." value="{{old('nombres') != null ? old('nombres') : (!empty($transportista->nombre) ? $transportista->nombre : ''  ) }}">
                                    <small id="invalid_nombres" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehiculo">Vehículo <span class="text-danger">*</span></label>
                                    <input type="text" name="vehiculo" id="vehiculo" class="form-control" placeholder="Ingrese vehiculo..." value="{{old('vehiculo') != null ? old('vehiculo') : (!empty($transportista->vehiculo) ? $transportista->vehiculo : ''  ) }}">
                                    <small id="invalid_vehiculo" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patente">Patente <span class="text-danger">*</span></label>
                                    <input type="text" name="patente" id="patente" maxlength="8" class="form-control" placeholder="Ingrese patente..." value="{{old('patente') != null ? old('patente') : (!empty($transportista->patente) ? $transportista->patente : ''  ) }}">
                                    <small id="invalid_patente" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="1" <?= old('estado') == '1' ? 'selected' : ($transportista->estado == 'Y' ? 'selected' : '') ?>>Activo</option>
                                        <option value="0" <?= old('estado') == '0' ? 'selected' : ($transportista->estado == 'N' ? 'selected' : '') ?>>Inactivo</option>
                                    </select>
                                    <small id="invalid_estado" class="text-danger"></small>
                                </div>
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
        validaCampos($('#rut').val(), 'rut', 'rut', true);
        validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        validaCampos($('#vehiculo').val(), 'vehiculo', 'texto_min', true);
        validaCampos($('#patente').val(), 'patente', 'texto_min', true);
        validaCampos($('#estado').val(), 'estado', 'select', true);
        $('#rut').keyup(function() {
            validaCampos($('#rut').val(), 'rut', 'rut', false);
        });
        $('#nombres').keyup(function() {
            validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        });
        $('#vehiculo').keyup(function() {
            validaCampos($('#vehiculo').val(), 'vehiculo', 'texto_min', true);
        });
        $('#patente').keyup(function() {
            validaCampos($('#patente').val(), 'patente', 'texto_min', true);
        });
        $('#estado').change(function() {
            validaCampos($('#estado').val(), 'estado', 'select', true);
        });

    });

    $("#btn_submit").click(function() {
        let rut = validaCampos($('#rut').val(), 'rut', 'rut', true);
        let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        let vehiculo = validaCampos($('#vehiculo').val(), 'vehiculo', 'texto_min', true);
        let patente = validaCampos($('#patente').val(), 'patente', 'texto_min', true);
        let estado = validaCampos($('#estado').val(), 'estado', 'select', true);
        if (rut == 1 && nombres == 1 && vehiculo == 1 && patente == 1 && estado == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>



@endsection
@endsection