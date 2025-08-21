<?php
define('FPDF_FONTPATH','fpdf/font/');
define('EURO', chr(128));
require 'pdf_js.php';

############## VARIABLE PARA TAMAÑO DE LOGO ##############
$GLOBALS['logo1_horizontal'] = 78;
$GLOBALS['logo2_horizontal'] = 78;

$GLOBALS['logo1_vertical'] = 50;
$GLOBALS['logo2_vertical'] = 50;
############## VARIABLE PARA TAMAÑO DE LOGO ##############
 

class PDF extends PDF_JavaScript
{
var $widths;
var $aligns;
var $flowingBlockAttr;
//$Tamhoriz = 88;


########################### FUNCION PARA MOSTRAR EL FOOTER ###########################
function Footer()
{
    if(!in_array(decrypt($_GET['tipo']), ['TICKETCIERRE', 'TICKETMOVIMIENTO', 'COMPROBANTE', '', '', ''])){
        //Posición: a 2 cm del final
        $this->Ln();
        $this->SetY(-12);
        $this->SetFont('courier','B',10);
        //Número de página
        $this->Cell(190,5,'SOFTWARE PARA GESTIÓN DE PRÉSTAMOS','T',0,'L');
        $this->AliasNbPages();
        $this->Cell(0,5,'Página '.$this->PageNo(),'T',1,'R');
        //Page number
        /*$pagenumber = '{nb}';
        if($this->PageNo() == 2){
            $this->Cell(173,10, ' FOOTER TEST  -  '.$pagenumber, 0, 0);
        }*/
        if($this->page>0)
        {
            // Page footer
            //$this->InFooter = true;
            //$this->Footer();
            //$this->InFooter = false;
            // Close page
            $this->_endpage();
        }
    } 
} 
########################## FUNCION PARA MOSTRAR EL FOOTER ############################
    
######################## FUNCION PARA CARGAR AUTOPRINTF ########################
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
######################## FUNCION PARA CARGAR AUTOPRINT ########################





############################################ REPORTES DE CONFIGURACION ############################################

########################## FUNCION LISTAR TIPOS DE DOCUMENTOS ##########################
function TablaListarDocumentos()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);  

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
   ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE DOCUMENTOS TRIBUTARIOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(50,8,'NOMBRE DE DOCUMENTO',1,0,'C', True);
    $this->Cell(125,8,'DESCRIPCIÓN DE DOCUMENTO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarDocumentos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,50,125));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["documento"]),utf8_decode($reg[$i]["descripcion"])));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR TIPOS DE DOCUMENTOS ##########################

########################## FUNCION LISTAR PROVINCIAS ##############################
function TablaListarProvincias()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE PROVINCIAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(20,8,'Nº',1,0,'C', True);
    $this->Cell(170,8,'NOMBRE DE PROVINCIA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarProvincias();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(20,170));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array(utf8_decode($reg[$i]["codprovincia"]),portales(utf8_decode($reg[$i]["provincia"]))));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PROVINCIAS ##############################

########################## FUNCION LISTAR DEPARTAMENTOS ##############################
function TablaListarDepartamentos()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################


    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE DEPARTAMENTOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(25,8,'Nº',1,0,'C', True);
    $this->Cell(80,8,'NOMBRE DE PROVINCIA',1,0,'C', True);
    $this->Cell(80,8,'NOMBRE DE DEPARTAMENTO',1,1,'C', True);  

    $tra = new Login();
    $reg = $tra->ListarDepartamentos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(25,80,80));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array(utf8_decode($reg[$i]["coddepartamento"]),portales(utf8_decode($reg[$i]["provincia"])),portales(utf8_decode($reg[$i]["departamento"]))));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR DEPARTAMENTOS ##############################

########################## FUNCION LISTAR TIPOS DE MONEDA ##############################
function TablaListarTiposMonedas()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE TIPOS DE MONEDA',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(85,8,'NOMBRE DE MONEDA',1,0,'C', True);
    $this->Cell(45,8,'SIGLAS',1,0,'C', True);
    $this->Cell(45,8,'SIMBOLO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarTipoMoneda();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,85,45,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["moneda"]),utf8_decode($reg[$i]["siglas"]),utf8_decode($reg[$i]["simbolo"])));
       }
    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR TIPOS DE MONEDA ##############################

########################## FUNCION LISTAR FORMAS DE PAGOS ##############################
function TablaListarFormasPagos()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE FORMAS DE PAGOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(20,8,'Nº',1,0,'C', True);
    $this->Cell(170,8,'FORMA DE PAGO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarFormasPagos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(20,170));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array(utf8_decode($reg[$i]["codformapago"]),portales(utf8_decode($reg[$i]["formapago"]))));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR FORMAS DE PAGOS ##############################

############################################ REPORTES DE CONFIGURACION ############################################









############################################ REPORTES DE USUARIOS ############################################

########################## FUNCION LISTAR USUARIOS ##############################
function TablaListarUsuarios()
{
    $tra = new Login();
    $reg = $tra->ListarUsuarios();
    
    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(335,10,'LISTADO DE USUARIOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'Nº DOCUMENTO',1,0,'C', True);
    $this->Cell(75,8,'NOMBRES Y APELLIDOS',1,0,'C', True);
    $this->Cell(25,8,'SEXO',1,0,'C', True);
    $this->Cell(40,8,'Nº DE TELÉFONO',1,0,'C', True);
    $this->Cell(55,8,'EMAIL',1,0,'C', True);
    $this->Cell(40,8,'USUARIO',1,0,'C', True);
    $this->Cell(55,8,'NIVEL',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,30,75,25,40,55,40,55));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){  

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["dni"]),utf8_decode($reg[$i]["nombres"]),utf8_decode($reg[$i]["sexo"]),utf8_decode($reg[$i]["telefono"]),utf8_decode($reg[$i]["email"]),utf8_decode($reg[$i]["usuario"]),utf8_decode($reg[$i]["nivel"])));
        }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR USUARIOS ##############################

########################## FUNCION LISTAR LOGS DE USUARIOS ##############################
function TablaListarLogs()
{
    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(335,10,'LISTADO DE LOGS DE ACCESO DE USUARIOS',0,0,'C');
    
    $this->Ln();
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(10,8,'N°',1,0,'C', True);
    $this->Cell(35,8,'IP EQUIPO',1,0,'C', True);
    $this->Cell(45,8,'TIEMPO ENTRADA',1,0,'C', True);
    $this->Cell(145,8,'NAVEGADOR DE ACCESO',1,0,'C', True);
    $this->Cell(60,8,'PÁGINAS DE ACCESO',1,0,'C', True);
    $this->Cell(35,8,'USUARIO',1,1,'C', True);

    $tra = new Login();
    $reg = $tra->ListarLogs();

    if($reg==""){
    echo "";      
    } else {
    
    /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,35,45,145,60,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["ip"]),utf8_decode($reg[$i]["tiempo"]),utf8_decode($reg[$i]["detalles"]),utf8_decode($reg[$i]["paginas"]),utf8_decode($reg[$i]["usuario"])));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR LOGS DE USUARIOS ##############################

############################################ REPORTES DE USUARIOS ############################################









############################### REPORTES DE CLIENTES ##############################

########################## FUNCION LISTAR CLIENTES ##############################
function TablaListarClientes()
{
    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(335,10,'LISTADO DE CLIENTES',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(40,8,'Nº DE DOCUMENTO',1,0,'C', True);
    $this->Cell(60,8,'NOMBRES Y APELLIDOS',1,0,'C', True);
    $this->Cell(25,8,'SEXO',1,0,'C', True);
    $this->Cell(35,8,'Nº DE TELEFONO',1,0,'C', True);
    $this->Cell(35,8,'Nº DE CELULAR',1,0,'C', True);
    $this->Cell(70,8,'DIRECCIÓN DOMICILIARIA',1,0,'C', True);
    $this->Cell(55,8,'DATOS DE REFERENCIA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarClientes();

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,40,60,25,35,35,70,55));

    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($documento = ($reg[$i]['documcliente'] == '0' ? "*********" : $reg[$i]['documento'])." ".$reg[$i]["cedcliente"]),portales(utf8_decode($reg[$i]["nomcliente"])),utf8_decode($reg[$i]['sexocliente']),utf8_decode($reg[$i]['tlfcliente'] == '' ? "*********" : $reg[$i]["tlfcliente"]),utf8_decode($reg[$i]['celcliente'] == '' ? "*********" : $reg[$i]['celcliente']),
        utf8_decode($departamento = ($reg[$i]['coddepartamento'] == '0' ? "" : $reg[$i]['departamento']." ")."".$provincia = ($reg[$i]['codprovincia'] == '0' ? "" : $reg[$i]['provincia']." ")." ".$reg[$i]["direccliente"]),utf8_decode($familiar = ($reg[$i]['nomreferencia'] == "" ? "**********" : $reg[$i]['nomreferencia']))));
       }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR CLIENTES ##############################

############################### REPORTES DE CLIENTES ##############################



















########################### REPORTES DE CAJAS DE VENTAS ##############################

########################## FUNCION LISTAR CAJAS ##############################
function TablaListarCajas()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');


    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE CAJAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(55,8,'NOMBRE DE CAJA',1,0,'C', True);
    $this->Cell(90,8,'RESPONSABLE DE CAJA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarCajas();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,35,55,90));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nrocaja"]),utf8_decode($reg[$i]['nomcaja']),utf8_decode($reg[$i]["nombres"])));
        }
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR CAJAS ##############################

########################## FUNCION TICKET CIERRE ARQUEO (TICKET) ##############################
function TicketCierre()
{  
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $tra = new Login();
    $reg = $tra->AperturasPorId();
  
    if (file_exists("./fotos/logo_principal.png")) {

    $logo = "./fotos/logo_principal.png";
    $this->Image($logo, 15, 3, 45, 15, "PNG");
    $this->Ln(8);

    }

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->Cell(70, 5, "TICKET DE CIERRE", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,4,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(70,3,$con[0]['documsucursal'] == '0' ? "" : "Nº ".$con[0]['documento']." ".utf8_decode($con[0]['cuitsucursal']),0,1,'C');

    if($con[0]['codprovincia'] != '0'){

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($provincia = ($con[0]['codprovincia'] == '0' ? " " : $con[0]['provincia'])." ".$departamento = ($con[0]['coddepartamento'] == '0' ? " " : $con[0]['departamento'])),0,1,'C');

    }

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($con[0]['direcsucursal']),0,1,'C');

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($con[0]['correosucursal']),0,1,'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"CAJA Nº:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(50,3,utf8_decode($reg[0]['nrocaja']."-".$reg[0]['nomcaja']),0,1,'L');
    
    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"CAJERO:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(50,3,utf8_decode($reg[0]['nombres']),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"FECHA EMISIÓN:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(40,3,date("d-m-Y H:i:s"),0,1,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"HORA APERTURA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode(date("d-m-Y H:i:s",strtotime($reg[0]['fechaapertura']))),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"HORA CIERRE:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode(date("d-m-Y H:i:s",strtotime($reg[0]['fechacierre']))),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"MONTO APERTURA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode($simbolo.number_format($reg[0]["montoinicial"], 2, '.', ',')),0,1,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"DESGLOSE EN COBROS",0,1,'C');
    $this->Ln(1);

    $a=1;
    $Monto_Efectivo = 0;
    for($i=0;$i<sizeof($reg);$i++):
    $Monto_Efectivo += ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);
       if($reg[$i]['formapago'] != ""){

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,portales(utf8_decode($reg[$i]['formapago'])),0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[$i]['montopagado'], 2, '.', ',')),0,1,'R');

       }
    endfor;

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"PRÉSTAMOS",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"PRÉSTAMOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["prestamos"], 2, '.', ',')),0,1,'R');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"MOVIMIENTOS EN CAJA",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"INGRESOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["ingresos"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EGRESOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["egresos"], 2, '.', ',')),0,1,'R');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->CellFitSpace(70,3,"REPORTE DE CAJA",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"TOTAL EN COBROS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["pagos"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"COBROS EN EFECTIVO:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($Monto_Efectivo, 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EFECTIVO EN CAJA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format(($reg[0]["montoinicial"]+$Monto_Efectivo+$reg[0]['ingresos'])-($reg[0]["egresos"]+$reg[0]["prestamos"]), 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EFECTIVO DISPONIBLE:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["dineroefectivo"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"DIF. EFECTIVO:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["diferencia"], 2, '.', ',')),0,1,'R');

    if($reg[0]["comentarios"]==""){

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln(3);

   } else { 

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(2);

    $this->SetX(2);
    $this->MultiCell(70,4,$this->SetFont('Courier',"",7).utf8_decode($reg[0]["comentarios"]),0,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln(3);

    }

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(13,4,"FIRMA:",0,0,'L');
    $this->Cell(55,5,utf8_decode("_________________________"),0,1,'L');
 
    $this->SetFont('Courier','BI',9);
    $this->SetX(2);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(70,3," ",0,1,'C');
    $this->Ln(3);
}
########################## FUNCION TICKET CIERRE ARQUEO (TICKET) ##############################

########################## FUNCION LISTAR APERTURAS DE CAJAS ##############################
function TablaListarAperturas()
{
    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE APERTURAS DE CAJAS',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(30,8,'APERTURA',1,0,'C', True);
    $this->Cell(30,8,'CIERRE',1,0,'C', True);
    $this->Cell(35,8,'INICIAL',1,0,'C', True);
    $this->Cell(45,8,'PRÉSTAMOS',1,0,'C', True);
    $this->Cell(40,8,'TOTAL EN COBROS',1,0,'C', True);
    $this->Cell(45,8,'TOTAL EN EFECTIVO',1,0,'C', True);
    $this->Cell(35,8,'EFECTIVO CAJA',1,0,'C', True);
    $this->Cell(30,8,'DIFERENCIA',1,1,'C', True);

    $tra = new Login();
    $reg = $tra->ListarAperturas();
    
    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(10,30,30,30,35,45,40,45,35,30));

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

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']),
        utf8_decode( date("d-m-Y H:i:s",strtotime($reg[$i]['fechaapertura']))),
        utf8_decode($reg[$i]['statusapertura'] == 1 ? "*********" : date("d-m-Y H:i:s",strtotime($reg[$i]['fechacierre']))),
        utf8_decode($simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['prestamos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['pagos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','))));
    }
   
    $this->Cell(135,5,'',0,0,'C');
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalPrestamos, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCobros, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalEfectivo, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalCaja, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalDiferencia, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR APERTURAS DE CAJAS ##############################

####################### FUNCION LISTAR APERTURAS DE CAJAS POR FECHAS ######################
function TablaListarAperturasxFechas()
{
    $tra = new Login();
    $reg = $tra->BuscarAperturasxFechas();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE APERTURAS DE CAJAS POR FECHAS',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"Nº DE CAJA: ".utf8_decode($reg[0]['nrocaja'].": ".$reg[0]['nomcaja']),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"RESPONSABLE: ".portales(utf8_decode($reg[0]['nombres'])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    
    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(45,8,'APERTURA',1,0,'C', True);
    $this->Cell(45,8,'CIERRE',1,0,'C', True);
    $this->Cell(35,8,'INICIAL',1,0,'C', True);
    $this->Cell(45,8,'PRÉSTAMOS',1,0,'C', True);
    $this->Cell(40,8,'TOTAL EN COBROS',1,0,'C', True);
    $this->Cell(45,8,'TOTAL EN EFECTIVO',1,0,'C', True);
    $this->Cell(35,8,'EFECTIVO CAJA',1,0,'C', True);
    $this->Cell(30,8,'DIFERENCIA',1,1,'C', True);

    $tra = new Login();
    $reg = $tra->ListarAperturas();
    
    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(10,45,45,35,45,40,45,35,30));

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

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode( date("d-m-Y H:i:s",strtotime($reg[$i]['fechaapertura']))),
        utf8_decode($reg[$i]['statusapertura'] == 1 ? "*********" : date("d-m-Y H:i:s",strtotime($reg[$i]['fechacierre']))),
        utf8_decode($simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['prestamos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['pagos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format(($reg[$i]["montoinicial"]+$Suma_Efectivo+$reg[$i]['ingresos'])-($reg[$i]["egresos"]+$reg[$i]["prestamos"]), 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','))));
    }
   
    $this->Cell(135,5,'',0,0,'C');
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalPrestamos, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCobros, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalEfectivo, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalCaja, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalDiferencia, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
###################### FUNCION LISTAR APERTURAS DE CAJAS POR FECHAS ######################

############################## REPORTES DE CAJAS ##############################


















############################## REPORTES DE MOVIMIENTOS ##############################

########################## FUNCION TICKET MOVIMIENTO (TICKET) ##############################
function TicketMovimiento()
{  
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $tra = new Login();
    $reg = $tra->MovimientosPorId();
  
    if (file_exists("./fotos/logo_principal.png")) {

    $logo = "./fotos/logo_principal.png";
    $this->Image($logo, 15, 3, 45, 15, "PNG");
    $this->Ln(8);

    }

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->Cell(70, 5, "TICKET DE MOVIMIENTO", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,4,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(70,3,$con[0]['documsucursal'] == '0' ? "" : "Nº ".$con[0]['documento']." ".utf8_decode($con[0]['cuitsucursal']),0,1,'C');

    if($con[0]['codprovincia'] != '0'){

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($provincia = ($con[0]['codprovincia'] == '0' ? " " : $con[0]['provincia'])." ".$departamento = ($con[0]['coddepartamento'] == '0' ? " " : $con[0]['departamento'])),0,1,'C');

    }

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($con[0]['direcsucursal']),0,1,'C');

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($con[0]['correosucursal']),0,1,'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Nro Ticket:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['numero']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(18,4,"CAJA:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(52,4,portales(utf8_decode($reg[0]['nrocaja']."-".$reg[0]['nomcaja'])), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(18,4,"CAJERO:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(52,4,portales(utf8_decode($reg[0]['nombres'])), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"FECHA: ".utf8_decode(date("d/m/Y",strtotime($reg[0]['fechamovimiento']))), 0, 0, 'J');
    $this->CellFitSpace(35,4,"HORA: ".utf8_decode(date("H:i:s",strtotime($reg[0]['fechamovimiento']))), 0, 0, 'R');
    $this->Ln();

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"TIPO:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['tipomovimiento']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"MONTO:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($simbolo.number_format($reg[0]["montomovimiento"], 2, '.', ',')), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->SetFillColor(2,157,116);
    $this->Cell(70, 5,"DESCRIPCIÓN DE MOVIMIENTO", 0, 0, 'L');
    $this->Ln(5);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->MultiCell(70,3,$this->SetFont('Courier','',8).utf8_decode($reg[0]['descripcionmovimiento'] == "") ? "SIN OBSERVACIONES" : $reg[0]['descripcionmovimiento'],0,'J');
    $this->Ln();

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln();

    $this->SetX(2);
    $this->MultiCell(70,3,$this->SetFont('Courier','BI',10)." ",0,'C');
    $this->Ln(3);  

    /*$this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"CAJA Nº:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(50,3,utf8_decode($reg[0]['nrocaja']."-".$reg[0]['nomcaja']),0,1,'L');
    
    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"CAJERO:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(50,3,utf8_decode($reg[0]['nombres']),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(20,3,"FECHA EMISIÓN:",0,0,'L');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(40,3,date("d-m-Y H:i:s"),0,1,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"HORA APERTURA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode(date("d-m-Y H:i:s",strtotime($reg[0]['fechaapertura']))),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"HORA CIERRE:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode(date("d-m-Y H:i:s",strtotime($reg[0]['fechacierre']))),0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,"MONTO APERTURA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(40,3,utf8_decode($simbolo.number_format($reg[0]["montoinicial"], 2, '.', ',')),0,1,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"DESGLOSE EN COBROS",0,1,'C');
    $this->Ln(1);

    $a=1;
    $Monto_Efectivo = 0;
    for($i=0;$i<sizeof($reg);$i++):
    $Monto_Efectivo += ($reg[$i]['formapago'] == "EFECTIVO" ? $reg[$i]['montopagado'] : 0);
       if($reg[$i]['formapago'] != ""){

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,portales(utf8_decode($reg[$i]['formapago'])),0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[$i]['montopagado'], 2, '.', ',')),0,1,'R');

       }
    endfor;

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"PRÉSTAMOS",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"PRÉSTAMOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["prestamos"], 2, '.', ',')),0,1,'R');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,3,"MOVIMIENTOS EN CAJA",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"INGRESOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["ingresos"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EGRESOS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["egresos"], 2, '.', ',')),0,1,'R');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->CellFitSpace(70,3,"REPORTE DE CAJA",0,1,'C');
    $this->Ln(1);

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"TOTAL EN COBROS:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["pagos"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"COBROS EN EFECTIVO:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($Monto_Efectivo, 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EFECTIVO EN CAJA:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format(($reg[0]["montoinicial"]+$Monto_Efectivo+$reg[0]['ingresos'])-($reg[0]["egresos"]+$reg[0]["prestamos"]), 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"EFECTIVO DISPONIBLE:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["dineroefectivo"], 2, '.', ',')),0,1,'R');

    $this->SetX(2);
    $this->SetFont('Courier','B',8);
    $this->CellFitSpace(34,3,"DIF. EFECTIVO:",0,0,'L');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(6,3,utf8_decode($simbolo),0,0,'R');
    $this->SetFont('Courier',"B",8);
    $this->CellFitSpace(30,3,utf8_decode(number_format($reg[0]["diferencia"], 2, '.', ',')),0,1,'R');

    if($reg[0]["comentarios"]==""){

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln(3);

   } else { 

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(2);

    $this->SetX(2);
    $this->MultiCell(70,4,$this->SetFont('Courier',"",7).utf8_decode($reg[0]["comentarios"]),0,'L');

    $this->SetFont('Courier','B',12);
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln(3);

    }

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(13,4,"FIRMA:",0,0,'L');
    $this->Cell(55,5,utf8_decode("_________________________"),0,1,'L');
 
    $this->SetFont('Courier','BI',9);
    $this->SetX(2);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(70,3," ",0,1,'C');
    $this->Ln(3);*/
}
########################## FUNCION TICKET MOVIMIENTO (TICKET) ##############################

####################### FUNCION LISTAR MOVIMIENTOS EN CAJA ##########################
function TablaListarMovimientos()
{
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE MOVIMIENTOS EN CAJA',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'CAJA',1,0,'C', True);
    $this->Cell(20,8,'TIPO',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN MOVIMIENTO',1,0,'C', True);
    $this->Cell(25,8,'FECHA',1,0,'C', True);
    $this->Cell(30,8,'MONTO',1,1,'C', True);

    $tra = new Login();
    $reg = $tra->ListarMovimientos();

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(10,35,20,70,25,30));

    $a=1;
    $TotalIngresos = 0;
    $TotalEgresos  = 0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalIngresos += ($reg[$i]['tipomovimiento'] == 'INGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
    $TotalEgresos  += ($reg[$i]['tipomovimiento'] == 'EGRESO' ? $reg[$i]['montomovimiento'] : "0.00");

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']),utf8_decode($reg[$i]['tipomovimiento']),portales(utf8_decode($reg[$i]['descripcionmovimiento'])),utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechamovimiento']))),utf8_decode($simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','))));
    }
   
    $this->Cell(135,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(25,5,'INGRESOS',0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalIngresos, 2, '.', ',')),0,1,'L');

    $this->Cell(135,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(25,5,'EGRESOS',0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalEgresos, 2, '.', ',')),0,1,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
######################## FUNCION LISTAR MOVIMIENTOS EN CAJAS #########################

##################### FUNCION LISTAR MOVIMIENTOS EN CAJA POR FECHAS #####################
function TablaListarMovimientosxFechas()
{
    $tra = new Login();
    $reg = $tra->BuscarMovimientosxFechas();
    
    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    } 

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO DE MOVIMIENTOS EN CAJA POR FECHAS',0,0,'C');

    $this->Ln();
    $this->Cell(190,5,"Nº DE CAJA: ".utf8_decode($reg[0]['nrocaja'].": ".$reg[0]['nomcaja']),0,0,'L');
    $this->Ln();
    $this->Cell(190,5,"RESPONSABLE: ".portales(utf8_decode($reg[0]['nombres'])),0,0,'L');
    $this->Ln();
    $this->Cell(190,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(190,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255, 255, 255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(20,8,'TIPO',1,0,'C', True);
    $this->Cell(85,8,'DESCRIPCIÓN MOVIMIENTO',1,0,'C', True);
    $this->Cell(45,8,'FECHA',1,0,'C', True);
    $this->Cell(30,8,'MONTO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(10,20,85,45,30));

    $a=1;
    $TotalIngresos = 0;
    $TotalEgresos  = 0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalIngresos += ($reg[$i]['tipomovimiento'] == 'INGRESO' ? $reg[$i]['montomovimiento'] : "0.00");
    $TotalEgresos  += ($reg[$i]['tipomovimiento'] == 'EGRESO' ? $reg[$i]['montomovimiento'] : "0.00");

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['tipomovimiento']),portales(utf8_decode($reg[$i]['descripcionmovimiento'])),utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechamovimiento']))),utf8_decode($simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','))));
    }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(30,5,'INGRESOS',0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalIngresos, 2, '.', ',')),0,1,'L');

    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(30,5,'EGRESOS',0,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalEgresos, 2, '.', ',')),0,1,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
}
##################### FUNCION LISTAR MOVIMIENTOS EN CAJAS POR FECHAS ###################

############################## REPORTES DE MOVIMIENTOS ##############################



















################################### REPORTES DE PRESTAMOS ##################################

########################### FUNCION PARA FACTURA DE PRESTAMO ##############################
function TablaFacturaPrestamo()
{      
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $new = new Login();
    $reg = $new->PrestamosPorId();

    if($reg[0]['statusprestamo'] == 1) { 
    $estado = "PENDIENTE"; 
    } elseif($reg[0]['statusprestamo'] == 2) {  
    $estado = "APROBADA";
    } elseif($reg[0]['statusprestamo'] == 3) { 
    $estado = "RECHAZADA";
    } elseif($reg[0]['statusprestamo'] == 4) { 
    $estado = "CANCELADA";
    } elseif($reg[0]['statusprestamo'] == 5) { 
    $estado = "PAGADA";
    }

    if (file_exists("./fotos/logo_principal.png")) {

        $logo = "./fotos/logo_principal.png";
        $this->Image($logo, 9, 8, 39, 15, "PNG");
        $this->Ln(8);
    }

    ############### BLOQUE DEL MEMBRETE ###################
    //Bloque de membrete principal
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.3);
    $this->RoundedRect(10, 6, 190, 26, '1.5', '');
    
    //Linea de membrete Nro 1
    $this->SetFont('Courier','BI',9);
    $this->SetXY(48, 7);
    $this->CellFitSpace(72, 4, utf8_decode($con[0]['nomsucursal']), 0, 0);
    $this->SetXY(120, 7);
    $this->CellFitSpace(78, 4, 'N° DE PRÉSTAMO: '.$reg[0]["codfactura"], 0, 0);
    
    //Linea de membrete Nro 2
    $this->SetXY(48, 11);
    $this->CellFitSpace(72, 4, $con[0]['documsucursal'] == '0' ? "" : $reg[0]['documento']." ".utf8_decode($con[0]['cuitsucursal']), 0, 0);
    $this->SetXY(120, 11);
    $this->CellFitSpace(78, 4, 'FECHA DE PRÉSTAMO: '.utf8_decode(date("d-m-Y",strtotime($reg[0]['fechaprestamo']))), 0, 0);
    
    //Linea de membrete Nro 3
    $this->SetXY(48, 15);
    $this->CellFitSpace(72, 4, utf8_decode($con[0]['direcsucursal']), 0, 0);
    $this->SetXY(120, 15);
    $this->CellFitSpace(78, 4, 'HORA DE PRÉSTAMO: '.utf8_decode(date("H:i:s",strtotime($reg[0]['fechaprestamo']))), 0, 0);
    
    //Linea de membrete Nro 4
    $this->SetXY(48, 19);
    $this->CellFitSpace(72, 4, utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento']." ").$provincia = ($con[0]['codprovincia'] == '0' ? "" : " ".$reg[0]['provincia']." ")), 0, 0);
    $this->SetXY(120, 19);
    $this->CellFitSpace(78, 4, 'FECHA DE EMISIÓN: '.utf8_decode(date("d-m-Y")), 0, 0); 
    
    //Linea de membrete Nro 5
    $this->SetXY(48, 23);
    $this->CellFitSpace(72, 4, utf8_decode($con[0]['correosucursal']), 0, 0);
    $this->SetXY(120, 23);
    $this->CellFitSpace(78, 4, 'USUARIO: '.utf8_decode($reg[0]["nombres"]), 0, 0);
    
    //Linea de membrete Nro 6
    $this->SetXY(48, 27);
    $this->CellFitSpace(72, 4, 'TLF: '.utf8_decode($con[0]['tlfsucursal']), 0, 0);
    $this->SetXY(120, 27);
    $this->CellFitSpace(78, 4, "N° CAJA ".utf8_decode($caja = ($reg[0]["codcaja"] == 0 ? "******" : $reg[0]["nrocaja"].": ".$reg[0]["nomcaja"])), 0, 0);
    ############### BLOQUE DEL MEMBRETE ###################

    ############### BLOQUE DE DATOS PERSONALES DEL CLIENTE ###################
    //Bloque de membrete principal
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.3);
    $this->RoundedRect(10, 40, 190, 26, '1.5', '');

    $this->SetXY(10, 34);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(190,6,' DATOS PERSONALES DEL CLIENTE',1,0,'L', True);
    $this->Ln();
    
    //Linea de membrete Nro 3
    $this->SetFont('Courier','B',9);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    
    $this->SetXY(11, 41);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, $documento = ($reg[0]['documcliente'] == '0' ? "Nº DE DOC:" : "Nº DE ".$reg[0]['documento']), 0, 0);
    $this->SetXY(11, 45);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, utf8_decode($reg[0]['cedcliente']), 0, 0);

    $this->SetXY(56, 41);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(100, 4, 'NOMBRES Y APELLIDOS', 0, 0);
    $this->SetXY(56, 45);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(100, 4, portales(utf8_decode($reg[0]['nomcliente'])), 0, 0);

    $this->SetXY(156, 41);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(43, 4, 'N° DE TELEFONO', 0, 0);
    $this->SetXY(156, 45);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(43, 4, utf8_decode($reg[0]['tlfcliente'] == "" ? "******" : $reg[0]['tlfcliente']), 0, 0);

    $this->SetXY(11, 49);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, "N° DE CELULAR", 0, 0);
    $this->SetXY(11, 53);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, utf8_decode($reg[0]['celcliente'] == "" ? "******" : $reg[0]['celcliente']), 0, 0);

    $this->SetXY(56, 49);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(143, 4, "DIRECCIÓN DOMICILIARIA", 0, 0);
    $this->SetXY(56, 53);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(143, 4, utf8_decode($direccion = ($reg[0]['direccliente'] == '' ? "******" : $reg[0]['direccliente'])." ".$departamento = ($reg[0]['coddepartamento'] == '0' ? " " : $reg[0]['departamento']))." ".$provincia = ($reg[0]['codprovincia'] == '0' ? " " : $reg[0]['provincia']), 0, 0);

    $this->SetXY(11, 57);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, "CORREO ELECTRONICO", 0, 0);
    $this->SetXY(11, 61);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, utf8_decode($email = ($reg[0]['email'] == '' ? "******" : $reg[0]['email'])), 0, 0);

    $this->SetXY(56, 57);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(100, 4, "PERSONA DE REFERENCIA", 0, 0);
    $this->SetXY(56, 61);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(100, 4, utf8_decode($persona = ($reg[0]['nomreferencia'] == '' ? "******" : $reg[0]['nomreferencia'])), 0, 0);

    $this->SetXY(156, 57);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(43, 4, "N° DE CELULAR", 0, 0);
    $this->SetXY(156, 61);
    $this->SetFont('Courier','',10);
    $this->Cell(43, 4, utf8_decode($email = ($reg[0]['email'] == '' ? "******" : $reg[0]['email'])), 0, 0);
    ############### BLOQUE DE DATOS PERSONALES DEL CLIENTE ###################

    ############### BLOQUE DE DATOS PRESTAMO ###################
    //Bloque de membrete principal
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.3);
    $this->RoundedRect(10, 74, 190, 26, '1.5', '');

    $this->SetXY(10, 68);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(190,6,' DATOS DE PRÉSTAMO',1,0,'L', True);
    $this->Ln();
    
    //Linea de membrete Nro 3
    $this->SetFont('Courier','B',9);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    
    $this->SetXY(11, 75);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(50, 4, "MONTO DE PRÉSTAMO", 0, 0);
    $this->SetXY(11, 79);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(50, 4, $simbolo.number_format($reg[0]['montoprestamo'], 2, '.', ','), 0, 0);

    $this->SetXY(61, 75);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, 'INTERESES (%)', 0, 0);
    $this->SetXY(61, 79);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, number_format($reg[0]['intereses'], 2, '.', ','), 0, 0);

    $this->SetXY(106, 75);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, 'MONTO INTERES', 0, 0);
    $this->SetXY(106, 79);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, $simbolo.number_format($reg[0]['totalintereses'], 2, '.', ','), 0, 0);

    $this->SetXY(151, 75);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(48, 4, "MONTO TOTAL", 0, 0);
    $this->SetXY(151, 79);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(48, 4, $simbolo.number_format($reg[0]['totalpago'], 2, '.', ','), 0, 0);


    $this->SetXY(11, 83);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(50, 4, "CUOTAS", 0, 0);
    $this->SetXY(11, 87);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(50, 4, $simbolo.number_format($reg[0]['cuotas'], 2, '.', ','), 0, 0);

    $this->SetXY(61, 83);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, 'MONTO X CUOTA', 0, 0);
    $this->SetXY(61, 87);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, $simbolo.number_format($reg[0]['montoxcuota'], 2, '.', ','), 0, 0);

    $this->SetXY(106, 83);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(45, 4, 'PERIODO DE PAGO', 0, 0);
    $this->SetXY(106, 87);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(45, 4, utf8_decode($reg[0]['periodopago']), 0, 0);

    $this->SetXY(151, 83);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(48, 4, "ESTADO", 0, 0);
    $this->SetXY(151, 87);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(189,54,58);  // Establece el color del texto (en este caso es ROJO)
    $this->CellFitSpace(48, 4, utf8_decode($estado), 0, 0);

    $this->SetXY(11, 91);
    $this->SetFont('Courier','B',9);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->CellFitSpace(188, 4, "OBSERVACIONES", 0, 0);
    $this->SetXY(11, 95);
    $this->SetFont('Courier','',10);
    $this->CellFitSpace(188, 4, utf8_decode($observaciones = ($reg[0]['observaciones'] == '' ? "******" : $reg[0]['observaciones'])), 0, 0);
    ############### BLOQUE DE DATOS PRESTAMO ###################


    ############### BLOQUE DETALLES DE PAGO ###################
    //$this->SetXY(6, 110);
    $this->SetY(102);
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(190,8,' DETALLES DE PAGOS',1,0,'L', True);
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3, 3, 3);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,5,'Nº',1,0,'C', False);
    $this->Cell(30,5,'FECHA DE PAGO',1,0,'C', False);
    $this->Cell(40,5,'SALDO INICIAL',1,0,'C', False);
    $this->Cell(35,5,'CUOTA',1,0,'C', False);
    $this->Cell(40,5,'SALDO FINAL',1,0,'C', False);
    $this->Cell(30,5,'STATUS',1,1,'C', False);
    
    $js = new Login();
    $detalle = $js->VerDetallesCuotas();

    if($detalle==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,30,40,35,40,30));

    $a=1;
    for($i=0;$i<sizeof($detalle);$i++){

    if($detalle[$i]["statuspago"]==0){
        $status = "**********";
    } elseif($detalle[$i]["statuspago"]==1){
        $status = "PAGADA";
    } elseif($detalle[$i]["statuspago"]==2){
        $status = "VENCIDA";
    } elseif($detalle[$i]["statuspago"]==3){
        $status = "ANULADA";
    }

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode(date("d-m-Y",strtotime($detalle[$i]['fechapago']))),utf8_decode($simbolo.number_format($detalle[$i]['saldoinicial'], 2, '.', ',')),utf8_decode($simbolo.number_format($detalle[$i]['capital'], 2, '.', ',')),utf8_decode($simbolo.number_format($detalle[$i]['saldofinal'], 2, '.', ',')),utf8_decode($status)));
       }
    }
    ############### BLOQUE DETALLES DE PAGO ###################


    $this->Ln(12);
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,6,'______________________________________',0,0,'C');
    $this->Cell(90,6,'______________________________________',0,0,'C');
    $this->Ln(); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,portales(utf8_decode($documento1 = ($con[0]['documsucursal'] == 0 ? "DOC." : $con[0]['documento'])." ".$reg[0]["dni"])),0,0,'C');
    $this->Cell(90,4,portales(utf8_decode($documento2 = ($reg[0]['documcliente'] == 0 ? "DOC." : $reg[0]['documento'])." ".$reg[0]["cedcliente"])),0,0,'C');
    $this->Ln(); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,portales(utf8_decode($con[0]["nomsucursal"])),0,0,'C');
    $this->Cell(90,4,portales(utf8_decode($reg[0]["nomcliente"])),0,0,'C');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,'PRESTAMISTA',0,0,'C');
    $this->Cell(90,4,'CLIENTE(A)',0,0,'C');
    $this->Ln(4);
}
########################### FUNCION PARA FACTURA DE PRESTAMO ##############################

########################### FUNCION PARA CONTRATO DE PRESTAMO ##############################
function TablaContratoPrestamo()
{      
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $new = new Login();
    $reg = $new->PrestamosPorId();

    if($reg[0]['statusprestamo'] == 1) { 
    $estado = "PENDIENTE"; 
    } elseif($reg[0]['statusprestamo'] == 2) {  
    $estado = "APROBADA";
    } elseif($reg[0]['statusprestamo'] == 3) { 
    $estado = "RECHAZADA";
    } elseif($reg[0]['statusprestamo'] == 4) { 
    $estado = "CANCELADA";
    } elseif($reg[0]['statusprestamo'] == 5) { 
    $estado = "PAGADA";
    }

    ################################# MEMBRETE A4 #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(55,5,$this->Image($logo, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo1_vertical']),0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(55,5,$this->Image($logo2, $this->GetX()+3, $this->GetY()+8, $GLOBALS['logo2_vertical']),0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,"TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(55,5,"",0,0,'C');
    $this->CellFitSpace(80,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(55,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE A4 #################################

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,12,'CONTRATO PRIVADO DE PROMESA DE PRÉSTAMO',0,1,'C');

    $this->SetFont('courier','B',14);
    $this->CellFitSpace(155,5,"CONTRATO Nº:",0,0,'R');
    $this->SetFont('courier','B',14);
    $this->SetTextColor(189,54,58); // Establece el color del texto (en este caso es ROJO)
    $this->CellFitSpace(35,5,utf8_decode($reg[0]["codfactura"]),0,1,'R');


    $this->Ln(4);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock("CONSTE POR EL PRESENTE DOCUMENTO QUE SE SUSCRIBE, EL CONTRATO DE PRÉSTAMO DE DINERO QUE CELEBRA DE UNA PARTE LA EMPRESA ");

    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($con[0]['nomsucursal'])));

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" DE LA OTRA PARTE EL (LA) SR.(A) ");

    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($reg[0]['nomcliente'])));

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(", CON ");

    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($var = ($reg[0]['documcliente'] == 0 ? "DOC." : $reg[0]['documento'])))." Nº ".$reg[0]['cedcliente']);

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(", DOMICILIADO/A EN ");

    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($reg[0]['direccliente'])));

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(", QUIEN EN ADELANTE SE LE DENOMINARÁ");

    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(" EL CLIENTE, ");

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock("PARA LOS EFECTOS A QUE SE CONTRAE LA CLAUSULA ADICIONAL DEL PRESENTE, Y LOS SEÑORES CLIENTES EN LOS TÉRMINOS Y CONDICIONES SIGUIENTES:");
    $this->finishFlowingBlock();
    $this->Ln(5); 

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('PRIMERO: '.portales(utf8_decode($con[0]['nomsucursal'])));

    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" ES UNA EMPRESA, CUYO OBJETIVO SOCIAL ES LA PRESTACIÓN DE TODA CLASE DE SERVICIOS FINANCIEROS, OTORGANDO O COLOCANDO CÉDITOS MEDIANTE PRÉSTAMOS CON GARANTIA REALES Y OTROS, CON APLICACIÓN DE TASAS DE INTERÉS ACORDE A LAS DISPOSICIONES VIGENTES, CRÉDITOS DIRIGIDOS A PERSONAS NATURALES Y JURÍDICAS.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('SEGUNDO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" DEL PRÉSTAMO, ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($con[0]['nomsucursal'])));
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" A SOLICITUD DEL (LOS) CLIENTE(S), APROBÓ OTORGARLES UN PRÉSTAMO CON EL FIN DE QUE EL (LOS) CLIENTE(S), PUEDAN UTILIZARLO COMO CONSUMO PERSONAL, ACTIVO FIJO Y/O CAPITAL DE TRABAJO, DINERO QUE ES ENTREGADO EN EFECTIVO, SIN UTILIZAR MEDIO DE PAGO ALGUNO, EL QUE ESTARÁ REPRESENTDOEN UNA O MAS LETRAS DE PAGOS Y/P PAGARÉ.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('TERCERO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" EL PRÉSTAMO OTORGADO ES DE, ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock($simbolo.number_format($reg[0]["montoprestamo"], 2, '.', ',')." ".portales(utf8_decode($con[0]['moneda'])));
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock("  CON UNA TASA DEL ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(number_format($reg[0]["intereses"], 2, '.', ',').'%');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" QUE GENERAN CUOTAS DEL CRÉDITO OTORGADO.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('CUARTO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" EL(LOS) CLIENTE(S), SE COMPROMETE(N) A DEVOLVER EL PRÉSTAMO OTORGADO EN EL PLAZO DE ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock($reg[0]["cuotas"].' CUOTAS');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" MEDIANTE AMORTIZACIONES ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($reg[0]['periodopago'])));
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" DE ACUERDO EL CRONOGRAMA ENTREGADO POR LA EMPRESA.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('QUINTO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" EL(LOS) CLIENTE(S), SE COMPROMETE(N) A PAGAR SUS CUOTAS (LETRAS) PUNTUALMENTE A ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($con[0]['nomsucursal'])));
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(", ANTE EL INCUMPLIMIENTO DEL PAGO DE UNA O MAS CUOTAS (LETRAS) SUCESIVAS, EL(LOS) CLIENTE(S) SE SOMETERÁN AL PAGO DE LOS INTERESES, MORA Y MAS GASTOS CASUSADOS POR LOS TRÁMITES PERTINENTES.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('SEXTO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" EN CASO QUE EL(LOS) CLIENTE(S) NO CUMPLIESEN LAS CONDICIONES DE LOS PAGOS SEÑALADOS, SE OBLIGARÁ A INFORMAR POR MOROSIDAD A LOS CLIENTES, CÓNYUGES Y/O AVALISTA(A)."); 
    $this->finishFlowingBlock();
    $this->Ln(5);

    $this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('SEPTIMO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" CLIENTE, C´NYUGE Y AVALISTAS DEJAN EXPRESA CONSTANCIA QUE CONSTITUYE FIANZA SOLIDARIA, INDIVISIBLE E ILIMITADA Y POR PLAZO INDETERMINADO A FAVOR DE LOS CLIENTES, CON EL OBJETO DE RESPONDER SOLIDARIAMENTE POR EL CUMPLIMIENTO DE TODAS LAS OBLIGACIONES QUE ÉSTOS ASUMEN CON ");
    $this->SetFont('Courier', 'B', 12);
    $this->WriteFlowingBlock(portales(utf8_decode($con[0]['nomsucursal'])));
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(", EN VIRTUD DEL OTORGAMIENTO DEL CRÉDITO A QUE SE REFIERE EL PRESENTE CONTRATO.");
    $this->finishFlowingBlock();
    $this->Ln(5);

    /*$this->SetFont('Courier','B',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('OCTAVO: ');
    $this->SetFont('Courier', '', 12);
    $this->WriteFlowingBlock(" DE ACUERDO A LOS ESTABLECIDO EN EL ARTICULO 123456 DEL CÓDIGO CIVIL, SE PACTA LA CAPITALIZACIÓN DE LOS INTERESES DEVENGADOS.");
    $this->finishFlowingBlock();
    $this->Ln(3);*/

    $this->SetFont('Courier','b',12);  
    $this->newFlowingBlock(190, 5, 0, 'J');
    $this->WriteFlowingBlock('SE SUSCRIBE EL PRESENTE CONTRATO EL '.Dias(date("w")).", A LOS ".date("d")." DIAS DE ".convertir(date("m"))." DEL ".date("Y"));
    $this->finishFlowingBlock();
    $this->Ln();

    $this->Ln(12);
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,6,'______________________________________',0,0,'C');
    $this->Cell(90,6,'______________________________________',0,0,'C');
    $this->Ln(); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,portales(utf8_decode($documento1 = ($con[0]['documsucursal'] == 0 ? "DOC." : $con[0]['documento'])." ".$reg[0]["dni"])),0,0,'C');
    $this->Cell(90,4,portales(utf8_decode($documento2 = ($reg[0]['documcliente'] == 0 ? "DOC." : $reg[0]['documento'])." ".$reg[0]["cedcliente"])),0,0,'C');
    $this->Ln(); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,portales(utf8_decode($con[0]["nomsucursal"])),0,0,'C');
    $this->Cell(90,4,portales(utf8_decode($reg[0]["nomcliente"])),0,0,'C');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(90,4,'PRESTAMISTA',0,0,'C');
    $this->Cell(90,4,'CLIENTE(A)',0,0,'C');
    $this->Ln(4);
}
########################### FUNCION PARA CONTRATO DE PRESTAMO ##############################

########################## FUNCION LISTAR PRESTAMOS ##############################
function TablaListarPrestamos()
{
    $tra = new Login();
    $reg = $tra->ListarPrestamos();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE PRESTAMOS',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(40,8,'MONTO PRESTAMO',1,0,'C', True);
    $this->Cell(25,8,'INTERESES %',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(35,8,'FECHA EMISIÓN',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(45,8,'TOTAL IMPORTE',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,40,25,20,25,35,30,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado    = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado    += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode($simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ',')),
        utf8_decode(number_format($reg[$i]['intereses'], 2, '.', ',')."%"),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','))));
    }
   
    $this->Cell(285,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS ##############################

########################## FUNCION LISTAR PRESTAMOS PENDIENTES ##############################
function TablaListarPrestamosPendientes()
{
    $tra = new Login();
    $reg = $tra->ListarPrestamosPendientes();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE PRESTAMOS PENDIENTES',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(40,8,'MONTO PRESTAMO',1,0,'C', True);
    $this->Cell(25,8,'INTERESES %',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(35,8,'FECHA EMISIÓN',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(45,8,'TOTAL IMPORTE',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,40,25,20,25,35,30,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado    = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado    += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode($simbolo.number_format($reg[$i]['montoprestamo'], 2, '.', ',')),
        utf8_decode(number_format($reg[$i]['intereses'], 2, '.', ',')."%"),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','))));
    }
   
    $this->Cell(285,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS PENDIENTES ##############################





########################## FUNCION LISTAR PRESTAMOS EN MORA ##############################
function TablaListarCuotasVencidas()
{
    $tra = new Login();
    $reg = $tra->ListarPrestamosMora();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE CUOTAS EN MORA',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(115,8,'PERIODO EN MORA',1,0,'C', True);
    $this->Cell(25,8,'CUOTAS',1,0,'C', True);
    $this->Cell(35,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(45,8,'TOTAL IMPORTE',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,115,25,35,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalDeuda    = 0;

    for($i=0;$i<sizeof($reg);$i++){ 
       
    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['cuotas_mora'];
    $TotalDeuda    += $reg[$i]['suma_mora'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(str_replace("<br>"," | ", $reg[$i]['meses_mora'])),
        utf8_decode($reg[$i]["cuotas_mora"]),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','))));
    }
   
    $this->Cell(285,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(45,5,utf8_decode($simbolo.number_format($TotalDeuda, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS EN MORA ##############################

########################## FUNCION LISTAR PRESTAMOS POR CAJAS ##############################
function TablaListarPrestamosxCajas()
{
    $tra = new Login();
    $reg = $tra->BuscarPrestamosxCajas();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PRESTAMOS POR CAJAS',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"Nº CAJA: ".utf8_decode($reg[0]["nrocaja"].": ".$reg[0]["nomcaja"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"RESPONSABLE DE CAJA: ".portales(utf8_decode($reg[0]["nombres"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(40,8,'FECHA PRESTAMO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL IMPORTE',1,0,'C', True);
    $this->Cell(35,8,'TOTAL PAGADO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEUDA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,20,25,30,40,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado   = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado   += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente'])),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','))));
    }
   
    $this->Cell(225,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPendiente, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS POR CAJAS ##############################

########################## FUNCION LISTAR PRESTAMOS POR FECHAS ##############################
function TablaListarPrestamosxFechas()
{
    $tra = new Login();
    $reg = $tra->BuscarPrestamosxFechas();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    if(decrypt($_GET["estado_prestamo"]) == 1){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PENDIENTRES POR FECHAS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 2){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS APROBADOS POR FECHAS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 3){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS RECHAZADOS POR FECHAS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 4){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS CANCELADOS POR FECHAS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 5){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PAGADOS POR FECHAS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 6){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS EN GENERAL POR FECHAS',0,0,'C');
    }

    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(40,8,'FECHA PRESTAMO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL IMPORTE',1,0,'C', True);
    $this->Cell(35,8,'TOTAL PAGADO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEUDA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,20,25,30,40,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado   = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado   += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente'])),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','))));
    }
   
    $this->Cell(225,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPendiente, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS POR FECHAS ##############################

########################## FUNCION LISTAR PRESTAMOS POR CLIENTES ##############################
function TablaListarPrestamosxClientes()
{
    $tra = new Login();
    $reg = $tra->BuscarPrestamosxClientes();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    if(decrypt($_GET["estado_prestamo"]) == 1){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PENDIENTRES POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 2){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS APROBADOS POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 3){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS RECHAZADOS POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 4){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS CANCELADOS POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 5){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PAGADOS POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 6){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS EN GENERAL POR CLIENTES',0,0,'C');
    }

    $this->Ln();
    $this->Cell(330,5,"Nº ".utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'])).": ".utf8_decode($reg[0]["cedcliente"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"NOMBRE DE CLIENTE: ".portales(utf8_decode($reg[0]["nomcliente"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"N° DE TELEFONO: ".utf8_decode($telefono = ($reg[0]['tlfcliente'] == '' ? "**********" : $reg[0]["tlfcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"N° DE CELULAR: ".utf8_decode($celular = ($reg[0]['celcliente'] == '' ? "**********" : $reg[0]["celcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"CORREO ELECTRONICO: ".portales(utf8_decode($correo = ($reg[0]['email'] == '' ? "**********" : $reg[0]["email"]))),0,0,'L');  

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(55,8,'REGISTRADO POR',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA PRESTAMO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL IMPORTE',1,0,'C', True);
    $this->Cell(35,8,'TOTAL PAGADO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEUDA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,55,20,25,30,45,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado   = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado   += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["nombres"]),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','))));
    }
   
    $this->Cell(225,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPendiente, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS POR CLIENTES ##############################

########################## FUNCION LISTAR PRESTAMOS POR VENDEDOR ##############################
function TablaListarPrestamosxUsuarios()
{
    $tra = new Login();
    $reg = $tra->BuscarPrestamosxUsuarios();
 
    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    if(decrypt($_GET["estado_prestamo"]) == 1){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PENDIENTRES POR USUARIOS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 2){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS APROBADOS POR CLIENTES',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 3){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS RECHAZADOS POR USUARIOS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 4){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS CANCELADOS POR USUARIOS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 5){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS PAGADOS POR USUARIOS',0,0,'C');
    } elseif(decrypt($_GET["estado_prestamo"]) == 6){
    $this->Cell(330,10,'LISTADO DE PRÉSTAMOS EN GENERAL POR USUARIOS',0,0,'C');
    }

    $this->Ln();
    $this->Cell(330,5,"Nº DE DOCUMENTO: ".utf8_decode($reg[0]["dni"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"NOMBRES: ".portales(utf8_decode($reg[0]["nombres"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(60,8,'DESCRIPCIÓN DE CIENTE',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(25,8,'ESTADO',1,0,'C', True);
    $this->Cell(30,8,'PERIODO PAGO',1,0,'C', True);
    $this->Cell(40,8,'FECHA PRESTAMO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL IMPORTE',1,0,'C', True);
    $this->Cell(35,8,'TOTAL PAGADO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEUDA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,20,25,30,40,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte   = 0;
    $TotalPagado   = 0;
    $TotalPendiente = 0;

    for($i=0;$i<sizeof($reg);$i++){ 

    if($reg[$i]["statusprestamo"] == 1){
        $status = "PENDIENTE";
    } elseif($reg[$i]["statusprestamo"] == 2){
        $status = "APROBADO";
    } elseif($reg[$i]["statusprestamo"] == 3){
        $status = "RECHAZADA";
    } elseif($reg[$i]["statusprestamo"] == 4){
        $status = "CANCELADA";
    } elseif($reg[$i]["statusprestamo"] == 5){
        $status = "PAGADA";
    }

    $TotalImporte   += $reg[$i]['totalpago'];
    $TotalPagado   += $reg[$i]['creditopagado'];
    $TotalPendiente += $reg[$i]['totalpago']-$reg[$i]['creditopagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['cedcliente'].": ".$reg[$i]['nomcliente'])),
        utf8_decode($reg[$i]["cuotas"]),
        utf8_decode($status),
        portales(utf8_decode($reg[$i]['periodopago'])),
        utf8_decode(date("d-m-Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ','))));
    }
   
    $this->Cell(225,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPendiente, 2, '.', ',')),0,0,'L');
    $this->Ln();

    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PRESTAMOS POR VENDEDOR ##############################

################################### REPORTES DE PRESTAMOS ##################################


































############################## REPORTES DE PAGOS ##################################

############################ FUNCION PARA MOSTRAR COMPROBANTE DE PAGO ##############################
function TablaComprobantePago()
{
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);

    $tra = new Login();
    $reg = $tra->PagosporId();

    if (file_exists("./fotos/logo_principal.png")) {

    $logo = "./fotos/logo_principal.png";
    $this->Image($logo, 16, 5, 38, 12, "PNG");
    $this->Ln(8);

    }
  
    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->Cell(70, 5, "RECIBO DE PAGO", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetX(2);
    $this->SetFont('Courier','B',10);
    $this->CellFitSpace(70,4,portales(utf8_decode($con[0]['nomsucursal'])), 0, 1, 'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(70,3,$con[0]['documsucursal'] == '0' ? "" : "Nº ".$con[0]['documento']." ".utf8_decode($con[0]['cuitsucursal']),0,1,'C');

    $this->SetX(2);
    $this->CellFitSpace(70,3,portales(utf8_decode($con[0]['direcsucursal'])),0,1,'C');


    if($con[0]['coddepartamento']!='0'){

    $this->SetX(2);
    $this->CellFitSpace(70,3,utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? " " : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? " " : $con[0]['provincia'])),0,1,'C');
    }

    $this->SetX(2);
    $this->CellFitSpace(70,3,portales(utf8_decode($con[0]['correosucursal'])),0,1,'C');

    $this->SetX(2);
    $this->CellFitSpace(70,3,"TEL: ".utf8_decode($con[0]['tlfsucursal']),0,1,'C');

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(70,3,$documento = ($reg[0]['documcliente'] == '0' ? "Nº DOC" : "Nº ".$reg[0]['documento']).": ".$reg[0]['cedcliente'],0,1,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->MultiCell(70,3,$this->SetFont('Courier','B',9).portales(utf8_decode($reg[0]['nomcliente'])),0,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->MultiCell(70,3,$this->SetFont('Courier','B',9).portales(utf8_decode($reg[0]['direccliente'])),0,'L');

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(2);

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Nro Ticket:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['numerorecibo']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Nro Préstamo:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['codfactura']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(18,4,"Nº CAJA:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(52,4,portales(utf8_decode($reg[0]['nrocaja'].": ".$reg[0]['nomcaja'])), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(18,4,"CAJERO:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(52,4,portales(utf8_decode($reg[0]['nombres'])), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"FECHA: ".utf8_decode(date("d/m/Y",strtotime($reg[0]['fecharecibo']))), 0, 0, 'J');
    $this->CellFitSpace(35,4,"HORA: ".utf8_decode(date("H:i:s",strtotime($reg[0]['fecharecibo']))), 0, 0, 'R');
    $this->Ln();

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,3,'---------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Total Cuotas:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['cuotas']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Total Pagadas:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['cuotaspagadas']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Total Pendientes:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['cuotas']-$reg[0]['cuotaspagadas']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Cuotas Pagadas:  ", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,utf8_decode($reg[0]['totalcuotas']), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Monto x Cuota:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,$simbolo.number_format($reg[0]['montoxcuota'], 2, '.', ','), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Importe Pagado:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,$simbolo.number_format($reg[0]['totalpagado'], 2, '.', ','), 0, 1, 'R');

    $this->SetX(2);
    $this->SetFont('Courier','',9);
    $this->CellFitSpace(35,4,"Metodo de Pago:", 0, 0, 'J');
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(35,4,portales(utf8_decode($reg[0]['detalles_medios'])), 0, 1, 'R');
    $this->Ln();

    $this->SetX(2);
    $this->SetFont('Courier','B',12);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'---------------------------',0,1,'C');
    $this->Ln(6);

    $this->SetX(2);
    $this->SetFont('Courier','B',9);
    $this->CellFitSpace(13,4,"FIRMA:",0,0,'L');
    $this->Cell(55,5,utf8_decode("_________________________"),0,1,'L');
    $this->Ln();
}
############################ FUNCION PARA MOSTRAR COMPROBANTE DE PAGOS ##############################

########################## FUNCION LISTAR PAGOS ##############################
function TablaListarPagos()
{
    $tra = new Login();
    $reg = $tra->ListarPagos();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']);  

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS DE PRÉSTAMOS',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(30,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(25,8,'Nº DE PAGO',1,0,'C', True);
    $this->Cell(55,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(30,8,'FECHA PAGO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(55,8,'PERIODO PAGADOS',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE PAGADO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,30,25,55,30,20,55,30,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
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

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["nrocaja"].": ".$reg[$i]["nomcaja"]),
        utf8_decode($reg[$i]["numerorecibo"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo']))),
        utf8_decode($reg[$i]['totalcuotas']),
        utf8_decode($meses_pagados),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','))));
    }
   
    $this->Cell(295,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS ##############################

########################## FUNCION LISTAR PAGOS DEL DIA ##############################
function TablaListarPagosxDia()
{
    $tra = new Login();
    $reg = $tra->ListarPagosxDia();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS DE PRÉSTAMOS DEL DIA',0,0,'C');

    $this->Ln();
    $this->Cell(335,6,"FECHA: ".date("d/m/Y"),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(30,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(25,8,'Nº DE PAGO',1,0,'C', True);
    $this->Cell(55,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(30,8,'FECHA PAGO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(55,8,'PERIODO PAGADOS',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE PAGADO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,30,25,55,30,20,55,30,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalPagado   = 0;

    for($i=0;$i<sizeof($reg);$i++){
    $meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['totalcuotas'];
    $TotalPagado   += $reg[$i]['totalpagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["nrocaja"].": ".$reg[$i]["nomcaja"]),
        utf8_decode($reg[$i]["numerorecibo"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo']))),
        utf8_decode($reg[$i]['totalcuotas']),
        utf8_decode($meses_pagados),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','))));
    }
   
    $this->Cell(295,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS DEL DIA ##############################

########################## FUNCION LISTAR PAGOS POR CAJAS ##############################
function TablaListarPagosxCajas()
{
    $tra = new Login();
    $reg = $tra->BuscarPagosxCajas();

   ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS DE PRÉSTAMOS POR CAJAS',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"Nº CAJA: ".utf8_decode($reg[0]["nrocaja"].": ".$reg[0]["nomcaja"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"RESPONSABLE DE CAJA: ".portales(utf8_decode($reg[0]["nombres"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(30,8,'Nº DE PAGO',1,0,'C', True);
    $this->Cell(65,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(30,8,'FECHA PAGO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(65,8,'PERIODO PAGADOS',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE PAGADO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,30,65,30,20,65,30,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalPagado   = 0;

    for($i=0;$i<sizeof($reg);$i++){
    $meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['totalcuotas'];
    $TotalPagado   += $reg[$i]['totalpagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["numerorecibo"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo']))),
        utf8_decode($reg[$i]['totalcuotas']),
        utf8_decode($meses_pagados),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','))));
    }
   
    $this->Cell(290,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS POR CAJAS ##############################

########################## FUNCION LISTAR PAGOS POR FECHAS ##############################
function TablaListarPagosxFechas()
{
    $tra = new Login();
    $reg = $tra->BuscarPagosxFechas();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS DE PRÉSTAMOS POR FECHAS',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(30,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(25,8,'Nº DE PAGO',1,0,'C', True);
    $this->Cell(55,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(30,8,'FECHA PAGO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(55,8,'PERIODO PAGADOS',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE PAGADO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,30,25,55,30,20,55,30,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalPagado   = 0;

    for($i=0;$i<sizeof($reg);$i++){
    $meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['totalcuotas'];
    $TotalPagado   += $reg[$i]['totalpagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["nrocaja"].": ".$reg[$i]["nomcaja"]),
        utf8_decode($reg[$i]["numerorecibo"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo']))),
        utf8_decode($reg[$i]['totalcuotas']),
        utf8_decode($meses_pagados),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','))));
    }
   
    $this->Cell(295,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS POR FECHAS ##############################

########################## FUNCION LISTAR PAGOS POR CLIENTES ##############################
function TablaListarPagosxClientes()
{
    $tra = new Login();
    $reg = $tra->BuscarPagosxClientes();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS DE PRÉSTAMOS POR CLIENTES',0,0,'C');


    $this->Ln();
    $this->Cell(330,5,"Nº ".utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'])).": ".utf8_decode($reg[0]["cedcliente"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"NOMBRE DE CLIENTE: ".portales(utf8_decode($reg[0]["nomcliente"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"N° DE TELEFONO: ".utf8_decode($telefono = ($reg[0]['tlfcliente'] == '' ? "**********" : $reg[0]["tlfcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"N° DE CELULAR: ".utf8_decode($celular = ($reg[0]['celcliente'] == '' ? "**********" : $reg[0]["celcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"CORREO ELECTRONICO: ".portales(utf8_decode($correo = ($reg[0]['email'] == '' ? "**********" : $reg[0]["email"]))),0,0,'L'); 

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(40,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(25,8,'Nº DE PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA PAGO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(80,8,'PERIODO PAGADOS',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE PAGADO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,40,25,45,20,80,30,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalPagado   = 0;

    for($i=0;$i<sizeof($reg);$i++){
    $meses_pagados = str_replace("<br>"," | ", $reg[$i]['meses_pagados']);

    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['totalcuotas'];
    $TotalPagado   += $reg[$i]['totalpagado'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode($reg[$i]["nrocaja"].": ".$reg[$i]["nomcaja"]),
        utf8_decode($reg[$i]["numerorecibo"]),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fecharecibo']))),
        utf8_decode($reg[$i]['totalcuotas']),
        utf8_decode($meses_pagados),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpagado'], 2, '.', ','))));
    }
   
    $this->Cell(290,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS POR CLIENTES ##############################

########################## FUNCION LISTAR PAGOS EN MORA POR FECHAS ##############################
function TablaListarMoraxFechas()
{
    $tra = new Login();
    $reg = $tra->BuscarMoraxFechas();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS EN MORA POR FECHAS',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"DESDE: ".date("d-m-Y", strtotime($_GET["desde"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"HASTA: ".date("d-m-Y", strtotime($_GET["hasta"])),0,0,'L');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(65,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(45,8,'FECHA PRÉSTAMO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(85,8,'PERIODO EN MORA',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE EN MORA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,65,45,20,85,30,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalSubtotal = 0;
    $TotalCuotas   = 0;
    $TotalMora     = 0;

    for($i=0;$i<sizeof($reg);$i++){
    $meses_mora = str_replace("<br>"," | ", $reg[$i]['meses_mora']);

    $TotalSubtotal += $reg[$i]['montoxcuota'];
    $TotalCuotas   += $reg[$i]['cuotas_mora'];
    $TotalMora   += $reg[$i]['suma_mora'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        portales(utf8_decode($reg[$i]['nomcliente'])),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($reg[$i]['cuotas_mora']),
        utf8_decode($meses_mora),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','))));
    }
   
    $this->Cell(295,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalMora, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS EN MORA POR FECHAS ##############################

########################## FUNCION LISTAR PAGOS EN MORA POR CLIENTES ##############################
function TablaListarMoraxClientes()
{
    $tra = new Login();
    $reg = $tra->BuscarMoraxClientes();

    ################################# MEMBRETE LEGAL #################################
    $logo = ( file_exists("./fotos/logo_principal.png") == "" ? "./assets/img/null.png" : "./fotos/logo_principal.png");
    $logo2 = ( file_exists("./fotos/logo_pdf.png") == "" ? "./assets/img/null.png" : "./fotos/logo_pdf.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = ($con[0]['simbolo'] == "" ? "" : $con[0]['simbolo']); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL
    $this->Cell(90,5,$this->Image($logo, $this->GetX()+10, $this->GetY()+6, $GLOBALS['logo1_horizontal']),0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['nomsucursal'])),0,0,'C');
    $this->Cell(90,5,$this->Image($logo2, $this->GetX()+5, $this->GetY()+6, $GLOBALS['logo2_horizontal']),0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuitsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    

    if($con[0]['coddepartamento']!='0'){

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($departamento = ($con[0]['coddepartamento'] == '0' ? "" : $con[0]['departamento'])." ".$provincia = ($con[0]['codprovincia'] == '0' ? "" : $con[0]['provincia']))),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,portales(utf8_decode($con[0]['direcsucursal'])),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,"Nº TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(90,5,"",0,0,'C');
    $this->Cell(150,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(90,5,"",0,0,'C');
    $this->Ln(5);
    ################################# MEMBRETE LEGAL #################################
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE PAGOS EN MORA POR CLIENTES',0,0,'C');

    $this->Ln();
    $this->Cell(330,5,"Nº ".utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'])).": ".utf8_decode($reg[0]["cedcliente"]),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"NOMBRE DE CLIENTE: ".portales(utf8_decode($reg[0]["nomcliente"])),0,0,'L'); 
    $this->Ln();
    $this->Cell(330,5,"N° DE TELEFONO: ".utf8_decode($telefono = ($reg[0]['tlfcliente'] == '' ? "**********" : $reg[0]["tlfcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"N° DE CELULAR: ".utf8_decode($celular = ($reg[0]['celcliente'] == '' ? "**********" : $reg[0]["celcliente"])),0,0,'L');
    $this->Ln();
    $this->Cell(330,5,"CORREO ELECTRONICO: ".portales(utf8_decode($correo = ($reg[0]['email'] == '' ? "**********" : $reg[0]["email"]))),0,0,'L'); 

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(25, 124, 168); // establece el color del fondo de la celda (en este caso es AZUL)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE PRÉSTAMO',1,0,'C', True);
    $this->Cell(35,8,'FECHA PRÉSTAMO',1,0,'C', True);
    $this->Cell(20,8,'CUOTAS',1,0,'C', True);
    $this->Cell(55,8,'PERIODO EN MORA',1,0,'C', True);
    $this->Cell(30,8,'MONTO X CUOTA',1,0,'C', True);
    $this->Cell(35,8,'TOTAL PRÉSTAMO',1,0,'C', True);
    $this->Cell(35,8,'PAGADO',1,0,'C', True);
    $this->Cell(35,8,'PENDIENTE',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE EN MORA',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,35,20,55,30,35,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
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

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codfactura"]),
        utf8_decode(date("d/m/Y H:i:s",strtotime($reg[$i]['fechaprestamo']))),
        utf8_decode($reg[$i]['cuotas_mora']),
        utf8_decode($meses_mora),
        utf8_decode($simbolo.number_format($reg[$i]['montoxcuota'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['creditopagado'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['suma_mora'], 2, '.', ','))));
    }
   
    $this->Cell(190,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPagado, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalPendiente, 2, '.', ',')),0,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalMora, 2, '.', ',')),0,0,'L');
    $this->Ln();
    }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y H:i:s'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR PAGOS EN MORA POR CLIENTES ##############################

############################### REPORTES DE CREDITOS ###############################


// FIN Class PDF
}
?>