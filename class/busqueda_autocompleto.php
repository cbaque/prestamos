<?php
include('class.consultas.php');

if (isset($_GET['Busqueda_Clientes_Activos'])):

$filtro = $_GET["term"];
$Json = new Json;
$cliente = $Json->BuscaClientesActivos($filtro);
echo json_encode($cliente);

endif;

if (isset($_GET['Busqueda_Clientes_General'])):

$filtro = $_GET["term"];
$Json = new Json;
$cliente = $Json->BuscaClientesGeneral($filtro);
echo json_encode($cliente);

endif;

if (isset($_GET['Busqueda_Terrenos'])):

$filtro = $_GET["term"];
$Json = new Json;
$terreno = $Json->BuscaTerrenos($filtro);
echo json_encode($terreno);

endif;

?>  