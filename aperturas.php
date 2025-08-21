<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero'];
validarAccesos($accesos) or die();

$con     = new Login();
$con     = $con->ConfiguracionPorId();
$simbolo = $con[0]['simbolo'] ?? $con[0]['simbolo']; 

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
   $reg = $tra->RegistrarAperturas();
   exit;
}
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
   $reg = $tra->CerrarCaja();
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
<body onLoad="muestraReloj(); getTime()" class="sidebar-noneoverflow">

<!--############################## MODAL PARA VER DETALLE DE APERTURAS ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Detalle de Apertura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="text-white" data-feather="x-circle"></i>
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
<!--############################## MODAL PARA VER DETALLE DE APERTURAS ######################################-->


<!--############################## MODAL PARA REGISTRO DE APERTURA ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalApertura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="save"></i> Gestión de Apertura</h5>
            </div>

        <form class="form-material" novalidate method="post" action="#" name="saveapertura" id="saveapertura">

        <div class="modal-body"><!-- modal-body -->
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">Seleccione Caja: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="codcaja" id="codcaja" class='form-control' required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $caja = new Login();
                    $caja = $caja->ListarCajas();
                    if($caja==""){ 
                        echo "";
                    } else {
                    for($i=0;$i<sizeof($caja);$i++){
                    ?>
                    <option value="<?php echo encrypt($caja[$i]['codcaja']); ?>"><?php echo $caja[$i]['nrocaja'].": ".$caja[$i]['nomcaja']." - ".$caja[$i]['nombres']; ?></option>         
                    <?php } } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Hora de Apertura: <span class="symbol required"></span></label>
                    <input type="hidden" name="proceso" id="proceso" value="save"/>
                    <input type="hidden" name="codapertura" id="codapertura">
                    <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="fecharegistro" id="fecharegistro" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Hora de Apertura" autocomplete="off" readonly="" aria-required="true"/> 
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Monto Inicial: <span class="symbol required"></span></label>
                    <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="montoinicial" id="montoinicial" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Monto Inicial" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>
        </div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
                <button class="btn" type="button" data-dismiss="modal" onclick="
                document.getElementById('proceso').value = 'save',
                document.getElementById('codsucursal').value = '',
                document.getElementById('codcaja').value = '',
                document.getElementById('codarqueo').value = '',
                document.getElementById('fecharegistro').value = '',
                document.getElementById('montoinicial').value = ''
                "><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA REGISTRO DE APERTURA ######################################-->

<!--############################## MODAL PARA REGISTRO DE CIERRE ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalCierre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Gestión de Cierre</h5>
            </div>

        <form class="form-material" novalidate method="post" action="#" name="cerrarapertura" id="cerrarapertura">

        <div class="modal-body"><!-- modal-body -->

        <h4 class="card-subtitle m-0 text-dark"><i data-feather="airplay"></i> Cajero</h4><hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Caja: <span class="symbol required"></span></label>
                    <input type="hidden" name="proceso" id="proceso" value="update"/>
                    <input type="hidden" name="codapertura" id="codapertura"/>
                    <br /><abbr class="text-dark alert-link" title="Nº de Caja"><label id="txtcaja"></label></abbr>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Nombre de Cajero: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Nombre de Cajero"><label id="txtnombre"></label></abbr>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Monto Apertura: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Monto Apertura"><?php echo $simbolo; ?><label id="txtmonto"></label></abbr>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Fecha Apertura: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Fecha Apertura"><label id="txtapertura"></label></abbr>
                </div>
            </div>
        </div>

        <hr><h4 class="card-subtitle m-0 text-dark"><i data-feather="file-text"></i> Préstamos</h4><hr>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">Préstamos: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Préstamos"><?php echo $simbolo; ?><label id="prestamos"></label></abbr>
                </div>
            </div>
        </div>

        <hr><h4 class="card-subtitle m-0 text-dark"><i data-feather="shopping-cart"></i> Detalles de Cobros</h4><hr>

        <div id="muestra_detalles_apertura"></div>
        

        <hr><h4 class="card-subtitle m-0 text-dark"><i data-feather="file-text"></i> Movimientos</h4><hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Ingresos: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Ingresos Efectivo"><?php echo $simbolo; ?><label id="ingresos"></label></abbr>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label class="control-label">Egresos: <span class="symbol required"></span></label>
                    <br /><abbr class="text-dark alert-link" title="Egresos"><?php echo $simbolo; ?><label id="egresos"></label></abbr>
                </div>
            </div>
        </div>

        <hr><h4 class="card-subtitle m-0 text-dark"><i data-feather="bold"></i> Balance en Caja</h4><hr>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">Hora de Cierre: <span class="symbol required"></span></label>
                    <input type="hidden" name="fecharegistro2" id="fecharegistro2"/>
                    <br /><abbr class="text-dark alert-link" title="Hora de Cierre"><label id="fechacierre"></label></abbr>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">Estimado en Caja: <span class="symbol required"></span></label>
                    <input type="hidden" name="efectivocaja" id="efectivocaja"/>
                    <br /><abbr class="text-dark alert-link" title="Estimado en Caja"><?php echo $simbolo; ?><label id="efectivocaja"></label></abbr>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">Efectivo Disponible: <span class="symbol required"></span></label>
                    <input type="hidden" name="diferencia" id="diferencia" value="0.00"/>
                    <input type="text" class="form-control cierrecaja" name="dineroefectivo" id="dineroefectivo" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Efectivo" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">Observaciones: </label>
                    <textarea type="text" class="form-control" name="comentarios" id="comentarios" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Observaciones de Cierre" rows="1" autocomplete="off" required="" aria-required="true"></textarea>
                </div>
            </div>
        </div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-update" id="btn-update" class="btn btn-primary"><i data-feather="edit-2"></i> Cerrar</button>
                <button class="btn" type="reset" data-dismiss="modal"><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA REGISTRO DE CIERRE ######################################-->

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
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Búsqueda de Aperturas</h5>
                                    </div>                  
                                </div>
                            </div>

                        <div class="table mb-4 mt-4">
                            <div class="btn-group">
                                <?php if ($_SESSION["acceso"] == "administrador") { ?>
                                <button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalApertura" title="Agregar" data-backdrop="static" data-keyboard="false"><i data-feather="monitor"></i> Agregar</button>
                                <?php } elseif ($_SESSION["acceso"] == "cajero") { ?>
                                <button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalApertura" title="Agregar" data-backdrop="static" data-keyboard="false" onclick="VerificaCaja();"><i data-feather="monitor"></i> Agregar</button>
                                <?php } ?>

                                <a class="btn waves-effect waves-light btn-primary" href="reportepdf?tipo=<?php echo encrypt("APERTURAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Pdf</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("APERTURAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><i data-feather="file-text"></i> Excel</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("APERTURAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><i data-feather="file-text"></i> Word</a>

                            </div>
                        </div>

                        <div id="aperturas"></div>
                               
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
    <?php if ($_SESSION['acceso'] == "administradorG"){ ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $(document).keypress(function(e) {        
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
            $("#BotonBusqueda").trigger("click");
            return false;
            }
        });                    
    }); 
    </script>
    <?php } else { ?>
    <script type="text/jscript">
    $('#aperturas').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#aperturas').load("consultas?CargaAperturas=si");
     }, 200);
    </script>
    <?php } ?>
    <!-- jQuery Noty-->

</body>
</html>