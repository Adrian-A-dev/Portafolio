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
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? strUpper($title_form) : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <div class="row" style="margin-top: -40px;">
                        <div class="card-body">
                            <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-heading text-center p-2" style="background-color: #f6750d; color:#fff;"><b>SERVICIOS DE DESAYUNOS & COMIDAS</b></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @if(!empty($servicios_comidas))
                                                    @foreach($servicios_comidas as $servicio)
                                                    <div class="col-lg-4">
                                                        <div class="card">
                                                            <div class="<?= $servicio->cantidad > 1 ? 'servicios_comidas' : '' ?> p-3" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                <div class="caption text-center">
                                                                    <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                    <hr>
                                                                    <div class="card-body smaller">
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Horarios:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Descripción:</b></small><br>
                                                                            <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center">
                                                                    <hr>

                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                    <?php else : ?>
                                                                        <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                        <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class=" card-footer text-center">
                                                                <div class="form-group text-center">
                                                                    <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                    <label for="">Cantidad </label>
                                                                    <select name="cantidad[<?= $servicio->id_servicio ?>]"  class="form-control" id="sel_servicios_comidas_<?= $servicio->id_servicio ?>" style="width: 100px ; margin: 0 auto;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                        <?php $i = 1;
                                                                        while ($i <= $cantidad_permitida) : ?>
                                                                            <option value="<?= $i ?>"><?= $i ?></option>
                                                                        <?php $i++;
                                                                        endwhile; ?>
                                                                    </select>
                                                                </div>
                                                                <ul class="list-inline">
                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <input type="checkbox" name="servicios_comidas[]" id="chk_servicios_comidas_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_comidas">
                                                                    <?php else : ?>
                                                                        <input type="checkbox" disabled class="form-control chk">
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="col-md-12">
                                                        <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-heading text-center p-2" style="background-color: #000; color:#fff;"><b>SERVICIOS DE TRANSPORTE</b></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @if(!empty($servicios_transporte))
                                                    @foreach($servicios_transporte as $servicio)
                                                    <div class="col-lg-4">
                                                        <div class="card">
                                                            <div class="<?= $servicio->cantidad > 1 ? 'servicios_transporte' : '' ?> p-3" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                <div class="caption text-center">
                                                                    <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                    <hr>
                                                                    <div class="card-body smaller">
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Horarios:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Lugar Origen:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_origen) ? strUpper($servicio->lugar_origen)  : 'Sin información' ?></b>
                                                                            <br>
                                                                            <br>
                                                                            <small><b class="text-muted">Lugar Destino:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_destino) ? strUpper($servicio->lugar_destino)  : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Descripción:</b></small><br>
                                                                            <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center">
                                                                    <hr>
                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                    <?php else : ?>
                                                                        <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                        <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-center">
                                                                <div class="form-group">
                                                                    <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                    <label for="">Cantidad </label>
                                                                    <select name="cantidad[<?= $servicio->id_servicio ?>]" id="sel_servicios_transporte_<?= $servicio->id_servicio ?>" class="form-control"  style="width: 100px ; margin: 0 auto;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                        <?php $i = 1;
                                                                        while ($i <= $cantidad_permitida) : ?>
                                                                            <option value="<?= $i ?>"><?= $i ?></option>
                                                                        <?php $i++;
                                                                        endwhile; ?>
                                                                    </select>
                                                                </div>
                                                                <ul class="list-inline">
                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <input type="checkbox" name="servicios_transporte[]" id="chk_servicios_transporte_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_transporte">
                                                                    <?php else : ?>
                                                                        <input type="checkbox" disabled class="form-control chk">
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="col-md-12">
                                                        <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-default">
                                            <div class="card-heading text-center p-2" style="background-color: #f6750d; color:#fff;"><b>SERVICIOS DE TOURS</b></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @if(!empty($servicios_tours))
                                                    @foreach($servicios_tours as $servicio)
                                                    <div class="col-lg-4">
                                                        <div class="card">
                                                            <div class="<?= $servicio->cantidad > 1 ? 'servicios_tours' : '' ?> p-3" style="cursor: <?= $servicio->cantidad > 1 ? 'pointer' : 'no-drop' ?>;" id="<?= $servicio->id_servicio ?>">
                                                                <div class="caption text-center">
                                                                    <h6 id="thumbnail-label"><b><?= !empty($servicio->servicio) ? $servicio->servicio : 'Sin información' ?></b></h6>
                                                                    <hr>
                                                                    <div class="card-body smaller">
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Horarios:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->hora_inicio) ? $servicio->hora_inicio  : 'Sin información' ?> a <?= !empty($servicio->hora_fin) ? $servicio->hora_fin . ' Hrs.' : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Lugar Origen:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_origen) ? strUpper($servicio->lugar_origen)  : 'Sin información' ?></b>
                                                                            <br>
                                                                            <br>
                                                                            <small><b class="text-muted">Lugar Destino:</b></small><br>
                                                                            <b class="text-left"> <?= !empty($servicio->lugar_destino) ? strUpper($servicio->lugar_destino)  : 'Sin información' ?></b>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="text-left">
                                                                            <small><b class="text-muted">Descripción:</b></small><br>
                                                                            <p> <b><?= !empty($servicio->descripcion_corta) ? $servicio->descripcion_corta : 'Sin información' ?></b></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-center">
                                                                    <hr>

                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <h4><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h4>
                                                                    <?php else : ?>
                                                                        <h6 style="text-decoration: line-through;"><b><?= $servicio->precio > 0 ? formatear_numero($servicio->precio) : 'GRATIS' ?></b></h6>
                                                                        <h4 class="text-danger"><b>NO DISPONIBLE</b></h4>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="caption card-footer text-center">
                                                                <div class="form-group">
                                                                    <?php $cantidad_permitida = ((int)$cantidad_huespedes < (int)$servicio->cantidad) ? (int)$cantidad_huespedes : (int)$servicio->cantidad;  ?>
                                                                    <label for="">Cantidad </label>
                                                                    <select name="cantidad[<?= $servicio->id_servicio ?>]" id="sel_servicios_tours_<?= $servicio->id_servicio ?>" class="form-control"  style="width: 100px ; margin: 0 auto;" <?= $cantidad_permitida < 1 ? 'disabled' : 'disabled' ?>>
                                                                        <?php $i = 1;
                                                                        while ($i <= $cantidad_permitida) : ?>
                                                                            <option value="<?= $i ?>"><?= $i ?></option>
                                                                        <?php $i++;
                                                                        endwhile; ?>
                                                                    </select>
                                                                </div>
                                                                <ul class="list-inline">
                                                                    <?php if ($servicio->cantidad > 1) : ?>
                                                                        <input type="checkbox" name="servicios_tours[]" id="chk_servicios_tours_<?= $servicio->id_servicio ?>" value="<?= $servicio->id_servicio ?>" class="form-control chk_servicios_tours">
                                                                    <?php else : ?>
                                                                        <input type="checkbox" disabled class="form-control chk">
                                                                    <?php endif; ?>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="col-md-12">
                                                        <h6 class="alert alert-danger text-center" style="font-size:14px ;"><b>Opps!... Aún No existen Servicios para esta Categoría Disponibles</b></h6>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                            <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js_content')
<script>
    $(".servicios_comidas").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_comidas_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_comidas_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_comidas_${id_servicio} option:selected`).prop("selected", false);
        } else {
            $(`#chk_servicios_comidas_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_comidas_${id_servicio}`).focus();
        }
    })
    $(".chk_servicios_comidas").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_comidas_', '');
        if ($(`#chk_servicios_comidas_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_comidas_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_comidas_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_comidas_${id_servicio} option:selected`).prop("selected", false);
        }

    })
    $(".servicios_transporte").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_transporte_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_transporte_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_transporte_${id_servicio} option:selected`).prop("selected", false);
        } else {
            $(`#chk_servicios_transporte_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_transporte_${id_servicio}`).focus();

        }

    })
    $(".chk_servicios_transporte").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_transporte_', '');
        if ($(`#chk_servicios_transporte_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_transporte_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_transporte_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_transporte_${id_servicio} option:selected`).prop("selected", false);
        }

    })



    $(".servicios_tours").click(function() {
        let id_servicio = $(this).attr('id');
        if ($(`#chk_servicios_tours_${id_servicio}`).is(":checked")) {
            $(`#chk_servicios_tours_${id_servicio}`).prop('checked', false);
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_tours_${id_servicio} option:selected`).prop("selected", false);


        } else {
            $(`#chk_servicios_tours_${id_servicio}`).prop('checked', true);
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_tours_${id_servicio}`).focus();

        }

    })

    $(".chk_servicios_tours").click(function() {
        let id_servicio = $(this).attr('id');
        id_servicio = id_servicio.replace('chk_servicios_tours_', '');
        if ($(`#chk_servicios_tours_${id_servicio}`).is(":checked")) {
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', false);
            $(`#sel_servicios_tours_${id_servicio}`).focus();
        } else {
            $(`#sel_servicios_tours_${id_servicio}`).attr('disabled', true);
            $(`#sel_servicios_tours_${id_servicio} option:selected`).prop("selected", false);
        }

    })
    $("#btn_submit").click(function() {
        let contador = 0;


        $(".chk_servicios_comidas").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        $(".chk_servicios_transporte").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        $(".chk_servicios_tours").each(function() {
            if ($(this).is(":checked")) {
                contador++;
            }
        });
        if (contador == 0) {
            toastr["error"](`Debe seleccionar al menos un servicio para continuar. Corrija e Intente nuevamente`, "Error de Validación")
        } else {
            cargando('Guardando Información...')
            $("#formulario").submit();
        }
    })
</script>


@endsection
@endsection