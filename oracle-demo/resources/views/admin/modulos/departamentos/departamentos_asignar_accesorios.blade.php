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
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 text-center"><?= isset($title_form) ? $title_form : 'Formulario de Registro' ?></h4>
                    <hr>
                </div>
                <div class="p-4">
                    <form action="<?= isset($action) ? $action : '' ?>" method="post" id="formulario">
                        {{ csrf_field() }}
                        <input type="hidden" name="form" id="form" value="1">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre de Departamento <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre" class="form-control" placeholder="Nombre de Departamento..." disabled value="{{  old('nombre') != null ?  old('nombre') : (!empty($departamento->departamento) ? $departamento->departamento : '') }}">
                                    <small id="invalid_nombre" class="text-danger"></small>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12  border">
                                <h5 class="card-header bg-dark text-white  text-center">ACCESORIOS DE DEPARTAMENTO <span class="text-danger">*</span></h5>
                                <br>
                                @if (!empty($accesorios))
                                <div id="accesorios_div">
                                    <h6 id="msg_chk" hidden class="alert alert-danger text-center"><b><i class="fa fa-info-circle"></i>
                                            ATENCIÓN:</b> Seleccione una o más accesorios para asociarlos al departamento
                                    </h6>
                                    <small id="invalid_accesorio" class="text-danger"></small>
                                    <div class="row d-flex justify-content-start p-4 text-center" id="div_chk_accesorios">
                                        @foreach ($accesorios as $accesorio) 
                                        <?php $accesorio = (object)$accesorio; ?>
                                        <div class="col-xl-4 col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <input type="checkbox" onchange="cuentaCheckbox(1, '')" class="checkbox" id="accesorio_{{$accesorio->id_accesorio}}" name="accesorio[]" {{ $accesorio->checked == '1' ? 'checked' : ''}} value="{{$accesorio->id_accesorio}}" />
                                                <label for="accesorio_{{$accesorio->id_accesorio}}" class="ml-2">{{$accesorio->accesorio}}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <h6 id="msg_vacio" {{empty($accesorios) ? '' : 'hidden'}} class="alert alert-danger text-center"><b><i class="fa fa-exclamation-triangle"></i>
                                        ATENCIÓN:</b> No existen accesorios asociados a su empresa
                                    <br>
                                    <a href="/dashboard/accesorios/nuevo" class="mt-2 btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Ir a Crear Nuevo Accesorio</a>
                                </h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <hr>
                                <a href="<?= isset($url_volver_listado) ? $url_volver_listado : '#' ?>" class="btn btn-dark"><i class="fa fa-reply"></i> Volver</a>
                                <button class="btn btn-primary" type="button" id="btn_submit"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@section('js_content')
@include('./generalJS')
<script>
    $(document).ready(function() {
        cuentaCheckbox(1, '');
    });
    $("#btn_submit").click(function() {
        let accesorio =  cuentaCheckbox(1, '');
        if (accesorio == 1) {
            cargando('Validando Información...')
            $("#formulario").submit();
        } else {
            toastr["error"](`Se encontraron 1 o más Campos con Problemas. Corrija e Intente nuevamente`, "Error de Validación")
        }
    })
</script>



@endsection
@endsection