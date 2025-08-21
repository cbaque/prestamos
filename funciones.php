<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$con     = new Login();
$con     = $con->ConfiguracionPorId();
$simbolo = $con[0]['simbolo'] ?? $con[0]['simbolo']; 

$new    = new Login();
?>

<?php 
######################## BUSCA DEPARTAMENTOS POR PROVINCIA ########################
if (isset($_GET['BuscaDepartamentos']) && isset($_GET['codprovincia'])) {
  
$departamento = $new->ListarDepartamentosxProvincia();

$codprovincia = limpiar($_GET['codprovincia']);

if($codprovincia=="") { ?>

<option value="">-- SIN RESULTADOS --</option>
<?php } else { ?>
<option value=""> -- SELECCIONE -- </option>
<?php
for($i=0;$i<sizeof($departamento);$i++){
?>
<option value="<?php echo encrypt($departamento[$i]['coddepartamento']); ?>"><?php echo $departamento[$i]['departamento']; ?></option>
<?php 
    }
  }
}
######################## BUSCA DEPARTAMENTOS POR PROVINCIA ########################
?>

<?php 
######################## SELECCIONE DEPARTAMENTOS POR PROVINCIA ########################
if (isset($_GET['SeleccionaDepartamentos']) && isset($_GET['codprovincia']) && isset($_GET['coddepartamento'])) {
  
$departamento = $new->SeleccionaDepartamento();
?>
<option value="">SELECCIONE</option>
<?php for($i=0;$i<sizeof($departamento);$i++){ ?>
<option value="<?php echo encrypt($departamento[$i]['coddepartamento']); ?>"<?php if (!(strcmp(decrypt($_GET['coddepartamento']), htmlentities($departamento[$i]['coddepartamento'])))) {echo "selected=\"selected\"";} ?>><?php echo $departamento[$i]['departamento']; ?></option>
<?php
  } 
}
######################## SELECCIONE DEPARTAMENTOS POR PROVINCIA ########################
?>





<?php
######################## MOSTRAR USUARIO EN VENTANA MODAL ############################
if (isset($_GET['BuscaUsuarioModal']) && isset($_GET['codigo'])) { 
$reg = $new->UsuariosPorId();
?>

  <table class="table-responsive" border="0" class="text-center">
  <tr>
    <td><strong>Nº de <?php echo $reg[0]['documusuario'] == '0' ? "Documento" : $reg[0]['documento']; ?>:</strong> <?php echo $reg[0]['dni']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombres y Apellidos:</strong> <?php echo $reg[0]['nombres']; ?></td>
  </tr>
  <tr>
    <td><strong>Sexo:</strong> <?php echo $reg[0]['sexo']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['telefono']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Celular: </strong> <?php echo $reg[0]['celular'] == '' ? "***********" : $reg[0]['celular']; ?></td>
  </tr>
  <tr>
    <td><strong>Departamento: </strong> <?php echo $reg[0]['coddepartamento'] == '0' ? "*********" : $reg[0]['departamento']; ?></td>
  </tr>
  <tr>
    <td><strong>Provincia: </strong> <?php echo $reg[0]['codprovincia'] == '0' ? "*********" : $reg[0]['provincia']; ?></td>
  </tr>
  <tr>
    <td><strong>Dirección Domiciliaria: </strong> <?php echo $reg[0]['direccion']; ?></td>
  </tr>
  <tr>
    <td><strong>Correo Electrónico: </strong> <?php echo $reg[0]['email']; ?></td>
  </tr>
  <tr>
    <td><strong>Fecha de Nacimiento: </strong> <?php echo $reg[0]['fnacimiento'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[0]['fnacimiento'])); ?></td>
  </tr>
  <tr>
    <td><strong>Usuario de Acceso: </strong> <?php echo $reg[0]['usuario']; ?></td>
  </tr>
  <tr>
    <td><strong>Nivel de Acceso: </strong> <?php echo $reg[0]['nivel']; ?></td>
  </tr>
  <tr>
  <td><strong>Status de Acceso: </strong> <?php echo $status = ( $reg[0]['status'] == 1 ? '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> ACTIVO</span>' : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> INACTIVO</span>'); ?></td>
  </tr>
</table>  

<?php
} 
######################## MOSTRAR USUARIO EN VENTANA MODAL ############################
?>





<?php 
############################# MUESTRA DIV CLIENTE ############################
if (isset($_GET['BuscaDivCliente'])) {
  
  ?>
<div class="row">
      <div class="col-md-12">
<font color="red"><strong> Para poder realizar la Carga Masiva de Clientes, el archivo Excel, debe estar estructurado de 14 columnas, la cuales tendrán las siguientes especificaciones:</strong></font><br><br>

  1. Tipo de Documento. (Debera de Ingresar el Nº de Documento a la que corresponde)<br>
  2. Nº de Documento.<br>
  3. Nombre de Cliente<br>
  4. Sexo. (MASCULINO - FEMENINO)<br>
  5. Nº de Telefono<br>
  6. Nº de Celular<br>
  7. Provincia. (Debera de Ingresar el Codigo de Provincia a la que corresponde)<br>
  8. Departamento. (Debera de Ingresar el Codigo de Departamento a la que corresponde)<br>
  9. Dirección de Cliente<br>
  10. Correo Electronico<br>
  11. Nº Documento de Persona Referencia<br>
  12. Nombre de Persona Referencia<br>
  13. Nº de Celular de Persona Referencia<br>
  14. Status. (Activo = 1. Inactivo = 2)<br><br>

  <font color="red"><strong> NOTA:</strong></font><br>
  a) Se debe de guardar como archivo .CSV  (delimitado por comas)(*.csv)<br>
  b) Descargar Plantilla <a href="fotos/clientes.csv">AQUI</a>.<br>
  c) Todos los datos deberán escribirse en mayúscula para mejor orden y visibilidad en los reportes<br>
  d) Deben de tener en cuenta que la carga masiva de Clientes, deben de ser cargados como se explica, para evitar problemas de datos dentro del Sistema<br><br>
    </div>
</div>                                 
<?php 
}
############################# MUESTRA DIV CLIENTE #############################
?>

<?php
######################## MOSTRAR CLIENTE EN VENTANA MODAL ############################
if (isset($_GET['BuscaClienteModal']) && isset($_GET['codcliente'])) { 
$reg = $new->ClientesPorId();
?>

  <table class="table-responsive" border="0">
  <tr>
    <td><strong>Código:</strong> <?php echo $reg[0]['codcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de <?php echo $reg[0]['documcliente'] == '0' ? "Documento" : $reg[0]['documento']; ?>:</strong> <?php echo $reg[0]['cedcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombres y Apellidos:</strong> <?php echo $reg[0]['nomcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Sexo: </strong> <?php echo $reg[0]['sexocliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['tlfcliente'] == '' ? "***********" : $reg[0]['tlfcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Celular: </strong> <?php echo $reg[0]['celcliente'] == '' ? "***********" : $reg[0]['celcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Provincia: </strong> <?php echo $reg[0]['codprovincia'] == '0' ? "******" : $reg[0]['provincia']; ?></td>
  </tr>
  <tr>
    <td><strong>Departamento: </strong> <?php echo $reg[0]['coddepartamento'] == '0' ? "******" : $reg[0]['departamento']; ?></td>
  </tr>
  <tr>
    <td><strong>Dirección Domiciliaria: </strong> <?php echo $reg[0]['direccliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Correo Electrónico: </strong> <?php echo $reg[0]['email'] == '' ? "*********" : $reg[0]['email']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº Documento de Persona Referencia: </strong> <?php echo $cedfamiliar = ($reg[0]['cedreferencia'] == '' ? "*********" : $reg[0]['cedreferencia']); ?></td>
  </tr>
  <tr>
    <td><strong>Nombre de Persona Referencia: </strong> <?php echo $nomreferencia = ($reg[0]['nomreferencia'] == '' ? "*********" : $reg[0]['nomreferencia']); ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Celular de Persona Referencia: </strong> <?php echo $celreferencia = ($reg[0]['celreferencia'] == '' ? "*********" : $reg[0]['celreferencia']); ?></td>
  </tr>
  <tr>
  <td><strong>Status: </strong> <?php echo $status = ( $reg[0]['status'] == 1 ? '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg> ACTIVO</span>' : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg> INACTIVO</span>'); ?></td>
  </tr>
</table>  
<?php
} 
######################## MOSTRAR CLIENTE EN VENTANA MODAL ############################
?>













<?php 
############################# BUSCAR CAJERO PARA COBRO VENTA #############################
if (isset($_GET['MuestraDatosCajeroVenta'])) {
  
$caja = new Login();
$caja = $caja->CajasUsuarioPorId();
?>
  <div class="row">
    <div class="col-md-6">
        <h4 class="mb-0 font-light">Nº de Caja:</h4>
        <input type="hidden" name="codcaja" id="codcaja" value="<?php echo $caja == '' ? "0" : encrypt($caja[0]["codcaja"]); ?>">
        <h4 class="mb-0 font-medium"><label id="nrocaja" name="nrocaja"><?php echo $caja == '' ? "NO TIENE CAJA ABIERTA" : $caja[0]["nrocaja"].": ".$caja[0]["nomcaja"]; ?></label></h4>
    </div>

    <div class="col-md-6">
        <h4 class="mb-0 font-light">Nombre de Cajero:</h4>
        <h4 class="mb-0 font-medium"><label id="nomcajero" name="nomcajero"><?php echo $caja == '' ? "***************" : $caja[0]["nombres"]; ?></label></h4>
    </div>
  </div>
  <hr>
<?php 
}
############################# BUSCAR CAJERO PARA COBRO VENTA ##########################
?>

<?php 
############################# BUSCAR DETALLE DE CAJA APERTURADA #############################
if (isset($_GET['BuscaDetalleCajaModal'])) {
  
$caja = new Login();
$caja = $caja->ListarCajas();
?>
  <div class="row">
      <div class="col-md-6">
          <div class="form-group has-feedback">
             <label class="control-label">Nº de Caja: <span class="symbol required"></span></label>
             <input type="hidden" name="codcaja" id="codcaja" value="<?php echo encrypt($caja[0]['codcaja']); ?>">
             <br /><abbr class="text-dark alert-link" title="Nº de Caja"><?php echo $caja[0]['nrocaja']; ?></abbr>
          </div>
      </div>

      <div class="col-md-6">
          <div class="form-group has-feedback">
             <label class="control-label">Nombre de Caja: <span class="symbol required"></span></label>
             <br /><abbr class="text-dark alert-link" title="Nombre de Caja"><?php echo $caja[0]['nomcaja']; ?></abbr>
          </div>
      </div>
  </div>

<?php 
}
############################# BUSCAR DETALLE DE CAJA APERTURADA ##########################
?>










<?php
####################### MOSTRAR CAJA DE VENTA EN VENTANA MODAL ########################
if (isset($_GET['BuscaCajaModal']) && isset($_GET['codcaja'])) { 

$reg = $new->CajasPorId();
?>
  
  <table class="table-responsive" border="0"> 
  <tr>
    <td><strong>Nº de Caja:</strong> <?php echo $reg[0]['nrocaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Responsable de Caja: </strong> <?php echo $reg[0]['nombres']; ?></td>
  </tr>
</table>
<?php 
} 
######################## MOSTRAR CAJA DE VENTA EN VENTANA MODAL #########################
?>


<?php
######################## MOSTRAR DETALLES EN APERTURA #######################
if (isset($_GET['MuestraDetallesApertura']) && isset($_GET['numero'])) { 

$reg = $new->AperturasPorId();
?>

<div class="row">
<?php
$a=1;
$Ventas_Efectivo = 0;
for($i=0;$i<sizeof($reg);$i++){
$Ventas_Efectivo += ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);   
if($reg[$i]['formapago'] != ""){
?>
<div class="col-md-4">
  <div class="form-group has-feedback">
    <label class="control-label"><?php echo $reg[$i]['formapago']; ?>: <span class="symbol required"></span></label>
    <br /><abbr class="text-dark alert-link" title="<?php echo $reg[$i]['formapago']; ?>"><label id="<?php echo $reg[$i]['formapago']; ?>"><?php echo $simbolo.number_format($reg[$i]['montopagado'], 2, '.', ','); ?></label></abbr>
  </div>
</div>
<?php } } ?>

</div>

<?php
} 
######################## MOSTRAR DETALLES EN APERTURA ########################
?>


<?php
######################## MOSTRAR APERTURA EN CAJA EN VENTANA MODAL #######################
if (isset($_GET['BuscaAperturaModal']) && isset($_GET['numero'])) { 

$reg = $new->AperturasPorId();
?>
<table class="table-responsive" border="0">
  <tr>
    <td><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg> Cajero</h4><hr></td>
  </tr>

  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Responsable:</strong> <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?></td>
  </tr>
  <tr>
    <td><strong>Hora Apertura:</strong> <?php echo date("d-m-Y H:i:s",strtotime($reg[0]['fechaapertura'])); ?></td>
  </tr>
  <tr>
    <td><strong>Hora Cierre:</strong> <?php echo $cierre = ( $reg[0]['statusapertura'] == '1' ? "**********" : date("d-m-Y H:i:s",strtotime($reg[0]['fechacierre']))); ?></td>
  </tr>
  <tr>
    <td><strong>Monto Inicial:</strong> <?php echo $simbolo.number_format($reg[0]['montoinicial'], 2, '.', ','); ?></td>
  </tr>

  <tr>
    <td><hr><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Cobros en Caja</h4><hr></td>
  </tr>

  <?php
  $a=1;
  $Monto_Efectivo = 0;
  for($i=0;$i<sizeof($reg);$i++){
  $Monto_Efectivo += ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);   
  if($reg[$i]['formapago'] != ""){
  ?>
  <tr>
    <td><strong><?php echo $reg[$i]['formapago']; ?>:</strong>  <?php echo $simbolo.number_format($reg[$i]['montopagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } } ?>

  <tr>
    <td><hr><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Préstamos en Caja</h4><hr></td>
  </tr>

  <tr>
    <td><strong>Préstamos:</strong> <?php echo $simbolo.number_format($reg[0]['prestamos'], 2, '.', ','); ?></td>
  </tr>

  <tr>
    <td><hr><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Movimientos</h4><hr></td>
  </tr>

  <tr>
    <td><strong>Ingresos:</strong> <?php echo $simbolo.number_format($reg[0]['ingresos'], 2, '.', ','); ?></td>
  </tr>

  <tr>
    <td><strong>Egresos:</strong> <?php echo $simbolo.number_format($reg[0]['egresos'], 2, '.', ','); ?></td>
  </tr>

  <tr>
    <td><hr><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bold"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path></svg> Balance en Caja</h4><hr></td>
  </tr>

  <tr>
    <td><strong>Total en Cobros:</strong> <?php echo $simbolo.number_format($reg[0]['pagos'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Cobros en Efectivo:</strong> <?php echo $simbolo.number_format($Monto_Efectivo, 2, '.', ','); ?></td>
  </tr>
  </tr>
  <tr>
    <td><strong>Efectivo en Caja:</strong> <?php echo $simbolo.number_format(($reg[0]["montoinicial"]+$Monto_Efectivo+$reg[0]['ingresos'])-($reg[0]["egresos"]+$reg[0]["prestamos"]), 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Efectivo Disponible:</strong> <?php echo $simbolo.number_format($reg[0]['dineroefectivo'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Diferencia en Efectivo:</strong> <?php echo $simbolo.number_format($reg[0]['diferencia'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Observaciones:</strong> <?php echo $reg[0]['comentarios'] == "" ? "******" : $reg[0]['comentarios']; ?></td>
  </tr>
</table>

<?php
} 
######################## MOSTRAR APERTURAS EN CAJA EN VENTANA MODAL ########################
?>


<?php
########################## BUSQUEDA APERTURAS DE CAJA POR FECHAS ##########################
if (isset($_GET['BuscaAperturasxFechas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
  echo "</div>";   
  exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarAperturasxFechas();
?>
  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Caja : <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?><br>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("APERTURASXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("APERTURASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("APERTURASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>Nº</th>
                    <th>Hora de Apertura</th>
                    <th>Hora de Cierre</th>
                    <th>Monto Inicial</th>
                    <th>Préstamos</th>
                    <th>Total en Cobros</th>
                    <th>Cobros en Efectivo</th>
                    <th>Total en Efectivo</th>
                    <th>Efectivo en Caja</th>
                    <th>Diferencia en Caja</th>
                  </tr>
                </thead>
                <tbody>
<?php
$Monto_Efectivo  = 0;
$TotalPrestamos  = 0;
$TotalIngresos   = 0;
$TotalEgresos    = 0;
$TotalCobros     = 0;
$TotalEfectivo   = 0;
$TotalCaja       = 0;
$TotalDisponible = 0;
$TotalDiferencia = 0;

$a=1; 
for($i=0;$i<sizeof($reg);$i++){

$Suma_Efectivo   = ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);
$TotalPrestamos  += $reg[$i]['prestamos'];
$TotalIngresos   += $reg[$i]['ingresos'];
$TotalEgresos    += $reg[$i]['egresos'];
$TotalCobros     += $reg[$i]['pagos'];
$TotalEfectivo   += $Suma_Efectivo;
$TotalCaja   += (($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]));
//$TotalCaja       += $reg[$i]['efectivocaja'];
$TotalDisponible += $reg[$i]['dineroefectivo'];
$TotalDiferencia += $reg[$i]['diferencia'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td><?php echo date("d-m-Y H:i:s",strtotime($reg[$i]['fechaapertura'])); ?></td>
    <td><?php echo $reg[$i]['statusapertura'] == 1 ? "**********" : date("d-m-Y H:i:s",strtotime($reg[$i]['fechacierre'])); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['prestamos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['pagos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($Suma_Efectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr class="text-dark alert-link">
    <td colspan="4"></td>
    <td><?php echo $simbolo.number_format($TotalPrestamos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCobros, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalEfectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCaja, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDisponible, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDiferencia, 2, '.', ','); ?></td>
    <td></td>
        </tr>
        </tbody>
        </table></div>

        </div>
      </div>
    </div>
  </div>
 <?php
  } 
}
########################## BUSQUEDA APERTURAS DE CAJAS POR FECHAS ##########################
?>

















<?php
###################### MOSTRAR MOVIMIENTO EN CAJA EN VENTANA MODAL #####################
if (isset($_GET['BuscaMovimientoModal']) && isset($_GET['numero'])) { 

$reg = $new->MovimientosPorId();
?>
<table class="table-responsive" border="0">
  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Responsable:</strong> <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?></td>
  </tr>
  <tr>
    <td><strong>Tipo de Movimiento:</strong> <?php echo $tipo = ( $reg[0]['tipomovimiento'] == "INGRESO" ? "<span class='badge badge-success'><i class='fa fa-check'></i> INGRESO</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> EGRESO</span>"); ?></td>
  </tr>
  <tr>
    <td><strong>Descripción de Movimiento:</strong> <?php echo $reg[0]['descripcionmovimiento']; ?></td>
  </tr>
  <tr>
    <td><strong>Monto de Movimiento:</strong> <?php echo $simbolo.number_format($reg[0]['montomovimiento'], 2, '.', ','); ?></td>
    </tr>
  <tr>
    <td><strong>Método de Movimiento:</strong> <?php echo $reg[0]['mediomovimiento']; ?></td>
  </tr>
  <tr>
    <td><strong>Fecha Movimiento:</strong> <?php echo date("d-m-Y H:i:s",strtotime($reg[0]['fechamovimiento'])); ?></td>
  </tr>
</table>
<?php
} 
##################### MOSTRAR MOVIMIENTO EN CAJA EN VENTANA MODAL ######################
?>

<?php
######################### BUSQUEDA MOVIMIENTOS DE CAJA POR FECHAS ########################
if (isset($_GET['BuscaMovimientosxFechas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

if($codcaja=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
  echo "</div>";   
  exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarMovimientosxFechas();
?>
  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Caja : <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?><br>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>Nº</th>
                    <th>Tipo Movimiento</th>
                    <th>Descripción</th>
                    <th>Método de Movimiento</th>
                    <th>Fecha Movimiento</th>
                    <th>Monto</th>
                  </tr>
                </thead>
                <tbody>
<?php
$a=1;
$TotalIngresos=0;
$TotalEgresos=0;
for($i=0;$i<sizeof($reg);$i++){ 
$TotalIngresos+= ($reg[$i]['tipomovimiento'] == 'INGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
$TotalEgresos+= ($reg[$i]['tipomovimiento'] == 'EGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td><?php echo $status = ( $reg[$i]['tipomovimiento'] == 'INGRESO' ? "<span class='badge badge-success'><i class='fa fa-check'></i> ".$reg[$i]['tipomovimiento']."</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> ".$reg[$i]['tipomovimiento']."</span>"); ?></td>
    <td><?php echo $reg[$i]['descripcionmovimiento']; ?></td>
    <td><?php echo $reg[$i]['mediomovimiento']; ?></td>
    <td><?php echo date("d-m-Y H:i:s",strtotime($reg[$i]['fechamovimiento'])); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="4"></td>
    <td class="text-dark alert-link">TOTAL INGRESOS</td>
    <td><?php echo $simbolo.number_format($TotalIngresos, 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td class="text-dark alert-link">TOTAL EGRESOS</td>
    <td><?php echo $simbolo.number_format($TotalEgresos, 2, '.', ','); ?></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA MOVIMIENTOS DE CAJAS POR FECHAS ##########################
?>









<?php
######################## MOSTRAR VENTAS EN VENTANA MODAL ############################
if (isset($_GET['BuscaPrestamoModal']) && isset($_GET['numero'])) { 
$reg = $new->PrestamosPorId();
//$simbolo = ($reg[0]['simbolo'] == "" ? "" : "<strong>".$reg[0]['simbolo']."</strong> ");
?>
  <table class="table-responsive" border="0">
  
  <tr>
    <td><hr><h4 class="card-subtitle m-0 text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Datos de Factura</h4><hr></td>
  </tr>

  <tr>
    <td><strong>N° Préstamo:</strong> <h4><?php echo $reg[0]['codfactura']; ?></h4></td>
  </tr>
  <tr>
    <td><strong>Nº <?php echo $reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>:</strong> <?php echo $reg[0]['cedcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombre de Cliente:</strong> <?php echo $reg[0]['nomcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['tlfcliente'] == '' ? "***********" : $reg[0]['tlfcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Celular: </strong> <?php echo $reg[0]['celcliente'] == '' ? "***********" : $reg[0]['celcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Monto de Préstamo:</strong> <?php echo $simbolo.number_format($reg[0]['montoprestamo'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Intereses <?php echo number_format($reg[0]['intereses'], 2, '.', ','); ?>%:</strong> <?php echo $simbolo.number_format($reg[0]['totalintereses'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Total Cuotas:</strong> <?php echo $reg[0]['cuotas']; ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Monto x Cuota:</strong> <?php echo $simbolo.number_format($reg[0]['montoxcuota'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Periodo de Pago:</strong> <?php echo $reg[0]['periodopago']; ?></td>
  </tr>
  <tr>
    <td class="text-danger alert-link"><strong>Pago Total:</strong> <?php echo $simbolo.number_format($reg[0]['totalpago'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td class="text-danger alert-link"><strong>Total Pagado:</strong> <?php echo $simbolo.number_format($reg[0]['creditopagado'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Estado:</strong> <?php if($reg[0]['statusprestamo'] == 1) { 
      echo '<span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> PENDIENTE</span>'; 
      } elseif($reg[0]['statusprestamo'] == 2) {  
      echo '<span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> APROBADA</span>';
      } elseif($reg[0]['statusprestamo'] == 3) { 
      echo '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> RECHAZADA</span>';
      } elseif($reg[0]['statusprestamo'] == 4) { 
      echo '<span class="badge badge-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> CANCELADA</span>';
      } elseif($reg[0]['statusprestamo'] == 5) { 
      echo '<span class="badge badge-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> PAGADA</span>'; 
      } ?></td>
  </tr>
  <tr>
    <td class="text-danger alert-link"><strong>Fecha de Préstamo:</strong> <?php echo date("d/m/Y H:i:s",strtotime($reg[0]['fechaprestamo'])); ?></td>
  </tr>
  <tr>
    <td><strong>Registrado por:</strong> <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?></td>
  </tr>
  <tr>
    <td><strong>Pagado por Caja:</strong> <?php echo $caja = ($reg[0]['codcaja'] == 0 ? "********" : $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']); ?></td>
  </tr>
  <tr>
    <td><strong>Observaciones:</strong> <?php echo $observaciones = ($reg[0]['observaciones'] == "" ? "********" : $reg[0]['observaciones']); ?></td>
  </tr>
</table> 

  <hr><h5 class="card-subtitle text-dark alert-link"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Detalles de Pagos</h5><hr>

  <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
    <thead>
      <tr>
        <th>N°</th>
        <th>Fecha de Pago</th>
        <th>Saldo Inicial</th>
        <th>Cuota</th>
        <th>Saldo Final</th>
        <th>Estado</th>
        <th>Fecha Pagado</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $tra = new Login();
    $detalle = $tra->VerDetallesCuotas();
    for($i=0;$i<sizeof($detalle);$i++){ 
    ?>
    <tr>
      <td class="text-dark alert-link"><?php echo $detalle[$i]["codcuota"]; ?></td>
      <td class="text-danger alert-link"><?php echo date("d-m-Y",strtotime($detalle[$i]["fechapago"])); ?></td>
      <td class="text-dark alert-link"><?php echo number_format($detalle[$i]["saldoinicial"], 2, '.', ','); ?></td>
      <td class="text-dark alert-link"><?php echo number_format($detalle[$i]["capital"], 2, '.', ','); ?></td>
      <td class="text-dark alert-link"><?php echo number_format($detalle[$i]["saldofinal"], 2, '.', ','); ?></td>
      <td class="text-dark alert-link">
      <?php if($detalle[$i]['statuspago'] == 0) { 
      echo '<span class="badge badge-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> PENDIENTE</span>'; 
      } elseif($detalle[$i]['statuspago'] == 1) {  
      echo '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg> PAGADA</span>';
      } elseif($detalle[$i]['statuspago'] == 2) {  
      echo '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> VENCIDA</span>';
      } elseif($detalle[$i]['statuspago'] == 3) { 
      echo '<span class="badge badge-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> CANCELADA</span>';
      } ?></td>
      <td class="text-danger alert-link"><?php echo ($detalle[$i]["fechapagado"] == "0000-00-00") ? "******" : date("d/m/Y",strtotime($detalle[$i]['fechapagado']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($detalle[$i]['fechapagado']))."</span>"; ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table></div>

<?php
} 
######################## MOSTRAR VENTAS EN VENTANA MODAL ############################
?>

<?php
######################## MOSTRAR RECIBO DE PAGO EN VENTANA MODAL ############################
if (isset($_GET['BuscaReciboPagoModal']) && isset($_GET['numero'])) { 
$reg = $new->PrestamosPorId();
$simbolo = ($reg[0]['simbolo'] == "" ? "" : "<strong>".$reg[0]['simbolo']."</strong> ");
?>
<div class="row">
  <div class="col-md-12">
  <?php
  if (file_exists("fotos/recibos/".$reg[0]["codventa"].".jpg")){
    echo "<img src='fotos/recibos/".$reg[0]["codventa"].".jpg?' class='rounded-circle' style='margin:0px;' width='400' height='200'>";
  } else if (file_exists("fotos/recibos/".$reg[0]["codventa"].".jpeg")){
    echo "<img src='fotos/recibos/".$reg[0]["codventa"].".jpeg?' class='rounded-circle' style='margin:0px;' width='400' height='200'>";
  } else if (file_exists("fotos/recibos/".$reg[0]["codventa"].".png")){   
    echo "<img src='fotos/recibos/".$reg[0]["codventa"].".png?' class='rounded-circle' style='margin:0px;' width='400' height='200'>";
  } else {
    echo "<img src='fotos/img.png' class='rounded-circle' style='margin:0px;' width='400' height='200'>";  
  } 
  ?> 
  </div>
</div>
<?php
} 
######################## MOSTRAR RECIBO DE PAGO EN VENTANA MODAL ############################
?>

<?php
########################## BUSQUEDA PRESTAMOS POR CAJAS Y FECHAS ##########################
if (isset($_GET['BuscaPrestamosxCajas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPrestamosxCajas();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Caja : <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?><br>
                         
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PRESTAMOSXCAJAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRESTAMOSXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRESTAMOSXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Nº Préstamo</th>
                    <th>Nombre de Cliente</th>
                    <th>Monto Préstamo</th>
                    <th>Intereses</th>
                    <th>Cuotas</th>                
                    <th>Periodo Pago</th>
                    <th>Estado</th> 
                    <th>Importe Total</th>
                    <th>Pagado</th>
                    <th>Deuda</th>               
                    <th>...</th>
                  </tr>
                </thead>
                <tbody>
<?php
$a=1;
$TotalMonto     = 0;
$TotalIntereses = 0;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalDeuda     = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalMonto     += $reg[$i]['montoprestamo'];
$TotalIntereses += $reg[$i]['totalintereses'];
$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalDeuda     += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
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
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php if($reg[$i]['statusprestamo'] == 2 || $reg[$i]['statusprestamo'] == 5) { ?>

    <span class="text-warning" style="cursor: pointer;" title="Contrato" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt("CONTRATO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>

    </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PRESTAMOS POR CAJAS Y FECHAS ##########################
?>

<?php
########################## BUSQUEDA PRESTAMOS POR FECHAS ##########################
if (isset($_GET['BuscaPrestamosxFechas']) && isset($_GET['estado_prestamo']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $estado_prestamo = limpiar($_GET['estado_prestamo']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($estado_prestamo=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE ESTADO DE PRÉSTAMO PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPrestamosxFechas();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Estado de Préstamo : <?php 
                          if(decrypt($estado_prestamo) == 6){ echo "TODOS"; } 
                          elseif(decrypt($estado_prestamo) == 1){ echo "PENDIENTES"; } 
                          elseif(decrypt($estado_prestamo) == 2){ echo "APROBADOS"; } 
                          elseif(decrypt($estado_prestamo) == 3){ echo "RECHAZADOS"; } 
                          elseif(decrypt($estado_prestamo) == 4){ echo "CANCELADOS"; } 
                          elseif(decrypt($estado_prestamo) == 5){ echo "PAGADOS"; } ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?estado_prestamo=<?php echo $estado_prestamo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PRESTAMOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRESTAMOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRESTAMOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Nº Préstamo</th>
                    <th>Caja</th>
                    <th>Nombre de Cliente</th>
                    <th>Monto Préstamo</th>
                    <th>Intereses</th>
                    <th>Cuotas</th>                
                    <th>Periodo Pago</th>
                    <th>Estado</th> 
                    <th>Importe Total</th>
                    <th>Pagado</th>
                    <th>Deuda</th>               
                    <th>...</th>
                  </tr>
                </thead>
                <tbody>
<?php
$a=1;
$TotalMonto     = 0;
$TotalIntereses = 0;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalDeuda     = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalMonto     += $reg[$i]['montoprestamo'];
$TotalIntereses += $reg[$i]['totalintereses'];
$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalDeuda     += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
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
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php if($reg[$i]['statusprestamo'] == 2 || $reg[$i]['statusprestamo'] == 5) { ?>

    <span class="text-warning" style="cursor: pointer;" title="Contrato" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt("CONTRATO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>

    </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="9"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PRESTAMOS POR FECHAS ##########################
?>

<?php
########################## BUSQUEDA PRESTAMOS POR CLIENTES ##########################
if (isset($_GET['BuscaPrestamosxClientes']) && isset($_GET['estado_prestamo']) && isset($_GET['codcliente'])) {
  
  $estado_prestamo = limpiar($_GET['estado_prestamo']);
  $codcliente = limpiar($_GET['codcliente']);

 if($estado_prestamo=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE ESTADO DE PRÉSTAMO PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($codcliente=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR REALICE LA BÚSQUEDA DEL CLIENTE CORRECTAMENTE</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPrestamosxClientes();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Estado de Préstamo : <?php 
                          if(decrypt($estado_prestamo) == 6){ echo "TODOS"; } 
                          elseif(decrypt($estado_prestamo) == 1){ echo "PENDIENTES"; } 
                          elseif(decrypt($estado_prestamo) == 2){ echo "APROBADOS"; } 
                          elseif(decrypt($estado_prestamo) == 3){ echo "RECHAZADOS"; } 
                          elseif(decrypt($estado_prestamo) == 4){ echo "CANCELADOS"; } 
                          elseif(decrypt($estado_prestamo) == 5){ echo "PAGADOS"; } ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> <?php echo $documcliente = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[0]['documento']); ?> : <?php echo $reg[0]['cedcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Cliente : <?php echo $reg[0]['nomcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Nº de Celular : <?php echo $movil = ($reg[0]['celcliente'] == "" ? "******" : $reg[0]['celcliente']); ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Correo : <?php echo $email = ($reg[0]['email'] == "" ? "******" : $reg[0]['email']); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?estado_prestamo=<?php echo $estado_prestamo; ?>&codcliente=<?php echo $codcliente; ?>&tipo=<?php echo encrypt("PRESTAMOSXCLIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRESTAMOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRESTAMOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Nº Préstamo</th>
                    <th>Caja</th>
                    <th>Monto Préstamo</th>
                    <th>Intereses</th>
                    <th>Cuotas</th>                
                    <th>Periodo Pago</th>
                    <th>Estado</th> 
                    <th>Importe Total</th>
                    <th>Pagado</th>
                    <th>Deuda</th>               
                    <th>...</th>
                  </tr>
                </thead>
                <tbody>
<?php
$a=1;
$TotalMonto     = 0;
$TotalIntereses = 0;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalDeuda     = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalMonto     += $reg[$i]['montoprestamo'];
$TotalIntereses += $reg[$i]['totalintereses'];
$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalDeuda     += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></td>    
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
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
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php if($reg[$i]['statusprestamo'] == 2 || $reg[$i]['statusprestamo'] == 5) { ?>

    <span class="text-warning" style="cursor: pointer;" title="Contrato" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt("CONTRATO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>

    </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PRESTAMOS POR CLIENTES ##########################
?>

<?php
########################## BUSQUEDA PRESTAMOS POR USUARIOS ##########################
if (isset($_GET['BuscaPrestamosxUsuarios']) && isset($_GET['estado_prestamo']) && isset($_GET['codigo']) && isset($_GET['desde']) && isset($_GET['hasta'])){
  
  $estado_prestamo = limpiar($_GET['estado_prestamo']);
  $codigo = limpiar($_GET['codigo']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($estado_prestamo=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE ESTADO DE PRÉSTAMO PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($codigo=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE USUARIO PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPrestamosxUsuarios();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Estado de Préstamo : <?php 
                          if(decrypt($estado_prestamo) == 6){ echo "TODOS"; } 
                          elseif(decrypt($estado_prestamo) == 1){ echo "PENDIENTES"; } 
                          elseif(decrypt($estado_prestamo) == 2){ echo "APROBADOS"; } 
                          elseif(decrypt($estado_prestamo) == 3){ echo "RECHAZADOS"; } 
                          elseif(decrypt($estado_prestamo) == 4){ echo "CANCELADOS"; } 
                          elseif(decrypt($estado_prestamo) == 5){ echo "PAGADOS"; } ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Nombres : <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?estado_prestamo=<?php echo $estado_prestamo; ?>&codigo=<?php echo $codigo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PRESTAMOSXUSUARIOS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&codigo=<?php echo $codigo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRESTAMOSXUSUARIOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?estado_prestamo=<?php echo $estado_prestamo; ?>&codigo=<?php echo $codigo; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRESTAMOSXUSUARIOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Nº Préstamo</th>
                    <th>Nombre de Cliente</th>
                    <th>Monto Préstamo</th>
                    <th>Intereses</th>
                    <th>Cuotas</th>                
                    <th>Periodo Pago</th>
                    <th>Estado</th> 
                    <th>Importe Total</th>
                    <th>Pagado</th>
                    <th>Deuda</th>               
                    <th>...</th>
                  </tr>
                </thead>
                <tbody>
<?php
$a=1;
$TotalMonto     = 0;
$TotalIntereses = 0;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalDeuda     = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalMonto     += $reg[$i]['montoprestamo'];
$TotalIntereses += $reg[$i]['totalintereses'];
$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalDeuda     += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td><?php echo "<span class='text-dark alert-link'>".$documcliente = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[$i]['documento'])." ".$reg[$i]['cedcliente'].":</span><br>".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
    <td><?php echo $reg[$i]['cuotas']; ?></td>
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
    <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php if($reg[$i]['statusprestamo'] == 2 || $reg[$i]['statusprestamo'] == 5) { ?>

    <span class="text-warning" style="cursor: pointer;" title="Contrato" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt("CONTRATO"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>

    <?php } ?>

    </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="8"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
    <td class="text-danger alert-link"><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PRESTAMOS POR USUARIOS ##########################
?>






















<?php 
######################## BUSCA DETALLES DE PRESTAMOS POR CLIENTES ########################
if (isset($_GET['CargaDetallesPrestamos']) && isset($_GET['codcliente'])) {

  $codcliente = limpiar($_GET['codcliente']);

  if($codcliente=="") {

    echo "<div class='alert alert-danger'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-text='true'>&times;</button>";
    echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR REALICE LA BÚSQUEDA DEL CLIENTE CORRECTAMENTE</center>";
    echo "</div>"; 
    exit;

  } else {
  
$busqueda = new Login();
$reg = $busqueda->BuscarDetallesPrestamosxClientes();  
?>

  <div class="row">

  <?php
  $a=1;
  for($i=0;$i<sizeof($reg);$i++){
  //$simbolo = ($reg[$i]['simbolo'] == "" ? "" : "<strong>".$reg[$i]['simbolo']."</strong>"); 
  ?>
  <!-- Plan -->
  <div class="col-md-3">
    <div class="pricing-plan mt-md-4 recommended" style="cursor: pointer;" onclick="BuscarDetallesCuotasPrestamos('<?php echo encrypt($reg[$i]['codprestamo']); ?>','<?php echo number_format($reg[$i]['montoxcuota'], 2, '.', ''); ?>');">
      <input type="hidden" name="cuotas_general" id="cuotas_general" value="<?php echo $reg[$i]['cuotas']; ?>"/>
      <div class="recommended-badge"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg> PRÉSTAMO</div>
      <h3>Nº DE FACTURA: <?php echo $reg[$i]['codfactura']; ?></h3>
      <p class="text-dark alert-link">MONTO PRÉSTAMO: <?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></p>
      <p class="text-dark alert-link">INTERESES: <?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></a></p>
      <p class="text-dark alert-link">CUOTAS: <?php echo $reg[$i]['cuotas']." (".$reg[$i]['periodopago'].")"; ?></p>
      <p class="text-dark alert-link">IMPORTE: <?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></p>
      <p class="text-dark alert-link">MONTO X CUOTA: <?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></p>
      <p class="text-dark alert-link">CUOTA INICIAL: <?php echo $simbolo.number_format($reg[$i]['cuotainicial'], 2, '.', ','); ?></p>
      <p class="text-primary alert-link">PAGADO: <?php echo $simbolo.number_format($reg[$i]['cuotainicial']+$reg[$i]['creditopagado'], 2, '.', ','); ?></p>
      <p class="text-danger alert-link">PENDIENTE: <?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['cuotainicial']-$reg[$i]['creditopagado'], 2, '.', ','); ?></p>
      <p class="text-dark alert-link">FECHA VENTA: <?php echo date("d-m-Y",strtotime($reg[$i]['fechaprestamo'])); ?></p>
    </div>
  </div>
  <?php } ?>
  </div>
<?php 
  }
}
######################## BUSCA DETALLES DE PRESTAMOS POR CLIENTES ########################
?>

<?php 
######################## BUSCA DETALLES DE CUOTAS CREDITOS POR CLIENTES ########################
if (isset($_GET['CargaDetallesCuotasPrestamos']) && isset($_GET['numero']) && isset($_GET['numero2'])) {

  $codprestamo = limpiar($_GET['numero']);
  $montoxcuota = limpiar($_GET['numero2']);

  if($codprestamo=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-text='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE PRESTAMO PARA TU BÚSQUEDA</center>";
  echo "</div>";
  exit;

  } else {
?>
  <div class="row layout-top-spacing">
      <div id="custom_styles" class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
        <div class="widget-content-area statbox widget box box-shadow">

          <div class="widget-header">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h5 class="card-subtitle text-dark alert-link"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Detalles de Cuotas</h5>
              </div>                 
            </div>
          </div>

    <div class="widget-content widget-content-area">
    <input type="hidden" name="montoxcuota" id="montoxcuota" value="<?php echo number_format($montoxcuota, 2, '.', ''); ?>"/>  
    <input type="hidden" name="detalles_cuotas" id="detalles_cuotas"/>
    <div class="row layout-top-spacing">
    <?php
    $tra = new Login();
    $detalle = $tra->VerDetallesCuotas();
    for($i=0;$i<sizeof($detalle);$i++){ 
    ?>
    <div class="col-md-2">
      <div class="form-check form-check-inline">
        <div class="custom-control custom-checkbox">
        <input type="hidden" name="codprestamo" id="codprestamo" value="<?php echo encrypt($detalle[$i]["codprestamo"]); ?>"/>
        <input type="hidden" name="codpago[]" id="codpago<?php echo $detalle[$i]["codcuota"]; ?>" value="<?php echo $detalle[$i]["codpago"]; ?>" <?php echo $activo = ($detalle[$i]["statuspago"] == 1 ? "' disabled='disabled'" : ""); ?>/>
        <input type="checkbox" class="custom-control-input checkbox-selector" name="codcuota[]" id="codcuota<?php echo $detalle[$i]["codcuota"]; ?>" value="<?php echo $detalle[$i]["codcuota"]; ?>" <?php echo $activo = ($detalle[$i]["statuspago"] == 1 ? "checked='checked' disabled='disabled'" : ""); ?> onClick="CargaDetallesCuotasPagar('<?php echo $detalle[$i]["codcuota"]; ?>',document.getElementById('codcuota<?php echo $detalle[$i]["codcuota"]; ?>').value,document.getElementById('montoxcuota').value)">
        <?php
        $currentDate = date("Y-m-d"); 
        $cuotaDate = date("Y-m-d", strtotime($detalle[$i]["fechapago"]));
        ?>
        <?php if($detalle[$i]["statuspago"] == 1){ ?>
        <label class="custom-control-label text-primary alert-link checkbox-selector" title="Pagada" for="codcuota<?php echo $detalle[$i]["codcuota"]; ?>"><?php echo date("d-m-Y",strtotime($detalle[$i]["fechapago"])); ?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></label> 
        <?php } elseif($detalle[$i]["statuspago"] == 2 || $cuotaDate < $currentDate){ ?>
        <label style="cursor: pointer;" class="custom-control-label text-danger alert-link checkbox-selector" title="Vencida" for="codcuota<?php echo $detalle[$i]["codcuota"]; ?>"><?php echo date("d-m-Y",strtotime($detalle[$i]["fechapago"])); ?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></label>
        <?php } else { ?>
        <label style="cursor: pointer;" class="custom-control-label text-dark alert-link checkbox-selector" for="codcuota<?php echo $detalle[$i]["codcuota"]; ?>"><?php echo date("d-m-Y",strtotime($detalle[$i]["fechapago"])); ?></label>
        <?php } ?>
        </div>
      </div> 
    </div>
    <?php } ?>
    </div>

    </div>

    <script>
    // Obtén todos los checkboxes con la clase "checkbox-selector"
    const checkboxes = document.querySelectorAll('.checkbox-selector');
    let ultimoCheckboxSeleccionado = null;

    let hayCheckboxSeleccionado = false;

    checkboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        ultimoCheckboxSeleccionado = checkbox.id;
        hayCheckboxSeleccionado = true;
      }
    });

    if (!hayCheckboxSeleccionado) {
      // Si no hay ningún checkbox seleccionado, bloquear todos y dejar el primero bien
      checkboxes.forEach((checkbox, index) => {
        checkbox.disabled = true;
        if (index === 0) {
          checkbox.disabled = false;
        }
      });
    }

    // Verifica si se encontró algún checkbox seleccionado
    if (ultimoCheckboxSeleccionado) {
      valorCodCuota = parseInt(ultimoCheckboxSeleccionado.replace('codcuota', ''), 10);

      // Habilita el siguiente checkbox en la secuencia
      const siguienteValorCodCuota = valorCodCuota + 1;
      const siguienteCheckbox = document.getElementById('codcuota' + siguienteValorCodCuota);

      if (siguienteCheckbox) {
        siguienteCheckbox.disabled = false;
      }

      // Desactiva todos los checkboxes excepto el siguiente en la secuencia
      checkboxes.forEach((checkbox) => {
        if (checkbox.id === 'codcuota' + siguienteValorCodCuota) {
          checkbox.disabled = false;
        } else {
          checkbox.disabled = true;
        }
      });
    } 

    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', (event) => {
        const valorCodCuota = parseInt(event.target.id.replace('codcuota', ''), 10);

        // Habilita el checkbox seleccionado
        event.target.disabled = false;

        if (event.target.checked) {
          // Habilita el siguiente checkbox en la secuencia
          const siguienteValorCodCuota = valorCodCuota + 1;
          const siguienteCheckbox = document.getElementById('codcuota' + siguienteValorCodCuota);

          if (siguienteCheckbox) {
            siguienteCheckbox.disabled = false;
          }
        } else {
          // Deshabilita el siguiente checkbox en la secuencia
          const siguienteValorCodCuota = valorCodCuota + 1;
          const siguienteCheckbox = document.getElementById('codcuota' + siguienteValorCodCuota);

          if (siguienteCheckbox) {
            siguienteCheckbox.disabled = true;
          }
        }
      });
    });
    </script>

    <hr>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group has-feedback">
          <label class="control-label">Metodo de Pago: <span class="symbol required"></span></label>
          <select style="color:#000;font-weight:bold;" name="metodopago" id="metodopago" class='form-control' required="" aria-required="true">
          <option value=""> -- SELECCIONE -- </option>
          <?php
          $formapago = new Login();
          $formapago = $formapago->ListarFormasPagos();
          if($formapago==""){
            echo "";    
          } else {
          for($i=0;$i<sizeof($formapago);$i++){ ?>
          <option value="<?php echo encrypt($formapago[$i]['codformapago']); ?>"><?php echo $formapago[$i]['formapago'] ?></option>
          <?php } } ?>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group has-feedback">
          <label class="control-label">Nº de Comprobante: </label>
          <div id="muestra_periodo"><input style="color:#000;font-weight:bold;" type="text" class="form-control" name="comprobante" id="comprobante" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Comprobante" autocomplete="off" required="" aria-required="true"/></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group has-feedback">
          <label class="control-label">Observaciones: </label>
          <textarea class="form-control" type="text" name="observaciones" id="observaciones" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Observaciones" autocomplete="off" rows="1" required="" aria-required="true"></textarea> 
        </div>
      </div>
    </div>

    <hr>

    <input type="hidden" name="total_cuotas" id="total_cuotas"/>
    <input type="hidden" name="monto_cuota" id="monto_cuota"/>
    <input type="hidden" name="total_pagado" id="total_pagado"/>
    <div class="row">
      <div class="col-md-12">
        <p style="font-size:30px;" class="card-subtitle text-dark alert-link">Cuotas a Pagar: <a class="card-subtitle text-danger alert-link" name="txt_cuotas" id="txt_cuotas">0</a></p>

        <p style="font-size:30px;" class="card-subtitle text-dark alert-link">Monto x Cuota: <a class="card-subtitle text-danger alert-link" name="txt_monto" id="txt_monto">0.00</a></p>

        <p style="font-size:30px;" class="card-subtitle text-dark alert-link">Total a Pagar: <a class="card-subtitle text-danger alert-link" name="txt_pagado" id="txt_pagado">0.00</a></p>
      </div>
    </div>

    <hr>

          <div class="text-right">
<button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Guardar</button>
<button class="btn btn-dark" type="button" onclick="ResetPagos();"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Limpiar</button>
                </div>

      </div>
    </div>
  </div>
<?php 
  }
}
######################## BUSCA DETALLES DE CUOTAS CREDITOS POR CLIENTES ########################
?>

<?php
######################## MOSTRAR PAGOS EN VENTANA MODAL ############################
if (isset($_GET['BuscaPagosModal']) && isset($_GET['numero']) && isset($_GET['numero2'])){ 
$reg = $new->PagosporId();
?>
  <table class="table-responsive" border="0">
  <tr>
    <td><strong>N° de Préstamo:</strong> <?php echo $reg[0]['codfactura']; ?></td>
  </tr>
  <tr>
    <td><strong>N° de Comprobante:</strong> <?php echo $reg[0]['numerorecibo']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº <?php echo $reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>:</strong> <?php echo $reg[0]['cedcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombre de Cliente:</strong> <?php echo $reg[0]['nomcliente']; ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Cuotas Pagadas:</strong> <?php echo $reg[0]['totalcuotas']; ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Monto x Cuota:</strong> <?php echo $simbolo.number_format($reg[0]['montoxcuota'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td class="text-danger alert-link"><strong>Importe Pagado:</strong> <?php echo $simbolo.number_format($reg[0]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td class="text-danger alert-link"><strong>Metodo de Pago:</strong> <?php echo $reg[0]['detalles_medios']; ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Nº Recibo:</strong> <?php echo $comprobante = ($reg[0]['comprobante'] == "" ? "**********" : $reg[0]['comprobante']); ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Observaciones:</strong> <?php echo $observaciones = ($reg[0]['observaciones'] == "" ? "**********" : $reg[0]['observaciones']); ?></td>
  </tr>
  <tr>
    <td class="text-dark alert-link"><strong>Fecha Pagado:</strong> <?php echo date("d/m/Y H:i:s",strtotime($reg[0]['fecharecibo'])); ?></td>
  </tr>
</table> 
<?php
} 
######################## MOSTRAR PAGOS EN VENTANA MODAL ############################
?>

<?php
########################## BUSQUEDA PAGOS POR CAJAS Y FECHAS ##########################
if (isset($_GET['BuscaPagosxCajas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPagosxCajas();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Caja : <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?><br>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PAGOSXCAJAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PAGOSXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PAGOSXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
          <th>N°</th>
          <th>N° de<br> Préstamo</th>
          <th>N° de<br> Comprobante</th>
          <th>Nombre de Cliente</th>
          <th>Fecha Pago</th>
          <th>Periodo Pagados</th>
          <th>Cuotas<br> Pagadas</th>
          <th>Monto x<br> Cuota</th>
          <th>Importe<br> Pagado</th>
          <th>...</th>
        </tr>
        </thead>
        <tbody>
<?php
$a=1;
$TotalSubtotal = 0;
$TotalImporte  = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalImporte  += $reg[$i]['totalpagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['numerorecibo']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']." ".$reg[$i]['cedcliente'].":<br>".$reg[$i]['nomcliente']."</span>"; ?>
    <td class="text-dark alert-link"><?php echo date("d/m/Y",strtotime($reg[$i]['fecharecibo']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($reg[$i]['fecharecibo']))."</span>"; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['meses_pagados']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&numero2=<?php echo encrypt($reg[$i]['codrecibo']); ?>&tipo=<?php echo encrypt("COMPROBANTE"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>
    </td>
    </tr>
    <?php } ?>
  <tr>
    <td colspan="8"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PAGOS POR CAJAS Y FECHAS ##########################
?>

<?php
########################## BUSQUEDA PAGOS POR FECHAS ##########################
if (isset($_GET['BuscaPagosxFechas']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPagosxFechas();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PAGOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PAGOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PAGOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
          <th>N°</th>
          <th>N° de<br> Préstamo</th>
          <th>N° de<br> Comprobante</th>
          <th>Caja</th>
          <th>Nombre de Cliente</th>
          <th>Fecha Pago</th>
          <th>Periodo Pagados</th>
          <th>Cuotas<br> Pagadas</th>
          <th>Monto x<br> Cuota</th>
          <th>Importe<br> Pagado</th>
          <th>...</th>
        </tr>
        </thead>
        <tbody>
<?php
$a=1;
$TotalSubtotal = 0;
$TotalImporte  = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalImporte  += $reg[$i]['totalpagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['numerorecibo']; ?></td>
    <td><abbr title="<?php echo "CAJERO: ".$reg[$i]['nombres']; ?>"><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></abbr></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']." ".$reg[$i]['cedcliente'].":<br>".$reg[$i]['nomcliente']."</span>"; ?>
    <td class="text-dark alert-link"><?php echo date("d/m/Y",strtotime($reg[$i]['fecharecibo']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($reg[$i]['fecharecibo']))."</span>"; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['meses_pagados']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&numero2=<?php echo encrypt($reg[$i]['codrecibo']); ?>&tipo=<?php echo encrypt("COMPROBANTE"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>
    </td>
    </tr>
    <?php } ?>
  <tr>
    <td colspan="9"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PAGOS POR FECHAS ##########################
?>


<?php
########################## BUSQUEDA PAGOS POR CLIENTES ##########################
if (isset($_GET['BuscaPagosxClientes']) && isset($_GET['codcliente'])) {
  
  $codcliente = limpiar($_GET['codcliente']);

 if($codcliente=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR REALICE LA BÚSQUEDA DEL CLIENTE CORRECTAMENTE</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarPagosxClientes();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> <?php echo $documcliente = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[0]['documento']); ?> : <?php echo $reg[0]['cedcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Cliente : <?php echo $reg[0]['nomcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Nº de Celular : <?php echo $movil = ($reg[0]['celcliente'] == "" ? "******" : $reg[0]['celcliente']); ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Correo : <?php echo $email = ($reg[0]['email'] == "" ? "******" : $reg[0]['email']); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcliente=<?php echo $codcliente; ?>&tipo=<?php echo encrypt("PAGOSXCLIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PAGOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PAGOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
        <thead>
        <tr>
          <th>N°</th>
          <th>N° de<br> Préstamo</th>
          <th>N° de<br> Comprobante</th>
          <th>Caja</th>
          <th>Fecha Pago</th>
          <th>Periodo Pagados</th>
          <th>Cuotas<br> Pagadas</th>
          <th>Monto x<br> Cuota</th>
          <th>Importe<br> Pagado</th>
          <th>...</th>
        </tr>
        </thead>
        <tbody>
<?php
$a=1;
$TotalSubtotal = 0;
$TotalImporte  = 0;

for($i=0;$i<sizeof($reg);$i++){ 
   
$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalImporte  += $reg[$i]['totalpagado'];
?>
  <tr>
    <td><?php echo $a++; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['codfactura']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['numerorecibo']; ?></td>
    <td><abbr title="<?php echo "CAJERO: ".$reg[$i]['nombres']; ?>"><?php echo $caja = ($reg[$i]['codcaja'] == '0' ?  "******" : "<span class='text-dark alert-link'>".$reg[$i]['nrocaja'].":</span><br>".$reg[$i]['nomcaja']); ?></abbr></td>
    <td class="text-dark alert-link"><?php echo date("d/m/Y",strtotime($reg[$i]['fecharecibo']))."<br><span class='text-dark alert-link'>".date("H:i:s",strtotime($reg[$i]['fecharecibo']))."</span>"; ?></td>
    <td class="text-danger alert-link"><?php echo $reg[$i]['meses_pagados']; ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&numero2=<?php echo encrypt($reg[$i]['codrecibo']); ?>&tipo=<?php echo encrypt("COMPROBANTE"); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>
    </td>
    </tr>
    <?php } ?>
  <tr>
    <td colspan="8"></td>
    <td class="text-dark alert-link"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
    <td></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PAGOS POR CLIENTES ##########################
?>

<?php
########################## BUSQUEDA PAGOS EN MORA POR FECHAS ##########################
if (isset($_GET['BuscaMoraxFechas']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA DESDE PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR INGRESE FECHA HASTA PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> LA FECHA DESDE NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarMoraxFechas();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Desde : <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta : <?php echo date("d-m-Y", strtotime($hasta)); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codsucursal=<?php echo $codsucursal; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("MORAXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codsucursal=<?php echo $codsucursal; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("MORAXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codsucursal=<?php echo $codsucursal; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("MORAXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Nº de Préstamo</th>
                        <th>Nombre de Cliente</th>
                        <th>Fecha Préstamo</th>
                        <th>Periodo en Mora</th>
                        <th>Cuotas en Mora</th>
                        <th>Monto x Cuota</th>
                        <th>Importe Mora</th>
                        <th>...</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
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
    <td class="text-dark alert-link"><?php echo $reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']." ".$reg[$i]['cedcliente'].":<br>".$reg[$i]['nomcliente']."</span>"; ?>
    <td class="text-dark alert-link"><?php echo date("d-m-Y",strtotime($reg[$i]['fechaprestamo'])); ?></td>
    <td class="text-danger alert-link"><?php echo str_replace("<br>","; ", $reg[$i]['meses_mora']); ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['cuotas_mora']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>
    </td>
  </tr>
  <?php  }  ?>
  <tr>
    <td colspan="5"></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><?php echo $TotalCuotas; ?></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalSubtotal, 2, '.', ','); ?></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PAGOS EN MORA POR FECHAS ##########################
?>

<?php
########################## BUSQUEDA PAGOS EN MORA POR CLIENTES ##########################
if (isset($_GET['BuscaClientesxMora']) && isset($_GET['codcliente'])) {
  
  $codcliente = limpiar($_GET['codcliente']);

 if($codcliente=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> POR FAVOR REALICE LA BÚSQUEDA DEL CLIENTE CORRECTAMENTE</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarMoraxClientes();
?>

  <div class="row">
      <div id="custom_styles" class="col-lg-12 layout-spacing col-md-12">
          <div class="statbox widget box box-shadow">
              <div class="widget-header">
                  <div class="row">
                      <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                          <h4>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> <?php echo $documcliente = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : "".$reg[0]['documento']); ?> : <?php echo $reg[0]['cedcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Cliente : <?php echo $reg[0]['nomcliente']; ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Nº de Celular : <?php echo $movil = ($reg[0]['celcliente'] == "" ? "******" : $reg[0]['celcliente']); ?><br>

                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Correo : <?php echo $email = ($reg[0]['email'] == "" ? "******" : $reg[0]['email']); ?>
                          </h4>
                      </div>                 
                  </div>
              </div>

        <div class="widget-content widget-content-area">

        <div class="table">
          <div class="btn-group">
            <a class="btn waves-effect waves-light btn-primary" href="reportepdf?codcliente=<?php echo $codcliente; ?>&tipo=<?php echo encrypt("MORAXCLIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Pdf</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("MORAXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Excel</a>

            <a class="btn waves-effect waves-light btn-primary" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("MORAXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Word</a>
          </div>
        </div>
                               
    <div id="div3"><table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Nº de Préstamo</th>
                        <th>Fecha Préstamo</th>
                        <th>Periodo en Mora</th>
                        <th>Cuotas en Mora</th>
                        <th>Monto x Cuota</th>
                        <th>Importe Mora</th>
                        <th>...</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
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
    <td class="text-dark alert-link"><?php echo date("d-m-Y",strtotime($reg[$i]['fechaprestamo'])); ?></td>
    <td class="text-danger alert-link"><?php echo str_replace("<br>","; ", $reg[$i]['meses_mora']); ?></td>
    <td class="text-dark alert-link"><?php echo $reg[$i]['cuotas_mora']; ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','); ?></td>
    <td>
    <span class="text-dark" style="cursor: pointer;" title="Factura" onClick="VentanaCentrada('reportepdf?numero=<?php echo encrypt($reg[$i]["codprestamo"]); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']); ?>', '', '', '1024', '568', 'true');"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></span>
    </td>
  </tr>
  <?php  }  ?>
  <tr>
    <td colspan="4"></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><?php echo $TotalCuotas; ?></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalSubtotal, 2, '.', ','); ?></td>
    <td class="text-danger alert-link" style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalDeuda, 2, '.', ','); ?></td>
  </tr>
  </tbody>
  </table></div>

        </div>
      </div>
    </div>
  </div>

 <?php
  } 
}
########################## BUSQUEDA PAGOS EN MORA POR CLIENTES ##########################
?>