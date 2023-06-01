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
                                <div class="panel panel-default" style="padding:15px ;">
                                    <div class="single-room-details fix">
                                        <div class="room-img">
                                            <img src="<?= asset(ASSETS_IMG) ?>/room/dpto.jpg" alt="Imagen Departamento">
                                        </div>
                                        <div class="single-room-details pl-50 text-center">
                                            <h5 class="s_room_title"><b>{{$departamento->departamento}}</b></h5>
                                            <hr>
                                            <h5><b>VALOR</b>: {{$departamento->valor_arriendo > 0 ? formatear_numero($departamento->valor_arriendo) : '$0'}} <span>/ por Noche</span></h5>
                                            <div class="room_price">
                                                <hr>
                                                <span style="font-size: 14px;">{{ !empty($departamento->descripcion_corta) ? $departamento->descripcion_corta : ''}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="row">
                                        <div class="col-md-4" style="padding: 0px;">
                                            <div>
                                                <div class="panel-body" style="padding: 30px;">
                                                    <form action="<?= isset($action) ? $action : '' ?>" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="date_picker_llegada">Fecha de Llegada <span class="text-danger">*</span></label>
                                                                    <input type="text" readonly name="date_picker_llegada" value="{{old('date_picker_llegada') != null ? old('date_picker_llegada') : (!empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) : '') }}" required id="date_picker_llegada" class="" placeholder="Seleccione Fecha Desde...">
                                                                    <small id="helpId" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="date_picker_salida">Fecha de Salida <span class="text-danger">*</span></label>
                                                                    <input type="text" readonly name="date_picker_salida" value="{{old('date_picker_salida') != null ? old('date_picker_salida') : (!empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) : '') }}" required id="date_picker_salida" class="" placeholder="Seleccione Fecha Hasta...">
                                                                    <small id="helpId" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                                                    <input type="text" name="nombre" id="nombre" class="" value="{{old('nombre') != null ? old('nombre') : (!empty(auth()->user()->cliente[0]->nombre) ? auth()->user()->cliente[0]->nombre .' '. auth()->user()->cliente[0]->apellido : '')}}" required placeholder="Ingrese Nombre...">
                                                                    <small id="helpId" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <label for="email">Correo <span class="text-danger">*</span></label>
                                                                    <input type="email" name="email" id="email" class="" value="{{old('email') != null ? old('email') : (!empty(auth()->user()->cliente[0]->email) ? auth()->user()->cliente[0]->email : '')}}" required placeholder="Ingrese Correo electrónico...">
                                                                    <small id="helpId" class="text-danger"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <hr>
                                                                <div class="prve-next-box mt-20">
                                                                    <!-- <div class="back-link">
                                                                        <a href="#">Back</a>
                                                                    </div> -->
                                                                    <button type="submit"><i class="fa fa-save"></i> Continuar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px;">
                                            <div class="">
                                                <div class="panel-body" style="padding: 30px;">
                                                    <section class="bg-ligth">
                                                        <div class="card p-2 border">
                                                            <h4 class="text-center"><b>CALENDARIO DISPONIBILIDAD</b></h4>

                                                            <div class="row">
                                                                <div class="col-md-12 text-center">
                                                                    <p>Revise la disponibilidad del departamento al día de hoy</p>
                                                                </div>
                                                            </div>
                                                            <div id="pnlSimpleCalendar" style="width:100%;"></div>
                                                        </div>
                                                    </section>
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
@section('js_content')


@include('./calendar')
<script>
    let maxdate = new Date();
    maxdate.setDate(maxdate.getDate() + 30);
    $('#date_picker_llegada').datepicker({
        startDate: new Date(),
        endDate: maxdate,
        datesDisabled: [<?= !empty($fechas['fecha_texto']) ? $fechas['fecha_texto'] : '' ?>],
    });
    $('#date_picker_salida').datepicker({
        startDate: new Date(),
        endDate: maxdate,
        datesDisabled: [<?= !empty($fechas['fecha_texto']) ? $fechas['fecha_texto'] : '' ?>],
    });
</script>
<script>
    $(function() {
        $('#pnlSimpleCalendar').calendar();
    });
</script>
@endsection
@endsection