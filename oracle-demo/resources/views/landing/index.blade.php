@extends('layout.header')
@section('head')
<div class="welcome-section">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-2">
            </div>
            <div class="col-md-8 col-sm-7 m">
                <div class="welcome-text">
                    <h2>
                        <span>BIENVENIDO A</span> <span class="coloring"> </span>
                    </h2>
                    <h1 class="cd-headline clip">
                        <span>TURISMO</span>
                        <span class="cd-words-wrapper coloring">
                            <b class="is-visible"> REAL</b>
                        </span>
                    </h1>
                    <p class="welcome-desc">Reserva tus departamentos de manera fácil y segura</p>
                    <div class="explore">
                        <a href="">DEPARTAMENTOS</a>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
@section('contenido')
<!--Hotel feature start-->
<div class="hotel-feature pt-80 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="feature-desc text-left">
                    <div class="section-title mb-30">
                        <h2>ACERCA DE <span>TURISMO REAL</span></h2>
                        <p>La empresa “Turismo Real”, es una empresa familiar que se dedica al arriendo de departamentos propiedad de la empresa, y otros servicios en zonas turísticas del país. </p>
                        <p>Tiene 10 años en el mercado y de a poco se ha hecho conocida por la calidad de sus departamentos, ubicación y trato gentil hacia los clientes. Actualmente la empresa cuenta con 10 departamentos ubicados en zonas de alto interés turístico para los clientes (Viña del Mar, La Serena, Pucón, Puerto Varas, etc.), todos ellos acondicionados con un alto estándar de calidad</p>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="feature-img-tab">
                    <div class="feature-tab-desc">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="feature1">
                                <div class="feature-tab-inner">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services6.jpg" alt="">
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="feature2">
                                <div class="feature-tab-inner">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services7.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feature-tab-item">
                        <ul class="nav" role="tablist">
                            <li class="active"><a href="#feature1" data-toggle="tab"><img src="<?= asset(ASSETS_IMG) ?>/services/tab-1.jpg" alt=""></a></li>
                            <li><a href="#feature2" data-toggle="tab"><img src="<?= asset(ASSETS_IMG) ?>/services/tab-2.jpg" alt=""></a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Hotel feature end-->

<!--Our Room start-->
<div class="our-room text-center ptb-80 white-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-75">
                    <h2>NUESTROS <span>DEPARTAMENTOS</span></h2>
                    @if(count($departamentos) > 0)
                    <p>Conoce alguno de nuestros departamentos disponibles</p>
                    @endif

                </div>
            </div>
        </div>
        <div class="our-room-show">
            <div class="row">
                @if(count($departamentos) > 0)
                <div class="carousel-list">
                    @foreach($departamentos as $departamento)
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento"></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h5 class="text-center"><a href="{{'/departamentos/'.$departamento->url_departamento}}"><b>{{$departamento->departamento}}</b></a></h5>
                                    <hr>
                                    <h6><b>Sucursal:</b> <span class="text-primary"><b>{{!empty($departamento->sucursal->sucursal) ? strUpper($departamento->sucursal->sucursal) : 'Sin información'}}</b></span></h6>
                                    <hr>
                                </div>
                                <div class="room-rent">
                                    <h5>{{$departamento->valor_arriendo > 0 ? formatear_numero($departamento->valor_arriendo) : '$0'}} / <span>Noche</span></h5>
                                </div>
                                <br>
                                <br>
                                <div class="room-book">
                                    @if($departamento->estado_departamento->permite_reservar == 'Y')
                                    <a href="<?= ('/departamentos/' . $departamento->url_departamento . '/reservar') ?>" class="">Reservar</a>
                                    @else
                                    <button type="button" disabled>No disponible</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach;
                </div>
                @else
                <div class="col-md-12">
                    <h6 class="alert alert-danger text-center"><b>Opps!... Aún No existen Departamentos Disponibles</b></h6>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
<!--Our room end-->

<div class="our-sevices text-center mt-80 pb-80 static2 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-80">
                    <h2>NUESTROS <span>SERVICIOS</span></h2>
                    <p>Conoce todos los servicios adicionales que Turismo Real Ofrece para ti y tu familia</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="services-tab-menu mb-70">
                    <ul class="nav" role="tablist">
                        <?php
                        $i = 1;
                        foreach ($tipo_servicios as $tipo) :
                        ?>
                            <li class="<?= $i == 1 ? 'active' : '' ?>"><a href="#<?= str_replace(' ', '', strLower($tipo->tipo_servicio)); ?>" data-toggle="tab"><?= !empty($tipo) ? strUpper($tipo->tipo_servicio) : 'Sin información' ?></a></li>
                        <?php
                            $i++;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="service-tab-desc text-left">
                <div class="tab-content">
                    <?php
                    $i = 1;
                    foreach ($tipo_servicios as $tipo) :
                    ?>
                        <div role="tabpanel" class="tab-pane <?= $i == 1 ? 'active' : '' ?>" id="<?= str_replace(' ', '', strLower($tipo->tipo_servicio)); ?>">
                            <?php if (count($tipo->servicios) > 0) : ?>
                                <?php foreach ($tipo->servicios as $servicio) : ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="single-services">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="services-img">
                                                        <img src="<?= asset(ASSETS_IMG) ?>/services/<?=!empty($tipo->imagen) ? $tipo->imagen : 'services5.jpg';?>" alt="imagen_<?=$tipo->tipo_servicio?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="services-desc">
                                                        <div class="services-desc-inner">
                                                            <br>
                                                            <h4 style="margin-top: -40px ;"><b><?= !empty($servicio->servicio) ? strUpper($servicio->servicio) : 'Sin información' ?></b></h4>
                                                            <div class="thumbnail-description smaller">
                                                                <div class="text-left">
                                                                    <?php if ($tipo->flg_horario == 'Y') : ?>
                                                                        <small><b class="text-muted">Horarios:</b></small><br>
                                                                        <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                        <hr>
                                                                    <?php endif; ?>
                                                                    <?php if ($tipo->flg_lugar == 'Y') : ?>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Lugar Origen:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_origen) ? strUpper($servicio->lugar_origen)  : 'Sin información' ?></b>
                                                                            <br>
                                                                            <br>
                                                                            <small><b class="text-muted">Lugar Destino:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_destino) ? strUpper($servicio->lugar_destino)  : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                    <?php endif; ?>
                                                                </div>

                                                                <div class="text-left">
                                                                    <small><b class="text-muted">Descripción:</b></small><br>
                                                                    <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                </div>
                                                                <hr>
                                                                <div class="text-left">
                                                                    <small><b class="text-muted">VALOR:</b></small><br>
                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <h6><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b> - <span class="text-success"><b>DISPONIBLE</b></span></h6>
                                                                    <?php else : ?>
                                                                        <h6><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b> - <span class="text-danger"><b>AGOTADO</b></span></h6>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="text-center">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <hr>
                                <div class="single-services text-center">
                                    <div class="services-desc">
                                        <h6 class="alert alert-danger"> <b>No existen Servicios asociados a esta categoría</b></h6>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php
                        $i++;
                    endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<!--Our services end-->


<!--Our staff end-->

<!--Our gallery start-->

<!--Our news end-->
<!--Hotel communities start-->
<div class="hotel-cmmunities ptb-100 text-center">
    <div class="community-bg-opacity"></div>
    <div class="container">
        <div class="row">
            <div class="communities-list">
                <div class="single-commmunites">
                    <h1 class="counter"><?= (int) $contador['annios']; ?></h1>
                    <h2>Años Experiencia</h2>
                </div>
                <div class="single-commmunites hidden-xs">
                    <h1 class="counter"><?= (int) $contador['sucursales']; ?></h1>
                    <h2>Sucursales</h2>
                </div>
                <div class="single-commmunites">
                    <h1 class="counter"><?= (int) $contador['reservas']; ?></h1>
                    <h2>Reservas</h2>
                </div>
                <div class="single-commmunites">
                    <h1 class="counter"><?= (int) $contador['clientes']; ?></h1>
                    <h2>Clientes</h2>
                </div>
            </div>
        </div>
    </div>




</div>
<!--Hotel communities end-->


@endsection