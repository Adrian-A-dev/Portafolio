@extends('layout.admin.layout_admin')
@section('contenido-admin')

<div class="min-height-200px">
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4><?= isset($title) ? $title  : '' ?></h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/gestion-reservas">Dashboard</a></li>
                        <li class="breadcrumb-item">
                            <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>">
                                <?= isset($volver_listado) ? $volver_listado : 'Listado' ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Simple Datatable start -->
    <div class="row">
        <div class="col-md-12">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <div class="invoice-wrap">
                        <div class="invoice-box">
                            <?php
                            $paso = $reserva->id_paso_reserva;

                            if ($reserva->id_estado_reserva >= 4) {
                                $paso_estado = 'Finalizado';
                                $badge = 'badge-success';
                            } elseif ($reserva->id_estado_reserva == 3) {
                                $paso_estado = 'Pendiente';
                                $badge = 'badge-warning';
                            } else {
                                $paso_estado = 'No generado';
                                $badge = 'badge-danger';
                            }
                            ?>
                            <h4 class="text-center mb-30 weight-600"><?= isset($title) ? strUpper($title)  : '' ?> - <span class='badge {{$badge}}'>{{strUpper($paso_estado)}}</span></h4>
                            <div class="row pb-30">
                                <div class="col-md-12">
                                    <div class="text-left">
                                        <h6 class="mb-15 alert alert-dark text-dark">INFORMACIÓN DE RESERVA</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left">
                                        <p class="font-14 mb-5">Cliente: <strong class="weight-600"><?= !empty($reserva->nombre_reserva) ? $reserva->nombre_reserva : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Fecha Llegada: <strong class="weight-600"><?= !empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) :  'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Departamento: <strong class="weight-600"><?= !empty($reserva->departamento->departamento) ? $reserva->departamento->departamento : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Cantidad de Noches: <strong class="weight-600"><?= ($reserva->cantidad_noches) > 0 ? formatear_miles($reserva->cantidad_noches) :  '1' ?></strong></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left">
                                        <p class="font-14 mb-5">Email: <strong class="weight-600"><?= !empty($reserva->email_reserva) ? $reserva->email_reserva : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Fecha Salida: <strong class="weight-600"><?= !empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) :  'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Sucursal: <strong class="weight-600"><?= !empty($reserva->departamento->sucursal->sucursal) ? $reserva->departamento->sucursal->sucursal : 'Sin información' ?></strong></p>
                                        <p class="font-14 mb-5">Valor por Noche: <strong class="weight-600"><?= ($reserva->valor_noche) > 0 ? formatear_numero($reserva->valor_noche) :  '$0' ?></strong></p>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-left">
                                        <h6 class="mb-15 alert alert-dark text-dark">DETALLE DE SERVICIOS</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-desc pb-30">
                                <div class="invoice-desc-head clearfix">
                                    <div class="invoice-sub">SERVICIO</div>
                                    <div class="invoice-hours">CANT</div>
                                    <div class="invoice-subtotal">TOTAL</div>
                                </div>
                                <div class="invoice-desc-body" style="overflow-y: scroll">
                                    <ul>
                                        <?php if (!empty($servicios_reserva)) : ?>
                                            <?php foreach ($servicios_reserva as $s) : ?>
                                                <li class="clearfix">
                                                    <div class="invoice-sub"><?= $s->servicio->servicio ?></div>
                                                    <div class="invoice-hours"><?= ($s->cantidad) > 0 ? formatear_miles($s->cantidad) :  '0' ?></div>
                                                    <div class="invoice-subtotal"><span class="weight-600"><?= ($s->precio) > 0 ? formatear_numero($s->precio) :  '0' ?></span></div>
                                                </li>
                                            <?php endforeach; ?>
                                            <li class="clearfix">
                                                <div class="invoice-sub"><b>TOTAL SERVICIOS</b></div>
                                                <div class="invoice-hours"><b><?= ($total_servicios_cantidad) > 0 ? formatear_miles($total_servicios_cantidad) :  '0' ?></b></div>
                                                <div class="invoice-subtotal"><b><?= ($total_servicios) > 0 ? formatear_numero($total_servicios) :  '$0' ?></b></div>
                                            </li>
                                        <?php else : ?>
                                            <li class="clearfix text-center">
                                                <h6 class="alert alert-danger">NO EXISTEN SERVICIOS ASOCIADOS</h6>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-left">
                                            <h6 class="mb-15 alert alert-dark text-dark">DETALLE DE PAGO</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-desc pb-30">
                                    <div class="invoice-desc-head clearfix">
                                        <div class="invoice-sub">DESCRIPCIÓN</div>
                                        <div class="invoice-subtotal">MONTO:</div>
                                    </div>
                                    <div class="invoice-desc-body">
                                        <ul>
                                            <li class="clearfix">
                                                <div class="invoice-sub"><b>VALOR TOTAL RESERVA:</b></div>
                                                <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->total_reserva) > 0 ? formatear_numero($reserva->total_reserva) :  '$0' ?></span></div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="invoice-sub"><b>TOTAL ABONADO RESERVA:</b></div>
                                                <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->total_pagado) > 0 ? formatear_numero($reserva->total_pagado) :  '$0' ?></span></div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="invoice-sub"><b>DIFERENCIA POR PAGAR:</b></div>
                                                <div class="invoice-subtotal"><span class="weight-600"> <strong class="weight-600"><?= ($reserva->diferencia_pago) > 0 ? formatear_numero($reserva->diferencia_pago) :  '$0' ?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: -220px;">
                                    <div class="col-md-12 text-center">
                                        <?php if ($reserva->diferencia_pago > 0) :  ?>
                                            <button class="btn btn-success" <?= $reserva->id_estado_reserva > 4 ? 'disabled hidden' : '' ?> type="button" id="btn_pago"><i class="fa fa-money"></i> Pagar diferencia</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-left">
                                            <h6 class="mb-15 alert alert-dark text-dark">LISTADO DE HUÉSPEDES</h6>
                                        </div>
                                        <div class="pb-20 table-responsive">
                                            <table class="data-table table stripe  nowrap text-center" id="tabla">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">NOMBRE</th>
                                                        <th>RUT</th>
                                                        <th>EMAIL</th>
                                                        <th>CELULAR</th>
                                                        <th>FECHA NACIMIENTO</th>
                                                        @if(auth()->user()->id_rol == 3)
                                                        <th>FECHA NACIMIENTO</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($huespedes))
                                                    @foreach($huespedes as $huesped)
                                                    <tr class="text-center" id="fila_<?= $huesped->id ?>">
                                                        <td id="nombres_<?= $huesped->id ?>" class="table-plus text-center"><?= !empty($huesped->id_huesped) ? (!empty($huesped->huesped->nombre) ? strUpper($huesped->huesped->nombre . ' ' . $huesped->huesped->apellido) : '-')  : "-" ?></td>
                                                        <td id="rut_<?= $huesped->id ?>" class="text-center"><?= !empty($huesped->id_huesped) ? (!empty($huesped->huesped->dni) ? formateaRut($huesped->huesped->dni) : "-") : '-'  ?></td>
                                                        <td id="email_<?= $huesped->id ?>" class="text-center"><?= !empty($huesped->id_huesped) ? (!empty($huesped->huesped->email) ? strLower($huesped->huesped->email) : "-") : '-'  ?></td>
                                                        <td id="celular_<?= $huesped->id ?>" class="text-center"><?= !empty($huesped->id_huesped) ? (!empty($huesped->huesped->celular) ? strLower($huesped->huesped->celular) : "-") : '-'  ?></td>
                                                        <td id="fecha_nacimiento_<?= $huesped->id ?>" class="text-center"><?= !empty($huesped->id_huesped) ? (!empty($huesped->huesped->fecha_nacimiento) ? ordenar_fechaHumano($huesped->huesped->fecha_nacimiento) : "-") : '-'  ?></td>
                                                        @if(auth()->user()->id_rol == 3)
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                                    <i class="dw dw-more"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                    <button type="button" id="<?= $huesped->id ?>" class="dropdown-item btn_edit"><i class="dw dw-edit2"></i> Editar</button>
                                                                    <button type="button" id="<?= $huesped->id ?>" <?= empty($huesped->id_huesped) ? 'hidden' : '' ?> class="dropdown-item btn_quitar quitar_<?= $huesped->id ?>"><i class="dw dw-remove-1"></i> Quitar</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-left">
                                                <h6 class="mb-15 alert alert-dark text-dark">CONDICIONES DE DEPARTAMENTO</h6>
                                            </div>
                                            <div class="row table-responsive">
                                                <div class="col-lg-6">
                                                    <table class="table">
                                                        <thead class="bg-dark text-white">
                                                            <th>ACCESORIO</th>
                                                            <th class="text-left">¿CUMPLE?</th>
                                                        </thead>
                                                        <tbody>
                                                            @if(!empty($condiciones))
                                                            @foreach ($condiciones as $condicion)
                                                            <?php $condicion = (object)$condicion; ?>
                                                            <tr>
                                                                <td><label for="accesorio_{{$condicion->id_accesorio}}" class="ml-2"> {{$condicion->accesorio}}</label></td>
                                                                <td class="text-center"><input type="checkbox" disabled class="form-control" style="width: 20px;" id="accesorio_{{$condicion->id_accesorio}}" name="accesorio[]" {{ $condicion->checked == '1' ? 'checked' : ''}} value="{{$condicion->id_accesorio}}" /></td>
                                                            </tr>
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td colspan="2">
                                                                <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... No se encontraron condiciones para mostrar</b></h6>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{ csrf_field() }}
                                    <input type="hidden" name="form" id="form" value="1">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <hr>
                                            <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                                            <button class="btn btn-primary" <?= $reserva->id_estado_reserva != 3 ? 'disabled hidden' : '' ?> type="button" id="btn_submit"><i class="fa fa-check-circle"></i> Confirmar Check-in</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($reserva->diferencia_pago > 0) :  ?>
    <div class="modal fade text-center" id="modal-pago">
        <div class="modal-dialog modal-md text-center">
            <form action="<?= isset($action_pago) ? $action_pago : '' ?>" method="POST" id="formulario_pago">
                <div class="modal-content ">
                    <div class="modal-body">
                        <i class="text-danger fa fa-2x fa-question-circle"></i>
                        <hr>
                        <p class="modal-title text-center">Pago de Diferencia de Reserva</p>
                        <hr>
                        {{ csrf_field() }}
                        <div class="row text-left">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="monto">Monto <span class="text-danger">*</span></label>
                                    <input type="text" name="monto" id="monto" class="form-control" disabled value="<?= ($reserva->diferencia_pago) > 0 ? formatear_numero($reserva->diferencia_pago) :  '$0' ?>" required placeholder="Monto...">
                                    <small id="invalid_monto" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tipo_tarjeta">Tipo de Tarjeta <span class="text-danger">*</span></label>
                                    <select name="tipo_tarjeta" id="tipo_tarjeta" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <option value="1">Tarjeta Crédito</option>
                                        <option value="2">Tarjeta Débito</option>
                                    </select>
                                    <small id="invalid_tipo_tarjeta" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="tarjeta">Número de Tarjeta <span class="text-danger">*</span><small> Card Ejemplo: 5523744648825206</small></label>
                                    <input type="text" name="tarjeta" id="tarjeta" class="form-control" value="" maxlength="16" required placeholder="Ingrese Número de Tarjeta...">
                                    <small id="invalid_tarjeta" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mes">Mes <span class="text-danger">*</span></label>
                                    <select name="mes" id="mes" class="form-control">
                                        <?php $meses = traerMesesNumero(); ?>
                                        <?php foreach ($meses as $key => $mes) : ?>
                                            <option value="<?= $key ?>"><?= $mes ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="invalid_mes" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="anio">Año <span class="text-danger">*</span></label>
                                    <select name="anio" id="anio" class="form-control">
                                        <option value="">Seleccione...</option>
                                        <?php $anio = date('Y');
                                        $i = 1; ?>
                                        <?php while ($i <= 11) : ?>
                                            <option value="<?= $anio ?>"><?= $anio ?></option>
                                            <?php $anio = $anio + 1;
                                            $i++; ?>
                                        <?php endwhile; ?>
                                    </select>
                                    <small id="invalid_anio" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cvv">CVV <span class="text-danger">*</span></label>
                                    <input type="password" name="cvv" id="cvv" class="form-control" maxlength="4" value="" required placeholder="Ingrese CVV...">
                                    <small id="invalid_cvv" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="pay-money-form mt-40">
                            <div class="payment-card">
                                <img src="<?= asset(ASSETS_IMG) ?>/logo/pay-card.png" alt="imagen tarjetas">
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer text-center">
                        <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                        <button type="button" id="btn_pago_confirm" class="btn btn-success"><i class="fa fa-money"></i> Pagar</button>
                    </div>

                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php endif; ?>
<div class="modal fade text-center" id="modal-edit">
    <div class="modal-dialog modal-lg text-center">
        <form action="#">
            <div class="modal-content ">
                <div class="modal-body">
                    <i class="text-danger fa fa-2x fa-question-circle"></i>
                    <hr>
                    <p class="modal-title text-center">Formulario de Asignación de Huésped</p>
                    <input type="hidden" id="id_edit">
                    <hr>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ingrese Nombres..." value="{{old('nombres')}}">
                                <small id="invalid_nombres" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ingrese Apellidos..." value="{{old('apellidos')}}">
                                <small id="invalid_apellidos" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="rut">Rut <span class="text-danger"></span></label>
                                <input type="text" name="rut" id="rut" class="form-control" placeholder="Ingrese Rut..." value="{{old('rut')}}" maxlength="12">
                                <small id="invalid_rut" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="email">Correo electrónico <span class="text-danger"></span></label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Ingrese Correo electrónico..." value="{{ old('email') }}">
                                <small id="invalid_email" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="celular">Celular</label>
                                <input type="tel" name="celular" id="celular" class="form-control" placeholder="Ingrese Celular..." minlength="12" maxlength="12" value="{{ old('celular') }}">
                                <small id="invalid_celular" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group text-left">
                                <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control fecha_nacimiento_input" readonly placeholder="Seleccione Fecha Nacimiento..." value="{{ old('fecha_nacimiento') }}">
                                <small id="invalid_fecha_nacimiento" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="button" id="btn_reset" class="btn btn-info"><i class="fa fa-file"></i> Limpiar</button>
                    <button type="button" id="save_huesped" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade text-center" id="modal-quitar">
    <div class="modal-dialog modal-md text-center">
        <div class="modal-content ">
            <div class="modal-body">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que deseas quitar este huésped <br><b id="nombre_quitar"></b>?</p>
                <input type="hidden" id="id_quitar">
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel_quitar" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="quitar" class="btn btn-danger"><i class="fa fa-times"></i> Quitar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade text-center" id="modal-check-in">
    <div class="modal-dialog modal-md text-center">
        <div class="modal-content ">
            <div class="modal-body">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que confirmar Check-IN? </p>
                <hr>
                <p class="alert alert-info"><small>Una vez confirmado no podrá volver a editar ni agregar Huéspedes</small></p>
                <div class="modal-body  text-center">
                <label class="text-left" for="politica_empresa"><input type="checkbox" id="politica_empresa" style="height: 15px;"> <small id="lbl_politica_empresa">Acepto y estoy de acuerdo con el estado del departamento al momento de la recepción de este</small></label>
            </div>
            </div>
           
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel_check_in" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="check_in" disabled class="btn btn-primary"><i class="fa fa-check-circle"></i> Confirmar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('js_content')

<script>
    $('.data-table').DataTable({
        scrollCollapse: true,
        autoWidth: true,
        responsive: true,
        searching: '<?= !empty($huespedes) ? true : false ?>',
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        columnDefs: [{
            targets: "datatable-nosort",
            orderable: false,
        }],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "language": {
            "info": "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Ingrese 1 o más letras",
            paginate: {
                next: '<i class="ion-chevron-right"></i>',
                previous: '<i class="ion-chevron-left"></i>'
            }
        },
    });
</script>


@include('./generalJS')
<script src="<?= asset(ASSETS_JS) ?>/validate_rut.js"></script>
<script>
    $(document).ready(function() {
        $('.fecha_nacimiento_input').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(1910, 0, 1),
            maxDate: new Date(),
        });

        validateForm()
        $('#nombres').keyup(function() {
            validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        });
        $('#apellidos').keyup(function() {
            validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        });
        $('#rut').keyup(function() {
            validaCampos($('#rut').val(), 'rut', 'rut', false);
        });
        $('#celular').keyup(function() {
            let cel = checkNumero($('#celular').val());
            $('#celular').val(cel)
            validaCampos($('#celular').val(), 'celular', 'celular', false);
        });
        $('#email').keyup(function() {
            validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido', false)
        });

    });

    function validateForm() {
        validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        validaCampos($('#rut').val(), 'rut', 'rut', false);
        validaCampos($('#celular').val(), 'celular', 'celular', false);
        validaCorreo($('#email').val(), 'email', '', false);
    }
    $(".btn_edit").click(function() {
        let id_btn = $(this).attr('id');
        let table = $('.data-table').DataTable();
        if ($("#fila_" + id_btn).hasClass('selected')) {
            $("#fila_" + id_btn).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $("#fila_" + id_btn).addClass('selected');
        }
        $("#id_edit").val(id_btn);
        if (id_btn > 0) {
            $("#modal-edit").modal('show');
            cargando('Cargando Registro...')
            $.ajax({
                url: "/gestion-reservas/reservas/obtener-huesped",
                type: "GET",
                data: {
                    id: id_btn,
                    _token: "{{ csrf_token() }}",
                },
                dataType: 'json',
                success: function(resp) {

                    let respuesta = JSON.stringify(resp);
                    let obj = $.parseJSON(respuesta);
                    let tipo = obj['tipo'];
                    let resultado = obj['msg'];
                    if (tipo == 'error') {
                        toastr["error"](`${resultado}`, "Error de Validación")
                    } else {
                        console.log(resultado);
                        $('#nombres').val(resultado.nombres)
                        $('#apellidos').val(resultado.apellidos)
                        $('#rut').val(resultado.rut)
                        $('#celular').val(resultado.celular)
                        $('#email').val(resultado.email)
                        $('#fecha_nacimiento').val(resultado.fecha_nacimiento)

                        validateForm()
                    }

                    Swal.close();
                },
                error: function(error) {
                    Swal.close();
                    console.log(JSON.stringify(error))
                }
            });
        } else {
            Swal.close();
            toastr["error"](`Ha ocurrido un error al cargar registro. Recargue e intente nuevamente.`, "Error de validación")
        }

    })

    $("#save_huesped").click(function() {
        let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        let apellidos = validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        let rut = validaCampos($('#rut').val(), 'rut', 'rut', false);
        let celular = validaCampos($('#celular').val(), 'celular', 'celular', false);
        let email = validaCorreo($('#email').val(), 'email', '', false);
        let id_tr = $("#id_edit").val();
        if (id_tr > 0) {
            if (nombres == 1 && apellidos == 1 && rut == 1 && celular == 1 && email == 1) {
                cargando('Asignando Huésped...')
                $.ajax({
                    url: `/gestion-reservas/reservas/asignar-huesped`,
                    type: "POST",
                    data: {
                        id: id_tr,
                        nombres: $('#nombres').val(),
                        apellidos: $('#apellidos').val(),
                        fecha_nacimiento: $('#fecha_nacimiento').val(),
                        rut: $('#rut').val(),
                        celular: $('#celular').val(),
                        email: $('#email').val(),
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(resp) {
                        swal.close();
                        if (resp == 'ok') {
                            $("#nombres_" + id_tr).html($('#nombres').val().toUpperCase() + ' ' + $('#apellidos').val().toUpperCase());
                            if ($('#rut').val().length > 0) {
                                $("#rut_" + id_tr).html($('#rut').val());
                            } else {
                                $("#rut_" + id_tr).html('-');
                            }
                            if ($('#celular').val().length > 0) {
                                $("#celular_" + id_tr).html($('#celular').val());
                            } else {
                                $("#celular_" + id_tr).html('-');
                            }
                            if ($('#email').val().length > 0) {
                                $("#email_" + id_tr).html($('#email').val().toLowerCase());
                            } else {
                                $("#email_" + id_tr).html('-');
                            }
                            if ($('#fecha_nacimiento').val().length > 0) {
                                $("#fecha_nacimiento_" + id_tr).html($('#fecha_nacimiento').val());
                            } else {
                                $("#fecha_nacimiento_" + id_tr).html('-');
                            }
                            $(".quitar_" + id_tr).attr('hidden', false);
                            $("#modal-edit").modal('hide');
                            let table = $('.data-table').DataTable();
                            table.$('tr.selected').removeClass('selected');
                            toastr["success"](`Huésped Asignado Correctamente`, "Asignación de Huéspedes")
                        } else {
                            toastr["error"](`${resp}`, "Error de Validación")
                        }
                    },
                    error: function(error) {
                        $("#modal-edit").modal('hide');
                        let table = $('.data-table').DataTable();
                        table.$('tr.selected').removeClass('selected');
                        swal.close();
                        toastr["error"](`Ooops!! Ha Ocurrido un Error Interno`, "Error Interno")
                        console.log(JSON.stringify(error))
                    }
                });
            } else {
                toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
            }
        } else {
            toastr["error"](`No se encontró registro para Asignar. Recargue e Intente de Nuevo`, "Error de Validación")
        }

    })
    $("#modal-edit").on('hidden.bs.modal', function() {
        let table = $('.data-table').DataTable();
        table.$('tr.selected').removeClass('selected');
    });
    $("#btn_reset").click(function() {
        $("input[type=text], input[type=tel]").val("");
        validateForm()
    })
</script>

<script>
    $(".btn_quitar").click(function() {
        let id_btn = $(this).attr('id');
        if (id_btn > 0) {
            $("#modal-quitar").modal('show');
            let table = $('.data-table').DataTable();
            if ($("#fila_" + id_btn).hasClass('selected')) {
                $("#fila_" + id_btn).removeClass('selected');
            } else {
                table.$('tr.selected').removeClass('selected');
                $("#fila_" + id_btn).addClass('selected');
            }
            $("#id_quitar").val(id_btn);
            $("#nombre_quitar").html($(`#nombres_${id_btn}`).html());
        } else {
            toastr["error"](`No se encontró registro para Quitar. Recargue e Intente de Nuevo`, "Error de Validación")
        }
    })
    $("#modal-quitar").on('hidden.bs.modal', function() {
        let table = $('.data-table').DataTable();
        table.$('tr.selected').removeClass('selected');
    });

    $("#quitar").click(function() {
        cargando('Quitando Huésped de Reserva...')
        let id_quitar = $("#id_quitar").val();
        let table = $('.data-table').DataTable();
        if (id_quitar > 0) {
            $.ajax({
                url: "/gestion-reservas/huespedes/quitar-huesped",
                type: "POST",
                data: {
                    id_quitar: id_quitar,
                    _token: "{{ csrf_token() }}",
                },
                success: function(resp) {
                    Swal.close();
                    $("#btn_cancel_quitar").click();
                    if (resp == 'ok') { // SI SE ELIMINA CLIENTE
                        $("#nombres_" + id_quitar).html('-');
                        $("#rut_" + id_quitar).html('-');
                        $("#celular_" + id_quitar).html('-');
                        $("#email_" + id_quitar).html('-');
                        $("#fecha_nacimiento_" + id_quitar).html('-');
                        toastr["success"](`Huésped Quitado Correctamente de Reserva`, "Gestión de Mis Huéspedes")
                        $(".quitar_" + id_quitar).attr('hidden', true);
                    } else { // SE NOTIFICA ERROR
                        toastr["error"](`${resp}`, "Error Interno")
                    }
                },
                error: function(error) {
                    Swal.close();
                    console.log(JSON.stringify(error))
                    toastr["error"](`Ooops!! Ha Ocurrido un Error Interno al Quitar Huésped`, "Error Interno")
                }
            });
        } else {
            Swal.close();
            toastr["error"](`Ha ocurrido un error al quitar registro. Recargue e intente nuevamente.`, "Error de validación")
        }
    });
</script>
<script>
    $("#btn_pago").click(function() {
        $("#modal-pago").modal('show');
    })
    $("#btn_submit").click(function() {
        $("#politica_empresa").attr('checked', false);
        $("#lbl_politica_empresa").removeClass('text-danger');
        $("#lbl_politica_empresa").addClass('text-danger');
        $("#check_in").attr('disabled', true);
        $("#modal-check-in").modal('show');

    })
    $("#politica_empresa").click(function() {
        if ($(`#politica_empresa`).is(":checked")) {
            $("#lbl_politica_empresa").removeClass('text-danger');
            $("#check_in").attr('disabled', false);
        } else {
            $("#lbl_politica_empresa").removeClass('text-danger');
            $("#lbl_politica_empresa").addClass('text-danger');
            $("#check_in").attr('disabled', true);
        }

    })
    $("#check_in").click(function() {
        $("#formulario").submit();
        cargando('Generando Check-In...');
        ("#modal-check-in").modal('hide');

    })
    
</script>

@include('./generalJS')
<?php if ($reserva->diferencia_pago > 0) :  ?>
    <script src="<?= asset(ASSETS_JS) ?>/jquery.creditCardValidator.js"></script>
    <script>
        $(document).ready(function() {
            validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
            validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
            validaCampos($('#mes').val(), 'mes', 'select', true);
            validaCampos($('#anio').val(), 'anio', 'select', true);
            validaCampos($('#cvv').val(), 'cvv', 'numero', true);
            $("#tipo_tarjeta").change(function() {
                validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
            })
            $("#tarjeta").keyup(function() {
                $('#tarjeta').validateCreditCard(function(result) {
                    if (result.valid) {
                        $("#tarjeta").removeClass('required');
                        cardValid = 1;
                        $("#tarjeta").css('border-color', 'green');
                        $("#invalid_tarjeta").html('');
                    } else {
                        $("#tarjeta").addClass('required');
                        cardValid = 0;
                    }
                });
                validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
            })

            $("#mes").change(function() {
                validaCampos($('#mes').val(), 'mes', 'select', true);
            })

            $("#anio").change(function() {
                validaCampos($('#anio').val(), 'anio', 'select', true);
            })

            $("#cvv").keyup(function() {
                validaCampos($('#cvv').val(), 'cvv', 'numero', true);
            })
        })

        $("#btn_pago_confirm").click(function() {
            let tipo_tarjeta = validaCampos($('#tipo_tarjeta').val(), 'tipo_tarjeta', 'select', true);
            let tarjeta = 0;
            $('#tarjeta').validateCreditCard(function(result) {
                if (result.valid) {
                    $("#tarjeta").removeClass('required');
                    tarjeta = 1;
                    $("#tarjeta").css('border-color', 'green');
                    $("#invalid_tarjeta").html('');
                } else {
                    $("#tarjeta").addClass('required');
                    tarjeta = 0;
                }
                validaCampos($('#tarjeta').val(), 'tarjeta', 'tarjeta', true);
            });
            let mes = validaCampos($('#mes').val(), 'mes', 'select', true);
            let anio = validaCampos($('#anio').val(), 'anio', 'select', true);
            let cvv = validaCampos($('#cvv').val(), 'cvv', 'numero', true);
            if (tipo_tarjeta == 1 && tarjeta == 1 && mes == 1 && anio == 1 && cvv == 1) {
                $("#formulario_pago").submit();
                cargando('Validando Información...')
            } else {
                toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
            }

        })
    </script>
<?php endif; ?>
@endsection
@endsection