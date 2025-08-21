<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor'];
validarAccesos($accesos) or die();

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="cargar")
{
    $reg = $tra->CargarClientes();
    exit;
} 
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
    $reg = $tra->RegistrarClientes();
    exit;
}
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
    $reg = $tra->ActualizarClientes();
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

<!--############################## MODAL PARA CARGA MASIVA DE CLIENTE ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalCargaMasiva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="upload-cloud"></i> Carga Clientes</h5>
                <button type="button" onClick="ModalCliente()" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="text-white" data-feather="x-circle"></i>
                </button>
            </div>

            <form class="form form-material" name="cargaclientes" id="cargaclientes" action="#" enctype="multipart/form-data">

            <div class="modal-body"><!-- modal-body -->

            <div class="row">
                <div class="col-md-12"> 
                    <div class="form-group has-feedback">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="form-group has-feedback"> 
                    <label class="control-label">Realice la Búsqueda del Archivo (CSV): <span class="symbol required"></span></label>
                    <div class="input-group">
                    <div class="form-control" data-trigger="fileinput"><i data-feather="file-text"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <input type="hidden" name="proceso" value="cargar"/>
                    <span class="input-group-addon btn btn-success btn-file">
                    <span class="fileinput-new"><i data-feather="file-text"></i> Selecciona Archivo</span>
                    <span class="fileinput-exists"><i data-feather="file-text"></i> Cambiar</span>
                    <input type="file" class="btn btn-default" data-original-title="Suba su Archivo CSV" data-rel="tooltip" placeholder="Suba su Imagen" name="sel_file" id="sel_file" autocomplete="off" required="" aria-required="true">
                    </span>
                    <a href="#" class="input-group-addon btn btn-dark fileinput-exists" data-dismiss="fileinput"><i data-feather="x-octagon"></i> Quitar</a>
                            </div><small><p>Para realizar la Carga masiva de Clientes el archivo debe de ser extensión (CSV Delimitado por Comas). Debe de llevar la cantidad de filas y columnas explicadas para la Carga exitosa de los registros.<br></small>
                            <div id="divcliente"></div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div> 

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="button" onClick="CargaDivCliente()" class="btn btn-success"><i data-feather="eye"></i> Ver Detalles</button>
                <button type="submit" name="btn-cliente" id="btn-cliente" class="btn btn-primary"><i data-feather="upload-cloud"></i> Cargar</button>
                <button class="btn" type="button" onClick="ModalCliente()" data-dismiss="modal"><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA CARGA MASIVA DE CLIENTE ######################################-->

<!--############################## MODAL PARA VER DETALLE DE CLIENTE ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Detalle de Cliente</h5>
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
<!--############################## MODAL PARA VER DETALLE DE CLIENTE ######################################-->


<!--############################## MODAL PARA GESTION DE CLIENTES ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="save"></i> Gestión de Clientes</h5>
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
                        <option value="<?php echo encrypt($documento[$i]['coddocumento']); ?>"><?php echo $documento[$i]['documento'] ?></option>
                    <?php } } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">Nº de Documento: <span class="symbol required"></span></label>
                    <input type="hidden" name="proceso" id="proceso" value="save"/>
                    <input type="hidden" name="formulario" id="formulario" value="clientes"/>
                    <input type="hidden" name="codcliente" id="codcliente">
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
            document.getElementById('proceso').value = 'save',
            document.getElementById('codcliente').value = '',
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
<!--############################## MODAL PARA GESTION DE CLIENTES ######################################-->

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
                                    <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Clientes</h5>
                                </div>                  
                            </div>
                        </div>

                        <div class="table mb-4 mt-4">
                            <div class="btn-group">
                                <?php if ($_SESSION['acceso'] == "administrador") { ?><button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalCargaMasiva" title="Carga Masiva"><i data-feather="upload-cloud"></i> Carga Masiva</button><?php } ?>

                                <button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalCliente" title="Agregar" data-backdrop="static" data-keyboard="false"><i data-feather="user-plus"></i> Agregar</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Reportes"><i data-feather="folder-plus"></i> Reportes</button>
                                    <div class="dropdown-menu dropdown-menu-left" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(164px, 35px, 0px);">
                                        <a class="dropdown-item" href="reportepdf?tipo=<?php echo encrypt("CLIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Pdf</a>

                                        <a class="dropdown-item" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("CLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Excel</a>

                                        <a class="dropdown-item" href="reporteexcel?documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("CLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Word</a>

                                        <a class="dropdown-item" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("CLIENTESCSV") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><i data-feather="file-text"></i> CSV</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div id="clientes"></div>

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
    $('#clientes').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#clientes').load("consultas?CargaClientes=si");
     }, 200);
    </script>
    <!-- jQuery Noty-->

</body>
</html>