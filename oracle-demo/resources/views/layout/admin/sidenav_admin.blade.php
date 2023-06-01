<div class="left-side-bar">
    <div class="brand-logo">
        <a href="/dashboard">
            <img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="" class="dark-logo">
            <img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="" class="light-logo">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="/dashboard" class="dropdown-toggle no-arrow <?= isset($side_dashboard) ? 'active' : ''?>">
                        <span class="micon dw dw-profits-1 active"></span><span class="mtext">Dashboard</span>
                    </a>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Gestión de Reservas</div>
                </li>
                <li>
                    <a href="/dashboard/reservas" class="dropdown-toggle no-arrow <?= isset($side_reservas) ? 'active' : ''?>">
                        <span class="micon dw dw-time-management active"></span><span class="mtext">Gestión de Reservas<span class='badge badge-danger'></span></span>
                    </a>
                </li>
                <li>
                    <a href="/dashboard/clientes" class="dropdown-toggle no-arrow <?= isset($side_clientes) ? 'active' : ''?>">
                        <span class="micon dw dw-deal active"></span><span class="mtext">Clientes<span class='badge badge-danger'></span></span>
                    </a>
                </li>
               
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Mantenedores</div>
                </li>


                <li class="dropdown <?= isset($side_sucursales) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-city"></span><span class="mtext">Sucursales <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu"  <?= isset($side_sucursales) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/sucursales/nueva" class="<?= isset($side_sucursales_new) ? 'active' : ''?>">Nueva Sucursal</a></li>
                        <li><a href="/dashboard/sucursales" class="<?= isset($side_sucursales_list) ? 'active' : ''?>">Gestión de Sucursales</a></li>

                    </ul>
                </li>
                <li class="dropdown <?= isset($side_accesorios) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-list"></span><span class="mtext">Accesorios <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_accesorios) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/accesorios/nuevo" class="<?= isset($side_accesorios_new) ? 'active' : ''?>">Nuevo Accesorio</a></li>
                        <li><a href="/dashboard/accesorios" class="<?= isset($side_accesorios_list) ? 'active' : ''?>">Gestión de Accesorios</a></li>
                    </ul>
                </li>
              
                <li class="dropdown <?= isset($side_departamentos) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-hotel"></span><span class="mtext">Departamentos <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_departamentos) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/departamentos/nuevo" class="<?= isset($side_departamentos_new) ? 'active' : ''?>">Nuevo Departamento</a></li>
                        <li><a href="/dashboard/departamentos" class="<?= isset($side_departamentos_list) ? 'active' : ''?>">Gestión de Departamentos</a></li>

                    </ul>
                </li>
               
                <li class="dropdown <?= isset($side_tipo_multas) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-notepad-2"></span><span class="mtext">Tipos de Multas <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_tipo_multas) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/tipo-multas/nuevo" class="<?= isset($side_tipo_multas_new) ? 'active' : ''?>">Nuevo Tipos de Multa</a></li>
                        <li><a href="/dashboard/tipo-multas" class="<?= isset($side_tipo_multas_list) ? 'active' : ''?>">Gestión de Tipos de Multas</a></li>

                    </ul>
                </li>
                <li class="dropdown <?= isset($side_multas) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-clipboard1"></span><span class="mtext">Multas <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_multas) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/multas/nueva" class="<?= isset($side_multas_new) ? 'active' : ''?>">Nueva Multa</a></li>
                        <li><a href="/dashboard/multas" class="<?= isset($side_multas_list) ? 'active' : ''?>">Gestión de Multas</a></li>

                    </ul>
                </li>
                @if(auth()->user()->id_rol == 1)

                <li class="dropdown <?= isset($side_tipo_servicios) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-diagram"></span><span class="mtext">Tipo de Servicios <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_tipo_servicios) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/tipo-servicios/nuevo" class="<?= isset($side_tipo_servicios_new) ? 'active' : ''?>">Nuevo Tipo de Servicio</a></li>
                        <li><a href="/dashboard/tipo-servicios" class="<?= isset($side_tipo_servicios_list) ? 'active' : ''?>">Gestión de Tipo de Servicios</a></li>

                    </ul>
                </li>
                @endif
                <li class="dropdown <?= isset($side_servicios) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-suitcase1" class="<?= isset($side_servicios_list) ? 'active' : ''?>"></span><span class="mtext">Servicios <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_servicios) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/servicios/nuevo" class="<?= isset($side_servicios_new) ? 'active' : ''?>">Nuevo Servicio</a></li>
                        <li><a href="/dashboard/servicios" class="<?= isset($side_servicios_list) ? 'active' : ''?>">Gestión de Servicios</a></li>

                    </ul>
                </li>
                <li class="dropdown <?= isset($side_transportistas) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-bus"></span><span class="mtext">Transportistas <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_transportistas) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/transportistas/nuevo" class="<?= isset($side_transportistas_new) ? 'active' : ''?>">Nuevo Transportista</a></li>
                        <li><a href="/dashboard/transportistas" class="<?= isset($side_transportistas_list) ? 'active' : ''?>">Gestión de Transportistas</a></li>

                    </ul>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Administrador</div>
                </li>
                @if(auth()->user()->id_rol == 3333)
                <li class="dropdown <?= isset($side_roles) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-user-11"></span><span class="mtext">Roles <span class='badge badge-warning'>New y Edit</span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_roles) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/roles/nuevo" class="<?= isset($side_roles_new) ? 'active' : ''?>">Nuevo Rol</a></li>
                        <li><a href="/dashboard/roles" class="<?= isset($side_roles_list) ? 'active' : ''?>">Gestión de Roles</a></li>
                    </ul>
                </li>
                @endif
                <li class="dropdown <?= isset($side_cargos) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-id-card1"></span><span class="mtext">Cargos <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_cargos) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/cargos/nuevo" class="<?= isset($side_cargos_new) ? 'active' : ''?>">Nuevo Cargo</a></li>
                        <li><a href="/dashboard/cargos" class="<?= isset($side_cargos_list) ? 'active' : ''?>">Gestión de Cargos</a></li>

                    </ul>
                </li>
                <li class="dropdown <?= isset($side_empleados) ? 'show' : ''?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon dw dw-name"></span><span class="mtext">Empleados <span class='badge badge-success'></span></span>
                    </a>
                    <ul class="submenu" <?= isset($side_empleados) ? 'style="display: block;"' : ''?> >
                        <li><a href="/dashboard/empleados/nuevo" class="<?= isset($side_empleados_new) ? 'active' : ''?>">Nuevo Empleado</a></li>
                        <li><a href="/dashboard/empleados" class="<?= isset($side_empleados_list) ? 'active' : ''?>">Gestión de Empleados</a></li>
                    </ul>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Extra</div>
                </li>
                <li>
                    <a href="/" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-internet"></span>
                        <span class="mtext">Sitio Web </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>