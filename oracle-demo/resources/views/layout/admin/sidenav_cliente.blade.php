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
                    <a href="/gestion-reservas" class="dropdown-toggle no-arrow <?= isset($side_dashboard) ? 'active' : ''?>">
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
                    <a href="/gestion-reservas/reservas" class="dropdown-toggle no-arrow <?= isset($side_reservas) ? 'active' : ''?>">
                        <span class="micon dw dw-time-management active"></span><span class="mtext">Gestión de Reservas<span class='badge badge-danger'></span></span>
                    </a>
                </li>
                <li>
                    <a href="/gestion-reservas/huespedes" class="dropdown-toggle no-arrow <?= isset($side_huespedes) ? 'active' : ''?>">
                        <span class="micon dw dw-conference active"></span><span class="mtext">Huéspedes<span class='badge badge-danger'></span></span>
                    </a>
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