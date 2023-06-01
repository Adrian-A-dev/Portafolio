@extends('emails.base_email')
@section('contenido')

<div class="jumbotron">
    <br>
    <br>
    <br>
    <h2 class="alert alert-primary text-center"><strong>Creación de Cuenta {{NOMBRE_APP}}</strong></h2>
    <br>
    <br>
    <div class="card">
        <div class="card-body">
            <div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
                <a href="{{URL_EMPRESA}}"><img class="img-fluid" src="{{URL_PUBLICA}}" alt="Logo {{NOMBRE_EMPRESA}}"></a>
            </div>
            <hr>
            <p class="text-left"> ¡Hola {{!empty($nombre) ? $nombre : 'nombre'}}!</p>
            <p class="text-left"> Es un agrado darte la Bienvenida a {{NOMBRE_APP}}. <br><br> Ya puedes disfrutar de tu cuenta. </p>
            <p class="text-left"> ¿Qué esperas? Ingresa a tu cuenta ahora </p>
            <p class="text-center" style="color:#fff;">
                <a class="btn btn-primary" href="{{URL_EMPRESA}}login" role="button" style="color:#fff;">Ir a {{NOMBRE_APP}}</a>
            </p>
            <p style="margin-left: 10px;">
                Tu correo de Acceso es: <strong style="color: #000;">"{{$email}}"</strong> <br>
            </p>
            <p class="text-left">¡Saludos Cordiales!<br><br>Equipo {{NOMBRE_EMPRESA}}</p>
            <hr>
        </div>
    </div>
    <br>
    <hr>
    <p class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:{{MAIL_BASE}}" style="color:#000;"><b>{{MAIL_BASE}}</b></a></p>
</div>
@endsection