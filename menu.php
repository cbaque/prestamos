<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$count = new Login();
//$p = $count->ContarRegistros();
?>

<?php 
switch($_SESSION['acceso'])  {

case 'administrador':  ?>

       <!--  BEGIN TOPBAR  -->
        <div class="topbar-nav header navbar" role="banner">
            <nav id="topbar">
                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-logo">
                        <a href="panel">
                            <img src="assets/img/favicon.png" class="navbar-logo" alt="logo">
                        </a>
                    </li>
                    <li class="nav-item theme-text">
                        <a href="panel" class="nav-link"> </a>
                    </li>
                </ul>

                <ul class="list-unstyled menu-categories" id="topAccordion">

                    <li class="menu single-menu active">
                        <a href="panel">
                            <div class="">
                                <span><i data-feather="home"></i>Panel</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="settings"></i>Administración</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Configuración 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="configuracion"> Configuración</a>
                                    </li>
                                    <li>
                                        <a href="documentos"> Tipos Documentos </a>
                                    </li>
                                    <li>
                                        <a href="provincias"> Provincias </a>
                                    </li>
                                    <li>
                                        <a href="departamentos"> Departamentos </a>
                                    </li>
                                    <li>
                                        <a href="monedas"> Tipos de Monedas </a>
                                    </li>
                                    <li>
                                        <a href="formaspagos"> Formas de Pagos </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Seguridad 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="usuarios"> Usuarios</a>
                                    </li>
                                    <li>
                                        <a href="logs"> Historial de Acceso</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Base de Datos 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="backup"> Backup</a>
                                    </li>
                                    <li>
                                        <a href="restore"> Restore</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="clientes">
                            <div class="">
                                <span><i data-feather="users"></i>Clientes</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="monitor"></i>Cajas</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="cajas"> Cajas</a>
                            </li>
                            <li>
                                <a href="aperturas"> Aperturas</a>
                            </li>
                            <li>
                                <a href="aperturasxfechas"> Aperturas x Fechas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="file-text"></i>Ingresos / Egresos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="movimientos"> Ingresos / Egresos</a>
                            </li>
                            <li>
                                <a href="movimientosxfechas"> Por Fechas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="dollar-sign"></i>Préstamos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="forprestamo"> Nueva Solicitud</a>
                            </li>
                            <li>
                                <a href="prestamos"> Búsqueda General</a>
                            </li>
                            <li>
                                <a href="prestamospendientes"> Préstamos Pendientes</a>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Reportes 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="prestamosxcajas"> Por Cajas</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxfechas"> Por Fechas</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxclientes"> Por Clientes</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxusuarios"> Por Usuarios</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="folder-plus"></i>Pagos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="forpago"> Nuevo Pago</a>
                            </li>
                            <li>
                                <a href="pagos"> Búsqueda de Pagos</a>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Reportes 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="pagosxcajas"> Pagos x Cajas</a>
                                    </li>
                                    <li>
                                        <a href="pagosxfechas"> Pagos x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="pagosxclientes"> Pagos x Clientes</a>
                                    </li>
                                    <li>
                                        <a href="moraxfechas"> Mora x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="moraxclientes"> Mora x Clientes</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="logout">
                            <div class="">
                                <span><i data-feather="power"></i>Cerrar Sesión</span>
                            </div>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
        <!--  END TOPBAR  -->

<?php
break;
case 'cajero': ?>

       <!--  BEGIN TOPBAR  -->
        <div class="topbar-nav header navbar" role="banner">
            <nav id="topbar">
                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-logo">
                        <a href="panel">
                            <img src="assets/img/favicon.png" class="navbar-logo" alt="logo">
                        </a>
                    </li>
                    <li class="nav-item theme-text">
                        <a href="panel" class="nav-link"> </a>
                    </li>
                </ul>

                <ul class="list-unstyled menu-categories" id="topAccordion">

                    <li class="menu single-menu active">
                        <a href="panel">
                            <div class="">
                                <span><i data-feather="home"></i>Panel</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="clientes">
                            <div class="">
                                <span><i data-feather="users"></i>Clientes</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="monitor"></i>Aperturas de Cajas</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="aperturas"> Aperturas</a>
                            </li>
                            <li>
                                <a href="aperturasxfechas"> Aperturas x Fechas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="file-text"></i>Ingresos / Egresos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="movimientos"> Ingresos / Egresos</a>
                            </li>
                            <li>
                                <a href="movimientosxfechas"> Por Fechas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="dollar-sign"></i>Préstamos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="forprestamo"> Nueva Solicitud</a>
                            </li>
                            <li>
                                <a href="prestamos"> Búsqueda General</a>
                            </li>
                            <li>
                                <a href="prestamospendientes"> Préstamos Pendientes</a>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Reportes 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="prestamosxcajas"> Por Cajas</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxfechas"> Por Fechas</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxclientes"> Por Clientes</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxusuarios"> Por Usuarios</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="folder-plus"></i>Pagos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="forpago"> Nuevo Pago</a>
                            </li>
                            <li>
                                <a href="pagos"> Búsqueda de Pagos</a>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Reportes 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="pagosxcajas"> Pagos x Cajas</a>
                                    </li>
                                    <li>
                                        <a href="pagosxfechas"> Pagos x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="pagosxclientes"> Pagos x Clientes</a>
                                    </li>
                                    <li>
                                        <a href="moraxfechas"> Mora x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="moraxclientes"> Mora x Clientes</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="logout">
                            <div class="">
                                <span><i data-feather="power"></i>Cerrar Sesión</span>
                            </div>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
        <!--  END TOPBAR  -->
<?php
break;
case 'vendedor': ?>

       <!--  BEGIN TOPBAR  -->
        <div class="topbar-nav header navbar" role="banner">
            <nav id="topbar">
                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-logo">
                        <a href="panel">
                            <img src="assets/img/favicon.png" class="navbar-logo" alt="logo">
                        </a>
                    </li>
                    <li class="nav-item theme-text">
                        <a href="panel" class="nav-link"> </a>
                    </li>
                </ul>

                <ul class="list-unstyled menu-categories" id="topAccordion">

                    <li class="menu single-menu active">
                        <a href="panel">
                            <div class="">
                                <span><i data-feather="home"></i>Panel</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="clientes">
                            <div class="">
                                <span><i data-feather="users"></i>Clientes</span>
                            </div>
                        </a>
                    </li>
                    

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="dollar-sign"></i>Préstamos</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li>
                                <a href="forprestamo"> Nueva Solicitud</a>
                            </li>
                            <li>
                                <a href="prestamos"> Búsqueda General</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="prestamospendientes">
                            <div class="">
                                <span><i data-feather="search"></i>Préstamos Pendientes</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="pagos">
                            <div class="">
                                <span><i data-feather="file-text"></i>Búsqueda de Pagos</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <span><i data-feather="folder-plus"></i>Reportes</span>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Préstamos 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="prestamosxfechas"> Por Fechas</a>
                                    </li>
                                    <li>
                                        <a href="prestamosxclientes"> Por Clientes</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sub-sub-submenu-list">
                                <a href="#datatable" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Pagos 
                                    <i data-feather="chevron-right"></i>
                                </a>
                                <ul class="collapse list-unstyled sub-submenu" id="datatable" data-parent="#datatable">
                                    <li>
                                        <a href="pagosxfechas"> Pagos x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="pagosxclientes"> Pagos x Clientes</a>
                                    </li>
                                    <li>
                                        <a href="moraxfechas"> Mora x Fechas</a>
                                    </li>
                                    <li>
                                        <a href="moraxclientes"> Mora x Clientes</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu single-menu">
                        <a href="logout">
                            <div class="">
                                <span><i data-feather="power"></i>Cerrar Sesión</span>
                            </div>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
        <!--  END TOPBAR  -->
<?php
break;
case 'cliente': ?>

        <!--  BEGIN TOPBAR  -->
        <div class="topbar-nav header navbar" role="banner">
            <nav id="topbar">
                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-logo">
                        <a href="panel">
                            <img src="assets/img/favicon.png" class="navbar-logo" alt="logo">
                        </a>
                    </li>
                    <li class="nav-item theme-text">
                        <a href="panel" class="nav-link"> </a>
                    </li>
                </ul>

                <ul class="list-unstyled menu-categories" id="topAccordion">

                    <li class="menu single-menu active">
                        <a href="panel">
                            <div class="">
                                <span><i data-feather="home"></i>Panel</span>
                            </div>
                        </a>
                    </li>

                    <!--<li class="menu single-menu">
                        <a href="datos_personales">
                            <div class="">
                                <span><i data-feather="edit-3"></i>Mis Datos</span>
                            </div>
                        </a>
                    </li>-->
                    
                    <li class="menu single-menu">
                        <a href="prestamospendientes">
                            <div class="">
                                <span><i data-feather="search"></i>Préstamos Pendientes</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="prestamos">
                            <div class="">
                                <span><i data-feather="search"></i>Búsqueda Préstamos</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="pagos">
                            <div class="">
                                <span><i data-feather="file-text"></i>Búsqueda de Pagos</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu single-menu">
                        <a href="logout">
                            <div class="">
                                <span><i data-feather="power"></i>Cerrar Sesión</span>
                            </div>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
        <!--  END TOPBAR  -->
<?php
break; } ?>