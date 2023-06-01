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
                        <th>Rut</th>
                        <th>Información Vehículo</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th class="datatable-nosort">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($transportistas))
                    @foreach($transportistas as $transportista)
                    <tr id="fila_<?= $transportista->id_transportista ?>">
                        <td class="table-plus"><input type="hidden" value="<?= $transportista->nombre ?>" id="nombre_<?= $transportista->id_transportista ?>">{{$transportista->nombre}}</td>
                        <td class="table-plus">{{!empty($transportista->dni) ? formateaRut($transportista->dni) : 'Sin información'}}</td>
                        <td class="table-plus text-left">
                            <b class="ml-4">Vehículo: </b>{{!empty($transportista->vehiculo) ? $transportista->vehiculo : 'N/A'}}<br>
                            <b class="ml-4">Patente: </b>{{!empty($transportista->patente) ? $transportista->patente : 'N/A'}}<br>
                        </td>
                        <td class="text-center"><?= $transportista->estado == 'Y' ? "<span class='badge badge-success'>Activo</span>" : "<span class='badge badge-danger'>Inactivo</span>" ?></td>
                        <td>{{ordenar_fechaHoraMinutoHumano($transportista->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="/dashboard/transportistas/{{$transportista->id_transportista}}/editar"><i class="dw dw-edit2"></i> Editar</a>
                                    @if(auth()->user()->id_rol <= 2)
                                    <button type="button" id="<?= $transportista->id_transportista ?>" class="dropdown-item btn_deleted" data-toggle="modal" data-target="#modal-delete"><i class="dw dw-delete-3"></i> Eliminar</button>
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
<div class="modal fade text-center" id="modal-delete">
    <div class="modal-dialog modal-md text-center">
        <div class="modal-content ">
            <div class="modal-body">
                <i class="text-danger fa fa-2x fa-question-circle"></i>
                <hr>
                <p class="modal-title text-center">¿Estás seguro que deseas eliminar este registro <br><b id="nombre_delete"></b>?</p>
                <input type="hidden" id="id_delete">
            </div>
            <div class="modal-footer text-center">
                <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                <button type="button" id="deleted" class="btn btn-danger"><i class="fa fa-trash"></i> Eliminar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('js_content')
<script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/datatable-setting.js"></script>
</body>
<script>
    $(".btn_deleted").click(function() {
        let id_btn = $(this).attr('id');
        let table = $('.data-table').DataTable();
        if ($("#fila_" + id_btn).hasClass('selected')) {
            $("#fila_" + id_btn).removeClass('selected');
        } else {
            table.$('tr.selected').removeClass('selected');
            $("#fila_" + id_btn).addClass('selected');
        }
        $("#id_delete").val(id_btn);
        $("#nombre_delete").html($(`#nombre_${id_btn}`).val());
    });

    $("#deleted").click(function() {
        cargando('Eliminado Registro...')
        let id_deleted = $("#id_delete").val();
        let table = $('.data-table').DataTable();
        if (id_deleted > 0) {
            $.ajax({
                url: "/dashboard/transportistas/eliminar",
                type: "POST",
                data: {
                    id_delete: id_deleted,
                    _token: "{{ csrf_token() }}",
                },
                success: function(resp) {
                    Swal.close();
                    $("#btn_cancel").click();
                    if (resp == 'ok') { // SI SE ELIMINA CLIENTE
                        table.row('.selected').remove().draw(false);
                        toastr["success"](`Transportista Eliminado Correctamente`, "Gestión de Transportistas")
                    } else { // SE NOTIFICA ERROR
                        toastr["error"](`${resp}`, "Error Interno")
                    }
                },
                error: function(error) {
                    Swal.close();
                    console.log(JSON.stringify(error))
                }
            });
        } else {
            Swal.close();
            toastr["error"](`Ha ocurrido un error al eliminar registro. Recargue e intente nuevamente.`, "Error de validación")
        }
    });
    $("#modal-delete").on('hidden.bs.modal', function() {
        let table = $('.data-table').DataTable();
        table.$('tr.selected').removeClass('selected');
    });
</script>
@endsection