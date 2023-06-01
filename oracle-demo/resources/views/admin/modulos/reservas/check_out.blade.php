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
        <div class="col-md-12">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <div class="invoice-wrap">
                        <div class="invoice-box">
                            <?php
                            $paso = $reserva->id_paso_reserva;

                            if ($reserva->id_estado_reserva == 7) {
                                $paso_estado = 'Finalizado';
                                $badge = 'badge-success';
                            } elseif ($reserva->id_estado_reserva == 5) {
                                $paso_estado = 'Pendiente';
                                $badge = 'badge-warning';
                            } else {
                                $paso_estado = 'No generado';
                                $badge = 'badge-danger';
                            }
                            ?>
                            <h4 class="text-center mb-30 weight-600"><?= isset($title) ? strUpper($title)  : '' ?> - <span class='badge {{$badge}}'>{{strUpper($paso_estado)}}</span></h4>
                            <div class="row pb-30">
                                <div class="col-md-12">
                                    <div class="text-left">
                                        <h6 class="mb-15 alert alert-dark text-dark">INFORMACIÓN DE RESERVA</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left">
                                        <p class="font-14 mb-5">Cliente: <strong class="weight-600"><?= !empty($reserva->nombre_reserva) ? $reserva->nombre_reserva : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Fecha Llegada: <strong class="weight-600"><?= !empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) :  'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Departamento: <strong class="weight-600"><?= !empty($reserva->departamento->departamento) ? $reserva->departamento->departamento : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Cantidad de Noches: <strong class="weight-600"><?= ($reserva->cantidad_noches) > 0 ? formatear_miles($reserva->cantidad_noches) :  '1' ?></strong></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left">
                                        <p class="font-14 mb-5">Email: <strong class="weight-600"><?= !empty($reserva->email_reserva) ? $reserva->email_reserva : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Fecha Salida: <strong class="weight-600"><?= !empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) :  'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Sucursal: <strong class="weight-600"><?= !empty($reserva->departamento->sucursal->sucursal) ? $reserva->departamento->sucursal->sucursal : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Valor por Noche: <strong class="weight-600"><?= ($reserva->valor_noche) > 0 ? formatear_numero($reserva->valor_noche) :  '$0' ?></strong></p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-left">
                                        <h6 class="mb-15 alert alert-dark text-dark">DETALLE DE SERVICIOS</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-desc pb-30">
                                <div class="invoice-desc-head clearfix">
                                    <div class="invoice-sub">SERVICIO</div>
                                    <div class="invoice-hours">CANT</div>
                                    <div class="invoice-subtotal">TOTAL</div>
                                </div>
                                <div class="invoice-desc-body" style="overflow-y: scroll">
                                    <ul>
                                        <?php if (!empty($servicios_reserva)) : ?>
                                            <?php foreach ($servicios_reserva as $s) : ?>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><?= $s->servicio->servicio ?></div>
                                                    <div class="invoice-hours"><?= ($s->cantidad) > 0 ? formatear_miles($s->cantidad) :  '0' ?></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"><?= ($s->precio) > 0 ? formatear_numero($s->precio) :  '$0' ?></span></div>
                                                </li>
                                            <?php endforeach; ?>
                                            <li class="clearfix">
                                                <div class="invoice-sub"><b>TOTAL SERVICIOS</b></div>
                                                <div class="invoice-hours"><b><?= ($total_servicios_cantidad) > 0 ? formatear_miles($total_servicios_cantidad) :  '0' ?></b></div>
                                                <div class="invoice-subtotal"><b><?= ($total_servicios) > 0 ? formatear_numero($total_servicios) :  '$0' ?></b></div>
                                            </li>
                                        <?php else : ?>
                                            <li class="clearfix text-center">
                                                <h6 class="alert alert-danger">NO EXISTEN SERVICIOS ASOCIADOS</h6>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-left">
                                                        <h6 class="mb-15 alert alert-dark text-dark">MULTAS DEPARTAMENTO</h6>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2"></div>
                                                        <div class="col-lg-8  table-responsive">
                                                            <table class="table">
                                                                <thead class="bg-dark text-white">
                                                                    <th>MULTA</th>
                                                                    <th>VALOR</th>
                                                                    <th class="text-center">SELECCIONAR</th>
                                                                </thead>
                                                                <tbody>
                                                                    @if(!empty($multas))
                                                                    @foreach ($multas as $multa)
                                                                    <?php $multa = (object)$multa; ?>
                                                                    <tr>
                                                                        <td><label for="multa_{{$multa->id_multa}}" class="ml-2"> {{$multa->multa}}</label></td>
                                                                        <td><label for="multa_{{$multa->id_multa}}" class="ml-2"> <input type="hidden" value="{{$multa->monto}}" id="monto_<?= $multa->id_multa ?>"> {{$multa->monto > 0 ? formatear_numero($multa->monto) : '$0'}}</label></td>
                                                                        <td class="text-center"><input type="checkbox" <?= $reserva->id_estado_reserva > 5 ? 'disabled' : '' ?> class="form-control multa" style="width: 20px;margin:0 auto;" id="multa_{{$multa->id_multa}}" name="multa[]" {{ $multa->checked == '1' ? 'checked' : ''}} value="{{$multa->id_multa}}" /></td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... No se encontraron Multas para mostrar. </h6>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12" style="margin-bottom: -30px;">
                                                            <div class="form-group">
                                                                <label for="otra_multa" class="ml-2"> <input type="checkbox" class="problema_adicional" style="width: 20px;margin:0 auto;" <?= $reserva->check_out->flg_problema_adicional == 'Y' ? 'checked' : '' ?> value="Y" name="otra_multa" id="otra_multa"> ¿Posee problema adicional?</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <div class="form-group">
                                                                <label for="observacion_adicional"></label>
                                                                <input type="text" name="observacion_adicional" id="observacion_adicional" class="form-control" placeholder="Ingrese Problema adicional" <?= $reserva->check_out->flg_problema_adicional == 'N' ? 'disabled' : '' ?> value="<?= !empty($reserva->check_out->observacion_adicional) ? $reserva->check_out->observacion_adicional : ''?>">
                                                                <small id="invalid_observacion_adicional" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <label for="total_adicional"></label>
                                                                <input type="text" name="total_adicional" id="total_adicional" class="form-control" placeholder="Ingrese Monto adicional" value="<?= $reserva->check_out->total_adicional > 0 ? formatear_numero($reserva->check_out->total_adicional) : '' ?>" <?= $reserva->check_out->flg_problema_adicional == 'N' ? 'disabled' : '' ?>>
                                                                <small id="invalid_total_adicional" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-left">
                                                <h6 class="mb-15 alert alert-dark text-dark">DETALLE DE PAGO</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-desc pb-30">
                                        <div class="invoice-desc-head clearfix">
                                            <div class="invoice-sub">DESCRIPCIÓN</div>
                                            <div class="invoice-subtotal">MONTO:</div>
                                        </div>
                                        <div class="invoice-desc-body">
                                            <ul>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><b>VALOR TOTAL RESERVA:</b></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->total_reserva) > 0 ? formatear_numero($reserva->total_reserva) :  '$0' ?></strong></span></div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><b>TOTAL ABONADO RESERVA:</b></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->total_pagado) > 0 ? formatear_numero($reserva->total_pagado) :  '$0' ?></strong></span></div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><b>DIFERENCIA POR PAGAR:</b></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->diferencia_pago) > 0 ? formatear_numero($reserva->diferencia_pago) :  '$0' ?></strong></span></div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><b>MULTAS:</b></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"><input type="hidden" id="total_multa" name="total_multa" value="<?= !empty($reserva->check_out->total_multa) ? $reserva->check_out->total_multa : 0 ?>"> <strong class="weight-600" id="txt_total_multa"><?= ($reserva->check_out->total_multa) > 0 ? formatear_numero($reserva->check_out->total_multa) :  '$0' ?></strong></span></div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><b>OTROS COBROS:</b></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600" id="txt_total_adicional"><?= ($reserva->check_out->total_adicional) > 0 ? formatear_numero($reserva->check_out->total_adicional) :  '$0' ?></strong></span></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input type="hidden" name="form" id="form" value="1">
                                    <input type="hidden" name="accion" id="accion" value="1">
                                    <div class="row" style="margin-top: -120px;">
                                        <div class="col-md-12 text-right">
                                            <hr>
                                            <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                                            <button class="btn btn-info" <?= $reserva->id_estado_reserva > 5 ? 'disabled hidden' : '' ?> type="button" id="btn_save"><i class="fa fa-save"></i> Guardar</button>
                                            <button class="btn btn-primary" <?= $reserva->id_estado_reserva > 5 ? 'disabled hidden' : '' ?> type="button" id="btn_submit"><i class="fa fa-check-circle"></i> Gestionar Check-out</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade text-center" id="modal-check-out">
    <div class="modal-dialog modal-md text-center">
        <form action="<?= isset($action_pago) ? $action_pago : '' ?>" method="POST" id="formulario_pago">
            <div class="modal-content ">
                <div class="modal-body">
                    <i class="text-danger fa fa-2x fa-question-circle"></i>
                    <hr>
                    <p class="modal-title text-center">¿Estás seguro que desear Realizar el Check-Out?</p>
                    <input type="text" id="valida_form" value="Y">
                    {{ csrf_field() }}
                    <div id="valida_pago">
                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Se encontró un monto a pagar. <br>Complete el pago para poder realizar el Check Out. </h6>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="monto_pagar">Monto a Pagar<span class="text-danger">*</span></label>
                                    <input type="text" name="monto_pagar" id="monto_pagar" class="form-control" readonly value="" required placeholder="Monto...">
                                    <small id="invalid_monto_pagar" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tipo_tarjeta">Tipo de Tarjeta <span class="text-danger">*</span></label>
                                    <select name="tipo_tarjeta" id="tipo_tarjeta" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <option value="1">Tarjeta Crédito</option>
                                        <option value="2">Tarjeta Débito</option>
                                    </select>
                                    <small id="invalid_tipo_tarjeta" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="tarjeta">Número de Tarjeta <span class="text-danger">*</span><small> Card Ejemplo: 5523744648825206</small></label>
                                    <input type="text" name="tarjeta" id="tarjeta" class="form-control" value="" maxlength="16" required placeholder="Ingrese Número de Tarjeta...">
                                    <small id="invalid_tarjeta" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mes">Mes <span class="text-danger">*</span></label>
                                    <select name="mes" id="mes" class="form-control">
                                        <?php $meses = traerMesesNumero(); ?>
                                        <?php foreach ($meses as $key => $mes) : ?>
                                            <option value="<?= $key ?>"><?= $mes ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="invalid_mes" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="anio">Año <span class="text-danger">*</span></label>
                                    <select name="anio" id="anio" class="form-control">
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
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cvv">CVV <span class="text-danger">*</span></label>
                                    <input type="password" name="cvv" id="cvv" class="form-control" maxlength="4" value="" required placeholder="Ingrese CVV...">
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
                    </div>
                </div>

                <div class="modal-footer text-center">
                    <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="button" id="btn_pago_confirm" class="btn btn-success"><i class="fa fa-check-circle"></i> Confirmar Check-Out</button>
                </div>

            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.modal -->
@section('js_content')
@include('./generalJS')
<script>
    valida_adicional();
    $("#otra_multa").click(function() {
        valida_adicional();
        if ($("#otra_multa").is(":checked")) {} else {
            $("#observacion_adicional").val('');
            $("#total_adicional").val('');
            $('#txt_total_adicional').html('$0')
        }

    })

    function valida_adicional() {
        let val = false;
        if ($("#otra_multa").is(":checked")) {
            $("#observacion_adicional").attr('disabled', false);
            $("#total_adicional").attr('disabled', false);
            val = true;

        } else {
            $("#observacion_adicional").attr('disabled', true);
            $("#total_adicional").attr('disabled', true);
        }
        validaCampos($('#observacion_adicional').val(), 'observacion_adicional', 'texto_min', val);
        validaCampos($('#total_adicional').val(), 'total_adicional', 'moneda', val);
        $('#observacion_adicional').keyup(function() {
            validaCampos($('#observacion_adicional').val(), 'observacion_adicional', 'texto_min', val);
        });
        $('#total_adicional').keyup(function() {
            val_tt = validaCampos($('#total_adicional').val(), 'total_adicional', 'moneda', val);
            let tt = '$0';
            if (val_tt == 1) {
                tt = $('#total_adicional').val();
            }
            $('#txt_total_adicional').html(tt)
        });
    }




    $(".multa").click(function() {
        id_chk = $(this).attr('id');
        id_monto_multa = id_chk.replace('multa_', '');
        monto = $("#monto_" + id_monto_multa).val();
        let multas = $("#total_multa").val();
        if ($(this).is(":checked")) {
            multas = (parseInt(multas) + parseInt(monto));
        } else {
            multas = (parseInt(multas) - parseInt(monto));
        }
        $("#total_multa").val(multas);
        $("#txt_total_multa").html(formateaMoneda(multas.toString()));

    })

    $("#btn_submit").click(function() {
        let obs_val = 1;
        let monto_val = 1;
        if ($("#otra_multa").is(":checked")) {
            obs_val = validaCampos($('#observacion_adicional').val(), 'observacion_adicional', 'texto_min', true);
            monto_val = validaCampos($('#total_adicional').val(), 'total_adicional', 'moneda', true);
        }
        if (obs_val == 1 && monto_val == 1) {
            let diferencia = soloNumeros('<?= ($reserva->diferencia_pago) > 0 ? ($reserva->diferencia_pago) :  0 ?>');
            let total_multa = soloNumeros($("#total_multa").val());
            let otros_cobros = soloNumeros($("#total_adicional").val());
            if (otros_cobros < 1) {
                otros_cobros = 0;
            }
            let total = parseInt(diferencia) + parseInt(total_multa) + parseInt(otros_cobros);
            console.log(diferencia)
            console.log(total_multa)
            console.log(otros_cobros)
            console.log(total)
            if (total > 0) {
                $("#monto_pagar").val(formateaMoneda(total.toString()));
                $("#valida_pago").attr('hidden', false);
                $("#btn_pago_confirm").html('<i class="fa fa-money"></i> Pagar y Confirmar Check-Out');
                $("#valida_form").val('Y');
            } else {
                $("#valida_pago").attr('hidden', true);
                $("#btn_pago_confirm").html('<i class="fa fa-check-circle"></i> Confirmar Check-Out');
                $("#valida_form").val('N');
            }



            $("#modal-check-out").modal('show');
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }

    })
</script>


<script src="<?= asset(ASSETS_JS) ?>/jquery.creditCardValidator.js"></script>
<script>
    $(document).ready(function() {
        validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
        validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
        validaCampos($('#mes').val(), 'mes', 'select', true);
        validaCampos($('#anio').val(), 'anio', 'select', true);
        validaCampos($('#cvv').val(), 'cvv', 'numero', true);
        $("#tipo_tarjeta").change(function() {
            validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
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

    $("#btn_pago_confirm").click(function() {
        if ($("#valida_form").val() == 'Y') {
            let tipo_tarjeta = validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
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
            if (tipo_tarjeta == 1 && tarjeta == 1 && mes == 1 && anio == 1 && cvv == 1) {
                $("#accion").val(2);
                $("#formulario").submit();
                cargando('Validando Información...')
            } else {
                toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
            }
        } else {
            $("#accion").val(2);
            $("#formulario").submit();
            cargando('Validando Información...')
        }


    })

    $("#btn_save").click(function() {
        $("#accion").val(1);
        $("#formulario").submit();
        cargando('Validando Información...')
    })
</script>

@endsection
@endsection