<?php
require_once("class/class.php");

######################## VERIFICA MESES VENCIDOS #################################
if (isset($_GET['Verifica_Meses_Vencidos'])) {
  
$meses = new Login();
$meses = $meses->VerificaMesesVencidos();

}
######################## VERIFICA MESES VENCIDOS #################################
?>