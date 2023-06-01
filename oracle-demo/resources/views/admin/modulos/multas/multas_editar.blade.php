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
            <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Edición' ?></h4>
            <hr>
        </div>
        <div class="p-4">
            <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                {{ csrf_field() }}
                <input type="hidden" name="form" id="form" value="1">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="multa">Multa <span class="text-danger">*</span></label>
                            <input type="text" name="multa" id="multa" class="form-control" placeholder="Ingrese Multa..." aria-describedby="helpId" 
                                    value="{{ old('multa') != null ? old('multa') : (!empty($multa->multa) ? $multa->multa : '')  }}">
                            <small id="invalid_multa" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="tipo_multa">Tipo de Multa <span class="text-danger">*</span></label>
                        <select name="tipo_multa" id="tipo_multa" class="form-control">
                            <option value="">Seleccione...</option>
                            @if(!empty($tipo_multas))
                                @foreach($tipo_multas as $tipo_multa)
                                <option value="{{$tipo_multa->id_tipo_multa}}" <?= (old('tipo_multa') != null && old('tipo_multa') == $tipo_multa->id_tipo_multa) ? 'selected' :
                                                 ($multa->id_tipo_multa == $tipo_multa->id_tipo_multa ? 'selected' : '') ?> >{{strUpper($tipo_multa->tipo_multa)}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="invalid_tipo_multa" class="text-danger"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="valor">Valor <span class="text-danger">*</span></label>
                            <input type="text" name="valor" id="valor" class="form-control" placeholder="Ingrese Valor..." aria-describedby="helpId" 
                                    value="{{ old('valor') != null ? old('valor') : ($multa->monto > 0 ? formatear_numero($multa->monto) : '$0')  }}">
                            <small id="invalid_valor" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                             <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="1" <?= old('estado') == '1' ? 'selected' : ($multa->estado == 'Y' ? 'selected' : '') ?>>Activo</option>
                                <option value="0" <?= old('estado') == '0' ? 'selected' : ($multa->estado == 'N' ? 'selected' : '') ?>>Inactivo</option>
                            </select>
                            <small id="invalid_estado" class="text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                        <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@section('js_content')
@include('./generalJS')
<script>
    $(document).ready(function() {
        validaCampos($('#multa').val(), 'multa', 'texto_min', true);
        validaCampos($('#tipo_multa').val(), 'tipo_multa', 'select', true);
        validaCampos($('#estado').val(), 'estado', 'select', true);
        validaCampos($('#valor').val(), 'valor', 'moneda', true);
        $('#multa').keyup(function() {
            validaCampos($('#multa').val(), 'multa', 'texto_min', true);
        });

        $('#tipo_multa').change(function() {
            validaCampos($('#tipo_multa').val(), 'tipo_multa', 'select', true);
        });
        $('#valor').keyup(function() {
            validaCampos($('#valor').val(), 'valor', 'moneda', true);
        });
        $('#estado').change(function() {
            validaCampos($('#estado').val(), 'estado', 'select', true);
        });
    });

    $("#btn_submit").click(function() {
        let multa =   validaCampos($('#multa').val(), 'multa', 'texto_min', true);
        let tipo_multa =   validaCampos($('#tipo_multa').val(), 'tipo_multa', 'select', true);
        let estado = validaCampos($('#estado').val(), 'estado', 'select', true);
        let valor =  validaCampos($('#valor').val(), 'valor', 'moneda', true);
        if (multa == 1 && tipo_multa == 1 && estado == 1 && valor == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>
@endsection
@endsection