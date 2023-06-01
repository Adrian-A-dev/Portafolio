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
                    <form action="<?= isset($action) ? $action : '' ?>" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="login_form" value="1">
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="text" name="email" id="email" class="form-control form-control-lg" placeholder="Ingrese usuario...">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group custom">
                                <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Ingrese Contraseña...">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit"> <i class="fa fa-sign-in"></i> Ingresar</button>
                                </div>
                                <hr>
                                @if($registro_btn)
                                <p class="text-center">¿Aún no tienes cuenta?</p>
                                <div class="input-group mb-0">

                                    <a class="btn btn-outline-primary btn-lg btn-block" href="/registro"><i class="fa fa-user-plus"></i> Registrate Aquí</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection