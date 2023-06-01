@extends('layout.header')
@section('css_contenido')
<link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/jquery.calendar.css" />
<style>
    .borrador {
        width: 100%;
        text-align: center;
        color: red;
        margin: 0 auto;
        position: absolute;
        font-size: 100px;
    }

    .div_borrador {

        position: absolute;
        width: 100%;
        text-align: center;
        margin: 0 auto;
        color: red;
        top: 10%;


    }

    @media (max-width:761PX) {
        .borrador {
            font-size: 60px;
        }

        .div_borrador {

            top: 50%;
        }
    }
    @media (max-width:400PX) {
        .borrador {
            font-size: 40px;
        }

        .div_borrador {

            top: 50%;
        }
    }
</style>
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
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="booking">
                            <div class="booking-info-deatils">
                                <?php
                                $cantidad_huespedes = $departamento->cantidad_huespedes > 0 ? ($departamento->cantidad_huespedes) : '1';
                                ?>
                                <div class="row">

                                    <div class="col-md-12">
                                        @if($reserva->id_estado_reserva != 6)
                                        <div class="alert alert-success" style="overflow: scroll;">
                                            <h6 class="alert alert-success text-center"><i class="fa fa-2x fa-check-circle"></i><b>RESERVA REALIZADA CORRECTAMENTE <br>ID RESERVA: {{!empty($reserva->id_reserva) ? sha1($reserva->id_reserva) : 'SIN INFORMACIÓN'}}</b></h6>
                                        </div>
                                        @endif

                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center">
                                                <h4 class="text-white"><b>RESUMEN DE RESERVA</b></h4>
                                                @if($reserva->id_estado_reserva == 6)
                                                <span class='badge badge-danger' style="background:#dc1818;">CANCELADA</span>
                                                @endif
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    @if($reserva->id_estado_reserva == 6)
                                                    <div style="text-align: center;" class="div_borrador">
                                                        <div class="col-12" style="opacity: 0.5;  margin: 0 auto;">
                                                            <h2 class="borrador" style="transform: rotate(-45deg); margin-top: 30%;font-family: Arial, Helvetica, sans-serif;">CANCELADA</h2>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="panel panel-default" style="padding:15px ;">
                                                    <div class="single-room-details fix">
                                                        <div class="room-img">

                                                            <table>
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="2">
                                                                            <img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento">
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <hr>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" class="text-center">
                                                                            <b>VALOR</b>: {{$reserva->valor_noche > 0 ? formatear_numero($reserva->valor_noche) : '$0'}} <span>/ por Noche</span>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="2" class="text-center">
                                                                            <h3 style="font-size: 14px;"><span><b>Capacidad Húespedes:</b></span> <b style="font-size: 17px;"> {{$reserva->numero_huespedes}} </b></h3>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <div class="single-room-details pl-50 text-center">
                                                            <h5 class="s_room_title"><b>{{$departamento->departamento}}</b></h5>
                                                            <div class="room_price">
                                                                <hr>
                                                                <span style="font-size: 14px;">{{ !empty($departamento->descripcion_corta) ? $departamento->descripcion_corta : ''}}</span>
                                                                <hr>
                                                            </div>
                                                            <table class="table">
                                                                <thead>

                                                                    <tr>
                                                                        <th class="text-dark text-center" colspan="2">
                                                                            INFORMACIÓN DE RESERVA
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            FECHA LLEGADA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{!empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            FECHA SALIDA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{!empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            CANTIDAD DE NOCHES:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{!empty($cantidad_noches) ? formatear_miles($cantidad_noches) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            VALOR TOTAL NOCHES:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{ $valor_total_noches > 0 ? formatear_numero($valor_total_noches) : 'SIN INFORMACIÓN'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            CANTIDAD DE SERVICIOS:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{!empty($total_servicios_cantidad) ? formatear_miles($total_servicios_cantidad) : '0'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            VALOR TOTAL SERVICIOS:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{!empty($total_servicios) ? formatear_numero($total_servicios) : '$0'}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-dark text-center" colspan="2">
                                                                            INFORMACIÓN DE PAGO
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            VALOR TOTAL RESERVA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{formatear_numero($reserva->total_reserva)}}</b>
                                                                        </th>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            TOTAL ABONADO RESERVA:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{formatear_numero($reserva->total_pagado)}}</b>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-muted">
                                                                            DIFERENCIA POR PAGAR:
                                                                        </th>
                                                                        <th>
                                                                            <b style="font-size: 16px">{{strUpper(formatear_numero($reserva->diferencia_pago))}}</b>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <div class="prve-next-box mt-20">
                                                            <div class="back-link ">
                                                                <a href="/departamentos"><i class="fa fa-calendar"></i> HACER OTRA RESERVA</a>
                                                                <a href="/reserva/{{($reserva->id_reserva)}}/download-pdf" target="_blank"><i class="fa fa-download"></i> Descargar Comprobante</a>
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
</div>
</div>



@endsection