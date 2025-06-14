        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    @if (Auth::check() && Auth::user()->rol_id == 3)
                        <ul class="navbar-nav" id="navbar-nav">+

                            <li class="menu-title"><span data-key="t-menu">Usuarios</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Nueva Reserva</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Consultar Reserva</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span> Calendario</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Ver Pagos</span>
                                </a>

                            </li>

                        </ul>
                    @endif
                    @if (Auth::check() && Auth::user()->rol_id == 2)
                        <ul class="navbar-nav" id="navbar-nav">
                            <li class="menu-title"><span data-key="t-menu">Consultor</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Calendario</span>
                                </a>

                            </li> <!-- end Dashboard Menu -->

                        </ul>
                    @endif
                    @if (Auth::check() && Auth::user()->rol_id == 1)
                        <ul class="navbar-nav" id="navbar-nav">
                            <li class="menu-title"><span data-key="t-menu">Administrador</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Nueva Reserva</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Consultar Reserva</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Ver pagos</span>
                                </a>
                                <a class="nav-link menu-link" href="#">
                                    <i class="ri-dashboard-2-line"></i> <span>Usuarios</span>
                                </a>

                            </li> <!-- end Dashboard Menu -->

                        </ul>
                    @endif
                </div>
                </li>

                </ul>
            </div>
            <!-- Sidebar -->
        </div>

        <div class="sidebar-background"></div>
        </div>
