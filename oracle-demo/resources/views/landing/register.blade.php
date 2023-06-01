@extends('layout.layout_no_login')
@section('contenido')

<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3"></div>
            <div class="col-md-6 ">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h2 class="text-center text-dark"><?= isset($title) ? $title : '' ?></h2>
                    </div>

                    <form action="<?= isset($action) ? $action : '' ?>" method="POST" id="form_reg">
                        {{ csrf_field() }}
                        <input type="hidden" name="register_form" value="1">
                        <div class="form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="text" name="nombres" id="nombres" class="form-control form-control-lg" placeholder="* Ingrese Nombres..." value="{{old('nombres', '')}}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <small id="invalid_nombres" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-lg" placeholder="* Ingrese Apellidos..." value="{{old('apellidos', '')}}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <small id="invalid_apellidos" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="text" name="email" id="email" class="form-control form-control-lg" placeholder="* Ingrese Correo electrónico..." value="{{old('email', '')}}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-email1"></i></span>
                                </div>
                            </div>
                            <small id="invalid_email" class="text-danger"></small>
                        </div>
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
                                    <button class="btn btn-primary btn-lg btn-block" type="button" id="btn_submit"> <i class="fa fa-user-plus"></i> Registrarse</button>
                                </div>
                                <hr>
                                <p class="text-center">¿Ya Posees Cuenta?</p>
                                <div class="input-group mb-0">

                                    <a class="btn btn-outline-primary btn-lg btn-block" href="/login"><i class="fa fa-sign-in"></i> Inicia Sesión Aquí</a>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content')
<script>
    $(document).ready(function() {
        $('#nombres').keyup(function() {
            validaCampos($('#nombres').val(), 'nombres');
        });
        $('#apellidos').keyup(function() {
            validaCampos($('#apellidos').val(), 'apellidos');
        });
        $('#email').keyup(function() {
            validaCorreo($('#email').val(), 'email', 'Ingrese un Correo Válido')
        });
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

        function validaCorreo(correo, id, msg = 'Campo Obligatorio') {
            if ($("#" + id)) {
                if (correo !== '') {
                    if (IsEmail(correo)) {
                        $("#" + id).css('border-color', 'green');
                        $("#invalid_" + id).text('');
                        return 1;
                    } else {
                        $("#" + id).css('border-color', 'red');
                        $("#invalid_" + id).text(msg);
                        return 0;
                    }
                } else {
                    $("#" + id).css('border-color', 'red');
                    $("#invalid_" + id).text('Campo Obligatorio');
                    return 0;
                }
            }
        }

        function IsEmail(email) {
            let regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        function notNumber(string, id) {
            string = string.replace(/[^a-zA-Z\s]/g, '');
            $("#" + id).val(string)
            return string

        }
        $("#btn_submit").click(function() {
            let nombres = validaCampos($("#nombres").val(), 'nombres');
            let apellidos = validaCampos($("#apellidos").val(), 'apellidos');
            let email = validaCorreo($("#email").val(), 'email');
            let password = validaCampos($("#password").val(), 'password');
            let password_confirm = validaCampos($("#password_confirm").val(), 'password_confirm');
            if (nombres == 1 && apellidos == 1 && email == 1 && password == 1 && password_confirm == 1) {
                $("#form_reg").submit();
            } else {

                toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
            }

        });
    });
</script>
@endsection