<?php
require_once('class/class.php');
$accesos = ['administrador'];
validarAccesos($accesos) or die();

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
   $reg = $tra->RegistrarCajas();
   exit;
}
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
   $reg = $tra->ActualizarCajas();
   exit;
}  
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
    
    <!--  BEGIN CUSTOM STYLE FILE -->
    <link rel="stylesheet" type="text/css" href="plugins/dropify/dropify.min.css">
    <link href="assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link href="assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->

    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/custom_dt_html5.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL CUSTOM STYLES -->

</head>
<body onLoad="muestraReloj()" class="sidebar-noneoverflow">

<!--############################## MODAL PARA GESTION DE CAJAS ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="save"></i> Gestión de Cajas</h5>
            </div>

        <form class="form-material" novalidate method="post" action="#" name="savecaja" id="savecaja">

        <div class="modal-body"><!-- modal-body -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">Responsable de Caja: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="codigo" id="codigo" class='form-control' required="" aria-required="true">
                    <option value="">-- SELECCIONE --</option>
                    <?php
                    $usuario = new Login();
                    $usuario = $usuario->ListarUsuarios();
                    if($usuario==""){ 
                        echo "";
                    } else {
                    for($i=0;$i<sizeof($usuario);$i++){
                    ?>
                    <option value="<?php echo encrypt($usuario[$i]['codigo']); ?>"><?php echo $usuario[$i]['dni'].": ".$usuario[$i]['nombres']; ?></option>         
                    <?php } } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Caja: <span class="symbol required"></span></label>
                    <input type="hidden" name="proceso" id="proceso" value="save"/>
                    <input type="hidden" name="codcaja" id="codcaja">
                    <input type="text" class="form-control" name="nrocaja" id="nrocaja" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Caja" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Nombre de Caja: <span class="symbol required"></span></label>
                    <input type="text" class="form-control" name="nomcaja" id="nomcaja" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Caja" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>
        </div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
                <button class="btn" type="button" data-dismiss="modal" onclick="
                document.getElementById('proceso').value = 'save',
                document.getElementById('codcaja').value = '',
                document.getElementById('nrocaja').value = '',
                document.getElementById('nomcaja').value = '',
                document.getElementById('codigo').value = ''
                "><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA GESTION DE CAJAS ######################################-->

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

                <div id="save">
                <!-- error will be shown here ! -->
                </div>
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                        
                        <div class="widget-content widget-content-area br-6">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Cajas</h5>
                                    </div>                  
                                </div>
                            </div>

                        <div class="table mb-4 mt-4">
                            <div class="btn-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalCaja" title="Agregar" data-backdrop="static" data-keyboard="false"><i data-feather="monitor"></i> Agregar</button>

                                <a class="btn waves-effect waves-light btn-primary" href="reportepdf?tipo=<?php echo encrypt("CAJAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Pdf</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("CAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><i data-feather="file-text"></i> Excel</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("CAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><i data-feather="file-text"></i> Word</a>

                            </div>
                        </div>

                        <div id="cajas"></div>
                               
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
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    
    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>

    <!-- Sweet-Alert -->
    <script src="assets/js/sweetalert-dev.js"></script>
    <!-- Sweet-Alert -->    

    <!-- Custom file upload -->
    <script src="assets/fileupload/bootstrap-fileupload.min.js"></script>

    <!-- script jquery -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- Calendario -->
    <link rel="stylesheet" href="assets/calendario/jquery-ui.css" />
    <script src="assets/calendario/jquery-ui.js"></script>
    <script src="assets/script/jscalendario.js"></script>
    <script src="assets/script/autocompleto.js"></script>
    <!-- Calendario -->

    <!-- jQuery Noty-->
    <script src="plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="plugins/noty/themes/relax.js"></script>
    <script type="text/jscript">
    $('#cajas').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#cajas').load("consultas?CargaCajas=si");
     }, 200);
    </script>
    <!-- jQuery Noty-->

</body>
</html>