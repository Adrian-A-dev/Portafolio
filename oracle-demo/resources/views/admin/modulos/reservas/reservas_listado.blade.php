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
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
                    </ol>
                </nav>
            </div>
           
        </div>
    </div>
    <!-- Simple Datatable start -->
    <div class="card-box mb-30">
        <div class="pd-20  text-center">
            <h4 class="text-blue h4 text-center"><?= isset($title_list) ? $title_list  : 'Listado' ?></h4>
            <hr>
        </div>
        <div class="pb-20 table-responsive">
            <table class="table stripe  nowrap text-center" id="tabla">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">ID RESERVA</th>
                        <th>CLIENTE</th>
                        <th class="table-plus datatable-nosort">PAGOS</th>
                        <th>HUÉSPEDES</th>
                        <th>ESTADO</th>
                        <th>FECHAS RESERVA</th>
                        <th>FECHA CREACIÓN</th>
                        <th class="datatable-nosort">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($reservas))
                    @foreach($reservas as $reserva)
                    <tr id="fila_<?= $reserva->id_reserva ?>">
                        <td class="table-plus">{{$reserva->id_reserva}}</td>
                        <td class="table-plus text-center">
                            <?= !empty($reserva->cliente->nombre) ? strUpper($reserva->cliente->nombre . ' ' . $reserva->cliente->apellido) : "<span class='text-danger'>Sin información</span>" ?> <br>
                        </td>
                        <?php

                        if ($reserva->diferencia_pago > 0) {
                            $pago = 'PARCIAL';
                            $pago_badge = 'badge-warning';
                        } elseif ($reserva->total_pagado > 0 && $reserva->total_pagado == $reserva->total_reserva) {
                            $pago = 'COMPLETO';
                            $pago_badge = 'badge-success';
                        } else {
                            $pago = 'NO REALIZADO';
                            $pago_badge = 'badge-danger';
                        }
                        ?>
                        <td class="table-plus text-left">
                            <b class="ml-4">TOTAL RESERVA:</b> <?= !empty($reserva->total_reserva) ? formatear_numero($reserva->total_reserva) : "$0" ?> <br>
                            <b class="ml-4">TOTAL ABONADO :</b> <?= !empty($reserva->total_pagado) ? formatear_numero($reserva->total_pagado) : "$0" ?><br>
                            <b class="ml-4">TOTAL NO PAGADO:</b> <?= !empty($reserva->diferencia_pago) ? formatear_numero($reserva->diferencia_pago) : "$0" ?><br>
                        </td>
                        <td class="table-plus text-left">
                            <b class="ml-4">CAPACIDAD:</b> <?= !empty($reserva->numero_huespedes) ? formatear_miles($reserva->numero_huespedes) : '0' ?> <br>
                            <b class="ml-4">ASIGNADOS :</b> <?= !empty($reserva->cantidad) ? formatear_miles($reserva->cantidad) : '0'  ?><br>
                            <b class="ml-4">POR ASIGNAR:</b> <?= (int)$reserva->numero_huespedes - (int)$reserva->cantidad ?><br>
                        </td>
                        <?php
                        $paso = $reserva->id_paso_reserva;
                        $badge = 'badge-warning';
                        if ($reserva->id_estado_reserva < 3 && $paso == 1) {
                            $paso_estado = 'Seleccion Fechas';
                        } elseif ($reserva->id_estado_reserva < 3 && $paso == 2) {
                            $paso_estado = 'Seleccion Servicios';
                        } elseif ($reserva->id_estado_reserva < 3 && $paso == 3) {
                            $paso_estado = 'Pendiente Pago';
                        } else {
                            $paso_estado = !empty($reserva->estado_reserva->estado_reserva) ? strUpper($reserva->estado_reserva->estado_reserva) : 'Sin información';
                            $badge = !empty($reserva->estado_reserva->badge) ? $reserva->estado_reserva->badge : 'badge-light';
                        }
                        ?>
                        <td class="table-plus estado_<?= $reserva->id_reserva ?>"><span class='badge {{$badge}}'>{{strUpper($paso_estado)}}</span></td>
                        <td class="table-plus text-left">
                            <b class="ml-4">LLEGADA:</b> <?= !empty($reserva->inicio_reserva) ? ordenar_fechaHumano($reserva->inicio_reserva) : "-" ?> <br>
                            <b class="ml-4">SALIDA &nbsp &nbsp:</b> <?= !empty($reserva->final_reserva) ? ordenar_fechaHumano($reserva->final_reserva) : "-" ?><br>
                        </td>
                        <td>{{ordenar_fechaHoraMinutoHumano($reserva->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <?php if ($reserva->id_paso_reserva == 4) : ?>
                                        @if($reserva->id_estado_reserva == 1 || $reserva->id_estado_reserva == 2)
                                        <a class="dropdown-item" href="/dashboard/reservas/{{$reserva->id_reserva}}/check_in"><i class="dw dw-edit2"></i>Realizar Check-In</a>
                                        @endif
                                        @if($reserva->id_estado_reserva >= 3)
                                        <a class="dropdown-item" href="/dashboard/reservas/{{$reserva->id_reserva}}/check_in"><i class="dw dw-file"></i>Ver Check-In</a>
                                        @endif
                                        @if($reserva->id_estado_reserva == 4)
                                        <a class="dropdown-item" href="/dashboard/reservas/{{$reserva->id_reserva}}/check_out"><i class="dw dw-checked"></i>Realizar Check-Out</a>
                                        @endif
                                        @if($reserva->id_estado_reserva == 7)
                                        <a class="dropdown-item" href="/dashboard/reservas/{{$reserva->id_reserva}}/check_out"><i class="dw dw-file"></i>Ver Check-Out</a>
                                        @endif
                                        <a class="dropdown-item" href="/dashboard/reservas/{{$reserva->id_reserva}}/download-pdf" target="_blank"><i class="dw dw-eye"></i> Ver Comprobante</a>
                                    <?php else : ?>
                                        <?php if ($reserva->id_estado_reserva == 6) : ?>
                                            <a class="dropdown-item disabled" href="/reserva/{{sha1($reserva->id_reserva)}}"><i class="dw dw-ban"></i> NO DISPONIBLE</a>
                                        <?php else : ?>
                                            <a class="dropdown-item" href="/reserva/{{sha1($reserva->id_reserva)}}"><i class="dw dw-eye"></i> Ver Reserva</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    @if($reserva->id_estado_reserva < 3) <button type="button" id="<?= $reserva->id_reserva ?>" class="dropdown-item btn_modal_cancel btn_<?= $reserva->id_reserva ?>"><i class="dw dw-ban"></i> Cancelar Reserva</button>
                                        @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade text-center" id="modal-cancel">
    <div class="modal-dialog modal-md text-center">
        <div class="modal-content ">
            <div class="modal-body">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que deseas cancelar la reserva <b>N°</b><b id="reserva_numero"></b>?</p>
                <p><b>ESTA ACCIÓN NO SE PUEDE DESHACER</b></p>
                <input type="hidden" id="id_cancel">
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="btn_confirm" class="btn btn-danger"><i class="fa fa-check-circle"></i> Confirmar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('js_content')
<script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/datatable-setting.js"></script>
<script>
    $('#tabla').DataTable({
        order: [
            [0, 'desc']
        ],
    });
</script>

<script>
    $(".btn_modal_cancel").click(function() {

        let id_btn = $(this).attr('id');
        let table = $('#tabla').DataTable();
        if ($("#fila_" + id_btn).hasClass('selected')) {
            $("#fila_" + id_btn).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $("#fila_" + id_btn).addClass('selected');
        }
        $("#id_cancel").val(id_btn);
        $("#reserva_numero").html(id_btn);
        $("#modal-cancel").modal('show');
    });

    $("#btn_confirm").click(function() {
        cargando('Cancelando Reserva...')
        let id_cancel = $("#id_cancel").val();
        let table = $('#tabla').DataTable();
        if (id_cancel > 0) {
            $.ajax({
                url: "/gestion-reservas/reservas/cancelar",
                type: "POST",
                data: {
                    id_cancel: id_cancel,
                    _token: "{{ csrf_token() }}",
                },
                success: function(resp) {
                    Swal.close();
                    $("#btn_cancel").click();
                    if (resp == 'ok') { // SI SE ELIMINA CLIENTE
                        $(".estado_" + id_cancel).html(`<span class='badge badge-danger'>CANCELADA</span>`);
                        $(".btn_" + id_cancel).attr('disabled', true);
                        $(".btn_" + id_cancel).attr('hidden', true);
                        $(".btn_asignar_" + id_cancel).attr('disabled', true);
                        $(".btn_asignar_" + id_cancel).attr('hidden', true);
                        toastr["success"](`Reserva Cancelada Correctamente`, "Gestión de Reservas")
                    } else { // SE NOTIFICA ERROR
                        toastr["error"](`${resp}`, "Error Interno")
                    }
                },
                error: function(error) {
                    Swal.close();
                    console.log(JSON.stringify(error))
                    toastr["error"](`Ha ocurrido un error al Cancelar Reserva. Recargue e intente nuevamente.`, "Error de validación")
                }
            });
        } else {
            Swal.close();
            toastr["error"](`Ha ocurrido un error al Cancelar Reserva. Recargue e intente nuevamente.`, "Error de validación")
        }
    });
    $("#modal-cancel").on('hidden.bs.modal', function() {
        let table = $('#tabla').DataTable();
        table.$('tr.selected').removeClass('selected');
    });
</script>
</body>
@endsection