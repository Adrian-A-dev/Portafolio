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
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
            <hr>
        </div>
        <div class="p-4">
            <form action="<?= isset($action) ? $action : '' ?>" method="post">
                {{ csrf_field() }}
                <div class="alert alert-primary text-center" role="alert">
                    <b><i class="fa fa-info-circle"></i> ATENCIÓN:</b><br> El correo electrónico debe ser válido para que el usuario pueda recibir los pasos de activación de cuenta</b>
                </div>
                <hr>
                <input type="hidden" name="user_form" id="user_form" value="1">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rut">Rut <span class="text-danger">*</span></label>
                            <input type="text" name="rut" id="rut" class="form-control" placeholder="Ingrese Rut..." aria-describedby="helpId">
                            <small id="invalid_rut" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombres">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Ingrese Nombres..." aria-describedby="helpId">
                            <small id="invalid_nombres" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Ingrese Apellidos..." aria-describedby="helpId">
                            <small id="invalid_apellidos" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rol">Rol <span class="text-danger">*</span></label>
                            <select name="rol" id="rol" class="form-control">
                                <option value="">Seleccione...</option>
                                @if(!empty($roles))
                                @foreach($roles as $rol)
                                <option value="{{$rol->id_rol}}">{{strUpper($rol->rol)}}</option>
                                @endforeach
                                @endif

                            </select>
                            <small id="invalid_nombres" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Ingrese Correo electrónico..." aria-describedby="helpId">
                            <small id="invalid_email" class="text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" name="celular" id="celular" class="form-control" placeholder="Ingrese Celular..." aria-describedby="helpId">
                            <small id="invalid_celular" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <hr>
                        <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                        <button class="btn btn-primary" type="submit" id="btn_submit_tarea"><i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection