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
        <div class="col-md-2"></div>
        <div class="col-md-8">
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre de Departamento <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre de Departamento..." value="{{  old('nombre') != null ?  old('nombre') : (!empty($departamento->departamento) ? $departamento->departamento : '') }}">
                                    <small id="invalid_nombre" class="text-danger"></small>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sucursal">Sucursal <span class="text-danger">*</span></label>
                                    <select name="sucursal" id="sucursal" class="form-control">
                                        <option value="" selected>Seleccione...</option>
                                        @if(!empty($sucursales))
                                        @foreach($sucursales as $sucursal)
                                        <option value="{{ $sucursal->id_sucursal }}" <?= old('sucursal') != null && old('sucursal') == $sucursal->id_sucursal ? 'selected' : ($departamento->id_sucursal == $sucursal->id_sucursal ? 'selected' : '') ?>>{{$sucursal->sucursal}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_sucursal" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="anfitrion">Anfitrión <span class="text-danger">*</span></label>
                                    <select name="anfitrion" id="anfitrion" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @if(!empty($anfitriones))
                                        @foreach($anfitriones as $anfitrion)
                                        <option value="{{ $anfitrion->id_empleado }}" <?= old('anfitrion') != null && old('anfitrion') == $anfitrion->id_empleado ? 'selected' : ($departamento->id_empleado == $anfitrion->id_empleado ? 'selected' : '') ?>>{{$anfitrion->nombre.' '.$anfitrion->apellido}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_anfitrion" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_huespedes">Cantidad Huéspedes <span class="text-danger">*</span></label>
                                    <input type="text" name="cantidad_huespedes" maxlength="2" id="cantidad_huespedes" class="form-control" placeholder="N° Huéspedes Permitidos..." value="{{  old('cantidad_huespedes') != null ?  old('cantidad_huespedes') : ($departamento->cantidad_huespedes > 0 ? formatear_miles($departamento->cantidad_huespedes) : '1') }}">
                                    <small id="invalid_cantidad_huespedes" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="precio_costo">Valor Departamento <span class="text-danger">*</span></label>
                                    <input type="text" name="precio_costo" maxlength="12" id="precio_costo" class="form-control" placeholder="Valor de Departamento..." value="{{ old('precio_costo') != null ?  old('precio_costo') : ($departamento->precio_costo > 0 ? formatear_numero($departamento->precio_costo) : '$0')}}">
                                    <small id="invalid_precio_costo" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="precio_arriendo">Valor Arriendo <span class="text-danger">*</span></label>
                                    <input type="text" name="precio_arriendo" maxlength="12" id="precio_arriendo" class="form-control" placeholder="Valor de Arriendo..." value="{{ old('precio_arriendo') != null ?  old('precio_arriendo') : ($departamento->valor_arriendo > 0 ? formatear_numero($departamento->valor_arriendo) : '$0')}}">
                                    <small id="invalid_precio_arriendo" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado Departamento <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="">Seleccione...</option>

                                        @if(!empty($estados_departamento))
                                        @foreach($estados_departamento as $estado)
                                        <option value="{{ $estado->id_estado_departamento }}" <?= old('estado') != null && old('estado') == $estado->id_estado_departamento ? 'selected' : ($departamento->id_estado_departamento == $estado->id_estado_departamento ? 'selected' : '') ?>>{{$estado->estado_departamento}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_estado" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nuevo">¿Mostrar como Nuevo? <span class="text-danger">*</span></label>
                                    <select name="nuevo" id="nuevo" class="form-control">
                                        <option value="1" <?= old('nuevo') == '1' ? 'selected' : ($departamento->flg_nuevo == 'Y' ? 'selected' : '') ?>>SÍ</option>
                                        <option value="0" <?= old('nuevo') == '0' ? 'selected' : ($departamento->flg_nuevo == 'N' ? 'selected' : '') ?>>NO</option>
                                    </select>
                                    <small id="invalid_nuevo" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="destacado">¿Mostrar como Destacado? <span class="text-danger">*</span></label>
                                    <select name="destacado" id="destacado" class="form-control">
                                        <option value="0" <?= old('destacado') == '0' ? 'selected' : ($departamento->flg_destacado == 'N' ? 'selected' : '') ?>>NO</option>
                                        <option value="1" <?= old('destacado') == '1' ? 'selected' : ($departamento->flg_destacado == 'Y' ? 'selected' : '') ?>>SÍ</option>
                                    </select>
                                    <small id="invalid_destacado" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion_corta">Descripción Corta <span class="text-danger">*</span></label>
                                    <textarea name="descripcion_corta" id="descripcion_corta" class="form-control" placeholder="Ingrese Descripción Corta..." rows="2">{{ old('descripcion_corta') != null ?  old('descripcion_corta') : (!empty($departamento->descripcion_corta) ? $departamento->descripcion_corta : '') }}</textarea>
                                    <small id="invalid_descripcion_corta" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción General <span class="text-danger"></span></label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese Descripción..." rows="5" style="resize: vertical; min-height: 100px;">{{ old('descripcion') != null ?  old('descripcion') : (!empty($departamento->descripcion_general) ? $departamento->descripcion_general : '') }}</textarea>
                                    <small id="invalid_descripcion" class="text-danger"></small>
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
    </div>
</div>
</div>

@section('js_content')
@include('./generalJS')
<script>
    $(document).ready(function() {

        validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        validaCampos($('#sucursal').val(), 'sucursal', 'select', true);
        validaCampos($('#anfitrion').val(), 'anfitrion', 'select', true);
        validaCampos($('#cantidad_huespedes').val(), 'cantidad_huespedes', 'numero', true);
        validaCampos($('#precio_costo').val(), 'precio_costo', 'moneda', true);
        validaCampos($('#precio_arriendo').val(), 'precio_arriendo', 'moneda', true);
        validaCampos($('#estado').val(), 'estado', 'select', true);
        validaCampos($('#nuevo').val(), 'nuevo', 'select', true);
        validaCampos($('#destacado').val(), 'destacado', 'select', true);
        validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        validaCampos($('#descripcion').val(), 'descripcion', 'texto_min_descripcion', false);




        $('#nombre').keyup(function() {
            validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        });
        $('#sucursal').change(function() {
            validaCampos($('#sucursal').val(), 'sucursal', 'select', true, 'Seleccione Sucursal');
        });
        $('#anfitrion').change(function() {
            validaCampos($('#anfitrion').val(), 'anfitrion', 'select', true, 'Seleccione Anfiitrión');
        });
        $('#cantidad_huespedes').keyup(function() {
            validaCampos($('#cantidad_huespedes').val(), 'cantidad_huespedes', 'numero', true);
        });
        $('#precio_costo').keyup(function() {
            validaCampos($('#precio_costo').val(), 'precio_costo', 'moneda', true);
        });
        $('#precio_arriendo').keyup(function() {
            validaCampos($('#precio_arriendo').val(), 'precio_arriendo', 'moneda', true);
        });
        $('#estado').change(function() {
            validaCampos($('#estado').val(), 'estado', 'select', true, 'Seleccione Estado');
        });
        $('#nuevo').change(function() {
            validaCampos($('#nuevo').val(), 'nuevo', 'select', true);
        });
        $('#destacado').change(function() {
            alidaCampos($('#destacado').val(), 'destacado', 'select', true);
        });
        $('#descripcion_corta').keyup(function() {
            validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        });
        $('#descripcion').keyup(function() {
            validaCampos($('#descripcion').val(), 'descripcion', 'texto_min_descripcion', false);
        });

    });

    $("#btn_submit").click(function() {
        let nombre = validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        let sucursal = validaCampos($('#sucursal').val(), 'sucursal', 'select', true);
        let anfitrion = validaCampos($('#anfitrion').val(), 'anfitrion', 'select', true);
        let cantidad_huespedes = validaCampos($('#cantidad_huespedes').val(), 'cantidad_huespedes', 'numero', true);
        let precio_costo = validaCampos($('#precio_costo').val(), 'precio_costo', 'moneda', true);
        let precio_arriendo = validaCampos($('#precio_arriendo').val(), 'precio_arriendo', 'moneda', true);
        let estado = validaCampos($('#estado').val(), 'estado', 'select', true);
        let nuevo = validaCampos($('#nuevo').val(), 'nuevo', 'select', true);
        let destacado = validaCampos($('#destacado').val(), 'destacado', 'select', true);
        let descripcion_corta = validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        let descripcion = validaCampos($('#descripcion').val(), 'descripcion', 'texto_min_descripcion', false);

        if (nombre == 1 && sucursal == 1 && anfitrion == 1 && cantidad_huespedes == 1 && precio_costo == 1 && precio_arriendo == 1 && estado == 1 && nuevo == 1 && destacado == 1 && descripcion_corta == 1 && descripcion == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>



@endsection
@endsection