//SELECCIONAR/DESELECCIONAR TODOS LOS CHECKBOX
function Separador1(val) {//SEPARADOR SIN DECIMAL
    return String(val).split("").reverse().join("")
    .replace(/(.{3}\B)/g, "$1.")
    .split("").reverse().join("");
}

function Separador(x) {//SEPARADOR CON DECIMAL
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$("#checkTodos").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
    //$("input[type='checkbox']:checked:enabled").prop('checked', $(this).prop("checked"));
});

// FUNCION PARA LIMPIAR CHECKBOX ACTIVOS
function LimpiarCheckbox(){
$("input[type='checkbox']:checked:enabled").attr('checked',false); 
}

function LimpiarRadio(){
$("input[type='radio']:checked:enabled").attr('checked',false); 
}

//BUSQUEDA EN CONSULTAS
$(document).ready(function () {
   (function($) {
    $('#FiltrarContenido').keyup(function () {
        var ValorBusqueda = new RegExp($(this).val(), 'i');
        $('.BusquedaRapida tr').hide();
        $('.BusquedaRapida tr').filter(function () {
        return ValorBusqueda.test($(this).text());
        }).show();
        })
    }(jQuery));
});






/////////////////////////////////// FUNCIONES DE USUARIOS //////////////////////////////////////

// FUNCION PARA MOSTRAR USUARIOS EN VENTANA MODAL
function VerUsuario(codigo){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaUsuarioModal=si&codigo='+codigo;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");   
        }
    });
}

// FUNCION PARA ACTUALIZAR USUARIOS
function UpdateUsuario(codigo,documusuario,dni,nombres,sexo,telefono,celular,
codprovincia,direccion,email,fnacimiento,usuario,nivel,status,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
  $("#saveusuario #codigo").val(codigo);
  $("#saveusuario #documusuario").val(documusuario);
  $("#saveusuario #dni").val(dni);
  $("#saveusuario #nombres").val(nombres);
  $("#saveusuario #sexo").val(sexo);
  $("#saveusuario #telefono").val(telefono);
  $("#saveusuario #celular").val(celular);
  $("#saveusuario #codprovincia").val(codprovincia);
  $("#saveusuario #direccion").val(direccion);
  $("#saveusuario #email").val(email);
  $("#saveusuario #fnacimiento").val(fnacimiento);
  $("#saveusuario #usuario").val(usuario);
  $("#saveusuario #nivel").val(nivel);
  $("#saveusuario #status").val((status == 1) ? $("#saveusuario #estado1").prop('checked', true) : $("#saveusuario #estado2").prop('checked', true));
  $("#saveusuario #proceso").val(proceso);
}

/////FUNCION PARA ACTUALIZAR STATUS DE USUARIOS 
function StatusUsuario(codigo,status,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Cambiar el Status de este Usuario?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Continuar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codigo="+codigo+"&status="+status+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Procesado!", "Datos Procesados con éxito!", "success");
            $("#usuarios").load("consultas.php?CargaUsuarios=si");
            $("#saveuser")[0].reset();

          } else { 

             swal("Oops", "Usted no tiene Acceso para Cambiar Status de Usuarios, no tienes los privilegios dentro del Sistema!", "error"); 

                }

            }
        })
    });
}

/////FUNCION PARA ELIMINAR USUARIOS 
function EliminarUsuario(codigo,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar este Usuario?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codigo="+codigo+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $("#usuarios").load("consultas.php?CargaUsuarios=si");
            $("#saveuser")[0].reset();
                  
          } else if(data==2){ 

             swal("Oops", "Este Usuario no puede ser Eliminado, tiene registros relacionados!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Usuarios, no tienes los privilegios dentro del Sistema!", "error"); 

                }

            }
        })
    });
}

// FUNCION PARA BUSCAR LOGS DE ACCESO
$(document).ready(function(){
//function BuscarPacientes() {  
    var consulta;
    //hacemos focus al campo de búsqueda
    $("#blogs").focus();
    //comprobamos si se pulsa una tecla
    $("#blogs").keyup(function(e){
      //obtenemos el texto introducido en el campo de búsqueda
      consulta = $("#blogs").val();

      if (consulta.trim() === '') {  

      $("#logs").html("<center><div class='alert alert-danger'><span class='fa fa-info-circle'></span> POR FAVOR REALICE LA BUSQUEDA CORRECTAMENTE</div></center>");
      return false;

      } else {
                                                                           
        //hace la búsqueda
        $.ajax({
          type: "POST",
          url: "search.php?CargaLogs=si",
          data: "b="+consulta,
          dataType: "html",
          beforeSend: function(){
              //imagen de carga
              $("#logs").html('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>');
          },
          error: function(){
              swal("Oops", "Ha ocurrido un error en la petición Ajax, verifique por favor!", "error"); 
          },
          success: function(data){                                                    
            $("#logs").empty();
            $("#logs").append(data);
          }
      });
     }
   });                                                               
});













/////////////////////////////////// FUNCIONES DE TIPOS DE DOCUMENTOS  //////////////////////////////////////

// FUNCION PARA ACTUALIZAR TIPOS DE DOCUMENTOS
function UpdateDocumento(coddocumento,documento,descripcion,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
  $("#savedocumento #coddocumento").val(coddocumento);
  $("#savedocumento #documento").val(documento);
  $("#savedocumento #descripcion").val(descripcion);
  $("#savedocumento #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR TIPOS DE DOCUMENTOS 
function EliminarDocumento(coddocumento,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar esta Tipo de Documento?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "coddocumento="+coddocumento+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#documentos').load("consultas?CargaDocumentos=si");
            $("#savedocumento")[0].reset();
                  
          } else if(data==2){ 

             swal("Oops", "Este Documento no puede ser Eliminado, tiene registros relacionados!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Documentos, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}








/////////////////////////////////// FUNCIONES DE PROVINCIAS //////////////////////////////////////

// FUNCION PARA ACTUALIZAR PROVINCIAS
function UpdateProvincia(codprovincia,provincia,proceso) 
{
  // aqui asigno cada valor a los campos correspondientes
  $("#saveprovincia #codprovincia").val(codprovincia);
  $("#saveprovincia #provincia").val(provincia);
  $("#saveprovincia #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR PROVINCIAS 
function EliminarProvincia(codprovincia,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar esta Provincia?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codprovincia="+codprovincia+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Los Datos han sido eliminados exitosamente!", "success");
            $('#provincias').load("consultas?CargaProvincias=si");
            $("#saveprovincia")[0].reset();
                  
          } else if(data==2){ 

             swal("Oops", "Esta Provincia no puede ser Eliminada, tiene registros relacionadas!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Provincias, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}









/////////////////////////////////// FUNCIONES DE DEPARTAMENTOS //////////////////////////////////////

// FUNCION PARA ACTUALIZAR DEPARTAMENTOS
function UpdateDepartamento(coddepartamento,departamento,codprovincia,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
  $("#savedepartamento #coddepartamento").val(coddepartamento);
  $("#savedepartamento #departamento").val(departamento);
  $("#savedepartamento #codprovincia").val(codprovincia);
  $("#savedepartamento #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR DEPARTAMENTOS 
function EliminarDepartamento(coddepartamento,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar este Departamento de Provincia?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "coddepartamento="+coddepartamento+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Los Datos han sido eliminados exitosamente!", "success");
            $('#departamentos').load("consultas?CargaDepartamentos=si");
            $("#savedepartamento")[0].reset();
                  
          } else if(data==2){ 

             swal("Oops", "Este Departamento no puede ser Eliminado, tiene registros relacionados!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Departamentos, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}

////FUNCION PARA MOSTRAR DEPARTAMENTOS POR PROVINCIA
function CargaDepartamentos(codprovincia){

$('#coddepartamento').html('<center><img src="assets/images/loading.gif" width="30" height="30"/></center>');
                
var dataString = 'BuscaDepartamentos=si&codprovincia='+codprovincia;

$.ajax({
        type: "GET",
        url: "funciones.php",
        data: dataString,
        success: function(response) {            
        $('#coddepartamento').empty();
        $('#coddepartamento').append(''+response+'').fadeIn("slow");
       }
  });
}

////FUNCION PARA MOSTRAR DEPARTAMENTOS POR PROVINCIA
function SelectDepartamento(codprovincia,coddepartamento){

  $("#coddepartamento").load("funciones.php?SeleccionaDepartamentos=si&codprovincia="+codprovincia+"&coddepartamento="+coddepartamento);

}








/////////////////////////////////// FUNCIONES DE TIPOS DE MONEDA //////////////////////////////////////

// FUNCION PARA ACTUALIZAR TIPOS DE MONEDA
function UpdateTipoMoneda(codmoneda,moneda,siglas,simbolo,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
    $("#savemoneda #codmoneda").val(codmoneda);
    $("#savemoneda #moneda").val(moneda);
    $("#savemoneda #siglas").val(siglas);
    $("#savemoneda #simbolo").val(simbolo);
    $("#savemoneda #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR TIPOS DE MONEDA 
function EliminarTipoMoneda(codmoneda,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar esta Tipo de Moneda?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#2f323e',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codmoneda="+codmoneda+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#monedas').load("consultas?CargaMonedas=si");
                  
          } else if(data==2){ 

             swal("Oops", "Este Tipo de Moneda no puede ser Eliminado, tiene registros relacionadas!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Tipos de Moneda, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}








/////////////////////////////////// FUNCIONES DE FORMAS DE PAGOS //////////////////////////////////////

// FUNCION PARA ACTUALIZAR FORMAS DE PAGOS
function UpdateFormaPago(codformapago,formapago,proceso) 
{
  // aqui asigno cada valor a los campos correspondientes
  $("#saveformapago #codformapago").val(codformapago);
  $("#saveformapago #formapago").val(formapago);
  $("#saveformapago #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR FORMAS DE PAGOS 
function EliminarFormaPago(codformapago,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar esta Forma de Pago?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codformapago="+codformapago+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Los Datos han sido eliminados exitosamente!", "success");
            $('#formaspagos').load("consultas?CargaFormasPagos=si");
            $("#saveformapago")[0].reset();
                  
          } else if(data==2){ 

             swal("Oops", "Esta Forma de Pago no puede ser Eliminada, tiene registros relacionadas!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Formas de Pagos, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}










/////////////////////////////////// FUNCIONES DE CLIENTES //////////////////////////////////////

// FUNCION PARA MOSTRAR DIV DE CARGA MASIVA DE CLIENTES
function CargaDivCliente(){

$('#divcliente').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');
                
var dataString = 'BuscaDivCliente=si';

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#divcliente').empty();
            $('#divcliente').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA LIMPIAR DIV DE CARGA MASIVA DE CLIENTES
function ModalCliente(){
  $("#divcliente").html("");
}

// FUNCION PARA MOSTRAR CLIENTES EN VENTANA MODAL
function VerCliente(codcliente){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaClienteModal=si&codcliente='+codcliente;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA ACTUALIZAR CLIENTES
function UpdateCliente(codcliente,documcliente,cedcliente,nomcliente,sexocliente,tlfcliente,celcliente,
codprovincia,direccliente,correocliente,cedreferencia,nomreferencia,celreferencia,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
    $("#savecliente #codcliente").val(codcliente);
    $("#savecliente #documcliente").val(documcliente);
    $("#savecliente #cedcliente").val(cedcliente);
    $("#savecliente #nomcliente").val(nomcliente);
    $("#savecliente #sexocliente").val(sexocliente);
    $("#savecliente #tlfcliente").val(tlfcliente);
    $("#savecliente #celcliente").val(celcliente);
    $("#savecliente #codprovincia").val(codprovincia);
    $("#savecliente #direccliente").val(direccliente);
    $("#savecliente #correocliente").val(correocliente);
    $("#savecliente #cedreferencia").val(cedreferencia);
    $("#savecliente #nomreferencia").val(nomreferencia);
    $("#savecliente #celreferencia").val(celreferencia);
    $("#savecliente #proceso").val(proceso);
}

/////FUNCION PARA ACTUALIZAR STATUS DE CLIENTES 
function StatusCliente(codcliente,statuscliente,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Cambiar el Status de este Cliente?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Continuar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codcliente="+codcliente+"&statuscliente="+statuscliente+"&tipo="+tipo,
                  success: function(data){

            if(data==1){

            swal("Procesado!", "Datos Procesados con éxito!", "success");
            $('#clientes').load("consultas?CargaClientes=si");
            $("#savecliente")[0].reset();

            } else { 

            swal("Oops", "Usted no tiene Acceso para Cambiar Status de Clientes, no tienes los privilegios dentro del Sistema!", "error"); 

                }

            }
        })
    });
}

/////FUNCION PARA ELIMINAR CLIENTES 
function EliminarCliente(codcliente,tipo) {
        swal({
        title: "¿Estás seguro?", 
        text: "¿Estás seguro de Eliminar este Cliente?", 
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        cancelButtonColor: '#2f323e',
        closeOnConfirm: false,
        confirmButtonText: "Eliminar",
        confirmButtonColor: "#3085d6"
        }, function() {
            $.ajax({
                type: "GET",
                url: "eliminar.php",
                data: "codcliente="+codcliente+"&tipo="+tipo,
                success: function(data){

            if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#clientes').load("consultas?CargaClientes=si");
            $("#savecliente")[0].reset();
                  
            } else if(data==2){ 

             swal("Oops", "Este Cliente no puede ser Eliminado, tiene registros relacionados!", "error"); 

            } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Clientes, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}


// FUNCION PARA MOSTRAR CLIENTES POR SUCURSAL
function CargaClientes(codsucursal){

$('#codcliente').html('<center><i class="fa fa-spin fa-spinner"></i></center>');
                
var dataString = 'BuscaClientesxSucursal=si&codsucursal='+codsucursal;

$.ajax({
        type: "GET",
        url: "funciones.php",
        data: dataString,
        success: function(response) {            
            $('#codcliente').empty();
            $('#codcliente').append(response).fadeIn("slow"); 
        }
   });
}

















/////////////////////////////////// FUNCIONES DE CAJAS DE VENTAS //////////////////////////////////////

// FUNCION PARA VERIFICAR CAJA APERTURADA
function VerificaCaja(){

$('#muestra_caja').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaDetalleCajaModal=si';

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestra_caja').empty();
            $('#muestra_caja').append(response).fadeIn("slow");     
        }
    });
}

// FUNCION PARA MOSTRAR CAJAS DE VENTAS EN VENTANA MODAL
function VerCaja(codcaja){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaCajaModal=si&codcaja='+codcaja;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");  
        }
    });
}

// FUNCION PARA ACTUALIZAR CAJAS DE VENTAS
function UpdateCaja(codcaja,nrocaja,nomcaja,codigo,proceso) 
{
  // aqui asigno cada valor a los campos correspondientes
  $("#savecaja #codcaja").val(codcaja);
  $("#savecaja #nrocaja").val(nrocaja);
  $("#savecaja #nomcaja").val(nomcaja);
  $("#savecaja #codigo").val(codigo);
  $("#savecaja #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR CAJAS DE VENTAS 
function EliminarCaja(codcaja,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar esta CAJA?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codcaja="+codcaja+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#cajas').load("consultas?CargaCajas=si");
                  
          } else if(data==2){ 

             swal("Oops", "Esta Caja para Venta no puede ser Eliminada, tiene Ventas relacionados!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Cajas, no eres el Administrador!", "error"); 

                }
            }
        })
    });
}














/////////////////////////////////// FUNCIONES DE ARQUEOS DE CAJAS //////////////////////////////////////

// FUNCION PARA MOSTRAR ARQUEO EN VENTANA MODAL
function VerApertura(codapertura){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaAperturaModal=si&numero='+codapertura;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");
        }
    });
}


// FUNCION PARA CERRAR ARQUEO
function CerrarApertura(codapertura,nrocaja,responsable,montoinicial,fechaapertura
  ,pagos,prestamos,ingresos,egresos,efectivocaja) 
{
    // aqui asigno cada valor a los campos correspondientes
  $("#cerrarapertura #codapertura").val(codapertura);
  $("#cerrarapertura #txtcaja").text(nrocaja);
  $("#cerrarapertura #txtnombre").text(responsable);
  $("#cerrarapertura #txtmonto").text(Separador(montoinicial));
  $("#cerrarapertura #txtapertura").text(fechaapertura);
  $("#cerrarapertura #pagos").text(Separador(pagos));
  $("#cerrarapertura #prestamos").text(Separador(prestamos));
  $("#cerrarapertura #ingresos").text(Separador(ingresos));
  $("#cerrarapertura #egresos").text(Separador(egresos));
  $("#cerrarapertura #efectivocaja").text(Separador(efectivocaja));
  $("#cerrarapertura #efectivocaja").val(efectivocaja);
  $('#cerrarapertura #muestra_detalles_apertura').load("funciones.php?MuestraDetallesApertura=si&numero="+codapertura);
}

//FUNCION PARA CALCULAR LA DIFERENCIA EN CIERRE DE CAJA
$(document).ready(function (){
   $('.cierrecaja').keyup(function (){
      
    var efectivo = $('input#dineroefectivo').val();
    var estimado = $('input#efectivocaja').val();
            
    //REALIZO EL CALCULO DE DIFERENCIA EN CAJA
    total = efectivo - estimado;
    var original = parseFloat(total.toFixed(2));
    $("#diferencia").val((efectivo == "" || efectivo == "0" || efectivo == "0.00") ? "0.00" : original.toFixed(2));
      
   });
});


//FUNCION PARA BUSQUEDA DE ARQUEOS POR FECHAS PARA REPORTES
function BuscarAperturasxFechas(){
                  
$('#muestraaperturasxfechas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var codcaja = $("#codcaja").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#aperturasxfechas").serialize();
var url = 'funciones.php?BuscaAperturasxFechas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
    success: function(response) {            
            $('#muestraaperturasxfechas').empty();
            $('#muestraaperturasxfechas').append(response).fadeIn("slow");
        }
    }); 
}

















/////////////////////////////////// FUNCIONES DE MOVIMIENTOS EN CAJAS DE COBROS //////////////////////////////////////


// FUNCION PARA MOSTRAR MOVIMIENTO EN CAJAS EN VENTANA MODAL
function VerMovimiento(codmovimiento){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaMovimientoModal=si&numero='+codmovimiento;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA ACTUALIZAR MOVIMIENTOS EN CAJAS
function UpdateMovimiento(codmovimiento,numero,codapertura,codcaja,tipomovimiento,descripcionmovimiento,montomovimiento,mediomovimiento,fechamovimiento,proceso) 
{
    // aqui asigno cada valor a los campos correspondientes
  $("#savemovimiento #codmovimiento").val(codmovimiento);
  $("#savemovimiento #numero").val(numero);
  $("#savemovimiento #codapertura").val(codapertura);
  $("#savemovimiento #codcaja").val(codcaja);
  $("#savemovimiento #tipomovimiento").val(tipomovimiento);
  $("#savemovimiento #tipomovimientobd").val(tipomovimiento);
  $("#savemovimiento #descripcionmovimiento").val(descripcionmovimiento);
  $("#savemovimiento #montomovimiento").val(montomovimiento);
  $("#savemovimiento #montomovimientobd").val(montomovimiento);
  $("#savemovimiento #mediomovimiento").val(mediomovimiento);
  $("#savemovimiento #mediomovimientobd").val(mediomovimiento);
  $("#savemovimiento #fecharegistro").val(fechamovimiento);
  $("#savemovimiento #proceso").val(proceso);
}

/////FUNCION PARA ELIMINAR MOVIMIENTOS EN CAJAS 
function EliminarMovimiento(codmovimiento,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar este Movimiento en Caja?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codmovimiento="+codmovimiento+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#movimientos').load("consultas?CargaMovimientos=si");
                  
          } else if(data==2){ 

             swal("Oops", "Este Movimiento en Caja no puede ser Eliminado, el Arqueo de Caja asociado se encuentra Cerrado!", "error"); 

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Movimiento en Cajas, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}

//FUNCION PARA BUSQUEDA DE MOVIMIENTOS DE CAJAS POR FECHAS PARA REPORTES
function BuscarMovimientosxFechas(){
                  
$('#muestramovimientosxfechas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var codcaja = $("#codcaja").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#movimientosxfechas").serialize();
var url = 'funciones.php?BuscaMovimientosxFechas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestramovimientosxfechas').empty();
            $('#muestramovimientosxfechas').append(response).fadeIn("slow"); 
        }
    }); 
}










/////////////////////////////////// FUNCIONES DE PRESTAMOS //////////////////////////////////////

// FUNCION PARA BUSCAR PRESTAMOS POR CRITERIO
function BuscarPrestamos(){
                        
$('#muestradetalles').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');
                
var search = $("#search_prestamos").val();
var dataString = $("#busquedaprestamos").serialize();
var url = 'consultas.php?CargaPrestamosProcesados=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestradetalles').empty();
            $('#muestradetalles').append(response).fadeIn("slow");
        }
    });
}

//FUNCION PARA LIMPIAR CAMPOS EN PRESTAMOS
function ResetPrestamo() 
{
  $("#saveprestamo")[0].reset();
  $("#codcliente").val("");
  $("#nrodocumento").val("");
  $("#search_cliente").val("");
  $("#montoprestamo").val("");
  $("#intereses").val("0.00");
  $("#cuotas").val("");
  $("#periodopago").val("");
  $("#fechaprestamo").val("");
  $("#montoxcuota").val("0.00");
  $("#montoxcuota2").val("0.00");
  $("#totalintereses").val("0.00");
  $("#totalpago").val("0.00");
  $("#totalpago2").val("0.00");
  $("#muestra_detalles").html("");
}

// FUNCION PARA CALCULAR TOTAL DE INTERESES
function CalculoPrestamo(){
      
  var MontoPrestamo = $('input#montoprestamo').val();
  var Cuotas = $('input#cuotas').val();
  var TxtIntereses   = $('input#intereses').val();
  var Divide      = TxtIntereses/100;

  var TxtIntereses = parseFloat(MontoPrestamo) * parseFloat(Divide);
  var TxtPago = parseFloat(MontoPrestamo) + parseFloat(TxtIntereses);
  var MontoxCuota = parseFloat(TxtPago) / parseFloat(Cuotas);

  $("#montoxcuota").val((Cuotas == "") ? "0.00" : MontoxCuota.toFixed(2));
  $("#montoxcuota2").val((Cuotas == "") ? "0.00" : Separador(MontoxCuota.toFixed(2)));

  $("#totalintereses").val(TxtIntereses.toFixed(2));
  $("#totalintereses2").val(Separador(TxtIntereses.toFixed(2)));

  $("#totalpago").val(TxtPago.toFixed(2));
  $("#totalpago2").val(Separador(TxtPago.toFixed(2)));
}


function MuestraDetallesPago(cuotas,totalpago,periodopago,fechapagocuota){

var dataString = 'MuestraDetallesPago=si&cuotas='+cuotas+"&totalpago="+totalpago+"&periodopago="+periodopago+"&fechapagocuota="+fechapagocuota;

$.ajax({
    type: "GET",
    url: "detalles_cuotas.php",
    data: dataString,
        success: function(response) {            
            $('#muestra_detalles').empty();
            $('#muestra_detalles').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA MOSTRAR VENTAS EN VENTANA MODAL
function VerPrestamo(codprestamo){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaPrestamoModal=si&numero='+codprestamo;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA ACTUALIZAR VENTAS
function UpdatePrestamo(codprestamo,proceso) {

  swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Actualizar este Préstamo?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Actualizar",
          confirmButtonColor: "#3085d6"
        }, function(isConfirm) {
    if (isConfirm) {
      location.href = "forprestamo?numero="+codprestamo+"&proceso="+proceso;
      // handle confirm
    } else {
      // handle all other cases
    }
  })
}

// FUNCION PARA CARGAR DATOS EN PRESTAMO PENDIENTE
function ProcesarPrestamoPendiente(codprestamo,codcliente,nomcliente,montoprestamo,intereses,totalintereses,totalpago,
    cuotas,montoxcuota,periodopago,fechaprestamo,statusprestamo) 
{
  // aqui asigno cada valor a los campos correspondientes
  $("#saveprocesarprestamo #codprestamo").val(codprestamo);
  $("#saveprocesarprestamo #codcliente").val(codcliente);
  $("#saveprocesarprestamo #search_cliente").val(nomcliente);
  $("#saveprocesarprestamo #montoprestamo").val(montoprestamo);
  $("#saveprocesarprestamo #intereses").val(intereses+'%');
  $("#saveprocesarprestamo #totalintereses").val(totalintereses);
  $("#saveprocesarprestamo #totalpago").val(totalpago);
  $("#saveprocesarprestamo #totalpago2").val(Separador(totalpago));
  $("#saveprocesarprestamo #cuotas").val(cuotas);
  $("#saveprocesarprestamo #montoxcuota").val(montoxcuota);
  $("#saveprocesarprestamo #periodopago").val(periodopago);
  $("#saveprocesarprestamo #fechaprestamo").val(fechaprestamo);
  $("#saveprocesarprestamo #statusprestamo").val(statusprestamo);
}

/////FUNCION PARA ELIMINAR VENTAS 
function PagarComision(codprestamo,codsucursal,modulo,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Procesar el Pago de Comisión al Vendedor de esta Venta?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Continuar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codventa="+codventa+"&codsucursal="+codsucursal+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            if(modulo==1){
                $('#ventas').load("consultas?CargaVentas=si");
            } else if(modulo==2){
                $('#ventas').load("consultas?CargaVentasPendientes=si");
            }
                  
          } else { 

             swal("Oops", "Usted no tiene Acceso para Procesar el Pago de Comisión Ventas, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}


/////FUNCION PARA ELIMINAR PRESTAMOS PENDIENTES 
function EliminarPrestamo(codprestamo,tipo) {
        swal({
          title: "¿Estás seguro?", 
          text: "¿Estás seguro de Eliminar este Préstamo?", 
          type: "warning",
          showCancelButton: true,
          cancelButtonText: "Cancelar",
          cancelButtonColor: '#d33',
          closeOnConfirm: false,
          confirmButtonText: "Eliminar",
          confirmButtonColor: "#3085d6"
        }, function() {
             $.ajax({
                  type: "GET",
                  url: "eliminar.php",
                  data: "codprestamo="+codprestamo+"&tipo="+tipo,
                  success: function(data){

          if(data==1){

            swal("Eliminado!", "Datos eliminados con éxito!", "success");
            $('#prestamos').load("consultas?CargaPrestamosPendientes=si");

          } else { 

             swal("Oops", "Usted no tiene Acceso para Eliminar Prestamos Pendientes, no tienes los privilegios dentro del Sistema!", "error"); 

                }
            }
        })
    });
}


//FUNCION PARA BUSQUEDA DE PRESTAMOS POR CAJAS Y FECHAS
function BuscarPrestamosxCajas(){
                  
$('#muestraprestamosxcajas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

//var tipo_pago = $('input:radio[name=tipo_pago]:checked').val();
var codcaja = $("#codcaja").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#prestamosxcajas").serialize();
var url = 'funciones.php?BuscaPrestamosxCajas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestraprestamosxcajas').empty();
            $('#muestraprestamosxcajas').append(response).fadeIn("slow");
        }
    }); 
}

// FUNCION PARA BUSQUEDA DE PRESTAMOS POR FECHAS
function BuscarPrestamosxFechas(){
                        
$('#muestraprestamosxfechas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var estado_prestamo = $("select#estado_prestamo").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#prestamosxfechas").serialize();
var url = 'funciones.php?BuscaPrestamosxFechas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestraprestamosxfechas').empty();
            $('#muestraprestamosxfechas').append(response).fadeIn("slow");
        }
    });
}

//FUNCION PARA BUSQUEDA DE PRESTAMOS POR CLIENTES
function BuscarPrestamosxClientes(){
                  
$('#muestraprestamosxclientes').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var estado_prestamo = $("select#estado_prestamo").val();
var codcliente = $("input#codcliente").val();
var dataString = $("#prestamosxclientes").serialize();
var url = 'funciones.php?BuscaPrestamosxClientes=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestraprestamosxclientes').empty();
            $('#muestraprestamosxclientes').append(response).fadeIn("slow");
        }
    }); 
}

//FUNCION PARA BUSQUEDA DE PRESTAMOS POR USUARIOS
function BuscarPrestamosxUsuarios(){
                  
$('#muestraprestamosxusuarios').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var estado_prestamo = $("select#estado_prestamo").val();
var codigo = $("select#codigo").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#prestamosxusuarios").serialize();
var url = 'funciones.php?BuscaPrestamosxUsuarios=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestraprestamosxusuarios').empty();
            $('#muestraprestamosxusuarios').append(response).fadeIn("slow");
        }
    }); 
}





















/////////////////////////////////// FUNCIONES DE PAGOS //////////////////////////////////////

// FUNCION PARA BUSCAR DETALLES PRESTAMOS
function BuscarDetallesPrestamos(codcliente){
                        
$('#muestra_detalles_prestamos').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');
$('#muestra_detalles_cuotas').html("");

var dataString = 'CargaDetallesPrestamos=si&codcliente='+codcliente;

$.ajax({type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestra_detalles_prestamos').empty();
            $('#muestra_detalles_prestamos').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA BUSCAR DETALLES CUOTAS
function BuscarDetallesCuotasPrestamos(codprestamo,montoxcuota){
                        
$('#muestra_detalles_cuotas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var dataString = 'CargaDetallesCuotasPrestamos=si&numero='+codprestamo+"&numero2="+montoxcuota;

$.ajax({type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestra_detalles_cuotas').empty();
            $('#muestra_detalles_cuotas').append(response).fadeIn("slow");
        }
    });
}

function CargaDetallesCuotasPagar(i){        

  var cuotas = new Array();
  var montoxcuota = $('input#montoxcuota').val();

  $("input[type=checkbox]:checked:enabled").each(function(){
    //cada elemento seleccionado            
    cuotas.push($(this).val());           
  });

  var cantidad = cuotas.length;
  var total = parseFloat(montoxcuota) * parseInt(cantidad);
  var Calcular=parseFloat(total.toFixed(2));

  $("#detalles_cuotas").val(cuotas);
  $("#txt_cuotas").text(cantidad);
  $("#txt_monto").text((cuotas == "0") ? "0.00" : montoxcuota);
  $("#txt_pagado").text((cantidad == "0") ? "0.00" : Calcular.toFixed(2));
  $("#total_cuotas").val(cantidad);
  $("#monto_cuota").val(montoxcuota);
  $("#total_pagado").val((cantidad == "0") ? "0.00" : Calcular.toFixed(2)); 
}


//FUNCION PARA LIMPIAR CAMPOS EN PAGOS
function ResetPagos() 
{
  $("input[type='checkbox']:checked:enabled").attr('checked',false);
  $("#metodopago").val("");
  $("#detalles_cuotas").val("");
  $("#txt_cuotas").text("0");
  $("#txt_monto").text("0.00");
  $("#txt_pagado").text("0.00");
  $("#total_cuotas").val("0");
  $("#monto_cuota").val("0.00");
  $("#total_pagado").val("0.00");
}

// FUNCION PARA BUSCAR DETALLES PAGOS POR CRITERIO
function BuscarPagos(){
                        
$('#muestradetalles').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');
                
var search = $("#search_pagos").val();
var dataString = $("#busquedapagos").serialize();
var url = 'consultas.php?CargaPagos=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestradetalles').empty();
            $('#muestradetalles').append(response).fadeIn("slow");
        }
    });
}

// FUNCION PARA MOSTRAR PAGOS EN VENTANA MODAL
function VerPago(codprestamo,codrecibo){

$('#muestradetallemodal').html('<center><i class="fa fa-spin fa-spinner"></i> Cargando información, por favor espere....</center>');

var dataString = 'BuscaPagosModal=si&numero='+codprestamo+"&numero2="+codrecibo;

$.ajax({
    type: "GET",
    url: "funciones.php",
    data: dataString,
        success: function(response) {            
            $('#muestradetallemodal').empty();
            $('#muestradetallemodal').append(response).fadeIn("slow");
        }
    });
}

//FUNCION PARA BUSQUEDA DE PAGOS POR CAJAS Y FECHAS
function BuscarPagosxCajas(){
                  
$('#muestrapagosxcajas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var codcaja = $("#codcaja").val();
var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#pagosxcajas").serialize();
var url = 'funciones.php?BuscaPagosxCajas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestrapagosxcajas').empty();
            $('#muestrapagosxcajas').append(response).fadeIn("slow");
        }
    }); 
}

// FUNCION PARA BUSQUEDA DE PAGOS POR FECHAS
function BuscarPagosxFechas(){
                        
$('#muestrapagosxfechas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#pagosxfechas").serialize();
var url = 'funciones.php?BuscaPagosxFechas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestrapagosxfechas').empty();
            $('#muestrapagosxfechas').append(response).fadeIn("slow");
        }
    });
}

//FUNCION PARA BUSQUEDA DE PAGOS POR CLIENTES
function BuscarPagosxClientes(){
                  
$('#muestrapagosxclientes').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var codcliente = $("input#codcliente").val();
var dataString = $("#pagosxclientes").serialize();
var url = 'funciones.php?BuscaPagosxClientes=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestrapagosxclientes').empty();
            $('#muestrapagosxclientes').append(response).fadeIn("slow"); 
        }
    }); 
}

//FUNCION PARA BUSQUEDA DE CLIENTES EN MORA
function BuscarMoraxClientes(){
                  
$('#muestramoraxclientes').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var codcliente = $("input#codcliente").val();
var dataString = $("#moraxclientes").serialize();
var url = 'funciones.php?BuscaClientesxMora=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestramoraxclientes').empty();
            $('#muestramoraxclientes').append(response).fadeIn("slow"); 
        }
    }); 
}

// FUNCION PARA BUSQUEDA DE PAGOS EN MORA POR FECHAS
function BuscarMoraxFechas(){
                        
$('#muestramoraxfechas').html('<center><div class="spinner-border spinner-border-reverse align-self-center text-dark"></div> Cargando información, por favor espere....</center>');

var desde = $("input#desde").val();
var hasta = $("input#hasta").val();
var dataString = $("#moraxfechas").serialize();
var url = 'funciones.php?BuscaMoraxFechas=si';

$.ajax({
    type: "GET",
    url: url,
    data: dataString,
        success: function(response) {            
            $('#muestramoraxfechas').empty();
            $('#muestramoraxfechas').append(response).fadeIn("slow");
        }
    });
}