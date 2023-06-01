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
<div class="contact-form-info ptb-100 white_bg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="contact-form">
                    <div class="contact-form-title mb-25">
                        <h2>¿Tienes dudas? Déjanos un Mensaje</h2>
                    </div>
                    <div class="contact-form-inner">
                        <form id="contact-form" action="#" method="post">
                            
                            
                            <div class="form-field-top mb-20">
                                <div class="form-field">
                                    <input name="name" type="text" placeholder="Ingrese nombre...">
                                </div>
                            </div>
                            <div class="form-field-top mb-20">
                                <div class="form-field">
                                    <input name="email" type="text" placeholder="Ingrese Correo electrónico...">
                                </div>
                            </div>
                            <div class="form-field-top mb-20">
                                <div class="form-field-subject border">
                                    <input name="asunto" type="text" placeholder="Ingrese Asunto...">
                                </div>
                            </div>
                            <div class="form-field-bottom">
                                <div class="textarea">
                                    <textarea name="message" placeholder="Ingrese Mensaje"></textarea>
                                </div>
                                <p class="form-messege"></p>
                                <div class="submit mt-30"><button type="submit">Enviar mensaje</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="contact-info">
                    <div class="contact-info-title mb-25">
                        <h3>Información de Contacto</h3>
                    </div>
                    <div class="contact-adresses">
                        <div class="phone mb-15">
                            <p>
                                <span><i class="mdi mdi-phone"></i></span>
                                <span class="desc">+569 1234 5678</span>
                            </p>
                        </div>
                        <div class="mail mb-15">
                            <p>
                                <span><i class="mdi mdi-email"></i></span>
                                <b><span class="desc"><a href="mailto:info@turismoreal.cl">info@turismoreal.cl</a></span></b>
                            </p>
                        </div>
                    </div>
                    <div class="social-media mt-50">
                        <h3 class="social-medai-title">ENCUENTRANOS EN:</h3>
                        <div class="social-media-list">
                            <a href="#facebook"><i class="mdi mdi-facebook"></i></a>
                            <a href="#linkedin"><i class="mdi mdi-linkedin"></i></a>
                            <a href="#instagram"><i class="mdi mdi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection