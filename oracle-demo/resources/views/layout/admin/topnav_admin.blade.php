<div class="header">
    <div class="header-left">
        <div class="menu-icon dw dw-menu"></div>
    </div>
    <div class="header-right">

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="<?= asset(ASSETS_VENDORS_ADMIN) ?>/images/us-none.png" alt="Avatar">
                    </span>
                    @if(auth()->user()->id_rol == 3)
                    <span class="user-name text-white">@<small><?= auth()->user()->cliente[0]->nombre ?></small></span>
                    @else
                    <span class="user-name text-white">@<small><?= auth()->user()->empleado[0]->nombre ?></small></span>
                    @endif

                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    @if(auth()->user()->id_rol == 3)
                    <a class="dropdown-item" href="/gestion-reservas/mi-perfil"><i class="dw dw-user1"></i> Mi Perfil</a>
                    <a class="dropdown-item" href="/gestion-reservas/mi-perfil/cambiar-contrasena"><i class="dw dw-padlock1"></i> Cambiar Contraseña</a>
                    @else
                    <a class="dropdown-item" href="/dashboard/mi-perfil"><i class="dw dw-user1"></i> Mi Perfil</a>
                    <a class="dropdown-item" href="/dashboard/mi-perfil/cambiar-contrasena"><i class="dw dw-padlock1"></i> Cambiar Contraseña</a>
                    @endif
                   
                    <a class="dropdown-item" href="/logout"><i class="dw dw-logout"></i> Cerrar Sesión</a>
                </div>
            </div>
        </div>

    </div>
</div>