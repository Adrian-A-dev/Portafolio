<!--Header section start-->
<div class="header-section">
    <div class="bg-opacity"></div>
    <div class="top-header sticky-header">
        <div class="top-header-inner">
            <div class="container">
                <div class="mgea-full-width">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <div class="logo mt-15">
                                <a href="/"><img src="<?= asset(ASSETS_IMG) ?>/logo/logo_turismo.png" alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-10 hidden-xs">
                            <div class="header-top ptb-10">
                                <div class="adresses">
                                    <div class="phone">
                                        <p>Celular <span>+569 1234 5678 </span></p>
                                    </div>
                                    <div class="email">
                                        <p>Email: <a href="mailto:info@turismoreal.cl">info@turismoreal.cl</a></p>
                                    </div>
                                    @if(!auth()->check())
                                    <div class="email">
                                        <p>Administrador: <a href="/login-admin">Login</a></p>
                                    </div>
                                    @endif
                                </div>
                                <div class="social-links">
                                    <a href="#"><i class="mdi mdi-facebook"></i></a>
                                    <a href="#"><i class="mdi mdi-linkedin"></i></a>
                                    <a href="#"><i class="mdi mdi-instagram"></i></a>
                                    <a href="#"><i class="mdi mdi-whatsapp"></i></a>
                                </div>
                            </div>
                            <div class="menu mt-25">
                                <div class="menu-list hidden-sm hidden-xs">
                                    <nav>
                                        <ul>
                                            <li><a href="/">INICIO</a></li>
                                           
                                            <li><a href="/departamentos">DEPARTAMENTOS</a>
                                            </li>
                                            <li><a href="/contacto">CONTACTO</a></li>
                                            @if(auth()->check() && auth()->user()->id_reserva > 0 && auth()->user()->id_paso_reserva < 4) <li><a href="/reserva/{{sha1(auth()->user()->id_reserva)}}">RESERVA EN CURSO</a></li>
                                                @endif

                                        </ul>
                                    </nav>
                                </div>
                                <div class="search-bar-icon">
                                    @if(auth()->check())
                                    <div class="menu-list hidden-sm hidden-xs" style="margin-top: -4px;">
                                        <nav>
                                            <ul>
                                                <li>
                                                    @if(auth()->user()->id_rol == 3)
                                                    <a href="#" style="font-size: 14px;"><i class="fa fa-user"></i> <?= !empty(auth()->user()->cliente[0]->nombre) ? auth()->user()->cliente[0]->nombre : auth()->user()->email ?><i class="fa fa-angle-down"></i></a>
                                                    @else
                                                    <a href="#" style="font-size: 14px;"><i class="fa fa-user"></i> <?= !empty(auth()->user()->empleado[0]->nombre) ? auth()->user()->empleado[0]->nombre : auth()->user()->email ?><i class="fa fa-angle-down"></i></a>
                                                    @endif
                                                    <ul class="dropdown_menu">
                                                        @if(auth()->user()->id_rol == 3)
                                                        <li><a href="/gestion-reservas">Dashboard</a></li>
                                                        <li><a href="/gestion-reservas/reservas">Mis Reservas</a></li>
                                                        @else
                                                        <li><a href="/dashboard">Dashboard</a></li>
                                                        @endif
                                                        <li><a href="/logout">Cerrar Sesión</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                    @else
                                    <a href="/login"> LOGIN <i class="fa fa-sign-in"></i></a>
                                    @endif;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile menu start -->
        <div class="mobile-menu-area hidden-lg hidden-md">
            <div class="container">
                <div class="col-md-12">
                    <nav id="dropdown">
                        <ul>
                            <li><a href="">INICIO</a></li>
                           
                            <li><a href="/departamentos">DEPARTAMENTOS</a></li>
                            <li><a href="/contacto">CONTACTO</a></li>
                            @if(auth()->check() && auth()->user()->id_reserva > 0 && auth()->user()->id_paso_reserva < 4) <li><a href="/reserva/{{sha1(auth()->user()->id_reserva)}}">RESERVA EN CURSO</a></li>
                                @endif;
                                @if(auth()->check())
                                <li>
                                    @if(auth()->user()->id_rol == 3)
                                    <a href="#" style="font-size: 14px;"><i class="fa fa-user"></i> <?= !empty(auth()->user()->cliente[0]->nombre) ? auth()->user()->cliente[0]->nombre : auth()->user()->email ?></a>
                                    @else
                                    <a href="#" style="font-size: 14px;"><i class="fa fa-user"></i> <?= !empty(auth()->user()->empleado[0]->nombre) ? auth()->user()->empleado[0]->nombre : auth()->user()->email ?></a>
                                    @endif
                                    <ul>
                                        @if(auth()->user()->id_rol == 3)
                                        <li><a href="/gestion-reservas">Dashboard</a></li>
                                        <li><a href="/gestion-reservas/reservas">Mis Reservas</a></li>
                                        @else
                                        <li><a href="/dashboard">Dashboard</a></li>
                                        @endif
                                        <li><a href="/logout">Cerrar Sesión</a></li>
                                    </ul>
                                </li>
                                @else
                                <li><a href="/login">LOGIN</a></li>
                                @endif;
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Mobile menu end -->
    </div>
    <!--Welcome secton-->
    @yield('error')
    @yield('head')

</div>

<!-- Header section end -->