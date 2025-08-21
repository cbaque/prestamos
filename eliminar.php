<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$tra  = new Login();
$tipo = decrypt($_GET['tipo']);
switch($tipo)
{
case 'STATUSUSUARIOS':
$tra->StatusUsuarios();
exit;
break;

case 'USUARIOS':
$tra->EliminarUsuarios();
exit;
break;

case 'DOCUMENTOS':
$tra->EliminarDocumentos();
exit;
break;

case 'PROVINCIAS':
$tra->EliminarProvincias();
exit;
break;

case 'DEPARTAMENTOS':
$tra->EliminarDepartamentos();
exit;
break;

case 'TIPOMONEDA':
$tra->EliminarTipoMoneda();
exit;
break;

case 'FORMASPAGOS':
$tra->EliminarFormasPagos();
exit;
break;

case 'STATUSCLIENTES':
$tra->StatusClientes();
exit;
break;

case 'CLIENTES':
$tra->EliminarClientes();
exit;
break;

case 'CAJAS':
$tra->EliminarCajas();
exit;
break;

case 'MOVIMIENTOS':
$tra->EliminarMovimientos();
exit;
break;

case 'CANCELAR_PRESTAMOS':
$tra->CancelarPrestamos();
exit;
break;

case 'PRESTAMOS':
$tra->EliminarPrestamos();
exit;
break;

}
?>