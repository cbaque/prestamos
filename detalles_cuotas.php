<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor'];
validarAccesos($accesos) or die();      
?>

<?php 
######################## MUESTRA DETALLES DE PAGO ########################
if (isset($_GET['MuestraDetallesPago']) && isset($_GET['cuotas']) && isset($_GET['totalpago']) && isset($_GET['periodopago']) && isset($_GET['fechapagocuota'])) {

if(limpiar($_GET['cuotas'] == "" || $_GET['totalpago'] == "" || $_GET['periodopago'] == "" || $_GET['fechapagocuota'] == "")){
  exit;
}
  
$cuotas       = limpiar($_GET['cuotas']);
$periodopago  = limpiar($_GET['periodopago']);
$current_date = limpiar(date("Y-m-d",strtotime($_GET['fechapagocuota'])));


if($periodopago == "DIARIO"){

$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} days");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('1 days');
$daterange  = new DatePeriod($start_date, $interval , $end_date);


} elseif($periodopago == "SEMANAL"){


$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} week");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('1 week');
$daterange  = new DatePeriod($start_date, $interval , $end_date);


} elseif($periodopago == "QUINCENAL"){


$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} days");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('15 days');
$daterange  = new DatePeriod($start_date, $interval, $cuotas-1);

} elseif($periodopago == "MENSUAL"){

$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} months");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('1 month');
$daterange  = new DatePeriod($start_date, $interval , $end_date);

} elseif($periodopago == "BIMESTRAL"){

$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} months");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('2 month');
$daterange  = new DatePeriod($start_date, $interval , $cuotas-1);

} elseif($periodopago == "SEMESTRAL"){

$start_date = new DateTime($current_date);
//$end_date = new DateTime("{$current_date} + {$cuotas} months");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('6 month');
$daterange  = new DatePeriod($start_date, $interval , $cuotas-1);

} elseif($periodopago == "ANUAL"){

$start_date = new DateTime($current_date);
$end_date   = new DateTime("{$current_date} + {$cuotas} year");

// Get dates between start-end
$interval   = DateInterval::createFromDateString('1 year');
$daterange  = new DatePeriod($start_date, $interval , $end_date);

}

$intCapital   = $_GET['totalpago'];
$intPlazo     = intval($_GET['cuotas']);
$MontoCuota   = number_format($intCapital / $intPlazo, 2, '.', '');
$capitalSaldo = number_format($intCapital, 2, '.', '');

$dates = [];
$tablaAmortizacion = array();
$num = 1;
$i = 0;
foreach($daterange as $date1){    
  $cuotaItem = array();
  $cuotaItem["numero"] = $num++;
  $cuotaItem["periodo"] = $date1->format('d-m-Y');
  $cuotaItem["inicial"] = $capitalSaldo;
  $cuotaItem["cuota"] = $MontoCuota;
  $cuotaItem["abono"] = $cuotaItem["cuota"];
  
  if($i == $intPlazo){
    $cuotaItem["cuota"] = $capitalSaldo;
    $cuotaItem["abono"] = $capitalSaldo;
    $cuotaItem["saldo"] = 0.00;
  } else {
    $capitalSaldo -= $cuotaItem["abono"];
    $cuotaItem["saldo"] = $capitalSaldo;
  }
    
  $tablaAmortizacion[] = $cuotaItem;
}
?>

<hr><h5 class="card-subtitle text-dark alert-link"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Detalles de Pagos</h5><hr>

  <div id="div5" class="table-responsive mb-0 mt-0">
  <table id="#default-ordering" class="table table-hover non-hover" style="width:100%">
    <thead>
      <tr>
        <th>N° de Pago</th>
        <th>Fecha de Pago</th>
        <th>Saldo Inicial</th>
        <th>Cuota</th>
        <th>Saldo Final</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tablaAmortizacion as $cuota) {?>
      <tr class="text-dark alert-link">
          <td><input type="hidden" name="codcuota[]" id="codcuota" value="<?php echo $cuota["numero"]; ?>"/><?php echo $cuota["numero"]; ?></td>
          <td><input type="hidden" name="fechapago[]" id="fechapago" value="<?php echo date("Y-m-d",strtotime($cuota["periodo"])); ?>"/><?php echo "<span class='text-danger alert-link'>".$cuota["periodo"]."</span>"; ?></td>
          <td><input type="hidden" name="saldoinicial[]" id="saldoinicial" value="<?php echo number_format($cuota["inicial"], 2, '.', ''); ?>"/><?php echo number_format($cuota["inicial"], 2, '.', ','); ?></td>
          <td><input type="hidden" name="capital[]" id="capital" value="<?php echo number_format($cuota["abono"], 2, '.', ''); ?>"/><?php echo number_format($cuota["abono"], 2, '.', ','); ?></td>
          <td><input type="hidden" name="saldofinal[]" id="saldofinal" value="<?php echo number_format($cuota["saldo"], 2, '.', ''); ?>"/><?php echo number_format($cuota["saldo"], 2, '.', ','); ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table></div><hr>
<?php 
}
######################## MUESTRA DETALLES DE PAGO ########################
?>