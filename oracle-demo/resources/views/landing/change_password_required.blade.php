@extends('layout.layout_no_login')
@section('contenido')
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3"></div>
            <div class="col-md-6 ">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h5 class="text-center text-dark"><?= isset($title) ? strUpper($title) : '' ?></h5>
                        <hr>
                    </div>
                    <form action="<?= isset($action) ? $action : '' ?>" method="POST" id="formulario">
                        {{ csrf_field() }}
                        <input type="hidden" name="form" value="1">
                        <div class="form-group">
                            <label for="password">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="* Ingrese Contraseña...">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <small id="invalid_password" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control form-control-lg" placeholder="* Confirme Contraseña...">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <small id="invalid_password_confirm" class="text-danger"></small>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <button class="btn btn-primary btn-lg btn-block" type="button" id="btn_submit"> <i class="fa fa-sign-in"></i> Ingresar</button>
                                </div>
                                <hr>
                            </div>
                            <div class="col-12 text-center">
                                <a class="btn btn-danger btn-sm" href="/logout"> <i class="fa fa-sign-out"></i> Cerrar sesión</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js_content')
<script>
    $(document).ready(function() {
        validaCampos($('#password').val(), 'password');
        validaCampos($('#password_confirm').val(), 'password_confirm');
        $('#password').keyup(function() {
            validaCampos($('#password').val(), 'password');
        });
        $('#password_confirm').keyup(function() {
            validaCampos($('#password_confirm').val(), 'password_confirm');
        });

        function validaCampos(texto, id, msg = 'Campo Obligatorio') {
            if ($("#" + id)) {
                if (texto !== '') {
                    if (id == 'password' || id == 'password_confirm') {
                        if (texto.length < 4) {
                            $("#" + id).css('border-color', 'red');
                            $("#invalid_" + id).text('Contraseña debe tener al menos 4 Caracteres');
                            return 0;
                        } else {
                            if (id == 'password_confirm') {
                                if ($('#password').val() !== texto) {
                                    $("#" + id).css('border-color', 'red');
                                    $("#invalid_" + id).text('Las Contraseñas No Coínciden');
                                    return 0;
                                } else {
                                    $("#" + id).css('border-color', 'green');
                                    $("#invalid_" + id).text('');
                                    return 1;
                                }
                            } else {
                                if (id == 'password') {
                                    if ($('#password_confirm').val() !== texto) {
                                        $("#" + id).css('border-color', 'green');
                                        $("#invalid_" + id).text('');
                                        $("#password_confirm").css('border-color', 'red');
                                        $("#invalid_password_confirm").text('Las Contraseñas No Coínciden');
                                        return 0;
                                    } else {
                                        $("#" + id).css('border-color', 'green');
                                        $("#invalid_" + id).text('');
                                        $("#password_confirm").css('border-color', 'green');
                                        $("#invalid_password_confirm").text('');
                                        return 1;
                                    }
                                }
                            }
                        }
                    } else {
                        if (id == 'nombres' || id == 'apellidos') {


                            texto = notNumber(texto, id);
                            texto = texto.trim();
                            if (texto.length < 3) {
                                $("#" + id).css('border-color', 'red');
                                $("#invalid_" + id).text('El largo Mínimo de 3 Caracteres');
                                return 0;
                            } else {
                                $("#" + id).css('border-color', 'green');
                                $("#invalid_" + id).text('');
                                return 1;
                            }

                        } else {
                            $("#" + id).css('border-color', 'green');
                            $("#invalid_" + id).text('');
                            return 1;
                        }

                    }
                    return 1;
                } else {
                    $("#" + id).css('border-color', 'red');
                    $("#invalid_" + id).text(msg);
                    return 0;
                }
            }
        }
        $("#btn_submit").click(function() {
            let password = validaCampos($("#password").val(), 'password');
            let password_confirm = validaCampos($("#password_confirm").val(), 'password_confirm');
            if (password == 1 && password_confirm == 1) {
                $("#formulario").submit();
            } else {

                toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
            }

        });
    });
</script>
@endsection
@endsection