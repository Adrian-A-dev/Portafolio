@extends('layout.header')
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
                    <h2>NUESTROS <span>DEPARTAMENTOS</span></h2>
                    <p>Conoce todos nuestros departamentos disponibles por Regiones </p>
                </div>
            </div>
        </div>
        <?php if (empty($sucursales_arr)) : ?>
            <div class="row">
                <div class="col-md-12">
                    <h6 class="alert alert-danger text-center"><b>NO EXISTEN DEPARTAMENTOS DISPONIBLES</b></h6>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($sucursales_arr as $region => $departamento) : ?>
                    <div class="col-md-12">
                        <div class="section-title mb-80 text-center">
                            <h2>REGIÓN <span><?= !empty($region) ? str_replace('_', ' ', $region) : 'SIN INFORMACIÓN'; ?></span></h2>
                        </div>
                        <div class="row">
                            <?php if (count($departamento) == 0) : ?>
                                <div class="col-md-12">
                                    <h6 class="alert alert-danger text-center"><b>OPPS!! AÚN NO EXISTEN DEPARTAMENTOS EN <span><?= !empty($region) ? str_replace('_', ' ', $region) : 'SIN INFORMACIÓN'; ?></b></h6>
                                </div>
                            <?php else : ?>
                                <?php foreach ($departamento as $departamento) : ?>
                                    <div class="col-md-4">
                                        <div class="single-room">
                                            <div class="room-img">
                                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento"></a>
                                            </div>
                                            <div class="room-desc">
                                                <div class="room-name">
                                                    <h5 class="text-center"><a href="<?= !empty($departamento->url_departamento) ? '/departamentos/' . $departamento->url_departamento : '' ?> "><b>{{ !empty($departamento->departamento) ? $departamento->departamento : 'SIN INFORMACIÓN'}}</b></a></h5>
                                                    <hr>
                                                    <h6><b>Sucursal:</b> <span class="text-primary"><b>{{!empty($departamento->sucursal->sucursal) ? strUpper($departamento->sucursal->sucursal) : 'Sin información'}}</b></span></h6>
                                                    <hr>
                                                </div>
                                                <div class="room-rent">
                                                    <h5>{{isset($departamento->valor_arriendo) && $departamento->valor_arriendo > 0 ? formatear_numero($departamento->valor_arriendo) : '$0'}} / <span>Noche</span></h5>
                                                </div>
                                                <br>
                                                <br>
                                                <div class="room-book">
                                                    @if(isset($departamento) && $departamento->estado_departamento->permite_reservar == 'Y')
                                                    <a href="<?= ('/departamentos/' . $departamento->url_departamento . '/reservar') ?>" class="">Reservar</a>
                                                    @else
                                                    <button type="button" disabled>No disponible</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <br>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

@endsection