<!doctype html>
<html class="no-js" lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> <?= isset($title) ? $title . ' | ' : '' ?> <?= NOMBRE_APP ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <!-- Place favicon.ico in the root directory -->
    <link href="<?= asset(ASSETS_IMG) ?>/logo/favicon_turismo.png" type="images/x-icon" rel="shortcut icon">
    <!-- All css files are included here. -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="<?= asset(ASSETS_CSS) ?>/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <style>
        .page-break {
            page-break-after: always;
        }

        .borrador {
            width: 100%;
            text-align: center;
            bottom: 0px;
            top: 0px;
            left: 30%;
            margin: 0 auto;
            position: fixed;

        }

        .div_borrador {
            z-index: 999999;
            position: absolute;
            width: 100%;
            text-align: center;
            margin: 0 auto;
            color: red;

        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="room-booking ptb-80 white_bg">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <div class="service-tab-desc text-left mt-60">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="booking">
                                    <div class="booking-info-deatils">
                                        <?php
                                        $cantidad_huespedes = isset($departamento) && $departamento->cantidad_huespedes > 0 ? ($departamento->cantidad_huespedes) : '1';
                                        ?>

                                        <div class="row  page-break">
                                            @if($reserva->id_estado_reserva == 6)
                                            <div style="text-align: center;" class="div_borrador">
                                                <div class="col-12" style="position: fixed; opacity: 0.5;  margin: 0 auto;">
                                                    <h2 class="borrador" style="transform: rotate(-45deg); font-size: 100px; margin-top: 30%;font-family: Arial, Helvetica, sans-serif;">CANCELADA</h2>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center">
                                                        <h4 class="text-white"><b>DETALLE DE RESERVA</b></h4>                                                       
                                                        @if($reserva->id_estado_reserva == 6)
                                                        <span class='badge badge-danger' style="background:#dc1818;">CANCELADA</span>
                                                        @endif
                                                    </div>

                                                    <h6 class="alert alert-success text-center"><i class="fa fa-2x fa-check-circle"></i><b>ID RESERVA: {{!empty($reserva->id_reserva) ? sha1($reserva->id_reserva) : 'SIN INFORMACIÓN'}}</b></h6>
                                                    <div class="panel-body">
                                                        <div class="panel panel-default" style="padding:15px ;">
                                                            <div class="row">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-8">
                                                                    <div class="single-room-details fix">
                                                                        <div class="room-img text-center">
                                                                            <table style="margin: 0 auto;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th colspan="2">
                                                                                            <img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento" style="width: 220px;">
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th colspan="2" class="text-center">
                                                                                            <b>VALOR</b>: {{$valor_noche > 0 ? formatear_numero($valor_noche) : 'SIN INFORMACIÓN'}} <span>/ por Noche</span>
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
                                                                            <hr>
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;" width="70%">
                                                                                            DEPARTAMENTO:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{isset($departamento) && $departamento->departamento ? $departamento->departamento : 'SIN INFORMACIÓN'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;" width="70%">
                                                                                            FECHA LLEGADA:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            FECHA SALIDA:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) : 'SIN INFORMACIÓN'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            CANTIDAD DE NOCHES:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($cantidad_noches) ? formatear_miles($cantidad_noches) : 'SIN INFORMACIÓN'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            VALOR TOTAL NOCHES:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{ $valor_total_noches > 0 ? formatear_numero($valor_total_noches) : 'SIN INFORMACIÓN'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            CANTIDAD DE SERVICIOS:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($total_servicios_cantidad) ? formatear_miles($total_servicios_cantidad) : '0'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            VALOR TOTAL SERVICIOS:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($total_servicios) ? formatear_numero($total_servicios) : '$0'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                            </table>
                                                                            <div class="panel-heading text-center" style="background: #f5f5f5;">
                                                                                <h5 class="text-white"><b>RESUMEN DE RESERVA</b></h5>
                                                                            </div>
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;" width="70%">
                                                                                            VALOR TOTAL RESERVA:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{!empty($reserva->total_reserva) ? formatear_numero($reserva->total_reserva) : '$0'}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            TOTAL ABONADO RESERVA:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{formatear_numero($reserva->total_pagado)}}</b>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            DIFERENCIA POR PAGAR:
                                                                                        </th>
                                                                                        <th>
                                                                                            <b style="font-size: 13px;">{{strUpper(formatear_numero($reserva->diferencia_pago))}}</b>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center">
                                                        <h4 class="text-white"><b>DETALLE DE SERVICIOS</b></h4>
                                                        @if($reserva->id_estado_reserva == 6)
                                                        <span class='badge badge-danger' style="background:#dc1818;">CANCELADA</span>
                                                        @endif
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="panel panel-default" style="padding:15px ;">
                                                            <div class="row">
                                                                @if($reserva->id_estado_reserva == 6)
                                                                <div style="text-align: center;" class="div_borrador">
                                                                    <div class="col-12" style="position: fixed; opacity: 0.5;  margin: 0 auto;">
                                                                        <h2 class="borrador" style="transform: rotate(-45deg); font-size: 100px; margin-top: 30%;font-family: Arial, Helvetica, sans-serif;">CANCELADA</h2>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-8">
                                                                    <div class="single-room-details fix">
                                                                        <div class="single-room-details pl-50 text-center">
                                                                            <table class="table">
                                                                                <thead>
                                                                                    @if(!empty($servicios_reserva) && count($servicios_reserva) > 0)
                                                                                    @foreach($servicios_reserva as $servicio)
                                                                                    <tr colspan="2">
                                                                                        <th class="text-muted" style="font-size: 12px;">
                                                                                            SERVICIO CONTRATADO:
                                                                                        </th>
                                                                                        <th class="text-dark text-left" style="font-size: 12px;">
                                                                                            {{$servicio->servicio->servicio}} (x{{$servicio->cantidad}})
                                                                                        </th>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                    @else
                                                                                    <tr colspan="2">
                                                                                        <th class="text-center" style="font-size: 12px;">
                                                                                            <h5 class="alert alert-danger">No existen servicios contratados</h5>
                                                                                        </th>
                                                                                    </tr>
                                                                                    @endif
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
            </div>
        </div>
    </div>
</body>

</html>