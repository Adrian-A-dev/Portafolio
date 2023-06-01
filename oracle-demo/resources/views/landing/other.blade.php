@extends('layout.header')
@section('head')
<!--Welcome secton-->
<div class="welcome-section text-center ptb-110">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="breadcurbs-inner">
                    <div class="breadcrubs">
                        <h2><?= isset($title) ? $title : '' ?></h2>
                        <div class="breadcrubs-menu">
                            <ul>
                                <li><a href="">Inicio<i class="mdi mdi-chevron-right"></i></a></li>
                                <li class="active"><?= isset($title) ? $title : '' ?></li>
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
                    <p>Conoce alguno de nuestros departamentos disponibles</p>
                </div>
            </div>
        </div>
        <div class="our-room-show">
            <div class="row">
                <div class="carousel-list">
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-room">
                            <div class="room-img">
                                <a href="#"><img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt=""></a>
                            </div>
                            <div class="room-desc">
                                <div class="room-name">
                                    <h3><a href="#">Nombre de Servicio</a></h3>
                                </div>
                                <div class="room-rent">
                                    <h5>$100.000 / <span>Noche</span></h5>
                                </div>
                                <div class="room-book">
                                    <a href="<?= ('reservar') ?>">Reservar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Our room end-->
<!--Our services start-->
<div class="our-sevices text-center ptb-80 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-80">
                    <h2>NUESTROS <span>SERVICIOS</span></h2>
                    <p>Conoce todos los servicios adicionales que Turismo Real Ofrece para ti y tu familia</p>
                </div>
            </div>
        </div>
    </div>
    <div class="our-services-list">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-services">
                        <div class="services-img">
                            <img src="<?= asset(ASSETS_IMG) ?>/services/services1.jpg" alt="">
                            <div class="services-title">
                                <h2>Breakfast & Buffet</h2>
                            </div>
                            <div class="services-hover-desc">
                                <div class="services-hover-inner">
                                    <div class="services-hover-table">
                                        <div class="services-hover-table-cell">
                                            <h2>Breakfast & Buffet</h2>
                                            <p>There are many variations of passages Loem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-services">
                        <div class="services-img">
                            <img src="<?= asset(ASSETS_IMG) ?>/services/services2.jpg" alt="">
                            <div class="services-title">
                                <h2>Towels and bedding</h2>
                            </div>
                            <div class="services-hover-desc">
                                <div class="services-hover-inner">
                                    <div class="services-hover-table">
                                        <div class="services-hover-table-cell">
                                            <h2>Breakfast & Buffet</h2>
                                            <p>There are many variations of passages Loem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-services">
                        <div class="services-img">
                            <img src="<?= asset(ASSETS_IMG) ?>/services/services3.jpg" alt="">
                            <div class="services-title">
                                <h2>24/7 Reception</h2>
                            </div>
                            <div class="services-hover-desc">
                                <div class="services-hover-inner">
                                    <div class="services-hover-table">
                                        <div class="services-hover-table-cell">
                                            <h2>24/7 Reception</h2>
                                            <p>There are many variations of passages Loem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-services">
                        <div class="services-img">
                            <img src="<?= asset(ASSETS_IMG) ?>/services/services4.jpg" alt="">
                            <div class="services-title">
                                <h2>City Tours</h2>
                            </div>
                            <div class="services-hover-desc">
                                <div class="services-hover-inner">
                                    <div class="services-hover-table">
                                        <div class="services-hover-table-cell">
                                            <h2>City Tourst</h2>
                                            <p>There are many variations of passages Loem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
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
<!--Our services end-->
<div class="our-sevices text-center pb-80 static2 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-80">
                    <h2>SERVICIOS <span>DESTACADOS</span></h2>
                    <p>Conoce los servicios adicionales destacados que Turismo Real Ofrece para ti y tu familia</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="services-tab-menu mb-70">
                    <ul class="nav" role="tablist">
                        <li class="active"><a href="#towels" data-toggle="tab">Servicio 1</a></li>
                        <li><a href="#breakfast" data-toggle="tab">Servicio 2</a></li>
                        <li><a href="#reception" data-toggle="tab">Servicio 3</a></li>
                        <li><a href="#tours" data-toggle="tab">Servicio 4</a></li>
                    </ul>
                </div>
            </div>
            <div class="service-tab-desc text-left">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="towels">
                        <div class="single-services">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-img">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services5.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-desc">
                                    <div class="services-desc-inner">
                                        <h2>Servicio 1</h2>
                                        <p class="text1">Descripción de Servicio </p>
                                        <p class="text2">
                                            Descripción de Servicio 2
                                        </p>
                                        <div class="room-book">
                                            <a href="#"><i class="fa fa-calendar "></i> Reservar </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="breakfast">
                        <div class="single-services">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-img">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services1.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-desc">
                                    <div class="services-desc-inner">
                                        <h2>Servicio 2</h2>
                                        <p class="text1">Descripción de Servicio </p>
                                        <p class="text2">
                                            Descripción de Servicio 2
                                        </p>
                                        <div class="room-book">
                                            <a href="#"><i class="fa fa-calendar "></i> Reservar </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="reception">
                        <div class="single-services">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-img">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services3.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="services-desc">
                                    <div class="services-desc-inner">
                                        <h2>Servicio 3</h2>
                                        <p class="text1">Descripción de Servicio </p>
                                        <p class="text2">
                                            Descripción de Servicio 2
                                        </p>
                                        <div class="room-book">
                                            <a href="#"><i class="fa fa-calendar "></i> Reservar </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tours">
                        <div class="single-services">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="services-img">
                                    <img src="<?= asset(ASSETS_IMG) ?>/services/services4.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="services-desc">
                                    <div class="services-desc-inner">
                                        <h2>Servicio 4</h2>
                                        <p class="text1">Descripción de Servicio </p>
                                        <p class="text2">
                                            Descripción de Servicio 2
                                        </p>
                                        <div class="room-book">
                                            <a href="#"><i class="fa fa-calendar "></i> Reservar </a>
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
<!--Our services end-->


<!--Our staff end-->
<!--Testimonial start-->
<div class="staff-tesimonial text-center white_bg">
    <div class="testimonail-bg-opacity"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-80">
                    <h2 class="text-white">clientes felices</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="testimonial-list">
                    <div class="single-testimonial">
                        <h2>Persona 1</h2>
                        <p>
                            Opinión 1
                        </p>
                    </div>
                    <div class="single-testimonial">
                        <h2>Persona 2</h2>
                        <p>
                            Opinión 2
                        </p>
                    </div>
                    <div class="single-testimonial">
                        <h2>Persona 3</h2>
                        <p>
                            Opinión 3
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!--Testimonial end-->
<!--Our gallery start-->

<!--Our news end-->
<!--Hotel communities start-->
<div class="hotel-cmmunities ptb-100 text-center">
    <div class="community-bg-opacity"></div>
    <div class="container">
        <div class="row">
            <div class="communities-list">
                <div class="single-commmunites">
                    <h1 class="counter">500</h1>
                    <h2>Reservas</h2>
                </div>
                <div class="single-commmunites">
                    <h1 class="counter">200</h1>
                    <h2>Clientes</h2>
                </div>
                <div class="single-commmunites">
                    <h1 class="counter">10</h1>
                    <h2>Años Experiencia</h2>
                </div>
                <div class="single-commmunites hidden-xs">
                    <h1 class="counter">1250</h1>
                    <h2>Clientes Felices</h2>
                </div>
            </div>
        </div>
    </div>

</div>
<!--Hotel communities end-->


@endsection