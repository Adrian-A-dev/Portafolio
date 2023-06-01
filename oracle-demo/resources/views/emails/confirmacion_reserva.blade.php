@extends('emails.base_email')
@section('contenido')
<div class="jumbotron">
    <br>
    <br>
    <br>
    <h2 class="alert alert-primary text-center"><strong>CONFIRMACIÓN DE RESERVA N° {{isset($numero_reserva) && $numero_reserva > 0 ? $numero_reserva : 'numero_reserva'}}<br> {{NOMBRE_APP}}</strong></h2>
    <br>
    <br>
    <div class="card">
        <div class="card-body">
            <div class="text-center" style="background: #000; padding: 10px; border-radius: 20px 20px 0px 0px;">
                <a href="{{URL_EMPRESA}}"><img class="img-fluid" src="{{URL_PUBLICA}}" alt="Logo {{NOMBRE_EMPRESA}}"></a>
            </div>
            <hr>
            <p class="text-left"> ¡Hola {{!empty($nombre_cliente) ? $nombre_cliente : 'nombre_cliente'}}!</p>
            <p class="text-left"> Gracias por realizar tu reserva en {{NOMBRE_APP}} <br><br>En el pdf adjunto podrás encontrar el detalle la reserva realizada</p>
            <p class="text-left">¡Saludos Cordiales!<br><br>Equipo {{NOMBRE_EMPRESA}}</p>
            <hr>
        </div>
    </div>
    <br>
    <hr>
    <p class="text-center">Si Tiene dudas contáctese con nosotros <a href="mailto:{{MAIL_BASE}}" style="color:#000;"><b>{{MAIL_BASE}}</b></a></p>
</div>
@endsection