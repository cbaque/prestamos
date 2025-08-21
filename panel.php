<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();

$new = new Login();
$con = $new->ContarRegistros();       
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title></title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES 
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <script src="assets/script/jquery.min.js"></script> 
    <script type="text/javascript" src="plugins/chart.js/chart.min.js"></script>-->
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!-- SCRIPT GRAFICOS -->
    <script src="assets/script/jquery.min.js"></script> 
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="plugins/chart.js/chart.min.js"></script>
    <script type="text/javascript" src="plugins/chart.js/legend.js"></script>
    <script type="text/javascript" src="plugins/chart.js/legend.legacy.js"></script>
    <script type="text/javascript" src="assets/script/graficos.js"></script>
    <!-- SCRIPT GRAFICOS -->
    
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
<body onLoad="muestraReloj()" class="alt-menu sidebar-noneoverflow">

<!--############################## MODAL PARA VER DETALLE DE PRESTAMO ######################################-->
<!-- Modal -->
<div class="modal fade" id="myModalDetalle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"><i class="text-white" data-feather="align-justify"></i> Detalle de Préstamo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x-circle"></i>
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
<!--############################## MODAL PARA VER DETALLE DE PRESTAMO ######################################-->

    <!-- BEGIN LOADER -->
    <div id="load_screen"> 
        <div class="loader">
        <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

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


        <!--  BEGIN CONTENT PART  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="page-header">
                    <div class="page-title">
                        <h3><i data-feather="grid"></i> Dashboard</h3>
                    </div>
                </div>

            <?php if ($_SESSION["acceso"] == "cliente"){ ?>

            <div class="row layout-top-spacing">
                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Pendientes</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-warning">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['pendientes']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Aprobados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-success">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['aprobados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Rechazados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-danger">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['rechazados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Pagados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-primary">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['pagados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row layout-top-spacing">
                                <!-- ============================================================== -->
                <!-- Listado de Cuotas Vencidas -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    
                    <div class="widget-content widget-content-area br-6">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Cuotas Vencidas</h5>
                                </div>                  
                            </div>
                        </div>
                    <div id="prestamos_vencidos" class="table-responsive mb-2 mt-2"></div> 
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Listado de Cuotas Vencidas -->
                <!-- ============================================================== -->
                
            </div>

            <?php } else { ?>

            <div class="row layout-top-spacing">
                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Pendientes</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-warning">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['pendientes']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Aprobados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-success">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['aprobados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Rechazados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-danger">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['rechazados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h6 class=""><i data-feather="more-vertical"></i> Pagados</h6>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="icon">
                                                <i data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h3 class="badge badge-primary">TOTAL</h3>
                                            <h4 class="text-dark alert-link"><?php echo $con[0]['pagados']; ?></h4>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row layout-top-spacing">
                <!-- ============================================================== -->
                <!-- Grafico Total Prestamos x Mes -->
                <!-- ============================================================== -->
                <?php
                $js = new Login();
                $pendiente = $js->SumaPrestamosPendientesxMeses();

                $ju = new Login();
                $aprobado = $ju->SumaPrestamosAprobadosxMeses();

                $jx = new Login();
                $rechazado = $jx->SumaPrestamosRechazadosxMeses();

                $jz = new Login();
                $pagado = $jz->SumaPrestamosPagadosxMeses();
                ?>
                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one">
                        <div class="widget-heading">
                            <h5 class="text-dark alert-link"><i data-feather="bar-chart-2"></i> Gráfico Total Préstamos x Mes</h5>
                        </div>
                        
                        <div class="widget-content">
                            <div class="tabs tab-content">
                    
                            <div id="chart-container">
                            <canvas id="bar-chart_1" width="800" height="360"></canvas>
                            </div>
                            <script>
                            // Bar chart
                            new Chart(document.getElementById("bar-chart_1"), {
                            type: 'bar',
                            data: {
                            labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
                            datasets: [
                            {
                                label: "Pendiente",
                                backgroundColor: ["#ca861d", "#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d","#ca861d"],
                                data: [<?php 

                                if($pendiente == "") { echo 0; } else {

                                    $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                                    foreach($pendiente as $row) {
                                        $mes = $row['mes_pendiente'];
                                        $meses[$mes] = $row['totalmes_pendiente'];
                                    }
                                    foreach($meses as $mes) {
                                            echo "{$mes},"; } } ?>]
                                    },
                                {
                                label: "Aprobado",
                                backgroundColor: ["#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b","#1d801b"],
                                data: [<?php 

                                if($aprobado == "") { echo 0; } else {

                                    $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                                    foreach($aprobado as $row) {
                                        $mes = $row['mes_aprobado'];
                                        $meses[$mes] = $row['totalmes_aprobado'];
                                    }
                                    foreach($meses as $mes) {
                                            echo "{$mes},"; } } ?>]
                                    },
                                {
                                label: "Rechazado",
                                backgroundColor: ["#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126","#bd3126"],
                                data: [<?php 

                                if($rechazado == "") { echo 0; } else {

                                    $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                                    foreach($rechazado as $row) {
                                        $mes = $row['mes_rechazado'];
                                        $meses[$mes] = $row['total_rechazado'];
                                    }
                                    foreach($meses as $mes) {
                                            echo "{$mes},"; } } ?>]
                                    },
                                {
                                label: "Pagado",
                                backgroundColor: ["#217092","#217092","#217092","#217092","#217092","#217092","#217092","#217092","#217092","#217092","#217092","#217092"],
                                data: [<?php 

                                if($pagado == "") { echo 0; } else {

                                    $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                                    foreach($pagado as $row) {
                                        $mes = $row['mes_pagado'];
                                        $meses[$mes] = $row['total_pagado'];
                                    }
                                    foreach($meses as $mes) {
                                            echo "{$mes},"; } } ?>]
                                    }]
                                },
                                options: {
                                    legend: { display: true },
                                    title: {
                                        display: true,
                                        text: 'Total de Préstamos Mensual'
                                    }
                                }
                            });
                            </script>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Grafico Total Prestamos x Mes -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Grafico Prestamos por Usuarios -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one">
                        <div class="widget-heading">
                            <h5 class="text-dark alert-link"><i data-feather="pie-chart"></i> Gráfico Préstamos x Usuarios</h5>
                        </div>
                        <div class="widget-content">
                            <div align="center" id="chart-container_2" class="widget-content">
                                <canvas id="DoughnutChart_2" width="250" height="250"></canvas>
                                <h5><div id="DoughnutLegend_2"></div></h5>
                            </div>
                        </div>
                        <script>
                        $(document).ready(function () {
                        showGraphDoughnut_2();
                        });
                        </script>
                    </div>
                </div>
                <!-- End Row -->
                <!-- ============================================================== -->
                <!-- Grafico Ventas por Usuarios -->
                <!-- ============================================================== -->

            </div>


            <div class="row layout-top-spacing">
                <!-- ============================================================== -->
                <!-- Listado de Cuotas Vencidas -->
                <!-- ============================================================== -->
                <div class="col-xl-8 col-lg-12 col-sm-12 layout-spacing">
                    
                    <div class="widget-content widget-content-area br-6">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h5 class="card-subtitle text-dark alert-link"><i data-feather="list"></i> Cuotas Vencidas</h5>
                                </div>                  
                            </div>
                        </div>
                    <div id="prestamos_vencidos" class="table-responsive mb-2 mt-2"></div> 
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Listado de Cuotas Vencidas -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Grafico Prestamos por Usuarios -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one">
                        <div class="widget-heading">
                            <h5 class="text-dark alert-link"><i data-feather="pie-chart"></i> Disponible x Cajas</h5>
                        </div>
                        <div class="widget-content">
                            <div align="center" id="chart-container_3" class="widget-content">
                                <canvas id="DoughnutChart_3" width="150" height="150"></canvas>
                                <h5><div id="DoughnutLegend_3"></div></h5>
                            </div>
                        </div>
                        <script>
                        $(document).ready(function () {
                        showGraphDoughnut_3();
                        });
                        </script>
                    </div>
                </div>
                <!-- End Row -->
                <!-- ============================================================== -->
                <!-- Grafico Ventas por Usuarios -->
                <!-- ============================================================== -->
            </div>

            <?php } ?>

                <div class="footer-wrapper text-primary">
                    <div class="footer-section f-section-1">
                        <i data-feather="copyright"></i> <span class="current-year"></span>.
                    </div>
                    <div class="footer-section f-section-2">
                        <p class="text-primary"><span class="current-detalle"></span></p>
                    </div>
                </div>

            </div>
        </div>
        <!--  END CONTENT PART  -->

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

    <script src="assets/js/scrollspyNav.js"></script>
    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>

    <!-- script jquery -->
    <script src="assets/script/jquery.min.js"></script> 
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/VentanaCentrada.js"></script>
    <?php //if ($_SESSION['acceso'] == "vendedor"){ ?>
    <script type="text/jscript">
    $('#prestamos_vencidos').append('<center><i data-feather="settings"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#prestamos_vencidos').load("consultas?CargaPrestamosMora=si");
     }, 200);
    </script>
    <?php //} ?>
    <!-- script jquery -->

</body>
</html>