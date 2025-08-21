<?php
require_once("class/class.php");
//header('Content-Type: text/html; charset=UTF-8');
//header("Content-Type: text/html; charset=iso-8859-1"); 
header('Content-Type: application/json');


################ GRAFICO DISPONIBLE EN CAJAS ########################
if (isset($_GET['DisponiblexAperturas'])):

$js = new Login();
$d = $js->DineroDisponiblexAperturas();

$data = array();
if (is_array($d)) {

	foreach ($d as $row) {
		$data[] = $row;
	}
}

echo json_encode($data);

endif;
################ GRAFICO DISPONIBLES EN CAJAS ########################


################ GRAFICO PRESTAMOS POR USUARIOS ########################
if (isset($_GET['PrestamosxUsuarios'])):

$user = new Login();
$u = $user->PrestamosxUsuarios();

$data = array();
if (is_array($u)) {

	foreach ($u as $row) {
		$data[] = $row;
	}
}

echo json_encode($data);

endif;
################ GRAFICO PRESTAMOS POR USUARIOS ########################
?>