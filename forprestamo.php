<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor'];
validarAccesos($accesos) or die();

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
    $reg = $tra->RegistrarPrestamos();
    exit;
}
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
    $reg = $tra->ActualizarPrestamos();
    exit;
} 
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="nuevocliente")
{
    $reg = $tra->RegistrarClientes();
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

    <!-- Calendario CSS -->
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <!-- Calendario CSS -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/elements/alert.css">
    <link href="assets/css/elements/search.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="plugins/dropify/dropify.min.css">
    <link href="assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link href="assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->

    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/custom_dt_html5.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL CUSTOM STYLES -->

</head>
<body onLoad="muestraReloj(); getTime()" class="sidebar-noneoverflow">

<!--############################## MODAL PARA REGISTRO DE NUEVO CLIENTE ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalCliente" tabindex="-1" role="dialog" style="overflow-y: scroll;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Gestión de Clientes</h5>
            </div>

            <form class="form-material" novalidate method="post" action="#" name="savecliente" id="savecliente" enctype="multipart/form-data">

            <div class="modal-body"><!-- modal-body -->

            <h5 class="card-subtitle text-dark"><i data-feather="user"></i> Datos de Cliente</h5><hr> 
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Tipo de Documento: <span class="symbol required"></span></label>
                        <select style="color:#000;font-weight:bold;" name="documcliente" id="documcliente" class='form-control' required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <?php
                        $documento = new Login();
                        $documento = $documento->ListarDocumentos();
                        if($documento==""){
                            echo "";    
                        } else {
                        for($i=0;$i<sizeof($documento);$i++){ ?>
                            <option value="<?php echo $documento[$i]['coddocumento'] ?>"><?php echo $documento[$i]['documento'] ?></option>
                        <?php } } ?>
                        </select>
                    </div>
                </div>

                 <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Documento: <span class="symbol required"></span></label>
                        <input type="hidden" name="proceso" id="proceso" value="nuevocliente"/>
                        <input type="hidden" name="formulario" id="formulario" value="forprestamo"/>
                        <input type="text" class="form-control" name="cedcliente" id="cedcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Documento" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nombre de Cliente: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="nomcliente" id="nomcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Cliente" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Genero: <span class="symbol required"></span></label>
                        <select style="color:#000;font-weight:bold;" name="sexocliente" id="sexocliente" class='form-control' required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <option value="MASCULINO"> MASCULINO </option>
                        <option value="FEMENINO"> FEMENINO </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Teléfono: </label>
                        <input type="text" class="form-control phone-inputmask" name="tlfcliente" id="tlfcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Teléfono" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Celular: <span class="symbol required"></span></label>
                        <input type="text" class="form-control phone-inputmask" name="celcliente" id="celcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Celular" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Provincia: </label>
                        <select style="color:#000;font-weight:bold;" class="form-control" id="codprovincia" name="codprovincia" onChange="CargaDepartamentos(this.form.codprovincia.value);" required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <?php
                        $provincia = new Login();
                        $provincia = $provincia->ListarProvincias();
                        if($provincia==""){
                            echo "";    
                        } else {
                        for($i=0;$i<sizeof($provincia);$i++){ ?>
                        <option value="<?php echo encrypt($provincia[$i]['codprovincia']); ?>"><?php echo $provincia[$i]['provincia']; ?></option>
                        <?php } } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Departamento: </label>
                        <select style="color:#000;font-weight:bold;" class="form-control" id="coddepartamento" name="coddepartamento" required="" aria-required="true">
                        <option value=""> -- SIN RESULTADOS -- </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Dirección Domiciliaria: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="direccliente" id="direccliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Dirección Domiciliaria" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Correo Electrónico: </label>
                        <input type="text" class="form-control" name="correocliente" id="correocliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Correo Electrónico" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-8"> 
                    <div class="form-group has-feedback">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="form-group has-feedback"> 
                                <label class="control-label">Realice la Búsqueda de Imagen: </label>
                                <div class="input-group">
                                <div class="form-control" data-trigger="fileinput"><i data-feather="image"></i>
                                <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-success btn-file">
                                <span class="fileinput-new"><i data-feather="image"></i> Selecciona Imagen</span>
                                <span class="fileinput-exists"><i data-feather="image"></i> Cambiar</span>
                                <input type="file" class="btn btn-default" data-original-title="Subir Imagen" data-rel="tooltip" placeholder="Suba su Imagen" name="imagen" id="imagen" autocomplete="off" title="Buscar Archivo">
                                </span>
                                <a href="#" class="input-group-addon btn btn-dark fileinput-exists" data-dismiss="fileinput"><i data-feather="x-octagon"></i> Quitar</a>
                                </div><small><p>Para Subir su Foto debe tener en cuenta:<br> * La Imagen debe ser extension.jpg<br> * La imagen no debe ser mayor de 1 MB</p></small>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>

            <h5 class="card-subtitle text-dark"><i data-feather="users"></i> Información de Referencia</h5><hr> 
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Documento: </label>
                        <input type="text" class="form-control" name="cedreferencia" id="cedreferencia" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Documento" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nombres y Apellidos: </label>
                        <input type="text" class="form-control" name="nomreferencia" id="nomreferencia" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombres y Apellidos" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Celular: </label>
                        <input type="text" class="form-control phone-inputmask" name="celreferencia" id="celreferencia" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Celular" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-cliente" id="btn-cliente" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
                <button class="btn" type="button" data-dismiss="modal" onclick="
                document.getElementById('documcliente').value = '',
                document.getElementById('cedcliente').value = '',
                document.getElementById('nomcliente').value = '',
                document.getElementById('sexocliente').value = '',
                document.getElementById('tlfcliente').value = '',
                document.getElementById('celcliente').value = '',
                document.getElementById('codprovincia').value = '',
                document.getElementById('coddepartamento').value = '',
                document.getElementById('direccliente').value = '',
                document.getElementById('correocliente').value = '',
                document.getElementById('cedreferencia').value = '',
                document.getElementById('nomreferencia').value = '',
                document.getElementById('celreferencia').value = '',
                document.getElementById('imagen').value = ''
                "><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA REGISTRO DE NUEVO CLIENTE ######################################-->

    
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
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="save"></i> Gestión de Préstamos</h5>
                                    </div>                 
                                </div>
                            </div>
        
    <?php  if (isset($_GET['codprestamo'])) {
      
    $reg = $tra->PrestamosPorId(); ?>
      
    <form class="form-material" novalidate method="post" action="#" name="updateprestamo" id="updateprestamo" data-id="<?php echo $reg[0]["codventa"] ?>" enctype="multipart/form-data">
        
    <?php } else { ?>

    <form class="form-material" novalidate method="post" action="#" name="saveprestamo" id="saveprestamo" enctype="multipart/form-data">
              
    <?php } ?>
            
    <div id="save">
    <!-- error will be shown here ! -->
    </div>

    <hr><h5 class="card-subtitle text-dark"><i data-feather="search"></i> Búsqueda de Cliente</h5><hr>

    <div class="row">
        <div class="col-md-12">
            <label class="control-label">Ingrese Criterio para Búsqueda de Cliente: <span class="symbol required"></span></label>
            <div class="input-group">
                <input style="color:#000;font-weight:bold;" class="form-control" type="text" name="search_cliente_activo" id="search_cliente_activo" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Búsqueda de Cliente" autocomplete="off" required="" aria-required="true">
                <input type="hidden" name="proceso" id="proceso" <?php if (isset($_GET['codprestamo'])) { ?> value="update" <?php } else { ?> value="save" <?php } ?>>
                <input type="hidden" name="codprestamo" id="codprestamo" <?php if (isset($reg[0]['codprestamo'])) { ?> value="<?php echo encrypt($reg[0]['codprestamo']); ?>"<?php } ?>>
                <input type="hidden" name="codcliente" id="codcliente"/>
                <input type="hidden" name="nrodocumento" id="nrodocumento"/>
                <div class="input-group-append">
                    <div class="btn-group" data-bs-toggle="buttons">
                        <button type="button" class="btn btn-success waves-effect waves-light" data-placement="left" title="Nuevo Cliente" data-original-title="" data-href="#" data-toggle="modal" data-target="#myModalCliente" data-backdrop="static" data-keyboard="false"><i data-feather="user-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr><h5 class="card-subtitle text-dark"><i data-feather="file-text"></i> Detalles de Préstamo</h5><hr>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Monto de Préstamo: <span class="symbol required"></span></label>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="montoprestamo" id="montoprestamo" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Monto de Préstamo" autocomplete="off" required="" aria-required="true"/>  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Intereses (%): <span class="symbol required"></span></label>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="intereses" id="intereses" onKeyUp="this.value=this.value.toUpperCase(); CalculoPrestamo(); MuestraDetallesPago(this.form.cuotas.value,this.form.totalpago.value,this.form.periodopago.value,this.form.fechapagocuota.value);" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Intereses (%)" value="0.00" autocomplete="off" required="" aria-required="true"/>  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Nº de Cuotas: <span class="symbol required"></span></label>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control number" name="cuotas" id="cuotas" onKeyUp="this.value=this.value.toUpperCase(); CalculoPrestamo(); MuestraDetallesPago(this.form.cuotas.value,this.form.totalpago.value,this.form.periodopago.value,this.form.fechapagocuota.value);" onKeyPress="EvaluateText('%f', this);" placeholder="Ingrese Nº de Cuotas" autocomplete="off" required="" aria-required="true"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Periodo de Pago: <span class="symbol required"></span></label>
                <select style="color:#000;font-weight:bold;" name="periodopago" id="periodopago" class="form-control" required="" aria-required="true">
                <option value=""> -- SELECCIONE -- </option>
                <option value="DIARIO">DIARIO</option>
                <option value="SEMANAL">SEMANAL</option>
                <option value="QUINCENAL">QUINCENAL</option>
                <option value="MENSUAL">MENSUAL</option>
                <option value="BIMESTRAL">BIMESTRAL</option>
                <option value="SEMESTRAL">SEMESTRAL</option>
                <option value="ANUAL">ANUAL</option>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Fecha de Pago: <span class="symbol required"></span></label>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="fechapagocuota" id="fechapagocuota" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Fecha Pago de Cuota" onChange="MuestraDetallesPago(this.form.cuotas.value,this.form.totalpago.value,this.form.periodopago.value,this.form.fechapagocuota.value);" autocomplete="off" aria-required="true"/>  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Monto por Cuota: <span class="symbol required"></span></label>
                <input type="hidden" name="montoxcuota" id="montoxcuota" value="0.00"/>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="montoxcuota2" id="montoxcuota2" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Monto por Cuota" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Total Intereses: <span class="symbol required"></span></label>
                <input type="hidden" name="totalintereses" id="totalintereses" value="0.00"/>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="totalintereses2" id="totalintereses2" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Total Intereses" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Total a Pagar: <span class="symbol required"></span></label>
                <input type="hidden" name="totalpago" id="totalpago" value="0.00"/>
                <input style="color:#000;font-weight:bold;" type="text" class="form-control" name="totalpago2" id="totalpago2" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Total a Pagar" value="0.00" autocomplete="off" disabled="" required="" aria-required="true"/>  
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label class="control-label">Observaciones: </label>
                <textarea class="form-control" type="text" name="observaciones" id="observaciones" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Observaciones" autocomplete="off" rows="1" required="" aria-required="true"></textarea> 
            </div>
        </div>
    </div>

    <div id="muestra_detalles"></div>

            <div class="text-right">

    <?php if (isset($_GET['codprestamo'])) { ?>
<button type="submit" name="btn-update" id="btn-update" class="btn btn-primary"><i data-feather="edit-2"></i> Actualizar</button>
<button class="btn btn-dark" type="reset"><i data-feather="x-circle"></i> Cancelar</button>
    <?php } else { ?>
<button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
<button class="btn btn-dark" type="button" onclick="ResetPrestamo();"><i data-feather="trash-2"></i> Limpiar</button>
    <?php } ?>          
            </div>

                                </form>
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
    <script src="assets/script/jquery.min.js"></script>
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
    <!-- Custom file upload -->

    <!-- script jquery 
    <script src="assets/script/jquery.min.js"></script> -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/VentanaCentrada.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- Calendario -->
    <link rel="stylesheet" href="assets/calendario/jquery-ui.css" />
    <link href="plugins/autocomplete/autocomplete.css" rel="stylesheet" type="text/css" />
    <script src="assets/calendario/jquery-ui.js"></script>
    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="assets/script/jscalendario.js"></script>
    <script src="assets/script/autocompleto.js"></script>
    <!-- Calendario -->

    <!-- jQuery Noty-->
    <script src="plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="plugins/noty/themes/relax.js"></script>
    <!-- jQuery Noty-->

</body>
</html>