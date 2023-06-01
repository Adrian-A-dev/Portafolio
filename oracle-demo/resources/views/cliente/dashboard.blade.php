@extends('layout.admin.layout_admin')
@section('contenido-admin')

<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
            <img src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/images/banner-img.png" alt="img bienvenida">
        </div>
        <div class="col-md-8">
            <h4 class="font-20 weight-500 mb-10 text-capitalize">
                Bienvenido <div class="weight-600 font-30 text-blue"><?= auth()->user()->cliente[0]->nombre ?></div>
            </h4>
            <p class="font-18 max-width-600">Bienvenido al Panel de Administración de <?= NOMBRE_EMPRESA ?>.
                Aquí encontrarás diversas opciones de menú para gestionar tus reservas y datos personales. </p>
        </div>
    </div>
</div>







<div class="row pb-10">
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= $reservas_totales > 0 ? formatear_miles($reservas_totales) : 0?></div>
                    <div class="font-14 text-secondary weight-500">RESERVAS</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-calendar1"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= $huespedes_totales > 0 ? formatear_miles($huespedes_totales) : 0?></div>
                    <div class="font-14 text-secondary weight-500">HUESPEDES</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#ff5b5b"><span class="icon-copy fa fa-users"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= $reservas_gastos_totales > 0 ? formatear_numero($reservas_gastos_totales) : '$0' ?></div>
                    <div class="font-14 text-secondary weight-500">GASTOS</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#ff5b5b"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
            <div class="d-flex flex-wrap">
                <div class="widget-data">
                    <div class="weight-700 font-24 text-dark"><?= $reservas_devoluciones_totales > 0 ? formatear_numero($reservas_devoluciones_totales) : '$0' ?></div>
                    <div class="font-14 text-secondary weight-500">DEVOLUCIONES</div>
                </div>
                <div class="widget-icon">
                    <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
    </div>



</div>
<br>
<br>
<br>
<br>



@endsection

@section('js_content')
<script src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/scripts/dashboard3.js"></script>
@endsection