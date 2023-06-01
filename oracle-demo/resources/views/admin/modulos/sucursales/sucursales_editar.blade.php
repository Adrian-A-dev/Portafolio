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
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
            <hr>
        </div>
        <div class="p-4">
            <form action="<?= isset($action) ? $action : '' ?>" id="formulario" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="form" id="form" value="1">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sucursal">Nombre de Sucursal <span class="text-danger">*</span></label>
                            <input type="text" name="sucursal" id="sucursal" class="form-control" placeholder="Ingrese Nombre de Sucursal..." value="{{ old('sucursal') != null ? old('sucursal') : (!empty($sucursal->sucursal) ? $sucursal->sucursal : '')  }}">
                            <small id="invalid_sucursal" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="1" <?= old('estado') == '1' ? 'selected' : ($sucursal->estado == 'Y' ? 'selected' : '') ?>>Activo</option>
                                <option value="0" <?= old('estado') == '0' ? 'selected' : ($sucursal->estado == 'N' ? 'selected' : '') ?>>Inactivo</option>
                            </select>
                            <small id="invalid_estado" class="text-danger"></small>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="region">Región <span class="text-danger">*</span></label>
                            <select name="region" id="region" class="form-control">
                                <option value="">Seleccione Region...</option>
                                @if(!empty($regiones))
                                @foreach($regiones as $region)
                                <option value="{{$region->id_region}}" <?= (old('region') != null && old('region') == $region->id_region) ? 'selected' : (!empty($sucursal->comuna->region_id_region) && $sucursal->comuna->region_id_region == $region->id_region ? 'selected' : '') ?>>{{strUpper($region->nombre_largo)}}</option>
                                @endforeach
                                @endif
                            </select>
                            <small id="invalid_region" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="comuna">Comuna <span class="text-danger">*</span></label>
                            <select name="comuna" id="comuna" class="form-control" <?= !empty($sucursal->comuna->region_id_region) ? '' : 'disabled' ?>>
                                <option value="">Seleccione Comuna...</option>
                                @if(!empty($comunas))
                                @foreach($comunas as $comuna)
                                <option value="{{$comuna->id_comuna}}" <?= old('region') != null && old('region') == $region->id_region ? 'selected' : ($sucursal->comuna_id_comuna == $comuna->id_comuna ? 'selected' : '') ?>>{{strUpper($comuna->comuna)}}</option>
                                @endforeach
                                @endif
                            </select>
                            <small id="invalid_comuna" class="text-danger"></small>
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

@section('js_content')
@include('./generalJS')
<script>
    $("#region").change(function() {
        let region = $(this).val();
        if (region > 0) {
            $.ajax({
                url: "/dashboard/obtener-comunas",
                type: "GET",
                data: {
                    region: region,
                },
                success: function(resp) {
                    if (resp == 'error') { // SI SE ELIMINA CLIENTE
                        toastr["error"](`Región No posee Comunas asociados`, "No se encuentran registros")
                        $("#comuna").attr('disabled', true);
                        $("#comuna").html('<option value="">Seleccione Comuna...</option>')
                        validaCampos($('#comuna').val(), 'comuna', 'select', true);
                    } else {
                        $("#comuna").attr('disabled', false);
                        $("#comuna").html(resp)
                        $("#comuna").focus()
                        validaCampos($('#comuna').val(), 'comuna', 'select', true);
                    }
                },
                error: function(error) {
                    console.log(JSON.stringify(error))
                    toastr["error"](`Error Interno`, "Error")
                    $("#comuna").attr('disabled', true);
                    $("#comuna").html('<option value="">Seleccione Comuna...</option>')
                    validaCampos($('#comuna').val(), 'comuna', 'select', false);
                }
            });
        } else {
            $("#comuna").html('<option value="">Seleccione Comuna...</option>')
            $("#comuna").attr('disabled', true);
            validaCampos($('#comuna').val(), 'comuna', 'select', false);
        }
    })
</script>

<script>
    $(document).ready(function() {
        validaCampos($('#sucursal').val(), 'sucursal', 'texto_min', true);
        validaCampos($('#estado').val(), 'estado', 'select', true);
        validaCampos($('#region').val(), 'region', 'select', true);
        <?php if (!empty($sucursal->comuna_id_comuna)) : ?>
            validaCampos($('#comuna').val(), 'comuna', 'select', true);
        <?php endif; ?>
        $('#sucursal').keyup(function() {
            validaCampos($('#sucursal').val(), 'sucursal', 'texto_min', true);
        });
        $('#estado').change(function() {
            validaCampos($('#estado').val(), 'estado', 'select', true);
        });

        $('#region').change(function() {
            validaCampos($('#region').val(), 'region', 'select', true);
        });
        $('#comuna').change(function() {
            validaCampos($('#comuna').val(), 'comuna', 'select', true);
        });
    });

    $("#btn_submit").click(function() {
        let sucursal = validaCampos($('#sucursal').val(), 'sucursal', 'texto_min', true);
        let estado = validaCampos($('#estado').val(), 'estado', 'select', true);
        let region = validaCampos($('#region').val(), 'region', 'select', true);
        let validado = false;
        if ($('#region').val() > 0) {
            validado = true;
        }
        let comuna = validaCampos($('#comuna').val(), 'comuna', 'select', validado);
        if (sucursal == 1 && estado == 1 && region == 1 && comuna == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>
@endsection


@endsection