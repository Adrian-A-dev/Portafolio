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
                        <li class="<?= empty($reserva) ? '' : (isset($reserva) && $reserva->id_paso_reserva == 3 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">3</span><span>Forma Pago de<br> Reserva</span></a></li>
                        <li class="<?= empty($reserva) ? '' : (isset($reserva) && $reserva->id_paso_reserva == 4 ? 'active' : '') ?>"><a data-toggle=""><span class="tab-border">4</span><span>Confirmación de<br> Reserva</span></a></li>
                    </ul>
                </div>
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
                                        <div class="col-md-7 col-lg-8" style="padding: 0px;">
                                            <div>
                                                <div class="panel-body" style="padding: 30px;">
                                                    <h6 class="text-center"><b>CONTRATACIÓN DE SERVICIOS</b></h6>
                                                    <hr>
                                                    <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading text-center" style="background-color: #f6750d; color:#fff;"><b>SERVICIOS DE DESAYUNOS & COMIDAS</b></div>
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            @if(!empty($servicios_comidas))
                                                                            @foreach($servicios_comidas as $servicio)
                                                                            <div class="col-lg-6">
                                                                                <div class="thumbnail">
                                                                                    <div class="<?= $servicio->cantidad > 1 ? 'servicios_comidas' : '' ?>" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                                        <div class="caption text-center">
                                                                                            <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                                            <hr>
                                                                                            <div class="thumbnail-description smaller">
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Horarios:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Descripción:</b></small><br>
                                                                                                    <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-center">
                                                                                            <hr>

                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                                            <?php else : ?>
                                                                                                <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                                                <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="caption card-footer text-center">
                                                                                        <div class="form-group">
                                                                                            <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                                            <label for="">Cantidad </label>
                                                                                            <select name="cantidad[<?= $servicio->id_servicio ?>]" id="sel_servicios_comidas_<?= $servicio->id_servicio ?>" class="" style="width: 100px ;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                                                <?php $i = 1;
                                                                                                while ($i <= $cantidad_permitida) : ?>
                                                                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                                                                <?php $i++;
                                                                                                endwhile; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <ul class="list-inline">
                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <input type="checkbox" name="servicios_comidas[]" id="chk_servicios_comidas_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_comidas">
                                                                                            <?php else : ?>
                                                                                                <input type="checkbox" disabled class="form-control chk">
                                                                                            <?php endif; ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                            @else
                                                                            <div class="col-md-12">
                                                                                <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel-footer"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading text-center" style="background-color: #000; color:#fff;"><b>SERVICIOS DE TRANSPORTE</b></div>
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            @if(!empty($servicios_transporte))
                                                                            @foreach($servicios_transporte as $servicio)
                                                                            <div class="col-lg-6">
                                                                                <div class="thumbnail">
                                                                                    <div class="<?= $servicio->cantidad > 1 ? 'servicios_transporte' : '' ?>" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                                        <div class="caption text-center">
                                                                                            <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                                            <hr>
                                                                                            <div class="thumbnail-description smaller">
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Horarios:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Lugar Origen:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->lugar_origen) ? strUpper($servicio->lugar_origen)  : 'Sin información' ?></b>
                                                                                                    <br>
                                                                                                    <br>
                                                                                                    <small><b class="text-muted">Lugar Destino:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->lugar_destino) ? strUpper($servicio->lugar_destino)  : 'Sin información' ?></b>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Descripción:</b></small><br>
                                                                                                    <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-center">
                                                                                            <hr>

                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                                            <?php else : ?>
                                                                                                <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                                                <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="caption card-footer text-center">
                                                                                        <div class="form-group">
                                                                                            <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                                            <label for="">Cantidad </label>
                                                                                            <select name="cantidad[<?= $servicio->id_servicio ?>]" id="sel_servicios_transporte_<?= $servicio->id_servicio ?>" class="" style="width: 100px ;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                                                <?php $i = 1;
                                                                                                while ($i <= $cantidad_permitida) : ?>
                                                                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                                                                <?php $i++;
                                                                                                endwhile; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <ul class="list-inline">
                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <input type="checkbox" name="servicios_transporte[]" id="chk_servicios_transporte_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_transporte">
                                                                                            <?php else : ?>
                                                                                                <input type="checkbox" disabled class="form-control chk">
                                                                                            <?php endif; ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                            @else
                                                                            <div class="col-md-12">
                                                                                <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel-footer"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading text-center" style="background-color: #f6750d; color:#fff;"><b>SERVICIOS DE TOURS</b></div>
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            @if(!empty($servicios_tours))
                                                                            @foreach($servicios_tours as $servicio)
                                                                            <div class="col-lg-6">
                                                                                <div class="thumbnail">
                                                                                    <div class="<?= $servicio->cantidad > 1 ? 'servicios_tours' : '' ?>" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                                        <div class="caption text-center">
                                                                                            <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                                            <hr>
                                                                                            <div class="thumbnail-description smaller">
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Horarios:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Lugar Origen:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->lugar_origen) ? strUpper($servicio->lugar_origen)  : 'Sin información' ?></b>
                                                                                                    <br>
                                                                                                    <br>
                                                                                                    <small><b class="text-muted">Lugar Destino:</b></small><br>
                                                                                                    <b class="text-left"> <?= !empty($servicio->lugar_destino) ? strUpper($servicio->lugar_destino)  : 'Sin información' ?></b>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="text-left">
                                                                                                    <small><b class="text-muted">Descripción:</b></small><br>
                                                                                                    <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-center">
                                                                                            <hr>

                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                                            <?php else : ?>
                                                                                                <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                                                <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="caption card-footer text-center">
                                                                                        <div class="form-group">
                                                                                            <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                                            <label for="">Cantidad </label>
                                                                                            <select name="cantidad[<?= $servicio->id_servicio ?>]" id="sel_servicios_tours_<?= $servicio->id_servicio ?>" class="" style="width: 100px ;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                                                <?php $i = 1;
                                                                                                while ($i <= $cantidad_permitida) : ?>
                                                                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                                                                <?php $i++;
                                                                                                endwhile; ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <ul class="list-inline">
                                                                                            <?php if ($servicio->cantidad > 1) : ?>
                                                                                                <input type="checkbox" name="servicios_tours[]" id="chk_servicios_tours_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_tours">
                                                                                            <?php else : ?>
                                                                                                <input type="checkbox" disabled class="form-control chk">
                                                                                            <?php endif; ?>

                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endforeach
                                                                            @else
                                                                            <div class="col-md-12">
                                                                                <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel-footer"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <hr>
                                                                <div class="prve-next-box mt-20">
                                                                    <div class="back-link">
                                                                        <a href="/reserva/{{sha1(auth()->user()->id_reserva)}}/volver"><i class="fa fa-reply"></i> Volver a paso 1</a>
                                                                    </div>
                                                                    <button type="button" id="btn_guardar"><i class="fa fa-save"></i> Continuar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-lg-4" style="padding: 0px;">
                                            <div>
                                                <div class="panel-body" style="padding: 30px;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="text-center"><b>DETALLE DE RESERVA</b></h6>
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
                                                                        <th colspan="2" class="text-center">
                                                                            <b>VALOR</b>: {{$departamento->valor_arriendo > 0 ? formatear_numero($departamento->valor_arriendo) : '$0'}} <span>/ por Noche</span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" class="text-center">
                                                                            <h3 style="font-size: 14px;"><span><b>Capacidad Húespedes:</b></span> <b style="font-size: 17px;"> {{$cantidad_huespedes}} </b></h3>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            FECHA LLEGADA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 18px;">{{!empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            FECHA SALIDA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 18px;">{{!empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            CANTIDAD DE NOCHES:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 18px;">{{formatear_miles($dias_reserva)}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            VALOR TOTAL NOCHES:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 18px;">{{formatear_numero($dias_reserva_valor)}}</b>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
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

<div class="modal fade text-center" id="modal_valida">
    <div class="modal-dialog modal-sm text-center">
        <div class="modal-content ">
            <div class="modal-body">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que deseas continuar sin Servicios?</p>
                <input type="hidden" id="id_usuario">
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="continuar" class="btn btn-primary"><i class="fa fa-save"></i> Continuar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

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
<!-- /.modal -->
@section('js_content')
<script>
    $(".servicios_comidas").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_comidas_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_comidas_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_comidas_${id_servicio} option:selected`).prop("selected", false);
        } else {
            $(`#chk_servicios_comidas_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_comidas_${id_servicio}`).focus();
        }
    })
    $(".chk_servicios_comidas").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_comidas_', '');
        if ($(`#chk_servicios_comidas_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_comidas_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_comidas_${id_servicio} option:selected`).prop("selected", false);
        }

    })
    $(".servicios_transporte").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_transporte_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_transporte_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_transporte_${id_servicio} option:selected`).prop("selected", false);
        } else {
            $(`#chk_servicios_transporte_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_transporte_${id_servicio}`).focus();

        }

    })
    $(".chk_servicios_transporte").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_transporte_', '');
        if ($(`#chk_servicios_transporte_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_transporte_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_transporte_${id_servicio} option:selected`).prop("selected", false);
        }

    })



    $(".servicios_tours").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_tours_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_tours_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_tours_${id_servicio} option:selected`).prop("selected", false);


        } else {
            $(`#chk_servicios_tours_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_tours_${id_servicio}`).focus();

        }

    })

    $(".chk_servicios_tours").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_tours_', '');
        if ($(`#chk_servicios_tours_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_tours_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_tours_${id_servicio} option:selected`).prop("selected", false);
        }

    })
    $("#btn_guardar").click(function() {
        let contador = 0;


        $(".chk_servicios_comidas").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        $(".chk_servicios_transporte").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        $(".chk_servicios_tours").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        if (contador == 0) {
            $("#modal_valida").modal("show");
        } else {
            cargando('Guardando Información...')
            $("#formulario").submit();
        }
    })

    $("#continuar").click(function() {
        cargando('Guardando Información...')
        $("#formulario").submit();
    });
</script>


@endsection
@endsection