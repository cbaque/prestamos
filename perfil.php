<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$tra = new Login();
$reg = $tra->SessionPorId();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title></title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="plugins/dropify/dropify.min.css">
    <link href="assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->

</head>
<body onLoad="muestraReloj()" class="sidebar-noneoverflow">
    
    <!--  BEGIN NAVBAR  -->
    <?php include('membrete.php'); ?>
    <!--  BEGIN NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN TOPBAR  -->
        <?php include('menu.php'); ?>
        <!--  BEGIN TOPBAR  -->
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">
                    <div id="custom_styles" class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                        <div class="widget-content-area statbox widget box box-shadow">
                            
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="align-justify"></i> Mi Perfil</h5>
                                    </div>                 
                                </div>
                            </div>

    <div class="widget-content widget-content-area">                                
        <form class="form form-material" novalidate method="post" action="#">

            <div class="row">
                <div class="col-lg-11 mx-auto">
                    <div class="row">
                        <div class="col-xl-3 col-lg-12 col-md-4">
                            <div class="upload pr-md-4 text-center">
                                
                            <?php if($_SESSION['acceso']=="cliente"){

                            if (isset($reg[0]['codigo'])) {
                                if (file_exists("fotos/clientes/".$reg[0]['codigo'].".jpg")){
                                    echo "<img src='fotos/clientes/".$reg[0]['codigo'].".jpg?' width='140' height='140' class='rounded-circle'>"; 
                                } else {
                                    echo "<img src='fotos/clientes/avatar.png' width='140' height='140' class='rounded-circle'>"; 
                                } } else {
                                    echo "<img src='fotos/clientes/avatar.png' width='140' height='140' class='rounded-circle'>"; 
                                }

                            } else {

                                if (isset($reg[0]['codigo'])) {
                                if (file_exists("fotos/usuarios/".$reg[0]['codigo'].".jpg")){
                                    echo "<img src='fotos/usuarios/".$reg[0]['codigo'].".jpg?' width='140' height='140' class='rounded-circle'>"; 
                                } else {
                                    echo "<img src='fotos/usuarios/avatar.png' width='140' height='140' class='rounded-circle'>"; 
                                } } else {
                                    echo "<img src='fotos/usuarios/avatar.png' width='140' height='140' class='rounded-circle'>"; 
                                }
                            }
                            ?>
                                <h6 class="card-title mt-2 text-info"><?php echo $reg[0]['nivel']; ?></h6>
                                <h6 class="card-subtitle"><?php echo $reg[0]['email']; ?></h6>

                            </div>
                        </div>

                        <div class="col-xl-9 col-lg-12 col-md-8 mt-md-0">
                            <div class="form">
                                
                                <?php if($_SESSION['acceso']=="cliente"){ ?>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Documento: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Documento"><?php echo $reg[0]['documento']." ".$reg[0]['cedcliente']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nombre de Cliente: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nombre de Cliente"><?php echo $reg[0]['nomcliente']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Sexo de Cliente: <span class="symbol required"></span></label>
                                            <br /><abbr title="Sexo de Cliente"><?php echo $reg[0]['sexocliente']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Teléfono: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Teléfono"><?php echo $reg[0]['tlfcliente'] == "" ? "**********" : $reg[0]['tlfcliente']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Celular: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Celular"><?php echo $reg[0]['celcliente'] == "" ? "**********" : $reg[0]['celcliente']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Departamento: <span class="symbol required"></span></label>
                                            <br /><abbr title="Departamento"><?php echo $reg[0]['coddepartamento'] == 0 ? "**********" : $reg[0]['departamento']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Provincia: <span class="symbol required"></span></label>
                                            <br /><abbr title="Provincia"><?php echo $reg[0]['codprovincia'] == 0 ? "**********" : $reg[0]['provincia']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Dirección Domiciliaria: <span class="symbol required"></span></label>
                                            <br /><abbr title="Dirección Domiciliaria"><?php echo $reg[0]['direccliente']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Correo Electronico: <span class="symbol required"></span></label>
                                            <br /><abbr title="Correo de Usuario"><?php echo $reg[0]['email'] == "" ? "**********" : $reg[0]['email']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Usuario de Acceso: <span class="symbol required"></span></label>
                                            <br /><abbr title="Usuario de Acceso"><?php echo $reg[0]['usuario']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nivel de Acceso: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nivel de Acceso"><?php echo $reg[0]['nivel']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Status de Acceso: <span class="symbol required"></span></label>
                                            <br /><abbr title="Status de Usuario"><?php echo $status = ( $reg[0]['status'] == 1 ? "ACTIVO" : "INACTIVO"); ?></abbr> 
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Documento Refer: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Documento"><?php echo $reg[0]['cedreferencia'] == "" ? "**********" : $reg[0]['cedreferencia']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nombre de Persona Refer: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nombre de Cliente"><?php echo $reg[0]['nomreferencia'] == "" ? "**********" : $reg[0]['nomreferencia']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Celular Refer: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Celular"><?php echo $reg[0]['celreferencia'] == "" ? "**********" : $reg[0]['celreferencia']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <?php } else { ?>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Documento: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Documento"><?php echo $reg[0]['documento']." ".$reg[0]['dni']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nombre de Usuario: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nombre de Usuario"><?php echo $reg[0]['nombres']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Sexo de Usuario: <span class="symbol required"></span></label>
                                            <br /><abbr title="Sexo de Usuario"><?php echo $reg[0]['sexo']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Teléfono: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Teléfono"><?php echo $reg[0]['telefono'] == "" ? "**********" : $reg[0]['telefono']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nº de Celular: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nº de Celular"><?php echo $reg[0]['celular'] == "" ? "**********" : $reg[0]['celular']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Departamento: <span class="symbol required"></span></label>
                                            <br /><abbr title="Departamento"><?php echo $reg[0]['coddepartamento'] == 0 ? "**********" : $reg[0]['departamento']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Provincia: <span class="symbol required"></span></label>
                                            <br /><abbr title="Provincia"><?php echo $reg[0]['codprovincia'] == 0 ? "**********" : $reg[0]['provincia']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Dirección Domiciliaria: <span class="symbol required"></span></label>
                                            <br /><abbr title="Dirección Domiciliaria"><?php echo $reg[0]['direccion']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Correo de Usuario: <span class="symbol required"></span></label>
                                            <br /><abbr title="Correo de Usuario"><?php echo $reg[0]['email']; ?></abbr>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Usuario de Acceso: <span class="symbol required"></span></label>
                                            <br /><abbr title="Usuario de Acceso"><?php echo $reg[0]['usuario']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Nivel de Acceso: <span class="symbol required"></span></label>
                                            <br /><abbr title="Nivel de Acceso"><?php echo $reg[0]['nivel']; ?></abbr>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label class="control-label alert-link">Status de Usuario: <span class="symbol required"></span></label>
                                            <br /><abbr title="Status de Usuario"><?php echo $status = ( $reg[0]['status'] == 1 ? "ACTIVO" : "INACTIVO"); ?></abbr> 
                                        </div>
                                    </div>
                                </div>

                                <?php } ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="footer-wrapper text-primary">
                <div class="footer-section f-section-1">
                    <i data-feather="copyright"></i> <span class="current-year"></span>.
                </div>
                <div class="footer-section f-section-2">
                    <p class="text-primary"><span class="current-detalle"></span></p>
                </div>
            </div>

        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="plugins/dropify/dropify.min.js"></script>
    <script src="plugins/blockui/jquery.blockUI.min.js"></script>
    <!-- <script src="plugins/tagInput/tags-input.js"></script> -->
    <script src="assets/js/users/account-settings.js"></script>
    
    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- script jquery -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

</body>
</html>