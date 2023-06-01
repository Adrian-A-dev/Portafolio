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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre de Servicio <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre de Servicio..." value="{{ old('nombre') != null ? old('nombre') : (!empty($servicio->servicio) ? $servicio->servicio : '') }}">
                                    <small id="invalid_nombre" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_servicio">Tipo Servicio <span class="text-danger">*</span></label>
                                    <select name="tipo_servicio" id="tipo_servicio" class="form-control">
                                        <option value="" selected>Seleccione...</option>
                                        @if(!empty($tipo_servicios))
                                        @foreach($tipo_servicios as $tipo_servicio)
                                        <option value="{{ $tipo_servicio->id_tipo_servicio }}" <?= old('tipo_servicio') != null && old('tipo_servicio') == $tipo_servicio->id_tipo_servicio ? 'selected' : (!empty($servicio->id_tipo_servicio) && $servicio->id_tipo_servicio == $tipo_servicio->id_tipo_servicio ? 'selected' : '') ?>>{{$tipo_servicio->tipo_servicio}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_tipo_servicio" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <h6 class="div_lugares" <?= old('val_lugares') != null && old('val_lugares') == 1 ? '' : ($servicio->flg_lugar == 'N' ? 'hidden'  : '') ?>>Información de Lugares</h6>
                        <hr class="div_lugares" <?= old('val_lugares') != null && old('val_lugares') == 1 ? '' : ($servicio->flg_lugar == 'N' ? 'hidden'  : '') ?>>
                        <div class="row div_lugares" <?= old('val_lugares') != null && old('val_lugares') == 1 ? '' : ($servicio->flg_lugar == 'N' ? 'hidden'  : '') ?>>
                            <input type="hidden" id="val_lugares" name="val_lugares" value="<?= old('val_lugares') != null && old('val_lugares') == 1 ? '1' : ($servicio->flg_lugar == 'N' ? '0'  : '1') ?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="origen">Lugar Origen <span class="text-danger">*</span></label>
                                    <input type="text" name="origen" maxlength="100" id="origen" class="form-control" placeholder="Ingrese Lugar de Origen..." value="{{ old('origen') != null ? old('origen') : (!empty($servicio->lugar_origen) ? $servicio->lugar_origen : '') }}">
                                    <small id="invalid_origen" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destino">Lugar Destino <span class="text-danger">*</span></label>
                                    <input type="text" name="destino" maxlength="100" id="destino" class="form-control" placeholder="Ingrese Lugar de Destino..." value="{{ old('destino') != null ? old('destino') : (!empty($servicio->lugar_destino) ? $servicio->lugar_destino : '') }}">
                                    <small id="invalid_destino" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <h6 class="div_horarios" <?= old('val_horarios') != null && old('val_horarios') == 1 ? '' : ($servicio->flg_horario == 'N' ? 'hidden'  : '') ?>>Información de Horarios</h6>
                        <hr class="div_horarios" <?= old('val_horarios') != null && old('val_horarios') == 1 ? '' : ($servicio->flg_horario == 'N' ? 'hidden'  : '') ?>>
                        <div class="row div_horarios" <?= old('val_horarios') != null && old('val_horarios') == 1 ? '' : ($servicio->flg_horario == 'N' ? 'hidden'  : '') ?>>
                            <input type="hidden" id="val_horarios" name="val_horarios" value="<?= old('val_horarios') != null && old('val_horarios') == 1 ? '1' : ($servicio->flg_horario == 'N' ? '0'  : '1') ?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio<span class="text-danger"></span></label>
                                    <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-control" readonly placeholder="Seleccione Fecha de Inicio..." value="{{ old('fecha_inicio') != null ? old('fecha_inicio') : (!empty($servicio->fecha_inicio) ? ordenar_fechaHumano($servicio->fecha_inicio) : '') }}">
                                    <small id="invalid_fecha_inicio" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin <span class="text-danger"></span></label>
                                    <input type="text" name="fecha_fin" id="fecha_fin" class="form-control" readonly placeholder="Seleccione Fecha de Fin..." value="{{ old('fecha_fin') != null ? old('fecha_fin') : (!empty($servicio->fecha_fin) ? ordenar_fechaHumano($servicio->fecha_fin) : '') }}">
                                    <small id="invalid_fecha_fin" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora_inicio">Hora Inicio <span class="text-danger">*</span></label>
                                    <input type="text" name="hora_inicio" maxlength="5" id="hora_inicio" class="form-control" placeholder="Ingrese Horario de Inicio..." value="{{ old('hora_inicio') != null ? old('hora_inicio') : (!empty($servicio->hora_inicio) ? ordenar_fechaHumano($servicio->hora_inicio) : '') }}">
                                    <small id="invalid_hora_inicio" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora_fin">Hora Fin <span class="text-danger">*</span></label>
                                    <input type="text" name="hora_fin" maxlength="5" id="hora_fin" class="form-control" placeholder="Ingrese Horario de Fin..." value="{{ old('hora_fin') != null ? old('hora_fin') : (!empty($servicio->hora_fin) ? ordenar_fechaHumano($servicio->hora_fin) : '') }}">
                                    <small id="invalid_hora_fin" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <h6 class="div_transportista" <?= old('val_transportista') != null && old('val_transportista') == 1 ? '' : ($servicio->flg_transportista == 'N' ? 'hidden'  : '') ?>>Información de Transportista</h6>
                        <hr class="div_transportista" <?= old('val_transportista') != null && old('val_transportista') == 1 ? '' : ($servicio->flg_transportista == 'N' ? 'hidden'  : '') ?>>
                        <div class="row div_transportista" <?= old('val_transportista') != null && old('val_transportista') == 1 ? '' : ($servicio->flg_transportista == 'N' ? 'hidden'  : '') ?>>
                            <input type="hidden" id="val_transportista" name="val_transportista" value="<?= old('val_transportista') != null && old('val_transportista') == 1 ? '1' : ($servicio->flg_transportista == 'N' ? '0'  : '1') ?>">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transportista">Transportista <span class="text-danger">*</span></label>
                                    <select name="transportista" id="transportista" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @if(!empty($transportistas))
                                        @foreach($transportistas as $transportista)
                                        <option value="{{ $transportista->id_transportista }}" <?= old('transportista') != null && old('transportista') == $transportista->id_transportista ? 'selected' : (!empty($servicio->id_transportista) && $servicio->id_transportista == $transportista->id_transportista ? 'selected' : '') ?>>{{$transportista->nombre.' '.$transportista->apellido}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <small id="invalid_transportista" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="precio">Valor Servicio <span class="text-danger">*</span></label>
                                    <input type="text" name="precio" maxlength="12" id="precio" class="form-control" placeholder="Ingrese Valor de Servicio..." value="{{ old('precio') != null ? old('precio') : (!empty($servicio->precio) ? formatear_numero($servicio->precio) : '$0') }}">
                                    <small id="invalid_precio" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad Disponible <span class="text-danger">*</span></label>
                                    <input type="text" name="cantidad" maxlength="10" id="cantidad" class="form-control" placeholder="Ingrese Cantidad Disponibles..." value="{{ old('cantidad') != null ? old('cantidad') : (!empty($servicio->cantidad) ? formatear_miles($servicio->cantidad) : 0) }}">
                                    <small id="invalid_cantidad" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="1" <?= old('estado') == '1' ? 'selected' : ($servicio->estado == 'Y' ? 'selected'  : '') ?>>Activo</option>
                                        <option value="0" <?= old('estado') == '0' ? 'selected' : ($servicio->estado == 'N' ? 'selected'  : '') ?>>Inactivo</option>
                                    </select>
                                    <small id="invalid_nuevo" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion_corta">Descripción Corta <span class="text-danger">*</span></label>
                                    <textarea name="descripcion_corta" id="descripcion_corta" class="form-control" maxlength="255" placeholder="Ingrese Descripción Corta..." rows="2">{{ old('descripcion_corta') != null ? old('descripcion_corta') : (!empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : '') }}</textarea>
                                    <small id="invalid_descripcion_corta" class="text-danger"></small>
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
        $('#fecha_inicio').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(),
        });
        $('#fecha_fin').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(),
        });
        $('#hora_inicio').timeDropper({
            format: 'HH:mm',
            autoswitch: false,
            meridians: false,
            mousewheel: false,
            setCurrentTime: false,
        });
        $('#hora_fin').timeDropper({
            format: 'HH:mm',
            autoswitch: false,
            meridians: false,
            mousewheel: false,
            setCurrentTime: false,
        });
        validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        validaCampos($('#tipo_servicio').val(), 'tipo_servicio', 'select', true);
        validaCampos($('#cantidad').val(), 'cantidad', 'numero', true);
        validaCampos($('#precio').val(), 'precio', 'moneda', true);
        validaCampos($('#estado').val(), 'estado', 'select', true);
        validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        // ocultarCampos()

        $('#nombre').keyup(function() {
            validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        });
        $('#tipo_servicio').change(function() {
            validaCampos($('#tipo_servicio').val(), 'tipo_servicio', 'select', true, 'Seleccione Tipo Servicio');
            let tipo_servicio = $(this).val();
            if (tipo_servicio > 0) {
                cargando()
                $.ajax({
                    url: "/dashboard/tipo-servicios/obtener-tipo-servicio",
                    type: "GET",
                    data: {
                        tipo_servicio: tipo_servicio,
                    },
                    dataType: 'json',
                    success: function(resp) {
                        Swal.close();
                        if (resp == 'error') { // SI SE ELIMINA CLIENTE
                            toastr["error"](`Tipo de Servicio Seleccionado No existe o fue eliminado`, "No se encuentran registros")
                        } else {
                            ocultarCampos()
                            console.log(resp)
                            if (resp.flg_lugar == 'Y') {
                                $(".div_lugares").attr('hidden', false)
                                validaCampos($('#origen').val(), 'origen', 'texto_min', true);
                                validaCampos($('#destino').val(), 'destino', 'texto_min', true);
                                $("#val_lugares").val(1);
                            }
                            if (resp.flg_horario == 'Y') {
                                $(".div_horarios").attr('hidden', false);
                                $("#val_horarios").val(1);
                                validaCampos($('#hora_inicio').val(), 'hora_inicio', '', true);
                                validaCampos($('#hora_fin').val(), 'hora_fin', '', true);
                            }
                            if (resp.flg_transportista == 'Y') {
                                $("#val_transportista").val(1);
                                $(".div_transportista").attr('hidden', false);
                                validaCampos($('#transportista').val(), 'transportista', 'select', true);
                            }

                            // validaCampos($('#comuna').val(), 'tribunal', 'select', true, 'Seleccione Tribunal');
                        }
                    },
                    error: function(error) {
                        Swal.close();
                        console.log(JSON.stringify(error))
                        toastr["error"](`Error Interno`, "Error")
                    }
                });
            } else {
                Swal.close();
                ocultarCampos()
            }
        });
        $('#origen').keyup(function() {
            validaCampos($('#origen').val(), 'origen', 'texto_min', true);
        });
        $('#destino').keyup(function() {
            validaCampos($('#destino').val(), 'destino', 'texto_min', true);
        });
        $('#transportista').change(function() {
            validaCampos($('#transportista').val(), 'transportista', 'select', true, 'Seleccione Transportista');
        });
        $('#fecha_inicio').blur(function() {
            validaCampos($('#fecha_inicio').val(), 'fecha_inicio', '', false);
        });
        $('#fecha_fin').blur(function() {
            validaCampos($('#fecha_fin').val(), 'fecha_fin', '', false);
        });

        $('#hora_inicio').blur(function() {
            validaCampos($('#hora_inicio').val(), 'hora_inicio', '', false);
        });

        $('#hora_fin').blur(function() {
            validaCampos($('#hora_fin').val(), 'hora_fin', '', false);
        });
        $('#precio').keyup(function() {
            validaCampos($('#precio').val(), 'precio', 'moneda', true);
        });
        $('#cantidad').keyup(function() {
            validaCampos($('#cantidad').val(), 'cantidad', 'numero', true);
        });
        $('#estado').change(function() {
            validaCampos($('#estado').val(), 'estado', 'select', true, 'Seleccione Estado');
        });
        $('#descripcion_corta').keyup(function() {
            validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        });
    })

    function ocultarCampos() {
        $(".div_lugares").attr('hidden', true);
        $(".div_horarios").attr('hidden', true);
        $(".div_transportista").attr('hidden', true);
        quitarEstilos('origen');
        quitarEstilos('destino');
        quitarEstilos('fecha_inicio');
        quitarEstilos('fecha_fin');
        quitarEstilos('hora_inicio');
        quitarEstilos('hora_fin');
        quitarEstilos('transportista');
        $("#val_lugares").val(0);
        $("#val_horarios").val(0);
        $("#val_transportista").val(0);
        $("#transportista").attr('selectedIndex', '-1');
    }


    $("#btn_submit").click(function() {
        let nombre = validaCampos($('#nombre').val(), 'nombre', 'texto_min', true);
        let tipo_servicio = validaCampos($('#tipo_servicio').val(), 'tipo_servicio', 'select', true);
        let precio = validaCampos($('#precio').val(), 'precio', 'moneda', true);
        let estado = validaCampos($('#estado').val(), 'estado', 'select', true);
        let descripcion_corta = validaCampos($('#descripcion_corta').val(), 'descripcion_corta', 'texto_min', true);
        let cantidad = validaCampos($('#cantidad').val(), 'cantidad', 'numero', true);
        let origen = 1;
        let destino = 1;
        let fecha_inicio = 1;
        let fecha_fin = 1;
        let hora_inicio = 1;
        let hora_fin = 1;
        let transportista = 1;

        let val_lugares = $("#val_lugares").val();
        if (val_lugares == 1) {
            origen = validaCampos($('#origen').val(), 'origen', 'texto_min', true);
            destino = validaCampos($('#destino').val(), 'destino', 'texto_min', true);
        }
        let val_horarios = $("#val_horarios").val();
        if (val_horarios == 1) {
            hora_inicio = validaCampos($('#hora_inicio').val(), 'hora_inicio', '', true);
            hora_fin = validaCampos($('#hora_fin').val(), 'hora_fin', '', true);
        }
        let val_transportista = $("#val_transportista").val();
        if (val_transportista == 1) {
            transportista = validaCampos($('#transportista').val(), 'transportista', 'select', true, 'Seleccione Transportista');
        }

        if (nombre == 1 && tipo_servicio == 1 && precio == 1 && estado == 1 &&
            descripcion_corta == 1 && origen == 1 && destino == 1 && fecha_inicio == 1 &&
            fecha_fin == 1 && hora_inicio == 1 && hora_fin == 1 && transportista == 1 && cantidad == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>



@endsection
@endsection