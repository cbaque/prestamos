<?php
require_once('class/class.php');
$accesos = ['administrador'];
validarAccesos($accesos) or die();

$tra = new Login(); 

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
   $reg = $tra->RegistrarUsuarios();
   exit;
}
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
   $reg = $tra->ActualizarUsuarios();
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

    <!-- timepicker CSS -->
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <!-- timepicker CSS -->
    
    <!--  BEGIN CUSTOM STYLE FILE -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
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

<!--############################## MODAL PARA VER DETALLE DE USUARIO ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Detalle de Usuario</h5>
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
<!--############################## MODAL PARA VER DETALLE DE USUARIO ######################################-->


<!--############################## MODAL PARA GESTION DE USUARIOS ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="save"></i> Gestión de Usuarios</h5>
            </div>

            <form class="form-material" novalidate method="post" action="#" name="saveusuario" id="saveusuario" enctype="multipart/form-data">

            <div class="modal-body"><!-- modal-body -->

            <h5 class="card-subtitle text-dark"><i data-feather="user"></i> Datos Personales</h5><hr> 
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Tipo de Documento: </label>
                        <select style="color:#000;font-weight:bold;" name="documusuario" id="documusuario" class='form-control' required="" aria-required="true">
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
                        <input type="hidden" name="codigo" id="codigo">
                        <input type="text" class="form-control" name="dni" id="dni" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Documento" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nombre y Apellidos: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="nombres" id="nombres" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre y Apellidos" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Genero: <span class="symbol required"></span></label>
                        <select style="color:#000;font-weight:bold;" name="sexo" id="sexo" class='form-control' required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <option value="MASCULINO"> MASCULINO </option>
                        <option value="FEMENINO"> FEMENINO </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Teléfono: </label>
                        <input type="text" class="form-control phone-inputmask" name="telefono" id="telefono" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Teléfono" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nº de Celular: <span class="symbol required"></span></label>
                        <input type="text" class="form-control phone-inputmask" name="celular" id="celular" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Celular" autocomplete="off" required="" aria-required="true"/>  
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
                        <input type="text" class="form-control" name="direccion" id="direccion" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Dirección Domiciliaria" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Correo Electrónico: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="email" id="email" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Correo Electrónico" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Fecha de Nacimiento: </label>
                        <input style="color:#000;font-weight:bold;width:140%;" type="text" class="form-control fnacimiento" name="fnacimiento" id="fnacimiento" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Fecha de Nacimiento" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4"> 
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

            <hr><h5 class="card-subtitle text-dark"><i data-feather="unlock"></i> Datos de Acceso</h5><hr>

            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Usuario de Acceso: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="usuario" id="usuario" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Usuario de Acceso" autocomplete="off" required="" aria-required="true"/>  
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Password de Acceso: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="password" id="password" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Password de Acceso" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Repita Password: <span class="symbol required"></span></label>
                        <input type="text" class="form-control" name="password2" id="password2" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Password de Acceso" autocomplete="off" required="" aria-required="true"/> 
                    </div>
                </div>
            </div>

            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Nivel de Acceso: <span class="symbol required"></span></label>
                        <select style="color:#000;font-weight:bold;" name="nivel" id="nivel" class="form-control" required="" aria-required="true">
                        <option value=""> -- SELECCIONE -- </option>
                        <option value="ADMINISTRADOR(A)">ADMINISTRADOR(A)</option>
                        <option value="CAJERO(A)">CAJERO(A)</option>
                        <option value="VENDEDOR(A)">VENDEDOR(A)</option>
                        </select>         
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label class="control-label">Estado de Acceso: <span class="symbol required"></span></label>
                        <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
                            <input type="radio" class="new-control-input" name="status" id="estado1" value="1" checked="checked">
                            <span class="new-control-indicator"></span>ACTIVO
                            </label>
                        </div>

                        <div class="n-chk">
                            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
                            <input type="radio" class="new-control-input" name="status" id="estado2" value="0">
                            <span class="new-control-indicator"></span>INACTIVO
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            </div><!-- modal-body -->

            <div class="modal-footer">
                <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><i data-feather="save"></i> Guardar</button>
                <button class="btn" type="button" data-dismiss="modal" onclick="
                document.getElementById('proceso').value = 'save',
                document.getElementById('codigo').value = '',
                document.getElementById('documusuario').value = '',
                document.getElementById('dni').value = '',
                document.getElementById('nombres').value = '',
                document.getElementById('sexo').value = '',
                document.getElementById('telefono').value = '',
                document.getElementById('celular').value = '',
                document.getElementById('codprovincia').value = '',
                document.getElementById('coddepartamento').value = '',
                document.getElementById('direccion').value = '',
                document.getElementById('email').value = '',
                document.getElementById('fnacimiento').value = '',
                document.getElementById('usuario').value = '',
                document.getElementById('password').value = '',
                document.getElementById('nivel').value = '',
                document.getElementById('status').value = '',
                document.getElementById('imagen').value = ''
                "><i data-feather="x-circle"></i> Cerrar</button>
            </div>

            </form>

        </div>
    </div>
</div>
<!--############################## MODAL PARA GESTION DE USUARIOS ######################################-->

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
                                        <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Usuarios</h5>
                                    </div>                  
                                </div>
                            </div>

                        <div class="table mb-4 mt-4">
                            <div class="btn-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" data-toggle="modal" data-target="#myModalUsuario" title="Agregar" data-backdrop="static" data-keyboard="false"><i data-feather="user-plus"></i> Agregar</button>

                                <a class="btn waves-effect waves-light btn-primary" href="reportepdf?tipo=<?php echo encrypt("USUARIOS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><i data-feather="file-text"></i> Pdf</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("USUARIOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><i data-feather="file-text"></i> Excel</a>

                                <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("USUARIOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><i data-feather="file-text"></i> Word</a>
                            </div>
                        </div>

                        <div id="usuarios"></div>
                               
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
    <!-- Custom file upload -->

    <!-- script jquery -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- Calendario -->
    <link rel="stylesheet" href="assets/calendario/jquery-ui.css" />
    <link href="plugins/autocomplete/autocomplete.css" rel="stylesheet" type="text/css" />
    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="assets/calendario/jquery-ui.js"></script>
    <script src="assets/script/jscalendario.js"></script>
    <!-- Calendario -->

    <!-- jQuery Noty-->
    <script src="plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script src="plugins/noty/themes/relax.js"></script>
    <script type="text/jscript">
    $('#usuarios').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#usuarios').load("consultas?CargaUsuarios=si");
     }, 200);
    </script>
    <!-- jQuery Noty-->

</body>
</html>