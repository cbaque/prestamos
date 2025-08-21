<?php
require_once('class/class.php');
$accesos = ['administrador'];
validarAccesos($accesos) or die();

$tra = new Login();
$reg = $tra->ConfiguracionPorId();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
   $reg = $tra->ActualizarConfiguracion();
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
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="edit-2"></i> Configuración General</h5>
                                    </div>                 
                                </div>
                            </div>

    <div class="widget-content widget-content-area">                                
        <form class="form-material" novalidate method="post" action="#" name="configuracion" id="configuracion" enctype="multipart/form-data">
            
        <div id="save">
           <!-- error will be shown here ! -->
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Tipo de Documento: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="documsucursal" id="documsucursal" class='form-control' required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $doc = new Login();
                    $doc = $doc->ListarDocumentos();
                    if($doc==""){
                        echo "";    
                    } else {
                    for($i=0;$i<sizeof($doc);$i++){ ?>
                    <option value="<?php echo encrypt($doc[$i]['coddocumento']); ?>"<?php if (!(strcmp($reg[0]['documsucursal'], htmlentities($doc[$i]['coddocumento'])))) { echo "selected=\"selected\""; } ?>><?php echo $doc[$i]['documento'] ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Registro: <span class="symbol required"></span></label>
                    <input type="hidden" name="proceso" id="proceso" value="update"/>
                    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo encrypt($reg[0]['id']); ?>"/>
                    <input type="text" class="form-control" name="cuitsucursal" id="cuitsucursal" value="<?php echo $reg[0]['cuitsucursal']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Sucursal" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Razón Social: <span class="symbol required"></span></label>
                    <input type="text" class="form-control" name="nomsucursal" id="nomsucursal" value="<?php echo $reg[0]['nomsucursal']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Razón Social" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Teléfono: <span class="symbol required"></span></label>
                    <input type="text" class="form-control phone-inputmask" name="tlfsucursal" id="tlfsucursal" value="<?php echo $reg[0]['tlfsucursal']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Teléfono" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Correo Electrónico: <span class="symbol required"></span></label>
                    <input type="text" class="form-control" name="correosucursal" id="correosucursal" value="<?php echo $reg[0]['correosucursal']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Correo Electrónico" autocomplete="off" required="" aria-required="true"/> 
                </div>
            </div>

           <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Provincia: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="codprovincia" id="codprovincia" onChange="CargaDepartamentos(this.form.codprovincia.value);" class='form-control' required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $provincia = new Login();
                    $provincia = $provincia->ListarProvincias();
                    if($provincia==""){
                        echo "";    
                    } else {
                    for($i=0;$i<sizeof($provincia);$i++){ ?>
                    <option value="<?php echo encrypt($provincia[$i]['codprovincia']); ?>"<?php if (!(strcmp($reg[0]['codprovincia'], htmlentities($provincia[$i]['codprovincia'])))) { echo "selected=\"selected\""; } ?>><?php echo $provincia[$i]['provincia']; ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Departamento: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" class="form-control" id="coddepartamento" name="coddepartamento" required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $departamento = new Login();
                    $departamento = $departamento->ListarDepartamentosAsignados($reg[0]['codprovincia']);
                    if($departamento==""){
                        echo "";    
                    } else {
                    for($i=0;$i<sizeof($departamento);$i++){ ?>
                    <option value="<?php echo encrypt($departamento[$i]['coddepartamento']); ?>"<?php if (!(strcmp($reg[0]['coddepartamento'], htmlentities($departamento[$i]['coddepartamento'])))) { echo "selected=\"selected\""; } ?>><?php echo $departamento[$i]['departamento']; ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Dirección de Sucursal: <span class="symbol required"></span></label>
                    <input type="text" class="form-control" name="direcsucursal" id="direcsucursal" value="<?php echo $reg[0]['direcsucursal']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Dirección de Sucursal" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Tipo de Documento: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="documencargado" id="documencargado" class='form-control' required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $doc = new Login();
                    $doc = $doc->ListarDocumentos();
                    if($doc==""){
                        echo "";    
                    } else {
                    for($i=0;$i<sizeof($doc);$i++){ ?>
                    <option value="<?php echo encrypt($doc[$i]['coddocumento']); ?>"<?php if (!(strcmp($reg[0]['documencargado'], htmlentities($doc[$i]['coddocumento'])))) { echo "selected=\"selected\""; } ?>><?php echo $doc[$i]['documento'] ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                   <label class="control-label">Nº de Doc. de Encargado: <span class="symbol required"></span></label>
                   <input type="text" class="form-control" name="dniencargado" id="dniencargado" value="<?php echo $reg[0]['dniencargado']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº Documento de Encargado" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Nombre de Encargado: <span class="symbol required"></span></label>
                    <input type="text" class="form-control" name="nomencargado" id="nomencargado" value="<?php echo $reg[0]['nomencargado']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Encargado" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Teléfono: <span class="symbol required"></span></label>
                    <input type="text" class="form-control phone-inputmask" name="tlfencargado" id="tlfencargado" value="<?php echo $reg[0]['tlfencargado']; ?>" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Teléfono Encargado" autocomplete="off" required="" aria-required="true"/>  
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label class="control-label">Tipo de Moneda: <span class="symbol required"></span></label>
                    <select style="color:#000;font-weight:bold;" name="codmoneda" id="codmoneda" class='form-control' required="" aria-required="true">
                    <option value=""> -- SELECCIONE -- </option>
                    <?php
                    $moneda = new Login();
                    $moneda = $moneda->ListarTipoMoneda();
                    if($moneda==""){
                        echo "";    
                    } else {
                    for($i=0;$i<sizeof($moneda);$i++){ ?>
                    <option value="<?php echo encrypt($moneda[$i]['codmoneda']); ?>"<?php if (!(strcmp($reg[0]['codmoneda'], htmlentities($moneda[$i]['codmoneda'])))) { echo "selected=\"selected\""; } ?>><?php echo $moneda[$i]['moneda']; ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="upload mt-4 pr-md-4">
                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="<?php if (file_exists("fotos/logo_principal.png")){
                        echo "fotos/logo_principal.png"; 
                    } ?>" data-max-file-size="1M" name="imagen" id="imagen"/>
                    <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Logo Principal</p>
                    <small><p>Para Subir la Imagen debe tener en cuenta:<br> * La Imagen debe ser extension.png<br> * La imagen no debe ser mayor de 1 MB</p></small>
                </div>
            </div>

            <div class="col-md-3">
                <div class="upload mt-4 pr-md-4">
                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="<?php if (file_exists("fotos/logo_pdf.png")){
                        echo "fotos/logo_pdf.png?".time(); 
                    } ?>" data-max-file-size="1M" name="imagen2" id="imagen2"/>
                    <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Logo PDF</p>
                    <small><p>Para Subir la Imagen debe tener en cuenta:<br> * La Imagen debe ser extension.png<br> * La imagen no debe ser mayor de 1 MB</p></small>
                </div>
            </div>
        </div>

                                    <div class="text-right">
        <button type="submit" name="btn-update" id="btn-update" class="btn btn-primary"><i data-feather="edit-2"></i> Actualizar</button>
        <button class="btn btn-dark" type="reset"><i data-feather="x-circle"></i> Cancelar</button>
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
    <script src="assets/js/users/account-settings.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    
    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>

    <!-- script jquery 
    <script src="assets/script/jquery.min.js"></script> -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- jQuery Noty-->
    <script src="plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="plugins/noty/themes/relax.js"></script>
    <!-- jQuery Noty-->

</body>
</html>