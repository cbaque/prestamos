<?php
require_once('class/class.php');
$accesos = ['administrador', 'cajero', 'vendedor', 'cliente'];
validarAccesos($accesos) or die();
require_once 'fpdf/pdf.php';

$casos = [
  'DOCUMENTOS'             => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarDocumentos',
    'output'  => ['Listado de Tipos de Documentos.pdf', 'I'],
  ],
  'DEPARTAMENTOS'               => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarDepartamentos',
    'output'  => ['Listado de Departamentos.pdf', 'I'],
  ],
  'PROVINCIAS'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarProvincias',
    'output'  => ['Listado de Provincias.pdf', 'I'],
  ],
  'TIPOMONEDA'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarTiposMonedas',
    'output'  => ['Listado de Tipos de Moneda.pdf', 'I'],
  ],
  'FORMASPAGOS'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarFormasPagos',
    'output'  => ['Listado de Formas Pagos.pdf', 'I'],
  ],
  'USUARIOS'               => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarUsuarios',
    'output'  => ['Listado de Usuarios.pdf', 'I'],
  ],
  'LOGS'                   => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarLogs',
    'output'  => ['Listado Logs de Acceso.pdf', 'I'],
  ],
  'CLIENTES'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarClientes',
    'output'  => ['Listado de Clientes.pdf', 'I'],
  ],
  'CAJAS'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarCajas',
    'output'  => ['Listado de Cajas.pdf', 'I'],
  ],
  'TICKETCIERRE'                => [
    'medidas' => ['P','mm','cierre'],
    'func'    => 'TicketCierre',
    'setPrintFooter' => 'true',
    'output'  => ['Ticket de Cierre de Caja.pdf', 'I'],
  ],
  'APERTURAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarAperturas',
    'output'  => ['Listado de Aperturas de Cajas.pdf', 'I'],
  ],
  'APERTURASXFECHAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarAperturasxFechas',
    'output'  => ['Listado de Aperturas por Fechas.pdf', 'I'],
  ],
  'TICKETMOVIMIENTO'                => [
    'medidas' => ['P','mm','movimiento'],
    'func'    => 'TicketMovimiento',
    'setPrintFooter' => 'true',
    'output'  => ['Ticket de Movimiento de Caja.pdf', 'I'],
  ],
  'MOVIMIENTOS'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarMovimientos',
    'output'  => ['Listado de Movimientos en Caja.pdf', 'I'],
  ],
  'MOVIMIENTOSXFECHAS'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaListarMovimientosxFechas',
    'output'  => ['Listado de Movimientos por Fechas.pdf', 'I'],
  ],
  'FACTURA'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaFacturaPrestamo',
    'output'  => ['Factura de Prestamo.pdf', 'I'],
  ],
  'CONTRATO'                => [
    'medidas' => ['P', 'mm', 'A4'],
    'func'    => 'TablaContratoPrestamo',
    'output'  => ['Contrato de Prestamo.pdf', 'I'],
  ],
  'PRESTAMOS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamos',
    'output'  => ['Listado de Prestamos.pdf', 'I'],
  ],
  'PRESTAMOSPENDIENTES'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamosPendientes',
    'output'  => ['Listado de Prestamos Pendientes.pdf', 'I'],
  ],
  'PRESTAMOSXCAJAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamosxCajas',
    'output'  => ['Listado de Prestamos por Cajas.pdf', 'I'],
  ],
  'PRESTAMOSXFECHAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamosxFechas',
    'output'  => ['Listado de Prestamos por Fechas.pdf', 'I'],
  ],
  'PRESTAMOSXCLIENTES'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamosxClientes',
    'output'  => ['Listado de Prestamos por Clientes.pdf', 'I'],
  ],
  'PRESTAMOSXUSUARIOS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPrestamosxUsuarios',
    'output'  => ['Listado de Prestamos por Usuarios.pdf', 'I'],
  ],
  'COMPROBANTE'                => [
    //'medidas' => ['P', 'mm', 'A4'],
    'medidas' => ['P','mm','ticket'],
    'func'    => 'TablaComprobantePago',
    'output'  => ['Comprobante de Pago.pdf', 'I'],
  ],
  'PAGOS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPagos',
    'output'  => ['Listado de Pagos.pdf', 'I'],
  ],
  'PAGOSVENCIDOS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarCuotasVencidas',
    'output'  => ['Listado de Pagos de Cuotas Vencidas.pdf', 'I'],
  ],
  'PAGOSDELDIA'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPagosxDia',
    'output'  => ['Listado de Pagos del Dia '.date("d/m/Y").'.pdf', 'I'],
  ],
  'PAGOSXCAJAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPagosxCajas',
    'output'  => ['Listado de Pagos por Cajas.pdf', 'I'],
  ],
  'PAGOSXFECHAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPagosxFechas',
    'output'  => ['Listado de Pagos por Fechas.pdf', 'I'],
  ],
  'PAGOSXCLIENTES'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarPagosxClientes',
    'output'  => ['Listado de Pagos por Clientes.pdf', 'I'],
  ],
  'MORAXFECHAS'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarMoraxFechas',
    'output'  => ['Listado de Pagos en Mora por Fechas.pdf', 'I'],
  ],
  'MORAXCLIENTES'                => [
    'medidas' => ['L', 'mm', 'LEGAL'],
    'func'    => 'TablaListarMoraxClientes',
    'output'  => ['Listado de Pagos en Mora por Clientes.pdf', 'I'],
  ],
];

ob_start();
$tipo      = decrypt($_GET['tipo']);
$caso_data = $casos[$tipo];
$pdf       = new PDF(
  $caso_data['medidas'][0],
  $caso_data['medidas'][1],
  $caso_data['medidas'][2]
);
if (in_array($tipo, ['', '', '', ''])) {
  $pdf->AutoPrint();
} 
$pdf->AddPage();
$pdf->{$caso_data['func']}();
$pdf->Output($caso_data['output'][0], $caso_data['output'][1]);
ob_end_flush();