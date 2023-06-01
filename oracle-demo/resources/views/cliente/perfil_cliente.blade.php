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
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($title) ? $title  : '' ?> </li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Edición de Mi Perfil' ?></h4>
            <hr>
        </div>
        <div class="p-4">
            <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                {{ csrf_field() }}
                <h6>Información de Usuario</h6>
                <hr>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Fecha Creación</label>
                            <input class="form-control" disabled value="{{ !empty($cliente->created_at) ? ordenar_fechaHoraMinutoHumano($cliente->created_at) : 'Sin información'}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="usuario">Usuario <span class="text-danger">*</span></label>
                            <input type="text" name="usuario" id="usuario" class="form-control" value="{{ auth()->user()->username }}" disabled>
                            <small id="invalid_usuario" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <h6>Información de Personal</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ingrese Nombres..." value="{{ old('nombres') != null ? old('nombres') : (!empty($cliente->nombre) ? $cliente->nombre : '')  }}">
                            <small id="invalid_nombres" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ingrese Apellidos..." value="{{ old('apellidos') != null ? old('apellidos') : (!empty($cliente->apellido) ? $cliente->apellido : '')  }}">
                            <small id="invalid_apellidos" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rut">Rut <span class="text-danger"></span></label>
                            <input type="text" name="rut" id="rut" class="form-control" placeholder="Ingrese Rut..." value="{{ old('rut') != null ? old('rut') : (!empty($cliente->dni) ? formateaRut($cliente->dni) : '')  }}" maxlength="12">
                            <small id="invalid_rut" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Ingrese Correo electrónico..." value="{{ old('email') != null ? old('email') : (!empty($cliente->email) ? $cliente->email : '')  }}">
                            <small id="invalid_email" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="tel" name="celular" id="celular" class="form-control" placeholder="Ingrese Celular..." minlength="12" maxlength="12" value="{{ old('celular') != null ? old('celular') : (!empty($cliente->celular) ? $cliente->celular : '')  }}">
                            <small id="invalid_celular" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha Nacimiento</label>
                            <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" readonly placeholder="Seleccione Fecha Nacimiento..." value="{{old('fecha_nacimiento') != null ? old('fecha_nacimiento') : (!empty($cliente->fecha_nacimiento) ? ordenar_fechaHumano($cliente->fecha_nacimiento) : '')}}">
                            <small id="invalid_fecha_nacimiento" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <h6>Información Adicional</h6>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="region">Región <span class="text-danger"></span></label>
                            <select name="region" id="region" class="form-control">
                                <option value="">Seleccione Region...</option>
                                @if(!empty($regiones))
                                @foreach($regiones as $region)
                                <option value="{{$region->id_region}}" <?= (old('region') != null && old('region') == $region->id_region) ? 'selected' : (!empty($cliente->comuna->region_id_region) && $cliente->comuna->region_id_region == $region->id_region ? 'selected' : '') ?>>{{strUpper($region->nombre_largo)}}</option>
                                @endforeach
                                @endif
                            </select>
                            <small id="invalid_region" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="comuna">Comuna <span class="text-danger"></span></label>
                            <select name="comuna" id="comuna" class="form-control" <?= !empty($cliente->id_comuna) ? '' : 'disabled' ?>>
                                <option value="">Seleccione Comuna...</option>
                                @if(!empty($comunas))
                                @foreach($comunas as $comuna)
                                <option value="{{$comuna->id_comuna}}" <?= old('region') != null && old('region') == $region->id_region ? 'selected' : ($cliente->id_comuna == $comuna->id_comuna ? 'selected' : '') ?>>{{strUpper($comuna->comuna)}}</option>
                                @endforeach
                                @endif
                            </select>
                            <small id="invalid_comuna" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="direccion">Dirección <span class="text-danger"></span></label>
                            <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ingrese direccion..." value="{{ old('direccion') != null ? old('direccion') : (!empty($cliente->direccion) ? $cliente->direccion : '')  }}">
                            <small id="invalid_direccion" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-12">
                        <p>¿Deseas Eliminar tu cuenta?</p>
                        <br>
                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#modal-delete"><i class="fa fa-trash"></i> Eliminar Cuenta</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Modificar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade text-center" id="modal-delete">
    <div class="modal-dialog modal-md text-center">
        <form action="/gestion-reservas/mi-perfil/eliminar-cuenta" method="post">
            {{ csrf_field() }}
            <div class="modal-content ">
                <div class="modal-body">
                    <i class="text-danger fa fa-2x fa-question-circle"></i>
                    <hr>
                    <p class="modal-title text-center">¿Estás seguro que deseas eliminar tu cuenta?</p>
                    <br>
                    <h6 class="alert alert-danger">ESTA ACCIÓN NO SE PUEDE DESHACER Y PERDERÁ TODAS SUS RESERVAS Y REGISTROS</h6>

                </div>
                <div class="modal-body  text-center">
                    <label class="text-left" for="eliminar_cuenta_chk"><input type="checkbox" id="eliminar_cuenta_chk" style="height: 15px;"> <span id="lbl_eliminar_cuenta_chk">Acepto y estoy consciente de lo que conlleva esta acción</span></label>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="submit" onclick="('Eliminado Cuenta...')" id="deleted" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar Cuenta</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@section('js_content')
@include('./generalJS')
<script src="<?= asset(ASSETS_JS) ?>/validate_rut.js"></script>
<script>
    $(document).ready(function() {
        $("#eliminar_cuenta_chk").attr('checked', false);
        $("#lbl_eliminar_cuenta_chk").removeClass('text-danger');
        $("#lbl_eliminar_cuenta_chk").addClass('text-danger');
        $("#deleted").attr('disabled', true);
        $("#modal_valida").modal("show");
        $("#eliminar_cuenta_chk").click(function() {
            if ($(`#eliminar_cuenta_chk`).is(":checked")) {
                $("#lbl_eliminar_cuenta_chk").removeClass('text-danger');
                $("#deleted").attr('disabled', false);
            } else {
                $("#lbl_eliminar_cuenta_chk").removeClass('text-danger');
                $("#lbl_eliminar_cuenta_chk").addClass('text-danger');
                $("#deleted").attr('disabled', true);
            }

        })
    });

    $("#continuar").click(function() {
        cargando('Validando Información...')
        $("#formulario").submit();
    });
    $("#region").change(function() {
        let region = $(this).val();
        if (region > 0) {
            $.ajax({
                url: "/dashboard/obtener-comunas",
                type: "GET",
                data: {
                    region: region,
                },
                success: function(resp) {
                    if (resp == 'error') { // SI SE ELIMINA CLIENTE
                        toastr["error"](`Región No posee Comunas asociados`, "No se encuentran registros")
                        $("#comuna").attr('disabled', true);
                        $("#comuna").html('<option value="">Seleccione Comuna...</option>')
                        validaCampos($('#comuna').val(), 'comuna', 'select', true);
                    } else {
                        $("#comuna").attr('disabled', false);
                        $("#comuna").html(resp)
                        $("#comuna").focus()
                        validaCampos($('#comuna').val(), 'comuna', 'select', true);
                    }
                },
                error: function(error) {
                    console.log(JSON.stringify(error))
                    toastr["error"](`Error Interno`, "Error")
                    $("#comuna").attr('disabled', true);
                    $("#comuna").html('<option value="">Seleccione Comuna...</option>')
                    validaCampos($('#comuna').val(), 'comuna', 'select', false);
                }
            });
        } else {
            $("#comuna").html('<option value="">Seleccione Comuna...</option>')
            $("#comuna").attr('disabled', true);
            validaCampos($('#comuna').val(), 'comuna', 'select', false);
        }
    })
</script>

<script>
    $(document).ready(function() {
        $('#fecha_nacimiento').datepicker({
            language: 'en',
            autoClose: true,
            dateFormat: 'dd-mm-yyyy',
            minDate: new Date(1910, 0, 1),
            maxDate: new Date(),
        });
        validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        validaCampos($('#rut').val(), 'rut', 'rut', false);
        validaCampos($('#celular').val(), 'celular', 'celular', false);
        validaCorreo($('#email').val(), 'email');
        validaCampos($('#region').val(), 'region', 'select', false);
        validaCampos($('#comuna').val(), 'comuna', 'select', false);
        validaCampos($('#direccion').val(), 'direccion', 'texto_min', false);


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
            validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido')
        });
        $('#region').change(function() {
            validaCampos($('#region').val(), 'region', 'select', false);
        });
        $('#comuna').change(function() {
            validaCampos($('#comuna').val(), 'comuna', 'select', true);
        });
        $('#direccion').keyup(function() {
            validaCampos($('#direccion').val(), 'direccion', 'texto_min', false);
        });


    });

    $("#btn_submit").click(function() {

        let nombres = validaCampos($('#nombres').val(), 'nombres', 'nombres', true);
        let apellidos = validaCampos($('#apellidos').val(), 'apellidos', 'nombres', true);
        let rut = validaCampos($('#rut').val(), 'rut', 'rut', false);
        let celular = validaCampos($('#celular').val(), 'celular', 'celular', false);
        let email = validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido');
        let region = validaCampos($('#region').val(), 'region', 'select', false);
        let validado = false;
        if ($('#region').val() > 0) {
            validado = true;
        }
        let comuna = validaCampos($('#comuna').val(), 'comuna', 'select', validado);
        let direccion = validaCampos($('#direccion').val(), 'direccion', 'texto_min', false);

        if (nombres == 1 && apellidos == 1 && rut == 1 && celular == 1 && email == 1 && region == 1 && comuna == 1 && direccion == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>
@endsection


@endsection