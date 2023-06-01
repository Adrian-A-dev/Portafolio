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


    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_list) ? $title_list  : 'Listado Asignación de Huespedes' ?></h4>
            <hr>
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
                        <th class="datatable-nosort">ACCIONES</th>
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
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row p-4">
            <div class="col-md-12 text-right">
                <hr>
                <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
            </div>
        </div>
    </div>

</div>
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
@section('js_content')
<script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/datatable-setting.js"></script>
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
                            $(".quitar_"+id_tr).attr('hidden', false);
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
                        $(".quitar_"+id_quitar).attr('hidden', true);
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

@endsection
@endsection