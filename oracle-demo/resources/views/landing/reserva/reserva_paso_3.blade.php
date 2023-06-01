@extends('layout.header')
@section('css_contenido')
<link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/jquery.calendar.css" />
@endsection
@section('head')
<div class="welcome-section text-center ptb-110">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="breadcurbs-inner">
                    <div class="breadcrubs">
                        <h2><?= isset($title) ? $title : '' ?></h2>
                        <div class="breadcrubs-menu">
                            <ul>
                                <li><a href="/">INICIO<i class="mdi mdi-chevron-right"></i></a></li>
                                <li><?= isset($title) ? $title : '' ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('contenido')
<!--Conatct form area Start-->
<?php
$cantidad_huespedes = $departamento->cantidad_huespedes > 0 ? ($departamento->cantidad_huespedes) : '1';
?>
<div class="room-booking ptb-80 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-80 text-center">
                    <h2>Detalle de <span>Reserva</span></h2>
                    <p>Reserva tu habitación y servicios en 3 Simples Pasos.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="booking-rooms-tab">
                    <ul class="nav" role="tablist">
                        <li class="<?= empty($reserva) ? 'active' : (isset($reserva) && $reserva->id_paso_reserva == 1 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">1</span><span>Reserva de<br>Departamento</span></a></li>
                        <li class="<?= empty($reserva) ? '' : (isset($reserva) && $reserva->id_paso_reserva == 2 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">2</span><span>Contratación de<br> Servicios</span></a></li>
                        <li class="<?= empty($reserva) ? '' : (isset($reserva) && $reserva->id_paso_reserva == 3 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">3</span><span>Realizar Pago de<br> Reserva</span></a></li>
                        <li class="<?= empty($reserva) ? '' : (isset($reserva) && $reserva->id_paso_reserva == 4 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">4</span><span>Confirmación de<br> Reserva</span></a></li>
                    </ul>
                </div>

                <!-- <?php if (!empty($servicios_reserva)) : ?>
                    <tr>
                        <th colspan="2" class="text-center">
                            <br>
                            <b style="font-size: 16px;">SERVICIOS CONTRATADOS</b>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center">
                            <?php foreach ($servicios_reserva as $s) : ?>
                                <b><?= $s->servicio->servicio ?></b><br>
                                <?= $s->precio > 0 ? formatear_numero($s->precio) : 'GRATIS' ?> <span>( x <?= $s->cantidad ?>)</span>
                            <?php endforeach; ?>
                        </th>
                    </tr>
                <?php endif; ?> -->
                <div class="service-tab-desc text-left mt-60">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-xl btn-danger" style="margin: 5px;" data-toggle="modal" data-target="#modal-cancel"><i class="fa fa-ban"></i> CANCELAR RESERVA</button>
                        </div>
                    </div>
                    <br>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="booking">
                            <div class="booking-info-deatils">
                                <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-7" style="padding: 0px;">
                                            <div>
                                                <div class="panel-body" style="padding: 30px;">
                                                    <h6 class="text-center"><b>PAGO DE RESERVA</b></h6>
                                                    <hr>
                                                    <h6 class="alert alert-info" style="font-size: 15px;"><i class="fa fa-info-circle"></i><b>ATENCIÓN: </b> Para finalizar proceso de reserva asocie su Tarjeta de Crédito / Débito para realizar pago parcial o completo del total de la Reserva</h6>
                                                    <br>

                                                    <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="tipo_pago">Tipo de Pago <span class="text-danger">*</span></label>
                                                                    <select name="tipo_pago" id="tipo_pago">
                                                                        <option value="1">Pago Completo</option>
                                                                        <option value="2">Pago Parcial 50% - 50% en Check-In</option>
                                                                    </select>
                                                                    <small id="invalid_tipo_pago" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="tipo_tarjeta">Tipo de Tarjeta <span class="text-danger">*</span></label>
                                                                    <select name="tipo_tarjeta" id="tipo_tarjeta">
                                                                        <option value="">Seleccione...</option>
                                                                        <option value="1">Tarjeta Crédito</option>
                                                                        <option value="2">Tarjeta Débito</option>
                                                                    </select>
                                                                    <small id="invalid_tipo_tarjeta" class="text-danger"></small>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="nombre">Nombre Titular <span class="text-danger">*</span></label>
                                                                    <input type="text" name="nombre" id="nombre" class="" value="" required placeholder="Ingrese Nombre de Tarjeta...">
                                                                    <small id="invalid_nombre" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="tarjeta">Número de Tarjeta <span class="text-danger">*</span></label>
                                                                    <input type="text" name="tarjeta" id="tarjeta" class="" value="" maxlength="16" required placeholder="Ingrese Número de Tarjeta...">
                                                                    <small id="invalid_tarjeta" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="mes">Mes <span class="text-danger">*</span></label>
                                                                    <select name="mes" id="mes">
                                                                        <?php $meses = traerMesesNumero(); ?>
                                                                        <?php foreach ($meses as $key => $mes) : ?>
                                                                            <option value="<?= $key ?>"><?= $mes ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <small id="invalid_mes" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="anio">Año <span class="text-danger">*</span></label>
                                                                    <select name="anio" id="anio">
                                                                        <option value="">Seleccione...</option>
                                                                        <?php $anio = date('Y');
                                                                        $i = 1; ?>
                                                                        <?php while ($i <= 11) : ?>
                                                                            <option value="<?= $anio ?>"><?= $anio ?></option>
                                                                            <?php $anio = $anio + 1;
                                                                            $i++; ?>
                                                                        <?php endwhile; ?>
                                                                    </select>
                                                                    <small id="invalid_anio" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="cvv">CVV <span class="text-danger">*</span></label>
                                                                    <input type="password" name="cvv" id="cvv" class="" maxlength="4" value="" required placeholder="Ingrese CVV...">
                                                                    <small id="invalid_cvv" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="pay-money-form mt-40">
                                                            <div class="payment-card">
                                                                <img src="<?= asset(ASSETS_IMG) ?>/logo/pay-card.png" alt="imagen tarjetas">
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <hr>
                                                                <div class="prve-next-box mt-20">
                                                                    <div class="back-link">
                                                                        <a href="/reserva/{{sha1(auth()->user()->id_reserva)}}/volver"><i class="fa fa-reply"></i> Volver a paso 2</a>
                                                                    </div>
                                                                    <button type="button" id="btn_guardar"><i class="fa fa-save"></i> Pagar y Continuar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-lg-5" style="padding: 0px;">
                                            <div>
                                                <div class="panel-body" style="padding: 30px;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="text-center"><b>RESUMEN DE RESERVA</b></h6>
                                                            <hr>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="2">
                                                                            <img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento">
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" class="text-center">
                                                                            <b style="font-size: 16px;">{{$departamento->departamento}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            COSTO (x{{($dias_reserva)}}) NOCHES:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 18px;">{{formatear_numero($dias_reserva_valor)}} </b>
                                                                        </th>
                                                                    </tr>
                                                                    <?php
                                                                    $sum_total_servicios = 0;
                                                                    $cantidad = 0;
                                                                    ?>
                                                                    <?php if (!empty($servicios_reserva)) : ?>
                                                                        <?php
                                                                        foreach ($servicios_reserva as $s) {
                                                                            $cantidad = $cantidad + ((int)$s->cantidad);
                                                                            $sum_total_servicios = (int)$s->precio *  (int)$s->cantidad;
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <th class="text-muted">
                                                                                COSTO (x{{$cantidad}}) SERVICIOS :
                                                                            </th>
                                                                            <th>
                                                                                <b style="font-size: 18px;">{{$sum_total_servicios > 0 ? formatear_numero($sum_total_servicios) : '$0' }} </b>
                                                                            </th>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                    <tr id="pago_completo">
                                                                        <th class="text-muted">
                                                                            TOTAL RESERVA:
                                                                        </th>
                                                                        <th>
                                                                            <?php $total =  $dias_reserva_valor + ((int)$sum_total_servicios); ?>
                                                                            <b style="font-size: 18px;">{{formatear_numero($total)}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr id="pago_parcial">
                                                                        <th class="text-muted">
                                                                            (50%) TOTAL RESERVA:
                                                                        </th>
                                                                        <th>
                                                                            <?php $total =  $dias_reserva_valor + ((int)$sum_total_servicios); ?>
                                                                            <b style="font-size: 18px;">{{formatear_numero(round($total/2))}} </b>
                                                                        </th>
                                                                    </tr>
                                                                </thead>

                                                            </table>
                                                            <small>Card Ejemplo: 5523744648825206</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_valida">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body  text-center">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que deseas realizar Pago de la Reserva?</p>
            </div>
            <div class="modal-body  text-center">
                <label class="text-left" for="politica_empresa"><input type="checkbox" id="politica_empresa" style="height: 15px;"> <small id="lbl_politica_empresa">Acepto los Términos de Reserva</small></label>
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="continuar" class="btn btn-success"><i class="fa fa-money"></i> Si, Pagar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade text-center" id="modal-cancel">
    <div class="modal-dialog modal-md text-center">
        <form action="/reservas/cancelar" method="post">
            {{ csrf_field() }}
            <div class="modal-content ">
                <div class="modal-body">
                    <input type="hidden" name="id_cancel" value="<?= $reserva->id_reserva ?>">
                    <i class="text-danger fa fa-2x fa-question-circle"></i>
                    <hr>
                    <p class="modal-title text-center">¿Estás seguro que deseas cancelar la reserva?</p>
                    <p><b>ESTA ACCIÓN NO SE PUEDE DESHACER</b></p>
                    <input type="hidden" id="id_cancel">
                </div>
                <div class="modal-footer text-center">
                    <button type="button" id="btn_cancel" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="submit" onclick="cargando('Cancelando Reserva...')" class="btn btn-danger"><i class="fa fa-check-circle"></i> Confirmar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('js_content')
@include('./generalJS')
<script src="<?= asset(ASSETS_JS) ?>/jquery.creditCardValidator.js"></script>
<script>
    $(document).ready(function() {
        validaCampos($('#tipo_pago').val(), 'tipo_pago', 'select', true);
        validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
        validaCampos($('#nombre').val(), 'nombre', 'nombres', true);
        validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
        validaCampos($('#mes').val(), 'mes', 'select', true);
        validaCampos($('#anio').val(), 'anio', 'select', true);
        validaCampos($('#cvv').val(), 'cvv', 'numero', true);
        TipoPago($("#tipo_pago").val())
        $("#tipo_pago").change(function() {
            validaCampos($('#tipo_pago').val(), 'tipo_pago', 'select', true);
            TipoPago($(this).val())
        })
        $("#tipo_tarjeta").change(function() {
            validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
        })
        $("#nombre").keyup(function() {
            validaCampos($('#nombre').val(), 'nombre', 'nombres', true);
        })
        $("#tarjeta").keyup(function() {
            $('#tarjeta').validateCreditCard(function(result) {
                if (result.valid) {
                    $("#tarjeta").removeClass('required');
                    cardValid = 1;
                    $("#tarjeta").css('border-color', 'green');
                    $("#invalid_tarjeta").html('');
                } else {
                    $("#tarjeta").addClass('required');
                    cardValid = 0;
                }
            });
            validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
        })

        $("#mes").change(function() {
            validaCampos($('#mes').val(), 'mes', 'select', true);
        })

        $("#anio").change(function() {
            validaCampos($('#anio').val(), 'anio', 'select', true);
        })

        $("#cvv").keyup(function() {
            validaCampos($('#cvv').val(), 'cvv', 'numero', true);
        })






    })

    $("#btn_guardar").click(function() {
        let tipo_tarjeta = validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
        let nombre = validaCampos($('#nombre').val(), 'nombre', 'nombres', true);
        let tarjeta = 0;
        $('#tarjeta').validateCreditCard(function(result) {
            if (result.valid) {
                $("#tarjeta").removeClass('required');
                tarjeta = 1;
                $("#tarjeta").css('border-color', 'green');
                $("#invalid_tarjeta").html('');
            } else {
                $("#tarjeta").addClass('required');
                tarjeta = 0;
            }
            validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
        });
        let mes = validaCampos($('#mes').val(), 'mes', 'select', true);
        let anio = validaCampos($('#anio').val(), 'anio', 'select', true);
        let cvv = validaCampos($('#cvv').val(), 'cvv', 'numero', true);
        if (tipo_tarjeta == 1 && nombre == 1 && tarjeta == 1 && mes == 1 && anio == 1 && cvv == 1) {
            $("#politica_empresa").attr('checked', false);
            $("#lbl_politica_empresa").removeClass('text-danger');
            $("#lbl_politica_empresa").addClass('text-danger');
            $("#continuar").attr('disabled', true);
            $("#modal_valida").modal("show");

        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }

    })
    $("#politica_empresa").click(function() {
        if ($(`#politica_empresa`).is(":checked")) {
            $("#lbl_politica_empresa").removeClass('text-danger');
            $("#continuar").attr('disabled', false);
        } else {
            $("#lbl_politica_empresa").removeClass('text-danger');
            $("#lbl_politica_empresa").addClass('text-danger');
            $("#continuar").attr('disabled', true);
        }

    })
    $("#continuar").click(function() {
        cargando('Validando Información...')
        $("#formulario").submit();
    });

    function TipoPago(valor) {
        if (valor == 2) {
            $("#pago_completo").attr('hidden', true);
            $("#pago_parcial").attr('hidden', false);
        } else {
            $("#pago_completo").attr('hidden', false);
            $("#pago_parcial").attr('hidden', true);
        }
    }
</script>


@endsection
@endsection