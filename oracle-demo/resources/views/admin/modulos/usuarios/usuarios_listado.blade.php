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
            <div class="col-md-6 col-sm-12 text-right">
                <a class="btn btn-primary" href="<?= isset($url_btn_nuevo) ? $url_btn_nuevo : '#' ?>">
                    <i class="fa fa-plus"></i>
                    <?= isset($btn_nuevo) ? $btn_nuevo : 'Nuevo' ?>
                </a>
            </div>
        </div>
    </div>
    <!-- Simple Datatable start -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4 text-center"><?= isset($title_list) ? $title_list  : 'Listado' ?></h4>
            <hr>
        </div>
        <div class="pb-20 table-responsive">
            <table class="data-table table stripe hover nowrap text-center" id="tabla">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">Nombre</th>
                        <th class="">Rol</th>
                        <th>Correo electrónico</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th class="datatable-nosort">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($usuarios))
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td class="table-plus">{{$usuario->nombre.' '.$usuario->apellido}}</td>
                        <td>{{$usuario->rol}}</td>
                        <td>{{$usuario->email}}</td>
                        <td class="text-center"><?= $usuario->estado == 'Y' ? "<span class='badge badge-success'>Activo</span>" : "<span class='badge badge-danger'>Inactivo</span>" ?></td>
                        <td>{{ordenar_fechaHoraMinutoHumano($usuario->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="/dashboard/usuarios/{{$usuario->id_usuario}}/editar"><i class="dw dw-edit2"></i> Editar</a>
                                    @if($usuario->id_rol != 1)
                                    <a class="dropdown-item" href="/dashboard/usuarios/{{$usuario->id_usuario}}/eliminar"><i class="dw dw-delete-3"></i> Eliminar</a>
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

@endsection

@section('js_content')
<script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/datatable-setting.js"></script>
</body>
@endsection