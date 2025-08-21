<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$con       = new Login();
$con       = $con->ConfiguracionPorId();
$simbolo   = $con[0]['simbolo'] ?? $con[0]['simbolo'];

$tipo      = decrypt($_GET['tipo']);
$documento = decrypt($_GET['documento']);
$extension = $documento == 'EXCEL' ? '.xls' : '.doc';

switch($tipo)
{
################################## MODULO DE USUARIOS ##################################
case 'USUARIOS': 

$tra = new Login();
$reg = $tra->ListarUsuarios();

$archivo = str_replace(" ", "_","LISTADO DE USUARIOS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>TIPO DE DOCUMENTO</th>
      <th>Nº DE DOCUMENTO</th>
      <th>NOMBRES Y APELLIDOS</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>SEXO</th>
      <th>Nº DE TELEFÓNO</th>
      <th>Nº DE CELULAR</th>
      <th>DIRECCIÓN DOMICILIARIA</th>
      <th>DEPARTAMENTO</th>
      <th>PROVINCIA</th>
      <th>CORREO ELECTRONICO</th>
      <th>FECHA NACIMIENTO</th>
      <?php } ?>
      <th>USUARIO</th>
      <th>NIVEL</th>
      <th>STATUS</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['documusuario'] == '0' ? "*********" : $reg[$i]['documento']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['dni']; ?></td>
    <td><?php echo $reg[$i]['nombres']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['sexo']; ?></td>
    <td><?php echo $reg[$i]['telefono'] == '' ? "*********" : '&nbsp;'.$reg[$i]['telefono']; ?></td>
    <td><?php echo $reg[$i]['celular'] == '' ? "*********" : '&nbsp;'.$reg[$i]['celular']; ?></td>
    <td><?php echo $reg[$i]['direccion']; ?></td>
    <td><?php echo $reg[$i]['coddepartamento'] == '0' ? "*********" : $reg[$i]['departamento']; ?></td>
    <td><?php echo $reg[$i]['codprovincia'] == '0' ? "*********" : $reg[$i]['provincia']; ?></td>
    <td><?php echo $reg[$i]['email']; ?></td>
    <td><?php echo $reg[$i]['fnacimiento'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[$i]['fnacimiento'])); ?></td>
    <?php } ?>
    <td><?php echo $reg[$i]['usuario']; ?></td>
    <td><?php echo $reg[$i]['nivel']; ?></td>
    <td><?php echo $status = ( $reg[$i]['status'] == 1 ? "<span style='font-size:12px;color:#0b1379;font-weight:bold;'> ACTIVO</span>" : "<span style='font-size:12px;color:#e7515a;font-weight:bold;'> INACTIVO</span>"); ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;

case 'LOGS': 

$archivo = str_replace(" ", "_","LISTADO LOGS DE ACCESO");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>IP EQUIPO</th>
      <th>TIEMPO DE ENTRADA</th>
      <th>NAVEGADOR DE ACCESO</th>
      <th>PÁGINAS DE ACCESO</th>
      <th>USUARIOS</th>
    </tr>
<?php 
$tra = new Login();
$reg = $tra->ListarLogs();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['ip']; ?></td>
    <td><?php echo $reg[$i]['tiempo']; ?></td>
    <td><?php echo $reg[$i]['detalles']; ?></td>
    <td><?php echo $reg[$i]['paginas']; ?></td>
    <td><?php echo $reg[$i]['usuario']; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;
################################ MODULO DE USUARIOS ##############################



############################### MODULO DE CONFIGURACIONES ###############################
case 'DOCUMENTOS': 

$archivo = str_replace(" ", "_","LISTADO DE DOCUMENTOS TRIBUTARIOS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
         <tr>
           <th>Nº</th>
           <th>NOMBRE DE DOCUMENTO</th>
           <th>DESCRIPCIÓN DE DOCUMENTO</th>
         </tr>
      <?php 
$tra = new Login();
$reg = $tra->ListarDocumentos();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo $reg[$i]['documento']; ?></td>
      <td><?php echo $reg[$i]['descripcion']; ?></td>
    </tr>
    <?php } } ?>
</table>
<?php
break;

case 'PROVINCIAS': 

$archivo = str_replace(" ", "_","LISTADO DE PROVINCIAS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>NOMBRE DE PROVINCIA</th>
    </tr>
<?php 
$tra = new Login();
$reg = $tra->ListarProvincias();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
    <tr class="even_row">
       <td><?php echo $reg[$i]['codprovincia']; ?></td>
       <td><?php echo $reg[$i]['provincia']; ?></td>
    </tr>
<?php } } ?>
</table>
<?php
break;

case 'DEPARTAMENTOS': 

$archivo = str_replace(" ", "_","LISTADO DE DEPARTAMENTOS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>NOMBRE DE DEPARTAMENTO</th>
      <th>NOMBRE DE PROVINCIA</th>
    </tr>
<?php 
$tra = new Login();
$reg = $tra->ListarDepartamentos();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
    <tr class="even_row">
      <td><?php echo $reg[$i]['coddepartamento']; ?></td>
      <td><?php echo $reg[$i]['departamento']; ?></td>
      <td><?php echo $reg[$i]['provincia']; ?></td>
    </tr>
<?php } } ?>
</table>
<?php
break;

case 'TIPOMONEDA': 

$archivo = str_replace(" ", "_","LISTADO DE TIPOS DE MONEDA");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>NOMBRE DE MONEDA</th>
      <th>SIGLAS</th>
      <th>SIMBOLO</th>
    </tr>
<?php 
$tra = new Login();
$reg = $tra->ListarTipoMoneda();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['moneda']; ?></td>
    <td><?php echo $reg[$i]['siglas']; ?></td>
    <td><?php echo $reg[$i]['simbolo']; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;

case 'FORMASPAGOS': 

$archivo = str_replace(" ", "_","LISTADO DE FORMAS DE PAGOS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>FORMA DE PAGO</th>
    </tr>
<?php 
$tra = new Login();
$reg = $tra->ListarFormasPagos();

if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $reg[$i]['codformapago']; ?></td>
    <td><?php echo $reg[$i]['formapago']; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;
############################### MODULO DE CONFIGURACIONES ##############################










############################### MODULO DE CLIENTES ###################################
case 'CLIENTES':

$tra = new Login();
$reg = $tra->ListarClientes();

$archivo = str_replace(" ", "_","LISTADO GENERAL DE CLIENTES"); 

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>TIPO DE DOCUMENTO</th>
      <th>Nº DE DOCUMENTO</th>
      <th>NOMBRES Y APELLIDOS</th>
      <th>SEXO</th>
      <th>Nº DE TELEFONO</th>
      <th>Nº DE CELULAR</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>DIRECCIÓN DOMICILIARIA</th>
      <th>DEPARTAMENTO</th>
      <th>PROVINCIA</th>
      <th>CORREO ELECTRONICO</th>
      <th>Nº DOCUMENTO DE REFERENCIA</th>
      <th>PERSONA DE REFERENCIA</th>
      <th>Nº DE CELULAR</th>
      <?php } ?>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['documcliente'] == '0' ? "*********" : $reg[$i]['documento']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['cedcliente']; ?></td>
    <td><?php echo $reg[$i]['nomcliente']; ?></td>
    <td><?php echo $reg[$i]['sexocliente']; ?></td>
    <td><?php echo $reg[$i]['tlfcliente'] == '' ? "*********" : '&nbsp;'.$reg[$i]['tlfcliente']; ?></td>
    <td><?php echo $reg[$i]['celcliente'] == '' ? "*********" : '&nbsp;'.$reg[$i]['celcliente']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['direccliente'] == '' ? "*********" : $reg[$i]['direccliente']; ?></td>
    <td><?php echo $reg[$i]['coddepartamento'] == '0' ? "*********" : $reg[$i]['departamento']; ?></td>
    <td><?php echo $reg[$i]['codprovincia'] == '0' ? "*********" : $reg[$i]['provincia']; ?></td>
    <td><?php echo $reg[$i]['email'] == '' ? "*********" : $reg[$i]['email']; ?></td>
    <td><?php echo $reg[$i]['cedreferencia'] == '' ? "*********" : '&nbsp;'.$reg[$i]['cedreferencia']; ?></td>
    <td><?php echo $reg[$i]['nomreferencia'] == '' ? "*********" : $reg[$i]['nomreferencia']; ?></td>
    <td><?php echo $reg[$i]['celreferencia'] == '' ? "*********" : '&nbsp;'.$reg[$i]['celreferencia']; ?></td>
    <?php } ?>
  </tr>
  <?php } } ?>
</table>
<?php
break;

case 'CLIENTESCSV':

$tra = new Login();
$reg = $tra->ListarClientes(); 

$archivo = str_replace(" ", "_","LISTADO GENERAL DE CLIENTES");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
<?php
if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['documcliente']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['cedcliente']; ?></td>
    <td><?php echo $reg[$i]['nomcliente']; ?></td>
    <td><?php echo $reg[$i]['sexocliente']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['tlfcliente']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['celcliente']; ?></td>
    <td><?php echo $reg[$i]['codprovincia']; ?></td>
    <td><?php echo $reg[$i]['coddepartamento']; ?></td>
    <td><?php echo $reg[$i]['direccliente']; ?></td>
    <td><?php echo $reg[$i]['email']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['cedreferencia']; ?></td>
    <td><?php echo $reg[$i]['nomreferencia']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['celreferencia']; ?></td>
    <td><?php echo $reg[$i]['status']; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;
############################### MODULO DE CLIENTES ###################################








##################################### MODULO DE CAJAS ###################################
case 'CAJAS':

$tra = new Login();
$reg = $tra->ListarCajas(); 

$archivo = str_replace(" ", "_","LISTADO DE CAJAS ASIGNADAS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE CAJA</th>
      <th>NOMBRE DE CAJA</th>
      <th>RESPONSABLE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1; 
for($i=0;$i<sizeof($reg);$i++){
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['nrocaja']; ?></td>
    <td><?php echo $reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['dni'].": ".$reg[$i]['nombres']; ?></td>
  </tr>
  <?php } } ?>
</table>
<?php
break;

case 'APERTURAS':

$tra = new Login();
$reg = $tra->ListarAperturas(); 

$archivo = str_replace(" ", "_","LISTADO DE APERTURAS DE CAJAS");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th>Nº</th>
    <th>NOMBRE DE CAJA</th>
    <?php if ($documento == "EXCEL") { ?>
    <th>RESPONSABLE</th>
    <th>APERTURA</th>
    <th>CIERRE</th>
    <th>OBSERVACIONES</th>
    <?php } ?>
    <th>MONTO INICIAL</th>
    <th>PRÉSTAMOS</th>
    <th>INGRESOS</th>
    <th>EGRESOS</th>
    <th>TOTAL EN COBROS</th>
    <th>COBROS EN EFECTIVO</th>
    <th>EFECTIVO EN CAJA</th>
    <th>EFECTIVO DISPONIBLE</th>
    <th>DIFERENCIA EFECTIVO</th>
  </tr>
<?php 
if($reg==""){
echo "";      
} else {

$Monto_Efectivo  = 0;
$TotalPrestamos  = 0;
$TotalIngresos   = 0;
$TotalEgresos    = 0;
$TotalCobros     = 0;
$TotalEfectivo  = 0;
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
$TotalCaja       += (($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]));
//$TotalCaja       += $reg[$i]['efectivocaja'];
$TotalDisponible += $reg[$i]['dineroefectivo'];
$TotalDiferencia += $reg[$i]['diferencia'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['dni'].": ".$reg[$i]['nombres']; ?></td>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaapertura'])); ?></td>
    <td><?php echo $reg[$i]['fechacierre'] == '0000-00-00 00:00:00' ? "*********" : date("d/m/Y H:i:s",strtotime($reg[$i]['fechacierre'])); ?></td>
    <td><?php echo $reg[$i]['comentarios'] == '' ? "*********" : $reg[$i]['comentarios']; ?></td>
    <?php } ?>
    <td><?php echo $simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['prestamos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['ingresos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['egresos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['pagos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($Suma_Efectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr style="color:#0b0c11;font-weight:bold;">
    <?php echo $documento == "EXCEL" ? '<td colspan="7"></td>' : '<td colspan="3"></td>'; ?>
    <td><?php echo $simbolo.number_format($TotalPrestamos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalIngresos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalEgresos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCobros, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalEfectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCaja, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDisponible, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDiferencia, 2, '.', ','); ?></td>
  </tr>
<?php } ?>
</table>
<?php
break;

case 'APERTURASXFECHAS':

$tra = new Login();
$reg = $tra->BuscarAperturasxFechas(); 

$archivo = str_replace(" ", "_","LISTADO DE APERTURAS DE (CAJA ".$reg[0]['nrocaja'].": ".$reg[0]['nomcaja']." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <th>Nº</th>
    <?php if ($documento == "EXCEL") { ?>
    <th>RESPONSABLE</th>
    <th>APERTURA</th>
    <th>CIERRE</th>
    <th>OBSERVACIONES</th>
    <?php } ?>
    <th>MONTO INICIAL</th>
    <th>PRÉSTAMOS</th>
    <th>INGRESOS</th>
    <th>EGRESOS</th>
    <th>TOTAL EN COBROS</th>
    <th>COBROS EN EFECTIVO</th>
    <th>EFECTIVO EN CAJA</th>
    <th>EFECTIVO DISPONIBLE</th>
    <th>DIFERENCIA EFECTIVO</th>
  </tr>
<?php 
if($reg==""){
echo "";      
} else {

$Monto_Efectivo  = 0;
$TotalPrestamos  = 0;
$TotalIngresos   = 0;
$TotalEgresos    = 0;
$TotalCobros     = 0;
$TotalEfectivo  = 0;
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
$TotalCaja       += (($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]));
//$TotalCaja       += $reg[$i]['efectivocaja'];
$TotalDisponible += $reg[$i]['dineroefectivo'];
$TotalDiferencia += $reg[$i]['diferencia'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['dni'].": ".$reg[$i]['nombres']; ?></td>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaapertura'])); ?></td>
    <td><?php echo $reg[$i]['fechacierre'] == '0000-00-00 00:00:00' ? "*********" : date("d/m/Y H:i:s",strtotime($reg[$i]['fechacierre'])); ?></td>
    <td><?php echo $reg[$i]['comentarios'] == '' ? "*********" : $reg[$i]['comentarios']; ?></td>
    <?php } ?>
    <td><?php echo $simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['prestamos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['ingresos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['egresos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['pagos'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($Suma_Efectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr style="color:#0b0c11;font-weight:bold;">
    <?php echo $documento == "EXCEL" ? '<td colspan="6"></td>' : '<td colspan="2"></td>'; ?>
    <td><?php echo $simbolo.number_format($TotalPrestamos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalIngresos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalEgresos, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCobros, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalEfectivo, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalCaja, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDisponible, 2, '.', ','); ?></td>
    <td><?php echo $simbolo.number_format($TotalDiferencia, 2, '.', ','); ?></td>
  </tr>
<?php } ?>
</table>
<?php
break;

case 'MOVIMIENTOS':

$tra = new Login();
$reg = $tra->ListarMovimientos(); 

$archivo = str_replace(" ", "_","LISTADO DE MOVIMIENTOS DE CAJAS");
header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>NOMBRE DE CAJA</th>
      <th>RESPONSABLE</th>
      <th>DESCRIPCIÓN</th>
      <th>TIPO</th>
      <th>FECHA MOVIMIENTO</th>
      <th>MONTO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalIngresos=0;
$TotalEgresos=0;
for($i=0;$i<sizeof($reg);$i++){ 
$TotalIngresos+= ($reg[$i]['tipomovimiento'] == 'INGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
$TotalEgresos+= ($reg[$i]['tipomovimiento'] == 'EGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['dni'].": ".$reg[$i]['nombres']; ?></td>
    <td><?php echo $reg[$i]['descripcionmovimiento']; ?></td>
    <td><?php echo $reg[$i]['tipomovimiento']; ?></td>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechamovimiento'])); ?></td>
    <td><?php echo $simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5"></td>
    <td><strong>TOTAL INGRESOS</strong></td>
    <td><?php echo $simbolo.number_format($TotalIngresos, 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td colspan="5"></td>
    <td><strong>TOTAL EGRESOS</strong></td>
    <td><?php echo $simbolo.number_format($TotalEgresos, 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
</table>
<?php
break;

case 'MOVIMIENTOSXFECHAS':

$tra = new Login();
$reg = $tra->BuscarMovimientosxFechas(); 

$archivo = str_replace(" ", "_","LISTADO DE MOVIMIENTOS EN (CAJA ".$reg[0]['nrocaja'].": ".$reg[0]['nomcaja']." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>NOMBRE DE CAJA</th>
      <th>RESPONSABLE</th>
      <th>DESCRIPCIÓN</th>
      <th>TIPO</th>
      <th>FECHA MOVIMIENTO</th>
      <th>MONTO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalIngresos=0;
$TotalEgresos=0;
for($i=0;$i<sizeof($reg);$i++){ 
$TotalIngresos+= ($reg[$i]['tipomovimiento'] == 'INGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
$TotalEgresos+= ($reg[$i]['tipomovimiento'] == 'EGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
      <td><?php echo $reg[$i]['dni'].": ".$reg[$i]['nombres']; ?></td>
      <td><?php echo $reg[$i]['descripcionmovimiento']; ?></td>
      <td><?php echo $reg[$i]['tipomovimiento']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechamovimiento'])); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','); ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="5"></td>
      <td><strong>TOTAL INGRESOS</strong></td>
      <td><?php echo $simbolo.number_format($TotalIngresos, 2, '.', ','); ?></td>
    </tr>
    <tr>
      <td colspan="5"></td>
      <td><strong>TOTAL EGRESOS</strong></td>
      <td><?php echo $simbolo.number_format($TotalEgresos, 2, '.', ','); ?></td>
    </tr>
    <?php } ?>
</table>
<?php
break;
#################################### MODULO DE CAJAS ####################################











############################### MODULO DE PRESTAMOS ###############################
case 'PRESTAMOS':

$tra = new Login();
$reg = $tra->ListarPrestamos(); 

$archivo = str_replace(" ", "_","LISTADO DE PRESTAMOS");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>TOTAL IMPORTE</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$CuotasPagadas    = 0;
$CuotasPendientes = 0;
//$CuotasMora     = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$CuotasPagadas    += $reg[$i]['cuotaspagadas'];
$CuotasPendientes += $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
//$CuotasMora     += $reg[$i]['cuotas_mora'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['periodopago']; ?></td>
      <td><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas'] == "" ? "0" : $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="9"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $CuotasPagadas; ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;

case 'PRESTAMOSPENDIENTES':

$tra = new Login();
$reg = $tra->ListarPrestamosPendientes(); 

$archivo = str_replace(" ", "_","LISTADO DE PRESTAMOS PENDIENTES");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>TOTAL IMPORTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['periodopago']; ?></td>
      <td><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="10"></td>' : '<td colspan="8"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;

case 'PRESTAMOSXCAJAS':

$tra = new Login();
$reg = $tra->BuscarPrestamosxCajas(); 

$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS EN CAJA (Nº: ".$reg[0]["nrocaja"].": ".$reg[0]["nomcaja"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>TOTAL IMPORTE</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$CuotasPagadas    = 0;
$CuotasPendientes = 0;
//$CuotasMora     = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$CuotasPagadas    += $reg[$i]['cuotaspagadas'];
$CuotasPendientes += $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
//$CuotasMora     += $reg[$i]['cuotas_mora'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td><?php echo $reg[$i]['periodopago']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas'] == "" ? "0" : $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="10"></td>' : '<td colspan="8"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $CuotasPagadas; ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;

case 'PRESTAMOSXFECHAS':

$estado_prestamo = limpiar(decrypt($_GET["estado_prestamo"]));

$tra = new Login();
$reg = $tra->BuscarPrestamosxFechas(); 

if($estado_prestamo == 1){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PENDIENTES POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 2){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS APROBADOS POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 3){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS RECHAZADOS POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 4){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS CANCELADOS POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 5){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PAGADOS POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 6){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS EN GENERAL POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
}

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>TOTAL IMPORTE</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$CuotasPagadas    = 0;
$CuotasPendientes = 0;
//$CuotasMora     = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$CuotasPagadas    += $reg[$i]['cuotaspagadas'];
$CuotasPendientes += $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
//$CuotasMora     += $reg[$i]['cuotas_mora'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $caja = ($reg[$i]['codcaja'] == 0 ? "********" : $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']); ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['periodopago']; ?></td>
      <td><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas'] == "" ? "0" : $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="9"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $CuotasPagadas; ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;

case 'PRESTAMOSXCLIENTES':

$estado_prestamo = limpiar(decrypt($_GET["estado_prestamo"]));

$tra = new Login();
$reg = $tra->BuscarPrestamosxClientes(); 

if($estado_prestamo == 1){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PENDIENTES DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
} elseif($estado_prestamo == 2){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS APROBADOS DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
} elseif($estado_prestamo == 3){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS RECHAZADOS DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
} elseif($estado_prestamo == 4){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS CANCELADOS DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
} elseif($estado_prestamo == 5){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PAGADOS DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
} elseif($estado_prestamo == 6){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS EN GENERAL DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");
}

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE CAJA</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>TOTAL IMPORTE</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$CuotasPagadas    = 0;
$CuotasPendientes = 0;
//$CuotasMora     = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$CuotasPagadas    += $reg[$i]['cuotaspagadas'];
$CuotasPendientes += $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
//$CuotasMora     += $reg[$i]['cuotas_mora'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $caja = ($reg[$i]['codcaja'] == 0 ? "********" : $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']); ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['periodopago']; ?></td>
      <td><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas'] == "" ? "0" : $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="10"></td>' : '<td colspan="8"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $CuotasPagadas; ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;

case 'PRESTAMOSXUSUARIOS':

$estado_prestamo = limpiar(decrypt($_GET["estado_prestamo"]));

$tra = new Login();
$reg = $tra->BuscarPrestamosxUsuarios(); 

if($estado_prestamo == 1){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PENDIENTES DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 2){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS APROBADOS DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 3){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS RECHAZADOS DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 4){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS CANCELADOS DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 5){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS PAGADOS DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
} elseif($estado_prestamo == 6){
$archivo = str_replace(" ", "_","LISTADO DE PRÉSTAMOS EN GENERAL DEL USUARIO (Nº: ".$reg[0]["dni"].": ".$reg[0]["nombres"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");
}

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>
      <th>PERIODO DE PAGO</th>
      <th>ESTADO</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MONTO DE PRÉSTAMO</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>Nº DE CUOTA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>TOTAL IMPORTE</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$MontoxCuota      = 0;
$CuotasPagadas    = 0;
$CuotasPendientes = 0;
//$CuotasMora     = 0;
$TotalImporte     = 0;
$TotalPagado      = 0;
$TotalPendiente   = 0;

for($i=0;$i<sizeof($reg);$i++){ 

$MontoxCuota      += $reg[$i]['montoxcuota'];
$CuotasPagadas    += $reg[$i]['cuotaspagadas'];
$CuotasPendientes += $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
//$CuotasMora     += $reg[$i]['cuotas_mora'];
$TotalImporte     += $reg[$i]['totalpago'];
$TotalPagado      += $reg[$i]['creditopagado'];
$TotalPendiente   += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo $caja = ($reg[$i]['codcaja'] == 0 ? "********" : $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']); ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['periodopago']; ?></td>
      <td><?php if($reg[$i]['statusprestamo']==1) { 
      echo '<a style="color:#e2a03f;font-weight:bold;">PENDIENTE</>'; 
      } elseif($reg[$i]['statusprestamo']==2) { 
      echo '<a style="color:#8dbf42;font-weight:bold;">APROBADA</>'; 
      } elseif($reg[$i]['statusprestamo']==3) { 
      echo '<a style="color:#e7515a;font-weight:bold;">RECHAZADA</>'; 
      } elseif($reg[$i]['statusprestamo']==4) { 
      echo '<a style="color:#5c1ac3;font-weight:bold;">CANCELADA</>'; 
      } elseif($reg[$i]['statusprestamo']==5) {  
      echo '<a style="color:#197ca8;font-weight:bold;">PAGADA</>';
      } ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['totalintereses'], 2, '.', ','); ?><sup><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?>%</sup></td>
      <?php } ?>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas'] == "" ? "0" : $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="9"></td>'; ?>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $CuotasPagadas; ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></td>
      <?php } ?>
    </tr>
</table>
<?php
break;
############################### MODULO DE PRESTAMOS ###############################











############################### MODULO DE PAGOS ###############################
case 'PAGOS':

$tra = new Login();
$reg = $tra->ListarPagos();  

$archivo = str_replace(" ", "_","LISTADO DE PAGOS DE PRÉSTAMOS");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE COMPROBANTE</th>
      <th>CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MÉTODO DE PAGO</th>
      <th>Nº DE RECIBO</th>
      <th>OBSERVACIONES</th>
      <?php } ?>
      <th>FECHA DE PAGO</th>           
      <th>PERIODO PAGADOS</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>IMPORTE PAGADO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalPagado   = 0;

for($i=0;$i<sizeof($reg);$i++){
//$meses_pagados = explode("<br>",utf8_decode($reg[$i]['meses_pagados'])); 
$meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['totalcuotas'];
$TotalPagado   += $reg[$i]['totalpagado'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['numerorecibo']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['detalles_medios']; ?></td>
    <td><?php echo $comprobante = ($reg[$i]['comprobante'] == "" ? "**********" : $reg[$i]['comprobante']); ?></td>
    <td><?php echo $observaciones = ($reg[$i]['observaciones'] == "" ? "**********" : $reg[$i]['observaciones']); ?></td>
    <?php } ?>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_pagados; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="12"></td>' : '<td colspan="9"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'PAGOSDELDIA':

$tra = new Login();
$reg = $tra->ListarPagosxDia();  

$archivo = str_replace(" ", "_","LISTADO DE PAGOS DE PRÉSTAMOS DEL DIA (".date("d/m/Y").")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE COMPROBANTE</th>
      <th>CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MÉTODO DE PAGO</th>
      <th>Nº DE RECIBO</th>
      <th>OBSERVACIONES</th>
      <?php } ?>
      <th>FECHA DE PAGO</th>           
      <th>PERIODO PAGADOS</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>IMPORTE PAGADO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalPagado   = 0;

for($i=0;$i<sizeof($reg);$i++){
//$meses_pagados = explode("<br>",utf8_decode($reg[$i]['meses_pagados'])); 
$meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['totalcuotas'];
$TotalPagado   += $reg[$i]['totalpagado'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['numerorecibo']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['detalles_medios']; ?></td>
    <td><?php echo $comprobante = ($reg[$i]['comprobante'] == "" ? "**********" : $reg[$i]['comprobante']); ?></td>
    <td><?php echo $observaciones = ($reg[$i]['observaciones'] == "" ? "**********" : $reg[$i]['observaciones']); ?></td>
    <?php } ?>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_pagados; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="12"></td>' : '<td colspan="9"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'PAGOSXCAJAS':

$tra = new Login();
$reg = $tra->BuscarPagosxCajas(); 

$archivo = str_replace(" ", "_","LISTADO DE PAGOS DE PRÉSTAMOS EN CAJA (Nº: ".$reg[0]["nrocaja"].": ".$reg[0]["nomcaja"]." DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE COMPROBANTE</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MÉTODO DE PAGO</th>
      <th>Nº DE RECIBO</th>
      <th>OBSERVACIONES</th>
      <?php } ?>
      <th>FECHA DE PAGO</th>           
      <th>PERIODO PAGADOS</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>IMPORTE PAGADO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalPagado   = 0;

for($i=0;$i<sizeof($reg);$i++){
$meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['totalcuotas'];
$TotalPagado   += $reg[$i]['totalpagado'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['numerorecibo']; ?></td>
    <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['detalles_medios']; ?></td>
    <td><?php echo $comprobante = ($reg[$i]['comprobante'] == "" ? "**********" : $reg[$i]['comprobante']); ?></td>
    <td><?php echo $observaciones = ($reg[$i]['observaciones'] == "" ? "**********" : $reg[$i]['observaciones']); ?></td>
    <?php } ?>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_pagados; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="8"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'PAGOSXFECHAS':

$tra = new Login();
$reg = $tra->BuscarPagosxFechas(); 

$archivo = str_replace(" ", "_","LISTADO DE PAGOS DE PRÉSTAMOS POR FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE COMPROBANTE</th>
      <th>CAJA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MÉTODO DE PAGO</th>
      <th>Nº DE RECIBO</th>
      <th>OBSERVACIONES</th>
      <?php } ?>
      <th>FECHA DE PAGO</th>           
      <th>PERIODO PAGADOS</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>IMPORTE PAGADO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalPagado   = 0;

for($i=0;$i<sizeof($reg);$i++){
$meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['totalcuotas'];
$TotalPagado   += $reg[$i]['totalpagado'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['numerorecibo']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['detalles_medios']; ?></td>
    <td><?php echo $comprobante = ($reg[$i]['comprobante'] == "" ? "**********" : $reg[$i]['comprobante']); ?></td>
    <td><?php echo $observaciones = ($reg[$i]['observaciones'] == "" ? "**********" : $reg[$i]['observaciones']); ?></td>
    <?php } ?>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_pagados; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="12"></td>' : '<td colspan="9"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'PAGOSXCLIENTES':

$tra = new Login();
$reg = $tra->BuscarPagosxClientes(); 

$archivo = str_replace(" ", "_","LISTADO DE PAGOS DE PRÉSTAMOS DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>Nº DE COMPROBANTE</th>
      <th>CAJA</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MÉTODO DE PAGO</th>
      <th>Nº DE RECIBO</th>
      <th>OBSERVACIONES</th>
      <?php } ?>
      <th>FECHA DE PAGO</th>           
      <th>PERIODO PAGADOS</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS PAGADAS</th>
      <th>IMPORTE PAGADO</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal = 0;
$TotalCuotas   = 0;
$TotalPagado   = 0;

for($i=0;$i<sizeof($reg);$i++){
$meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

$TotalSubtotal += $reg[$i]['montoxcuota'];
$TotalCuotas   += $reg[$i]['totalcuotas'];
$TotalPagado   += $reg[$i]['totalpagado'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['numerorecibo']; ?></td>
    <td><?php echo '&nbsp;'.$reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
    <?php if ($documento == "EXCEL") { ?>
    <td><?php echo $reg[$i]['detalles_medios']; ?></td>
    <td><?php echo $comprobante = ($reg[$i]['comprobante'] == "" ? "**********" : $reg[$i]['comprobante']); ?></td>
    <td><?php echo $observaciones = ($reg[$i]['observaciones'] == "" ? "**********" : $reg[$i]['observaciones']); ?></td>
    <?php } ?>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_pagados; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['totalcuotas']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="8"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'MORAXFECHAS':

$tra = new Login();
$reg = $tra->BuscarMoraxFechas(); 

$archivo = str_replace(" ", "_","LISTADO DE PAGOS EN MORA (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>FECHA DE PRÉSTAMO</th>           
      <th>PERIODO EN MORA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS EN MORA</th>
      <th>TOTAL DE PRÉSTAMO</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
      <th>IMPORTE EN MORA</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalPendiente = 0;
$TotalCuotas    = 0;
$TotalMora      = 0;

for($i=0;$i<sizeof($reg);$i++){
$meses_mora = str_replace("<br>"," | ", $reg[$i]['meses_mora']);

$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
$TotalCuotas    += $reg[$i]['cuotas_mora'];
$TotalMora      += $reg[$i]['suma_mora'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_mora; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas_mora']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="7"></td>' : '<td colspan="7"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></strong></td>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></strong></td>
    <td style="color:#dc1a0b;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalMora, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;

case 'MORAXCLIENTES':

$tra = new Login();
$reg = $tra->BuscarMoraxClientes(); 

$archivo = str_replace(" ", "_","LISTADO DE PAGOS EN MORA DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"].")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE PRÉSTAMO</th>
      <th>FECHA DE PRÉSTAMO</th>           
      <th>PERIODO EN MORA</th>
      <th>MONTO X CUOTA</th>
      <th>CUOTAS EN MORA</th>
      <th>TOTAL DE PRÉSTAMO</th>
      <th>TOTAL PAGADO</th>
      <th>TOTAL PENDIENTE</th>
      <th>IMPORTE EN MORA</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalImporte   = 0;
$TotalPagado    = 0;
$TotalPendiente = 0;
$TotalCuotas    = 0;
$TotalMora      = 0;

for($i=0;$i<sizeof($reg);$i++){
$meses_mora = str_replace("<br>"," | ", $reg[$i]['meses_mora']);

$TotalImporte   += $reg[$i]['totalpago'];
$TotalPagado    += $reg[$i]['creditopagado'];
$TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];
$TotalCuotas    += $reg[$i]['cuotas_mora'];
$TotalMora      += $reg[$i]['suma_mora'];
?>
  <tr class="even_row">
    <td><?php echo $a++; ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
    <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo'])); ?></td>
    <td style="color:#dc1a0b;font-weight:bold;"><?php echo $meses_mora; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $reg[$i]['cuotas_mora']; ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','); ?></td>
  </tr>
  <?php } ?>
  <tr>
    <?php echo $documento == "EXCEL" ? '<td colspan="6"></td>' : '<td colspan="6"></td>'; ?>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></strong></td>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
    <td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></strong></td>
    <td style="color:#dc1a0b;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalMora, 2, '.', ','); ?></strong></td>
    <?php } ?>
  </tr>
</table>
<?php
break;
############################### MODULO DE CREDITOS ###############################



















############################### MODULO DE DEVOLUCIONES ###############################
case 'DEVOLUCIONES':

$tra = new Login();
$reg = $tra->ListarDevoluciones(); 

$archivo = str_replace(" ", "_","LISTADO DE DEVOLUCIONES EN SUCURSAL (".$sucursal = ($reg == "" ? "" : $reg[0]['cuitsucursal']." ".$reg[0]['nomsucursal']).")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
      <tr>
        <th>Nº</th>
        <th>Nº DE DEVOLUCIÓN</th>
        <th>Nº DE FACTURA</th>
        <th>FECHA DE VENTA</th>
        <th>DESCRIPCIÓN DE CLIENTE</th>
        <th>ETAPA</th>
        <?php if ($documento == "EXCEL") { ?>
        <th>MANZANA</th>
        <th>LOTE</th>
        <th>PRECIO VENTA</th>
        <th>INTERESES %</th>
        <?php } ?>
        <th>FECHA DE DEVOLUCIÓN</th>
        <th>OBSERVACIONES</th>
        <th>Nº CUOTAS</th>
        <th>CUOTAS PAGADAS</th>
        <th>CUOTAS PENDIENTES</th>
        <th>CUOTAS EN MORA</th>
        <th>IMPORTE</th>
        <th>PAGADO</th>
        <th>PENDIENTE</th>
      </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal=0;
$Cuotas=0;
$CuotasPagadas=0;
$CuotasPendientes=0;
$CuotasMora=0;
$TotalImporte=0;
$TotalPagado=0;
$TotalPendiente=0;

for($i=0;$i<sizeof($reg);$i++){ 
$simbolo = ($reg[$i]['simbolo'] == "" ? "" : "<strong>".$reg[$i]['simbolo']."</strong>");
   
$TotalSubtotal+=$reg[$i]['precioventa'];
$Cuotas+=$reg[$i]['cuotas'];
$CuotasPagadas+=$reg[$i]['cuotaspagadas'];
$CuotasPendientes+=$reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
$CuotasMora+=$reg[$i]['cuotas_mora'];
$TotalImporte+=$reg[$i]['totalpago'];
$TotalPagado+=$reg[$i]['creditopagado'];
$TotalPendiente+=$reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['factura_venta']; ?></td> 
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo $reg[$i]['nometapa']; ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $reg[$i]['manzana']; ?></td>
      <td><?php echo $reg[$i]['lote']; ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['precioventa'], 2, '.', ','); ?></td>
      <td><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?></td>
      <?php } ?>
      
      <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechadevolucion'])); ?></td>
      <td><?php echo $reg[$i]['observaciones']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas_mora']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php } ?>
    <tr>
<?php echo $documento == "EXCEL" ? '<td colspan="12"></td>' : '<td colspan="8"></td>'; ?>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $Cuotas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPagadas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPendientes; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasMora; ?></strong></td>
<td style="color:#0b0c11;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></strong></td>
<td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></strong></td>
<?php } ?>
         </tr>
</table>
<?php
break;

case 'DEVOLUCIONESXFECHAS':

$tra = new Login();
$reg = $tra->BuscarDevolucionesxFechas(); 

$archivo = str_replace(" ", "_","LISTADO DE DEVOLUCIONES (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"]))." Y SUCURSAL: ".$reg[0]['cuitsucursal'].": ".$reg[0]['nomsucursal'].")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE DEVOLUCIÓN</th>
      <th>Nº DE FACTURA</th>
      <th>FECHA DE VENTA</th>
      <th>DESCRIPCIÓN DE CLIENTE</th>
      <th>ETAPA</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MANZANA</th>
      <th>LOTE</th>
      <th>PRECIO VENTA</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>FECHA DE DEVOLUCIÓN</th>
      <th>OBSERVACIONES</th>
      <th>Nº CUOTAS</th>
      <th>CUOTAS PAGADAS</th>
      <th>CUOTAS PENDIENTES</th>
      <th>CUOTAS EN MORA</th>
      <th>IMPORTE</th>
      <th>PAGADO</th>
      <th>PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal=0;
$Cuotas=0;
$CuotasPagadas=0;
$CuotasPendientes=0;
$CuotasMora=0;
$TotalImporte=0;
$TotalPagado=0;
$TotalPendiente=0;

for($i=0;$i<sizeof($reg);$i++){ 
$simbolo = ($reg[$i]['simbolo'] == "" ? "" : "<strong>".$reg[$i]['simbolo']."</strong>");
   
$TotalSubtotal+=$reg[$i]['precioventa'];
$Cuotas+=$reg[$i]['cuotas'];
$CuotasPagadas+=$reg[$i]['cuotaspagadas'];
$CuotasPendientes+=$reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
$CuotasMora+=$reg[$i]['cuotas_mora'];
$TotalImporte+=$reg[$i]['totalpago'];
$TotalPagado+=$reg[$i]['creditopagado'];
$TotalPendiente+=$reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['factura_venta']; ?></td> 
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
      <td><?php echo $reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente']; ?></td>
      <td><?php echo $reg[$i]['nometapa']; ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $reg[$i]['manzana']; ?></td>
      <td><?php echo $reg[$i]['lote']; ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['precioventa'], 2, '.', ','); ?></td>
      <td><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?></td>
      <?php } ?>
      
      <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechadevolucion'])); ?></td>
      <td><?php echo $reg[$i]['observaciones']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas_mora']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php } ?>
    <tr>
<?php echo $documento == "EXCEL" ? '<td colspan="12"></td>' : '<td colspan="8"></td>'; ?>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $Cuotas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPagadas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPendientes; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasMora; ?></strong></td>
<td style="color:#0b0c11;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></strong></td>
<td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></strong></td>
<?php } ?>
         </tr>
</table>
<?php
break;

case 'DEVOLUCIONESXCLIENTES':

$tra = new Login();
$reg = $tra->BuscarDevolucionesxClientes(); 

$archivo = str_replace(" ", "_","LISTADO DE DEVOLUCIONES DEL CLIENTE (".$reg[0]["cedcliente"].": ".$reg[0]["nomcliente"]." Y SUCURSAL: ".$reg[0]['cuitsucursal'].": ".$reg[0]['nomsucursal'].")");

header("Content-Type: application/vnd.ms-$documento"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("content-disposition: attachment;filename=".$archivo.$extension);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <th>Nº</th>
      <th>Nº DE DEVOLUCIÓN</th>
      <th>Nº DE FACTURA</th>
      <th>FECHA DE VENTA</th>
      <th>ETAPA</th>
      <?php if ($documento == "EXCEL") { ?>
      <th>MANZANA</th>
      <th>LOTE</th>
      <th>PRECIO VENTA</th>
      <th>INTERESES %</th>
      <?php } ?>
      <th>FECHA DE DEVOLUCIÓN</th>
      <th>OBSERVACIONES</th>
      <th>Nº CUOTAS</th>
      <th>CUOTAS PAGADAS</th>
      <th>CUOTAS PENDIENTES</th>
      <th>CUOTAS EN MORA</th>
      <th>IMPORTE</th>
      <th>PAGADO</th>
      <th>PENDIENTE</th>
    </tr>
<?php 
if($reg==""){
echo "";      
} else {
  
$a=1;
$TotalSubtotal=0;
$Cuotas=0;
$CuotasPagadas=0;
$CuotasPendientes=0;
$CuotasMora=0;
$TotalImporte=0;
$TotalPagado=0;
$TotalPendiente=0;

for($i=0;$i<sizeof($reg);$i++){ 
$simbolo = ($reg[$i]['simbolo'] == "" ? "" : "<strong>".$reg[$i]['simbolo']."</strong>");
   
$TotalSubtotal+=$reg[$i]['precioventa'];
$Cuotas+=$reg[$i]['cuotas'];
$CuotasPagadas+=$reg[$i]['cuotaspagadas'];
$CuotasPendientes+=$reg[$i]['cuotas']-$reg[$i]['cuotaspagadas'];
$CuotasMora+=$reg[$i]['cuotas_mora'];
$TotalImporte+=$reg[$i]['totalpago'];
$TotalPagado+=$reg[$i]['creditopagado'];
$TotalPendiente+=$reg[$i]['totalpago']-$reg[$i]['creditopagado'];
?>
    <tr class="even_row">
      <td><?php echo $a++; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['codfactura']; ?></td>
      <td><?php echo '&nbsp;'.$reg[$i]['factura_venta']; ?></td> 
      <td><?php echo date("d/m/Y H:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
      <td><?php echo $reg[$i]['nometapa']; ?></td>
      <?php if ($documento == "EXCEL") { ?>
      <td><?php echo $reg[$i]['manzana']; ?></td>
      <td><?php echo $reg[$i]['lote']; ?></td>
      <td><?php echo $simbolo.number_format($reg[$i]['precioventa'], 2, '.', ','); ?></td>
      <td><?php echo number_format($reg[$i]['intereses'], 2, '.', ','); ?></td>
      <?php } ?>
      
      <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechadevolucion'])); ?></td>
      <td><?php echo $reg[$i]['observaciones']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas']-$reg[$i]['cuotaspagadas']; ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $reg[$i]['cuotas_mora']; ?></td>
      <td style="color:#0b0c11;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
      <td style="color:#1d2591;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ','); ?></td>
      <td style="color:#d1293b;font-weight:bold;"><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','); ?></td>
    </tr>
    <?php } ?>
    <tr>
<?php echo $documento == "EXCEL" ? '<td colspan="11"></td>' : '<td colspan="7"></td>'; ?>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $Cuotas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPagadas; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasPendientes; ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $CuotasMora; ?></strong></td>
<td style="color:#0b0c11;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalImporte, 2, '.', ','); ?></strong></td>
<td style="color:#1d2591;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPagado, 2, '.', ','); ?></strong></td>
<td style="color:#d1293b;font-weight:bold;"><strong><?php echo $simbolo.number_format($TotalPendiente, 2, '.', ','); ?></strong></td>
<?php } ?>
</tr>
</table>
<?php
break;
############################### MODULO DE DEVOLUCIONES ###############################
}
?>