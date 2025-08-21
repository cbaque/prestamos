<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
    $reg = $tra->ProcesarPrestamosPendientes();
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
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/elements/alert.css">
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

<!--############################## MODAL PARA VER DETALLE DE PRESTAMO ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Detalle de Préstamo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x-circle"></i>
                </button>
            </div>

            <div class="modal-body"><!-- modal-body -->

            <div id="muestradetallemodal"></div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button class="btn" type="button" data-dismiss="modal"><i data-feather="x-circle"></i> Cerrar</button>
            </div>

        </div>
    </div>
</div>
<!--############################## MODAL PARA VER DETALLE DE PRESTAMO ######################################-->

<!--############################## MODAL PARA PROCESAR PRESTAMO PRENDIENTE ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalProcesarPrestamo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="file-text"></i> Gestión de Préstamo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x-circle"></i>
                </button>
            </div>

            <form class="form-material" novalidate method="post" action="#" name="saveprocesarprestamo" id="saveprocesarprestamo">

            <div class="modal-body"><!-- modal-body -->

            <div class="row">
                <div class="col-md-8">
                    <label class="control-label">Nombres de Cliente: <span class="symbol required"></span></label>
                    <div class="input-group">
                        <input style="color:#000;font-weight:bold;" class="form-control" type="text" name="search_cliente" id="search_cliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Búsqueda de Cliente" autocomplete="off" disabled="" required="" aria-required="true">
                        <input type="hidden" name="proceso" id="proceso" value="save">
                        <input type="hidden" name="codprestamo" id="codprestamo"/>
                        <input type="hidden" name="codcliente" id="codcliente"/>
                        <input type="hidden" name="nrodocumento" id="nrodocumento"/>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Monto de Préstamo: <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="montoprestamo" id="montoprestamo" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Monto de Préstamo" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Intereses (%): <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="intereses" id="intereses" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Intereses (%)" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Total Intereses (%): <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="totalintereses" id="totalintereses" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Total Intereses" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Importe Total: <span class="symbol required"></span></label>
                        <input type="hidden" name="totalpago" id="totalpago"/>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="totalpago2" id="totalpago2" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Importe Total" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Cuotas: <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="cuotas" id="cuotas" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Cuotas" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Monto x Cuotas: <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="montoxcuota" id="montoxcuota" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Monto x Cuotas" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Periodo de Pago: <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="periodopago" id="periodopago" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Periodo de Pago" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Fecha de Solicitud: <span class="symbol required"></span></label>
                        <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="fechaprestamo" id="fechaprestamo" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Fecha de Solicitud" autocomplete="off" disabled="" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Estado de Solicitud: <span class="symbol required"></span></label>
                        <select style="color:#000;font-weight:bold;" name="statusprestamo" id="statusprestamo" class="form-control" <?php if($_SESSION["acceso"] == "cajero"){ echo "disabled='disabled'"; } ?> required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <option style="color:#e2a03f;" value="1">&#9724; PENDIENTE</option>
                        <option style="color:#8dbf42;" value="2">&#9724; APROBADA</option>
                        <option style="color:#e7515a;" value="3">&#9724; RECHAZADA</option>
                        <option style="color:#5c1ac3;" value="4">&#9724; CANCELADA</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Pagar Préstamo: <span class="symbol required"></span></label>
                        <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
                            <input type="radio" class="new-control-input" name="statuspagado" id="statuspagado1" value="1">
                            <span class="new-control-indicator"></span>SI
                            </label>
                        </div>

                        <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
                            <input type="radio" class="new-control-input" name="statuspagado" id="statuspagado2" value="0" checked="checked">
                            <span class="new-control-indicator"></span>NO
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
                <button class="btn" type="button" data-dismiss="modal"><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA PROCESAR PRESTAMO PRENDIENTE ######################################-->

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
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Búsqueda de Préstamos Pendientes</h5>
                                    </div>                  
                                </div>
                            </div>

                        <div class="table-responsive mb-4 mt-4">
                            <?php if ($_SESSION["acceso"] == "administrador" || $_SESSION["acceso"] == "cajero") { ?>
                            <div class="btn-group">

                                <a class="btn waves-effect waves-light btn-primary" href="reportepdf?tipo=<?php echo encrypt("PRESTAMOSPENDIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Pdf</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRESTAMOSPENDIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><i data-feather="file-text"></i> Excel</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRESTAMOSPENDIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><i data-feather="file-text"></i> Word</a>
                               
                            </div>
                            <?php } ?>
                        </div>

                        <div id="prestamos"></div>
                               
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
    <script type="text/javascript" src="assets/script/VentanaCentrada.js"></script>
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
    $('#prestamos').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#prestamos').load("consultas?CargaPrestamosPendientes=si");
     }, 200);
    </script>
    <!-- jQuery Noty-->

</body>
</html>