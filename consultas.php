<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$con     = new Login();
$con     = $con->ConfiguracionPorId();
$simbolo = $con[0]['simbolo'] ?? $con[0]['simbolo'];

$tra = new Login();  
?>

<?php
############################# CARGAR USUARIOS ############################
if (isset($_GET['CargaUsuarios'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr role="row">
        <th>N°</th>
        <th>Foto</th>
        <th>N° de Documento</th>
        <th>Nombres y Apellidos</th>
        <th>Usuario</th>
        <th>Nivel</th>
        <th>Status</th>
        <th>Acciones</th>
        </tr>
        </thead>
        <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarUsuarios();

if($reg==""){
    
    echo "";   

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){ 
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td><?php if (file_exists("fotos/".$reg[$i]["codigo"].".jpg")){
    echo "<img src='fotos/".$reg[$i]["codigo"].".jpg?' class='rounded-circle' style='margin:0px;' width='50' height='50'>";
       }else{
    echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-image'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><circle cx='8.5' cy='8.5' r='1.5'></circle><polyline points='21 15 16 10 5 21'></polyline></svg>";  
    } ?></td>
    <td><?php echo $reg[$i]['documento']." ".$reg[$i]['dni']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['nombres']; ?></td>
    <td><?php echo $reg[$i]['usuario']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['nivel']; ?></td>
    <td><?php echo $status = ($reg[$i]['status'] == 1 ? '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> ACTIVO</span>' : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> INACTIVO</span>'); ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerUsuario('<?php echo encrypt($reg[$i]["codigo"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalUsuario" title="Editar" onClick="UpdateUsuario('<?php echo encrypt($reg[$i]["codigo"]); ?>','<?php echo encrypt($reg[$i]["documusuario"]); ?>','<?php echo $reg[$i]["dni"]; ?>','<?php echo $reg[$i]["nombres"]; ?>','<?php echo $reg[$i]["sexo"]; ?>','<?php echo $reg[$i]["telefono"]; ?>','<?php echo $reg[$i]["celular"]; ?>','<?php echo ($reg[$i]['codprovincia'] == '0' ? "" : encrypt($reg[$i]['codprovincia'])); ?>','<?php echo $reg[$i]["direccion"]; ?>','<?php echo $reg[$i]["email"]; ?>','<?php echo $reg[$i]["fnacimiento"] == '0000-00-00' ? "" : date("d-m-Y",strtotime($reg[$i]["fnacimiento"])); ?>','<?php echo $reg[$i]["usuario"]; ?>','<?php echo $reg[$i]["nivel"]; ?>','<?php echo $reg[$i]['status']; ?>','update'); SelectDepartamento('<?php echo ($reg[$i]['codprovincia'] == '0' ? "" : encrypt($reg[$i]['codprovincia'])); ?>','<?php echo encrypt($reg[$i]["coddepartamento"]); ?>');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

    <?php if($reg[$i]["status"] == 1){ ?>
    <span class="text-dark" style="cursor: pointer;" title="Inactivar Usuario" onClick="StatusUsuario('<?php echo encrypt($reg[$i]["codigo"]); ?>','<?php echo encrypt($reg[$i]["status"]); ?>','<?php echo encrypt("STATUSUSUARIOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg></span>
    <?php } else { ?>
    <span class="text-secondary" style="cursor: pointer;" title="Activar Usuario" onClick="StatusUsuario('<?php echo encrypt($reg[$i]["codigo"]); ?>','<?php echo encrypt($reg[$i]["status"]); ?>','<?php echo encrypt("STATUSUSUARIOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg></span>
    <?php } ?>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarUsuario('<?php echo encrypt($reg[$i]["codigo"]); ?>','<?php echo encrypt("USUARIOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>
    </td>
    </tr>
    <?php } } ?>
  </tbody>
</table></div>
<?php
} 
############################# CARGAR USUARIOS ############################
?>


<?php
############################# CARGAR LOGS DE ACCESO ############################
if (isset($_GET['CargaLogs'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Ip de Máquina</th>
                <th>Fecha</th>
                <th>Navegador</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
<?php 
$reg = $tra->ListarLogs();

if($reg==""){
    
    echo "";  

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr>
        <td><?php echo $a++; ?></td>
        <td><?php echo $reg[$i]['ip']; ?></td>
        <td><?php echo $reg[$i]['tiempo']; ?></td>
        <td><?php echo $reg[$i]['detalles']; ?></td>
        <td><?php echo $reg[$i]['usuario']; ?></td>
    </tr>
    <?php } } ?>
    </tbody>
    </table></div>
<?php
} 
############################# CARGAR LOGS DE ACCESO ############################
?>




<?php
############################# CARGAR TIPO DOCUMENTOS ############################
if (isset($_GET['CargaDocumentos'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
          <thead>
              <tr>
                  <th>N°</th>
                  <th>Nombre</th>
                  <th>Descripción de Documento</th>
                  <th>Acciones</th>
              </tr>
          </thead>
          <tbody>
<?php 
$reg = $tra->ListarDocumentos();

if($reg==""){
    
    echo "";  

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr>
        <td><?php echo $a++; ?></td>
        <td><?php echo $reg[$i]['documento']; ?></td>
        <td><?php echo $reg[$i]['descripcion']; ?></td>
        <td>
        <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDocumento" title="Editar" onClick="UpdateDocumento('<?php echo encrypt($reg[$i]["coddocumento"]); ?>','<?php echo $reg[$i]["documento"]; ?>','<?php echo $reg[$i]["descripcion"]; ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

        <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarDocumento('<?php echo encrypt($reg[$i]["coddocumento"]); ?>','<?php echo encrypt("DOCUMENTOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>

        <!--<span class="text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>-->

        </td>
    </tr>
    <?php } } ?>
    </tbody>
  </table></div>
<?php
} 
############################# CARGAR TIPO DOCUMENTOS ############################
?>



<?php
############################# CARGAR PROVINCIAS ############################
if (isset($_GET['CargaProvincias'])) { 
?>
<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
            <thead>
            <tr role="row">
                <th>N°</th>
                <th>Provincias</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarProvincias();

if($reg==""){
    
    echo "";    

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['provincia']; ?></td>
    <td>
    <span class="text-info" style="cursor: pointer;" data-toggle="modal" data-target="#myModalProvincia" title="Editar" data-backdrop="static" data-keyboard="false" onClick="UpdateProvincia('<?php echo encrypt($reg[$i]['codprovincia']); ?>','<?php echo $reg[$i]["provincia"]; ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarProvincia('<?php echo encrypt($reg[$i]["codprovincia"]); ?>','<?php echo encrypt("PROVINCIAS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>
        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR PROVINCIAS ############################
?>




<?php
############################# CARGAR DEPARTAMENTOS ############################
if (isset($_GET['CargaDepartamentos'])) { 
?>
<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
            <thead>
            <tr role="row">
                <th>N°</th>
                <th>Departamento</th>
                <th>Provincia</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarDepartamentos();

if($reg==""){
    
    echo "";    

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['departamento']; ?></td>
    <td><?php echo $reg[$i]['provincia']; ?></td>
    <td>
    <span class="text-info" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDepartamento" title="Editar" data-backdrop="static" data-keyboard="false" onClick="UpdateDepartamento('<?php echo encrypt($reg[$i]["coddepartamento"]); ?>','<?php echo $reg[$i]["departamento"]; ?>','<?php echo encrypt($reg[$i]['codprovincia']); ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarDepartamento('<?php echo encrypt($reg[$i]["coddepartamento"]); ?>','<?php echo encrypt("DEPARTAMENTOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>
        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR DEPARTAMENTOS ############################
?>





<?php
############################# CARGAR TIPO MONEDA ############################
if (isset($_GET['CargaMonedas'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre de Moneda</th>
                    <th>Siglas</th>
                    <th>Simbolo</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
<?php 
$reg = $tra->ListarTipoMoneda();

if($reg==""){
    
    echo "";  

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr>
        <td><?php echo $a++; ?></td>
        <td><?php echo $reg[$i]['moneda']; ?></td>
        <td><?php echo $reg[$i]['siglas']; ?></td>
        <td><?php echo $reg[$i]['simbolo']; ?></td>
        <td>
        <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalMoneda" title="Editar" onClick="UpdateTipoMoneda('<?php echo encrypt($reg[$i]["codmoneda"]); ?>','<?php echo $reg[$i]["moneda"]; ?>','<?php echo $reg[$i]["siglas"]; ?>','<?php echo $reg[$i]["simbolo"]; ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

        <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarTipoMoneda('<?php echo encrypt($reg[$i]["codmoneda"]); ?>','<?php echo encrypt("TIPOMONEDA"); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>
        </td>
    </tr>
    <?php } } ?>
    </tbody>
</table></div>
<?php
} 
############################# CARGAR TIPO MONEDA ############################
?>





<?php
############################# CARGAR FORMAS DE PAGOS ############################
if (isset($_GET['CargaFormasPagos'])) { 
?>
<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
            <thead>
            <tr role="row">
                <th>N°</th>
                <th>Forma de Pago</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarFormasPagos();

if($reg==""){
    
    echo "";    

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['formapago']; ?></td>
    <td>
    <span class="text-info" style="cursor: pointer;" data-toggle="modal" data-target="#myModalFormaPago" title="Editar" data-backdrop="static" data-keyboard="false" onClick="UpdateFormaPago('<?php echo encrypt($reg[$i]['codformapago']); ?>','<?php echo $reg[$i]["formapago"]; ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarFormaPago('<?php echo encrypt($reg[$i]["codformapago"]); ?>','<?php echo encrypt("FORMASPAGOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>
        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR FORMAS DE PAGOS ############################
?>



<?php
############################# CARGAR CLIENTES ############################
if (isset($_GET['CargaClientes'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
            <tr>
              <th>N°</th>
              <th>Foto</th>
              <th>N° de Documento</th>
              <th>Nombres y Apellidos</th>
              <th>Nº de Teléfono</th>
              <th>Nº de Celular</th>
              <th>Email</th>
              <th>Status</th>
              <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
<?php 
$reg = $tra->ListarClientes();

if($reg==""){
    
    echo "";  

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
    <tr>
    <td><?php echo $a++; ?></td>
    <td><?php if (file_exists("fotos/".$reg[$i]["codcliente"].".jpg")){
    echo "<img src='fotos/".$reg[$i]["codcliente"].".jpg?' class='rounded-circle' style='margin:0px;' width='50' height='50'>";
       }else{
    echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-image'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><circle cx='8.5' cy='8.5' r='1.5'></circle><polyline points='21 15 16 10 5 21'></polyline></svg>";   
    } ?></td>
    <td><?php echo $reg[$i]['documento']." ".$reg[$i]['cedcliente']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['nomcliente']; ?></td>
    <td><?php echo $reg[$i]['tlfcliente'] == "" ? "**********" : $reg[$i]['tlfcliente']; ?></td>
    <td><?php echo $reg[$i]['celcliente'] == "" ? "**********" : $reg[$i]['celcliente']; ?></td>
    <td><?php echo $reg[$i]['email'] == "" ? "**********" : $reg[$i]['email']; ?></td>
    <td><?php echo $status = ($reg[$i]['status'] == 1 ? '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> ACTIVO</span>' : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> INACTIVO</span>'); ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerCliente('<?php echo encrypt($reg[$i]["codcliente"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalCliente" title="Editar" onClick="UpdateCliente('<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo encrypt($reg[$i]["documcliente"]); ?>','<?php echo $reg[$i]["cedcliente"]; ?>','<?php echo $reg[$i]["nomcliente"]; ?>','<?php echo $reg[$i]["sexocliente"]; ?>','<?php echo $reg[$i]["tlfcliente"]; ?>','<?php echo $reg[$i]["celcliente"]; ?>','<?php echo ($reg[$i]['codprovincia'] == '0' ? "" : encrypt($reg[$i]['codprovincia'])); ?>','<?php echo $reg[$i]["direccliente"]; ?>','<?php echo $reg[$i]["email"]; ?>','<?php echo $reg[$i]["cedreferencia"]; ?>','<?php echo $reg[$i]["nomreferencia"]; ?>','<?php echo $reg[$i]["celreferencia"]; ?>','update'); SelectDepartamento('<?php echo ($reg[$i]['codprovincia'] == '0' ? "" : encrypt($reg[$i]['codprovincia'])); ?>','<?php echo encrypt($reg[$i]["coddepartamento"]); ?>');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>

    <?php if($_SESSION["acceso"]=="administrador" && $reg[$i]["status"] == 1){ ?>
    <span class="text-warning" style="cursor: pointer;" title="Inactivar Cliente" onClick="StatusCliente('<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo encrypt($reg[$i]["status"]); ?>','<?php echo encrypt("STATUSCLIENTES") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg></span>
    <?php } elseif($_SESSION["acceso"]=="administrador" && $reg[$i]["status"] == 0){ ?>
    <span class="text-primary" style="cursor: pointer;" title="Activar Cliente" onClick="StatusCliente('<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo encrypt($reg[$i]["status"]); ?>','<?php echo encrypt("STATUSCLIENTES") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg></span>
    <?php } ?>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarCliente('<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo encrypt("CLIENTES") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>

    </td>
  </tr>
<?php } } ?>
</tbody>
</table></div>
<?php
} 
############################# CARGAR CLIENTES ############################
?>



<?php
############################# CARGAR CAJAS ############################
if (isset($_GET['CargaCajas'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr role="row">
            <th>N°</th>
            <th>Nombre de Caja</th>
            <th>Nº Documento</th>
            <th>Responsable de Caja</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarCajas();

if($reg==""){
    
    echo "";   

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['dni']; ?></td>
    <td><?php echo $reg[$i]['nombres']; ?></td>
    <td>
    <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalCaja" title="Editar" onClick="UpdateCaja('<?php echo encrypt($reg[$i]["codcaja"]); ?>','<?php echo $reg[$i]["nrocaja"]; ?>','<?php echo $reg[$i]["nomcaja"]; ?>','<?php echo encrypt($reg[$i]["codigo"]); ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>
    
    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarCaja('<?php echo encrypt($reg[$i]["codcaja"]); ?>','<?php echo encrypt("CAJAS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span> 
        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR CAJAS ############################
?>




<?php
############################# CARGAR APERTURAS ############################
if (isset($_GET['CargaAperturas'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                <tr role="row">
                    <th>N°</th>
                    <th>Caja</th>
                    <th>Responsable</th>
                    <th>Hora de Apertura</th>
                    <th>Hora de Cierre</th>
                    <th>Monto Inicial</th>
                    <th>Préstamos</th>
                    <th>Efectivo en Caja</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarAperturas();

if($reg==""){
    
    echo "";   

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){
$Suma_Efectivo = ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);   
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$reg[$i]['dni'].":</span> ".$reg[$i]['nombres']; ?></td>
    <td class="text-dark alert-link"><?php echo date("d-m-Y H:i:s",strtotime($reg[$i]['fechaapertura'])); ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['statusapertura'] == 1 ? "**********" : date("d-m-Y H:i:s",strtotime($reg[$i]['fechacierre'])); ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['prestamos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ','); ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerApertura('<?php echo encrypt($reg[$i]["codapertura"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <?php if($reg[$i]["statusapertura"]=='1'){ ?>
    <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalCierre" title="Cerrar" onClick="CerrarApertura('<?php echo encrypt($reg[$i]["codapertura"]); ?>',
    '<?php echo $reg[$i]["nrocaja"].": ".$reg[$i]["nomcaja"]; ?>',
    '<?php echo $reg[$i]["dni"].": ".$reg[$i]["nombres"]; ?>',
    '<?php echo number_format($reg[$i]["montoinicial"], 2, '.', ''); ?>',
    '<?php echo date("d-m-Y H:i:s",strtotime($reg[$i]['fechaapertura'])); ?>',
    '<?php echo number_format($reg[$i]["pagos"], 2, '.', ''); ?>',
    '<?php echo number_format($reg[$i]["prestamos"], 2, '.', ''); ?>',
    '<?php echo number_format($reg[$i]["ingresos"], 2, '.', ''); ?>',
    '<?php echo number_format($reg[$i]["egresos"], 2, '.', ''); ?>',
    '<?php echo number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ''); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg></span>

    <?php } else { ?>

    <span class="text-default" style="cursor: pointer;" title="Ticket de Cierre" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codapertura"]); ?>&tipo=<?php echo encrypt("TICKETCIERRE"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>
        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR APERTURAS ############################
?>





<?php
############################# CARGAR MOVIMIENTOS ############################
if (isset($_GET['CargaMovimientos'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
            <thead>
            <tr role="row">
            <th>N°</th>
            <th>Caja</th>
            <th>Responsable</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Monto</th>
            <th>Método</th>
            <th>Fecha</th>
            <th>Acciones</th>
            </tr>
            </thead>
            <tbody class="BusquedaRapida">

<?php 
$reg = $tra->ListarMovimientos();

if($reg==""){
    
    echo "";   

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
    <tr role="row" class="odd">
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['nombres']; ?></td>
    <td><?php echo $tipo = ( $reg[$i]['tipomovimiento'] == "INGRESO" ? "<span class='badge badge-success'><i class='fa fa-check'></i> INGRESO</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> EGRESO</span>"); ?></td>
    <td><?php echo $reg[$i]['descripcionmovimiento']; ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['mediomovimiento']; ?></td>
    <td><?php echo $reg[$i]['fechamovimiento']; ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerMovimiento('<?php echo encrypt($reg[$i]["codmovimiento"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <?php if ($_SESSION["acceso"]=="administrador" && $reg[$i]['statusapertura']=="1") { ?>
    <span class="text-primary" style="cursor: pointer;" data-toggle="modal" data-target="#myModalMovimiento" title="Editar" onClick="UpdateMovimiento('<?php echo encrypt($reg[$i]["codmovimiento"]); ?>','<?php echo encrypt($reg[$i]["numero"]); ?>','<?php echo encrypt($reg[$i]["codapertura"]); ?>','<?php echo encrypt($reg[$i]["codcaja"]); ?>','<?php echo $reg[$i]["tipomovimiento"]; ?>','<?php echo $reg[$i]["descripcionmovimiento"]; ?>','<?php echo $reg[$i]["montomovimiento"]; ?>','<?php echo $reg[$i]["mediomovimiento"]; ?>','<?php echo date("d-m-Y H:i:s",strtotime($reg[$i]['fechamovimiento'])); ?>','update')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>
    <?php } ?>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarMovimiento('<?php echo encrypt($reg[$i]["codmovimiento"]); ?>','<?php echo encrypt("MOVIMIENTOS"); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span> 

    <span class="text-default" style="cursor: pointer;" title="Ticket de Movimiento" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["numero"]); ?>&tipo=<?php echo encrypt("TICKETMOVIMIENTO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

        </td>
        </tr>
        <?php } } ?>
        </tbody>
    </table></div>
<?php
} 
############################# CARGAR MOVIMIENTOS ############################
?>






<?php
############################# CARGAR PRESTAMOS PROCESADOS ############################
if (isset($_GET['CargaPrestamosProcesados']) && isset($_GET['search_prestamos'])){ 

$criterio = limpiar($_GET['search_prestamos']);
?>
<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
            <th>N°</th>
            <th>Caja</th>
            <th>Nombre de Cliente</th>
            <th>Monto Préstamo</th>
            <th>Intereses</th>
            <th>Cuotas</th>      
            <th>Importe Total</th>          
            <th>Periodo Pago</th>
            <th>Estado</th>                
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
<?php
if($criterio==""){
    
  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE VALOR PARA TU CRITERIO DE BÚSQUEDA </center>";
  echo "</div>";
  exit;    

} else {

$reg = $tra->BusquedaPrestamos();
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){ 
?>
    <tr>
    <td><?php echo $a++; ?></td>
    <td><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['periodopago']; ?></td>
    <td><?php if($reg[$i]['statusprestamo'] == 1) { 
    echo '<span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> PENDIENTE</span>'; 
    } elseif($reg[$i]['statusprestamo'] == 2) {  
    echo '<span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> APROBADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 3) { 
    echo '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> RECHAZADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 4) { 
    echo '<span class="badge badge-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> CANCELADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 5) { 
    echo '<span class="badge badge-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> PAGADA</span>'; 
    } ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerPrestamo('<?php echo encrypt($reg[$i]["codprestamo"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <?php if ($_SESSION['acceso'] == "administrador" && $reg[$i]['statusprestamo'] == 1) { ?>

    <span class="text-info" style="cursor: pointer;" data-toggle="modal" data-target="#myModalProcesarPrestamo" title="Procesar Préstamo" onClick="ProcesarPrestamoPendiente('<?php echo encrypt($reg[$i]["codprestamo"]); ?>','<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo $reg[0]['documento'].": ".$reg[0]['cedcliente'].": ".$reg[0]['nomcliente']; ?>','<?php echo number_format($reg[$i]["montoprestamo"], 2, '.', ''); ?>','<?php echo number_format($reg[$i]['intereses'], 2, '.', ''); ?>','<?php echo number_format($reg[$i]["totalintereses"], 2, '.', ''); ?>','<?php echo number_format($reg[$i]['totalpago'], 2, '.', ''); ?>','<?php echo $reg[$i]['cuotas']; ?>','<?php echo number_format($reg[$i]['montoxcuota'], 2, '.', ''); ?>','<?php echo $reg[$i]['periodopago']; ?>','<?php echo date("d-m-Y",strtotime($reg[$i]['fechaprestamo'])); ?>','<?php echo $reg[$i]["statusprestamo"]; ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span>

    <span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarPrestamoPendiente('<?php echo encrypt($reg[$i]["codprestamo"]); ?>','<?php echo encrypt("PRESTAMOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span>

    <?php } ?>

    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php if($reg[$i]['statusprestamo'] == 2 || $reg[$i]['statusprestamo'] == 5) { ?>

    <span class="text-warning" style="cursor: pointer;" title="Contrato" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt("CONTRATO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>

    </td>
  </tr>
<?php } } ?>
</tbody>
</table></div>
<?php
} 
############################# CARGAR PRESTAMOS PROCESADOS ############################
?>


<?php
############################# CARGAR PRESTAMOS PENDIENTES ############################
if (isset($_GET['CargaPrestamosPendientes'])) { 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
            <th>N°</th>
            <th>Nombre de Cliente</th>
            <th>Monto Préstamo</th>
            <th>Intereses</th>
            <th>Cuotas</th>      
            <th>Importe Total</th>          
            <th>Periodo Pago</th>
            <th>Estado</th>
            <th>Fecha</th>              
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
<?php
$reg = $tra->ListarPrestamosPendientes();

if($reg==""){
    
    echo "";  

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){ 
?>
    <tr>
    <td><?php echo $a++; ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente'].""; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['periodopago']; ?></td>
    <td><?php if($reg[$i]['statusprestamo'] == 1) { 
    echo '<span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> PENDIENTE</span>'; 
    } elseif($reg[$i]['statusprestamo'] == 2) {  
    echo '<span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> APROBADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 3) { 
    echo '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> RECHAZADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 4) { 
    echo '<span class="badge badge-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> CANCELADA</span>';
    } elseif($reg[$i]['statusprestamo'] == 5) { 
    echo '<span class="badge badge-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> PAGADA</span>'; 
    } ?></td>
    <td class="text-dark alert-link"><?php echo date("d/m/Y",strtotime($reg[$i]['fechaprestamo']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($reg[$i]['fechaprestamo']))."</span>"; ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerPrestamo('<?php echo encrypt($reg[$i]["codprestamo"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <?php if ($_SESSION['acceso'] == "administrador" && $reg[$i]['statusprestamo'] == 1 || $_SESSION['acceso'] == "administrador" && $reg[$i]['statusprestamo'] == 2 || $_SESSION['acceso'] == "cajero" && $reg[$i]['statusprestamo'] == 2) { ?>

    <span class="text-info" style="cursor: pointer;" data-toggle="modal" data-target="#myModalProcesarPrestamo" title="Procesar Préstamo" onClick="ProcesarPrestamoPendiente('<?php echo encrypt($reg[$i]["codprestamo"]); ?>','<?php echo encrypt($reg[$i]["codcliente"]); ?>','<?php echo $reg[0]['documento'].": ".$reg[0]['cedcliente'].": ".$reg[0]['nomcliente']; ?>','<?php echo number_format($reg[$i]["montoprestamo"], 2, '.', ''); ?>','<?php echo number_format($reg[$i]['intereses'], 2, '.', ''); ?>','<?php echo number_format($reg[$i]["totalintereses"], 2, '.', ''); ?>','<?php echo number_format($reg[$i]['totalpago'], 2, '.', ''); ?>','<?php echo $reg[$i]['cuotas']; ?>','<?php echo number_format($reg[$i]['montoxcuota'], 2, '.', ''); ?>','<?php echo $reg[$i]['periodopago']; ?>','<?php echo date("d-m-Y",strtotime($reg[$i]['fechaprestamo'])); ?>','<?php echo $reg[$i]["statusprestamo"]; ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span>

    <?php } ?>

    <?php if ($_SESSION['acceso'] == "administrador") { ?><span class="text-danger" style="cursor: pointer;" title="Eliminar" onClick="EliminarPrestamo('<?php echo encrypt($reg[$i]["codprestamo"]); ?>','<?php echo encrypt("PRESTAMOS") ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span><?php } ?>

    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    </td>
  </tr>
<?php } } ?>
</tbody>
</table></div>
<?php
} 
############################# CARGAR PRESTAMOS PENDIENTES ############################
?>








<?php
############################# CARGAR PAGOS ############################
if (isset($_GET['CargaPagos']) && isset($_GET['search_pagos'])) {

$criterio = limpiar($_GET['search_pagos']); 
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
            <th>N°</th>
            <th>N° de Préstamo</th>
            <th>N° de Comprobante</th>
            <th>Caja</th>
            <th>Nombre de Cliente</th>
            <th>Monto x Cuota</th>
            <th>Cuotas Pagadas</th>
            <th>Importe Pagado</th>
            <th>Fecha Pago</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
<?php 
if($criterio==""){
    
  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE VALOR PARA TU CRITERIO DE BÚSQUEDA </center>";
  echo "</div>";
  exit;    

} else {

$reg = $tra->BusquedaPagos();
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){ 
?>
    <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['numerorecibo']; ?></td>
    <td><abbr title="<?php echo "CAJERO: ".$reg[$i]['nombres']; ?>"><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></abbr></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo date("d/m/Y",strtotime($reg[$i]['fecharecibo']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($reg[$i]['fecharecibo']))."</span>"; ?></td>
    <td>
    <span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerPago('<?php echo encrypt($reg[$i]["codprestamo"]); ?>','<?php echo encrypt($reg[$i]["codrecibo"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>

    <!--<a class="text-dark" title="Factura" href="reportepdf?numero=<?php echo encrypt($reg[$i]['codprestamo']); ?>&numero2=<?php echo encrypt($reg[$i]['codrecibo']); ?>&tipo=<?php echo encrypt("COMPROBANTE"); ?>" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>-->

    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&numero2=<?php echo encrypt($reg[$i]['codrecibo']); ?>&tipo=<?php echo encrypt("COMPROBANTE"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

            </td>
          </tr>
        <?php } } ?>
      </tbody>
    </table></div>
<?php
} 
############################# CARGAR PAGOS ############################
?>









<?php
############################# CARGAR PRESTAMOS EN MORA ############################
if (isset($_GET['CargaPrestamosMora'])) {
?>

<div class="table-responsive mb-0 mt-0">
    <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
            <thead>
            <tr>
                <th>N°</th>
                <th>Nº de Préstamo</th>
                <th>Nombre de Cliente</th>
                <th>Cuotas en Mora</th>
                <th>Importe en Mora</th>
                <th>...</th>
            </tr>
            </thead>
            <tbody>
<?php
$reg = $tra->ListarPrestamosMora();

if($reg==""){
    echo "";  
} else { 
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalDeuda    = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['cuotas_mora'];
$TotalDeuda    += $reg[$i]['suma_mora'];
?>
    <tr>
        <td><?php echo $a++; ?></td>
        <td class="text-danger alert-link"><?php echo $reg[$i]['codfactura']; ?></td> 
        <td class="text-dark alert-link"><?php echo $reg[$i]['nomcliente']; ?></td>
        <td class="text-danger alert-link"><abbr title="<?php echo "Periodo Vencidos: ".str_replace("<br>"," | ", $reg[$i]['meses_mora']); ?>"><?php echo $reg[$i]['cuotas_mora']; ?></abbr></td>
        <td><?php echo $simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','); ?></td>
        <td><span class="text-success" style="cursor: pointer;" data-toggle="modal" data-target="#myModalDetalle" title="Ver" onClick="VerPrestamo('<?php echo encrypt($reg[$i]["codprestamo"]); ?>')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span></td>
    </tr>
    <?php } } ?>
    </tbody>
    </table></div>

<?php
} 
############################# CARGAR PRESTAMOS EN MORA ############################
?>

  <!-- BEGIN PAGE LEVEL CUSTOM SCRIPTS -->
  <script src="plugins/table/datatable/datatables.js"></script>
  <script>        
        $('#html5-extension').DataTable( {
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Mostrar Página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Mostrar :  _MENU_",
            },
            "order": [[ 0, "asc" ]],
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7,
            drawCallback: function () { $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered mb-5'); }
      } );
    </script>
  <!-- END PAGE LEVEL CUSTOM SCRIPTS -->

  <script src="assets/js/scrollspyNav.js"></script>
  <script src="plugins/font-icons/feather/feather.min.js"></script>
  <script type="text/javascript">
      feather.replace();
  </script>