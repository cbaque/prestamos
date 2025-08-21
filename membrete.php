<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$count = new Login();
$count = $count->ContarRegistros();

$arqueo = new Login();
$arqueo = $arqueo->AperturaxUsuario();
?>
<!--  BEGIN NAVBAR  -->
<div class="header-container">
    <header class="header navbar navbar-expand-sm">

        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

        <div class="nav-logo align-self-center">
            <a class="navbar-brand2" href="panel">
                <?php if (file_exists("fotos/logo_principal.png")){
                echo "<img src='fotos/logo_principal.png?".time()."' width='220px' alt='Logo Principal'>"; 
                    } else {
                echo "<span class='navbar-brand-name alert-link'>PRÉSTAMOS</span>"; 
                    }
                ?>
            </a>
        </div>

        <ul class="navbar-item flex-row mr-auto">
            <li class="nav-item align-self-center search-animated">
                <form class="form-inline search-full form-inline search" role="search">
                    <!--<div class="search-bar">
                        <input type="text" class="form-control search-form-control ml-lg-auto" placeholder="Search...">
                    </div>-->
                </form>
                <!--<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>-->
            </li>
        </ul>

        <ul class="navbar-item flex-row nav-dropdowns">

            <li class="nav-item dropdown message-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle hour text-dark" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <span id="spanreloj"></span>
                </a>
            </li>


            <!-- MOSTRAR ALERTA PRESTAMOS -->
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" title="Ventas" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><?php echo $count[0]["pendientes"] > 0 || $count[0]["cancelados"] > 0 ? "<span style='background:#2196f3;' class='badge badge-info'></span>" : ""; ?>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">

                        <div class="dropdown-item">
                            <div class="media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-info"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <div class="media-body">
                                    <?php if($count[0]["pendientes"] > 0){ ?>
                                    <a href="reportepdf?tipo=<?php echo encrypt("PRESTAMOSPENDIENTES") ?>" class="message-item" target="_blank" rel="noopener noreferrer" title="Exportar Pdf">
                                    <div class="data-info">
                                        <h6 class="">Préstamos Pendientes</h6>
                                        <p class=""><span class="badge badge-warning"><?php echo $count[0]["pendientes"]; ?></span></p>
                                    </div>
                                    </a>
                                    <?php } else { ?>
                                    <div class="data-info">
                                        <h6 class="">Préstamos Pendientes</h6>
                                        <p class=""><span class="badge badge-warning"><?php echo $count[0]["pendientes"]; ?></span></p>
                                    </div>
                                    <?php } ?>

                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </li>
            <!-- MOSTRA ALERTA PRESTAMOS -->

            <?php if($count[0]["vencidos"] > 0){ ?>

            <!-- CUOTAS VENCIDAS -->
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cuotas Vencidas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><?php echo $count[0]["vencidos"] > 0 ? "<span style='background:#c43b29;' class='badge badge-success'></span>" : ""; ?>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">

                        <div class="dropdown-item">
                            <div class="media file-upload">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text text-danger"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <div class="media-body">
                                    <?php if($count[0]["vencidos"] > 0){ ?>
                                    <a href="reportepdf?tipo=<?php echo encrypt("PAGOSVENCIDOS") ?>" class="message-item" target="_blank" rel="noopener noreferrer" title="Exportar Pdf">
                                    <div class="data-info">
                                        <h6 class="">Cuotas Vencidas</h6>
                                        <p class=""><span class="badge badge-danger"><?php echo $count[0]["vencidos"]; ?></span></p>
                                    </div>
                                    </a>
                                    <?php } else { ?>
                                    <div class="data-info">
                                        <h6 class="">Cuotas Vencidas</h6>
                                        <p class=""><span class="badge badge-danger"><?php echo $count[0]["vencidos"]; ?></span></p>
                                    </div>
                                    <?php } ?>

                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <!-- CUOTAS VENCIDAS -->

            <?php } ?>

            
            <?php if ($_SESSION['acceso'] == "administrador" && $arqueo == "" || $_SESSION['acceso'] == "cajero" && $arqueo == ""){ ?>

            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Caja Cerrada">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg><span style="background:#d51c0d;" class="badge badge-danger"></span>
                </a>
            </li>

            <?php } else if ($_SESSION['acceso'] == "administrador" && $arqueo != "" || $_SESSION['acceso'] == "cajero" && $arqueo != ""){ ?>

            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Caja Aperturada">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg><span style="background:#2196f3;" class="badge badge-info"></span>
                </a>
            </li>

            <?php } ?>

            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                    <div class="media">
                        <?php if($_SESSION['acceso']=="cliente"){
                            if (isset($_SESSION['codigo'])) {
                                if (file_exists("fotos/clientes/".$_SESSION['codigo'].".jpg")){
                                echo "<img src='fotos/clientes/".$_SESSION['codigo'].".jpg?' width='40' height='40' class='img-fluid'>"; 
                                } else {
                                echo "<img src='fotos/clientes/avatar.png' width='80' class='img-fluid'>"; 
                                } } else {
                                echo "<img src='fotos/clientes/avatar.png' width='80' class='img-fluid'>"; 
                                }
                        } else {
                            if (isset($_SESSION['codigo'])) {
                                if (file_exists("fotos/usuarios/".$_SESSION['codigo'].".jpg")){
                                echo "<img src='fotos/usuarios/".$_SESSION['codigo'].".jpg?' width='40' height='40' class='img-fluid'>"; 
                                } else {
                                echo "<img src='fotos/usuarios/avatar.png' width='80' class='img-fluid'>"; 
                                } } else {
                                echo "<img src='fotos/usuarios/avatar.png' width='80' class='img-fluid'>"; 
                            }
                        } ?>
                        <div class="media-body align-self-center">
                            <h6 class="text-dark alert-link"><?php echo $_SESSION['nombres']; ?></h6>
                            <h6><small class="text-info font-12 alert-link"><?php echo $_SESSION['nivel']; ?></small></h6>
                        </div>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="user-profile-dropdown">
                    <div class="">
                        <div class="dropdown-item">
                            <a class="" href="perfil"><i data-feather="user"></i> Mi Perfil</a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="password"><i data-feather="edit"></i> Actualizar Password</a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="bloqueo"><i data-feather="lock"></i> Bloquear Sesión</a>
                        </div>
                        <div class="dropdown-item">
                            <a class="" href="logout"><i data-feather="log-out"></i> Cerrar Sesión</a>
                        </div>
                    </div>
                </div>

            </li>
        </ul>
    </header>
</div>
    <!--  END NAVBAR  -->