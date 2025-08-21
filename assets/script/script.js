/*Author: Ing. Ruben D. Chirinos R. Tlf: +58 0414-7225970, email: elsaiya@gmail.com

/* FUNCION JQUERY PARA VALIDAR ACCESO DE USUARIOS*/
$('document').ready(function() {
						   
	$("#formlogin").validate({
          rules:
	     {
			usuario: { required: true, },
			password: { required: true, },
	     },
          messages:
	     {
		     usuario:{ required: "Ingrese su Usuario" },
			password:{ required: "Ingrese su Password" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#formlogin").serialize();
			
		$.ajax({
		type : 'POST',
		url  : 'index.php',
		async : false,
		data : data,
		beforeSend: function()
		{	
			$("#login").fadeOut();
			
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-login").attr('disabled', true);
	     },
		success :  function(response)
		          {						
				if(response==1){ 
							 
			$("#login").fadeIn(1000, function(){ 
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-login").attr('disabled', false);
			    
				    });
		   
                    } else if(response==2){
								 
			$("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL USUARIO INGRESADO NO EXISTE, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-login").attr('disabled', false);
			 
			        }); 
		   
				} else if(response==3){
								 
			$("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE USUARIO SE ENCUENTRA ACTUALMENTE INACTIVO, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-login").attr('disabled', false);
			 
			        });  
		   
				} else if(response==4){
								 
			$("#login").fadeIn(1000, function(){
		
	     var n = noty({
          text: "<span class='fa fa-warning'></span> EL PASSWORD INGRESADO ES ERRONEO, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	      $("#btn-login").attr('disabled', false);
			 
			        });  
		   
				} else if(response==5){
								 
			$("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> HA OCURRIDO UN ERROR EN EL PROCESAMIENTO DE LA INFORMACION, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-login").attr('disabled', false);
			 
			           }); 
		   
			    } else {
								  
			$("#login").fadeIn(1000, function(){
			
	     $("#btn-login").attr('disabled', false);
	     $.get('verifica_meses.php', {'Verifica_Meses_Vencidos': true});
	     location.href = response;
				 
				         });  
					}
			     }
		     });
			return false;
		}
	     /* login submit */
     }); 
});
/* FUNCION JQUERY PARA VALIDAR ACCESO DE USUARIOS*/


/* FUNCION JQUERY PARA VALIDAR ACCESO DE USUARIOS*/
$('document').ready(function()
{ 
	$("#lockscreen").validate({
          rules:
	     {
			usuario: { required: true, },
			password: { required: true, },
	     },
          messages:
	     {
		    usuario:{ required: "Ingrese su Usuario" },
			password:{ required: "Ingrese su Password" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#lockscreen").serialize();
			
		$.ajax({
		type : 'POST',
		url  : 'lockscreen.php',
		async : false,
		data : data,
		beforeSend: function()
		{	
			$("#login").fadeOut(1000);
			
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-login").attr('disabled', true);
	     },
		success : function(response)
		          {						
				if(response==1){ 
							 
			$("#login").fadeIn(1000, function(){ 
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-login").attr('disabled', false);
			    
				     });
                    } 
                    else if(response==2){
								 
			$("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> LOS DATOS INGRESADOS NO EXISTEN, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-login").attr('disabled', false);
			 
			            });
		   
				} else if(response==3){
								 
		     $("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE USUARIO SE ENCUENTRA ACTUALMENTE INACTIVO, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-login").attr('disabled', false);
			 
			        });  
		   
				} else if(response==4){
								 
			$("#login").fadeIn(1000, function(){
		
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL PASSWORD INGRESADO ES ERRONEO, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-login").attr('disabled', false);
			 
			         });  
		   
				} else {
								  
			$("#login").fadeIn(1000, function(){
			
          $("#btn-login").attr('disabled', false);
          location.href = response;
				 
				         });  
					}
			     }
		     });
			return false;
	     }
	     /* login submit */
     }); 
});
/* FUNCION JQUERY PARA VALIDAR ACCESO DE USUARIOS*/


/* FUNCION JQUERY PARA RECUPERAR CONTRASEÑA DE USUARIOS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#formrecover").validate({
          rules:
	     {
			email: { required: true,  email: true  },
			tipo: { required: true },
	     },
          messages:
 	     {
			email:{ required: "Ingrese su Correo Electronico", email: "Ingrese un Correo Electronico Valido" },
			tipo:{ required: "Seleccione Tipo Ingreso" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#formrecover").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'pass_recovery.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#recover").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-recuperar").attr('disabled', true);
		},
		success :  function(data)
			{						
			if(data==1){
							
				$("#recover").fadeIn(1000, function(){ 
	
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-recuperar").attr('disabled', false);
		    
			    });																			
			}
			else if(data==2) {
							
			$("#recover").fadeIn(1000, function(){ 
	
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL CORREO INGRESADO NO FUE ENCONTRADO ACTUALMENTE, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-recuperar").attr('disabled', false);
		    
			    });
			}
			else if(data==3) {
							
			$("#recover").fadeIn(1000, function(){ 
	
		var n = noty({
          text: "<span class='fa fa-warning'></span> LA NUEVA CLAVE DE ACCESO NO PUDO SER ENVIADA A SU CORREO, OCURRIO UN ERROR AL CONECTAR CON EL PROVEEDOR DE CORREO, INTENTE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
          $("#btn-recuperar").attr('disabled', false);
		    
			    });
			
			} else {
								
			$("#recover").fadeIn(1000, function(){
								
		$("#formrecover")[0].reset();
		var n = noty({
		text: '<center> &nbsp; '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $("#btn-recuperar").attr('disabled', false);
			                                
						});
					}
				}
			});
		     return false;
		}
	   /* form submit */
    }); 
});
/*  FIN DE FUNCION PARA RECUPERAR CONTRASEÑA DE USUARIOS */
 
 
/* FUNCION JQUERY PARA VALIDAR ACTUALIZACION DE CONTRASEÑA */	 
$('document').ready(function()
{ 						
     /* validation */
	$("#updatepassword").validate({
     rules:
	     {
			usuario: {required: true },
			password: {required: true, minlength: 8},  
               password2:   {required: true, minlength: 8, equalTo: "#password"}, 
	     },
          messages:
	     {
              usuario:{ required: "Ingrese Usuario de Acceso" },
              password:{ required: "Ingrese su Nuevo Password", minlength: "Ingrese 8 caracteres como minimo" },
		    password2:{ required: "Repita su Nuevo Password", minlength: "Ingrese 8 caracteres como minimo", equalTo: "Este Password no coincide" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#updatepassword").serialize();
		var id= $("#updatepassword").attr("data-id");
          var codigo = id;
		
		$.ajax({
		type : 'POST',
		url  : 'password.php?codigo='+codigo,
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-update").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){ 
	
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-update").attr('disabled', false);
		    
				    });									
																			
				}
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){ 
	
		var n = noty({
          text: "<span class='fa fa-warning'></span> NO PUEDE USAR LA CLAVE ACTUAL, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-update").attr('disabled', false);
		    
				     });
				
				} else {
								
			$("#save").fadeIn(1000, function(){
								
		$("#updatepassword")[0].reset();
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
	     $("#btn-update").attr('disabled', false);
		setTimeout(' window.location.href = "logout"; ',5000);
			 
						});									
					}
			     }
			});
			return false;
		}
	    /* form submit */
     }); 
});
 /* FIN DE  FUNCION JQUERY PARA VALIDAR ACTUALIZACION DE CONTRASEÑA */


















/* FUNCION JQUERY PARA VALIDAR ACTUALIZACION DE CONFIGURACION GENERAL */	 
$('document').ready(function()
{ 
     /* validation */
	$("#configuracion").validate({
     rules:
	     {
			documsucursal: { required: true },
			cuitsucursal: { required: true, digits: false},
			nomsucursal: { required: true },
			tlfsucursal: { required: true,  digits : false },
			correosucursal: { required: true,  email : true },
			codprovincia: { required: false },
			coddepartamento: { required: false },
			direcsucursal: { required: true },
			documencargado: { required: true },
			dniencargado: { required: true, number: true },
			nomencargado: { required: true, lettersonly: true },
			tlfencargado: { required: true,  digits : false },
			codmoneda: { required: true },
	     },
          messages:
	     {
               documsucursal:{ required: "Seleccione Tipo de Documento" },
               cuitsucursal:{ required: "Ingrese N&deg; de Sucursal", digits: "Ingrese solo digitos para N&deg; de Sucursal" },
			nomsucursal:{ required: "Ingrese Raz&oacute;n Social" },
			tlfsucursal: { required: "Ingrese N&deg; de Tel&eacute;fono", digits: "Ingrese solo digitos para Tel&eacute;fono" },
			correosucursal: { required: "Ingrese Correo Electronico", email: "Ingrese un Correo v&aacute;lido" },
			codprovincia:{ required: "Seleccione Provincia" },
			coddepartamento:{ required: "Seleccione Departamento" },
			direcsucursal: { required: "Ingrese Direcci&oacute;n" },
			documencargado:{ required: "Seleccione Tipo de Documento" },
               dniencargado: { required: "Ingrese N&deg; de Documento", number: "Ingrese solo numeros" },
			nomencargado:{ required: "Ingrese Nombre de Encargado", lettersonly: "Ingrese solo letras para Nombres" },
			tlfencargado: { required: "Ingrese N&deg; de Tel&eacute;fono", digits: "Ingrese solo digitos para Tel&eacute;fono" },
			codmoneda:{ required: "Seleccione Tipo de Moneda" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#configuracion").serialize();
		var formData = new FormData($("#configuracion")[0]);
		
		$.ajax({
		type : 'POST',
		url  : 'configuracion.php',
	     async : false,
		data : formData,
		//necesario para subir archivos via ajax
          cache: false,
		contentType: false,
		processData: false,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	          $("#btn-update").attr('disabled', true);
		},
		success :  function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'error',
          timeout: 5000, });
	     $("#btn-update").attr('disabled', false);
		 
			         }); 
																			
				} else { 
						     
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'success',
          timeout: 5000, });
	     $("#btn-update").attr('disabled', false);
			                                
						});
					}
				}
		     });
		     return false;
	     }
	     /* form submit */	 
     });   
});
/* FUNCION JQUERY PARA VALIDAR ACTUALIZACION DE CONFIGURACION GENERAL */











/* FUNCION JQUERY PARA VALIDAR REGISTRO DE USUARIOS */	 
$('document').ready(function()
{ 
     jQuery.validator.addMethod("lettersonly", function(value, element) {
          return this.optional(element) || /^[a-zA-ZñÑáéíóúÁÉÍÓÚ,. ]+$/i.test(value);
     });

     /* validation */
	$("#saveusuario").validate({
     rules:
	     {
			documusuario: { required: false },
			dni: { required: true,  digits : true, minlength: 7 },
			nombres: { required: true, lettersonly: true },
			sexo: { required: true, },
			telefono: { required: false, },
			celular: { required: true, },
			codprovincia: { required: false },
			coddepartamento: { required: false },
			direccion: { required: true, },
			email: { required: true, email: true },
			fnacimiento: { required: false,  date : false },
			usuario: { required: true, },
			password: {required: true, minlength: 8},  
               password2:   {required: true, minlength: 8, equalTo: "#password"}, 
			nivel: { required: true, },
			status: { required: true, },
	     },
          messages:
	     {
               documusuario:{ required: "Seleccione Tipo de Documento" },
               dni:{ required: "Ingrese N&deg; de Documento", digits: "Ingrese solo d&iacute;gitos para N&deg; de Documento", minlength: "Ingrese 7 d&iacute;gitos como m&iacute;nimo" },
			nombres:{ required: "Ingrese Nombres y Apellidos", lettersonly: "Ingrese solo letras para Nombre y Apellidos" },
               sexo:{ required: "Seleccione Sexo de Usuario" },
               telefono:{ required: "Ingrese N&deg; de Tel&eacute;fono" },
               celular:{ required: "Ingrese N&deg; de Celular" },
               codprovincia:{ required: "Seleccione Provincia" },
			coddepartamento:{ required: "Seleccione Departamento" },
			direccion:{ required: "Ingrese Direcci&oacute;n Domiciliaria" },
			email:{ required: "Ingrese Email de Usuario", email: "Ingrese un Email V&aacute;lido" },
			fnacimiento: { required: "Ingrese Fecha de Nacimiento", date: "Ingrese Fecha Valida" },
			usuario:{ required: "Ingrese Usuario de Acceso" },
			password:{ required: "Ingrese Password de Acceso", minlength: "Ingrese 8 caracteres como m&iacute;nimo" },
		     password2:{ required: "Repita Password de Acceso", minlength: "Ingrese 8 caracteres como m&iacute;nimo", equalTo: "Este Password no coincide" },
			nivel:{ required: "Seleccione Nivel de Acceso" },
			status:{ required: "Seleccione Status de Acceso" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#saveusuario").serialize();
		var formData = new FormData($("#saveusuario")[0]);
		
		$.ajax({
		type : 'POST',
		url  : 'usuarios.php',
	     async : false,
		data : formData,
		//necesario para subir archivos via ajax
          cache: false,
          contentType: false,
          processData: false,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
		    $("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}  
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> YA EXISTE UN USUARIO CON ESTE N&deg; DE DOCUMENTO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}   
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE CORREO ELECTR&Oacute;NICO YA SE ENCUENTRA REGISTRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else if(data==4){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE USUARIO YA SE ENCUENTRA REGISTRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
									
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalUsuario').modal('hide');
		$("#saveusuario")[0].reset();
          $("#proceso").val("save");	
		$('#codusuario').val("");
		$("#btn-submit").attr('disabled', false);
		$('#coddepartamento').html("<option value=''>-- SIN RESULTADOS --</option>");
		$('#muestrasucursales').load("funciones?MuestraSucursales=si");
		$('#usuarios').html("");
		$('#usuarios').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#usuarios').load("consultas?CargaUsuarios=si");
		}, 200);

						});
				     }
				}
		     });
		     return false;
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE USUARIOS */










/* FUNCION JQUERY PARA VALIDAR REGISTRO DE TIPOS DE DOCUMENTOS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#savedocumento").validate({
     rules:
	     {
			documento: { required: true, },
			descripcion: { required: true, },
	     },
          messages:
	     {
			documento:{ required: "Ingrese Nombre de Documento" },
               descripcion:{ required: "Ingrese Descripci&oacute;n de Documento" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#savedocumento").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'documentos.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
          $("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE DOCUMENTO YA EXISTE, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
          $("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalDocumento').modal('hide');
		$("#savedocumento")[0].reset();
          $("#proceso").val("save");
		$('#coddocumento').val("");
		$('#documentos').html("");	
		$("#btn-submit").attr('disabled', false);
		$('#documentos').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
          setTimeout(function() {
          $('#documentos').load("consultas?CargaDocumentos=si");
          }, 200);
									
						});
					}
				}
			});
			return false;
		}
	    /* form submit */	
     });    
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE TIPOS DE DOCUMENTOS */








/* FUNCION JQUERY PARA VALIDAR REGISTRO DE PROVINCIAS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#saveprovincia").validate({
     rules:
	     {
			provincia: { required: true },
	     },
          messages:
	     {
               provincia:{ required: "Ingrese Nombre de Provincia" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#saveprovincia").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'provincias.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
			     if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
	     $("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE PROVINCIA YA SE ENCUENTRA REGISTRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
	     $("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalProvincia').modal('hide');
		$("#saveprovincia")[0].reset();
          $("#proceso").val("save");	
		$('#codprovincia').val("");	
		$('#provincias').html("");
	     $("#btn-submit").attr('disabled', false);
		$('#provincias').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#provincias').load("consultas?CargaProvincias=si");
		}, 200);			
						});
				     }
			     }
			});
			return false;
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE PROVINCIAS */









/* FUNCION JQUERY PARA VALIDAR REGISTRO DE DEPARTAMENTOS */	 
$('document').ready(function()
{ 
    /* validation */
	$("#savedepartamento").validate({
     rules:
	     {
		    departamento: { required: true, },
		    codprovincia: { required: true, },
	     },
          messages:
	     {
              departamento:{ required: "Ingrese Nombre de Departamento"},
              codprovincia:{ required: "Seleccione Provincia"},
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#savedepartamento").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'departamentos.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
          $("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE DEPARTAMENTO YA EXISTE PARA LA PROVINCIA SELECCIONADA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
          $("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalDepartamento').modal('hide');
		$("#savedepartamento")[0].reset();
          $("#proceso").val("save");	
		$('#iddepartamento').val("");
		$('#departamentos').html("");
		$("#btn-submit").attr('disabled', false);
		$('#departamentos').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 $('#departamentos').load("consultas?CargaDepartamentos=si");
		}, 200);				
						});
					}
				}
			});
			return false;
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE DEPARTAMENTOS */







/* FUNCION JQUERY PARA VALIDAR REGISTRO DE TIPOS DE MONEDA */	 
$('document').ready(function()
{ 
     /* validation */
	$("#savemoneda").validate({
     rules:
	     {
			moneda: { required: true },
			siglas: { required: true },
			simbolo: { required: true },
	     },
          messages:
	     {
			moneda:{ required: "Ingrese Nombre de Moneda" },
            siglas:{ required: "Ingrese Siglas de Moneda" },
            simbolo:{ required: "Ingrese Simbolo de Moneda" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
	   			
		var data = $("#savemoneda").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'monedas.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE MONEDA YA EXISTE, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000 });
          $('body').removeClass('modal-open');
          $('#myModalMoneda').modal('hide');
		$("#savemoneda")[0].reset();
          $("#proceso").val("save");
		$('#codmoneda').val("");
		$('#monedas').html("");
		$("#btn-submit").attr('disabled', false);	
		$('#monedas').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
          setTimeout(function() {
          $('#monedas').load("consultas?CargaMonedas=si");
          }, 200);			
						});
					}
				}
			});
			return false;
		}
	     /* form submit */
     }); 	   
});
/*  FUNCION PARA VALIDAR REGISTRO DE TIPOS DE MONEDA */








/* FUNCION JQUERY PARA VALIDAR REGISTRO DE FORMAS DE PAGOS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#saveformapago").validate({
     rules:
	     {
			formapago: { required: true },
	     },
          messages:
	     {
               formapago:{ required: "Ingrese Forma de Pago" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#saveformapago").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'formaspagos.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
	        $("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
			     if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
	     $("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE FORMA DE PAGO YA SE ENCUENTRA REGISTRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
	     $("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalFormaPago').modal('hide');
		$("#saveformapago")[0].reset();
          $("#proceso").val("save");	
		$('#codformapago').val("");	
		$('#formaspagos').html("");
	     $("#btn-submit").attr('disabled', false);
		$('#formaspagos').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#formaspagos').load("consultas?CargaFormasPagos=si");
		}, 200);			
						});
				     }
			     }
			});
			return false;
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE FORMAS DE PAGOS */









/* FUNCION JQUERY PARA CARGA MASIVA DE CLIENTES */	 
$('document').ready(function()
{ 						
     /* validation */
	$("#cargaclientes").validate({
     rules:
	     {
			sel_file: { required: false },
	     },
          messages:
	     {
               sel_file:{ required: "Por favor Seleccione Archivo para Cargar" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
	   			
		var data = $("#cargaclientes").serialize();
		var formData = new FormData($("#cargaclientes")[0]);
		var sel_file = $('#sel_file').val();

          if (sel_file == "") {
            
			swal("Oops", "POR FAVOR REALICE LA BUSQUEDA DEL ARCHIVO A CARGAR!", "error");
               return false;

          } else {
		
		$.ajax({
		type : 'POST',
		url  : 'clientes.php',
	     async : false,
		data : formData,
		//necesario para subir archivos via ajax
          cache: false,
          contentType: false,
          processData: false,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-cliente").attr('disabled', true);
		},
		success : function(data)
				{						
		          if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> NO SE HA SELECCIONADO NINGUN ARCHIVO PARA CARGAR, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-cliente").attr('disabled', false);
								
					});
				}  
		          else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ERROR! ARCHIVO INVALIDO PARA LA CARGA MASIVA DE CLIENTES, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-cliente").attr('disabled', false);
																		
					});
				}
			     else{
								
		     $("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000 });
          $('body').removeClass('modal-open');
          $('#myModalCargaMasiva').modal('hide');
		$("#cargaclientes")[0].reset();
		$('#divcliente').html("");
		$("#btn-cliente").attr('disabled', false);
									
						});
					}
				}
			});
			return false;
		     }
		}
	     /* form submit */
     }); 
});
/*  FUNCION PARA CARGA MASIVA DE CLIENTES */

/* FUNCION JQUERY PARA VALIDAR REGISTRO DE CLIENTES */	 
$('document').ready(function()
{ 
     jQuery.validator.addMethod("lettersonly", function(value, element) {
          return this.optional(element) || /^[a-zA-ZñÑáéíóúÁÉÍÓÚ,. ]+$/i.test(value);
     });

     /* validation */
	$("#savecliente").validate({
     rules:
	     {
			documcliente: { required: true },
			cedcliente: { required: true, digits: false },
			nomcliente: { required: true },
			sexocliente: { required: false },
			tlfcliente: { required: false,  digits : false },
			celcliente: { required: true,  digits : false },
			codprovincia: { required: false },
			coddepartamento: { required: false },
			direccliente: { required: true },
			correocliente: { required: false,  email : true },
			statuscliente: { required: true },
			cedreferencia: { required: false, digits: false },
			nomreferencia: { required: false },
			celreferencia: { required: false },
	     },
          messages:
	     {
			documcliente:{ required: "Seleccione Tipo de Documento" },
               cedcliente:{ required: "Ingrese N&deg; de Documento", digits: "Ingrese solo digitos para N&deg; de Documento" },
			nomcliente:{ required: "Ingrese Nombre de Cliente" },
			sexocliente:{ required: "Seleccione Genero" },
			tlfcliente: { required: "Ingrese N&deg; de Tel&eacute;fono", digits: "Ingrese solo digitos para Tel&eacute;fono" },
			celcliente: { required: "Ingrese N&deg; de Celular", digits: "Ingrese solo digitos para Celular" },
			codprovincia:{ required: "Seleccione Provincia" },
			coddepartamento:{ required: "Seleccione Departamento" },
			direccliente: { required: "Ingrese Direcci&oacute;n Domiciliara" },
			correocliente: { required: "Ingrese Correo Electr&oacute;nico", email: "Ingrese un Correo v&aacute;lido" },
			statuscliente:{ required: "Seleccione Status" },
			cedreferencia:{ required: "Ingrese N&deg; de Documento", digits: "Ingrese solo digitos para N&deg; de Documento" },
			nomreferencia:{ required: "Ingrese Nombre de Persona" },
			celreferencia:{ required: "Ingrese N&deg; de Celular" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
	   			
		var data = $("#savecliente").serialize();
		var formData = new FormData($("#savecliente")[0]);
		var formulario = $('#formulario').val();
		var cedcliente = $('#cedcliente').val();
	     var nomcliente = $('#nomcliente').val();

		if(formulario != "clientes"){
		     $("#codcliente").val("0");
		     $("#nrodocumento").val(cedcliente);
		     $("#search_cliente_activo").val(cedcliente +": "+ nomcliente);
          }
		
		$.ajax({
		type : 'POST',
		url  : formulario+'.php',
	     async : false,
		data : formData,
		//necesario para subir archivos via ajax
          cache: false,
          contentType: false,
          processData: false,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-cliente").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
          $("#btn-cliente").attr('disabled', false);
								
					});
				} 
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE SELECCIONAR UNA SUCURSAL, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
          $("#btn-cliente").attr('disabled', false);
									
					});
				} 
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE CORREO ELECTR&Oacute;NICO YA SE ENCUENTRA REGISTRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
          $("#btn-cliente").attr('disabled', false);
									
					});
				}
				else if(data==4){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> YA EXISTE UN CLIENTE CON ESTE N&deg; DE DOCUMENTO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
          $("#btn-cliente").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000 });
          $('body').removeClass('modal-open');
          $('#myModalCliente').modal('hide');
		$("#savecliente")[0].reset();
          $("#btn-cliente").attr('disabled', false);
		$('#coddepartamento').html("<option value=''>-- SIN RESULTADOS --</option>");

		if(formulario == "clientes"){
               $("#proceso").val("save");
		     $('#codcliente').val("");
			$('#clientes').html("");
			$('#clientes').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
			setTimeout(function() {
			 	$('#clientes').load("consultas?CargaClientes=si");
			}, 200);
			//$("#BotonBusqueda").trigger("click");
          }				
						});
					}
				}
			});
			return false;
		}
	     /* form submit */	   
     }); 
});
/*  FUNCION PARA VALIDAR REGISTRO DE CLIENTES */

















/* FUNCION JQUERY PARA VALIDAR REGISTRO DE CAJAS PARA VENTAS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#savecaja").validate({
     rules:
	     {
			codigo: { required: true, },
			nrocaja: { required: true, },
			nomcaja: { required: true, },
	     },
          messages:
	     {
			codigo:{ required: "Seleccione Responsable" },
               nrocaja:{ required: "Ingrese N&deg; de Caja" },
               nomcaja:{ required: "Ingrese Nombre de Caja" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#savecaja").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'cajas.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				} 
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE N&deg; DE CAJA YA SE ENCUENTRA ASIGNADA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				} 
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE NOMBRE DE CAJA YA SE ENCUENTRA ASIGNADA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else if(data==4){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE USUARIO YA TIENE UNA CAJA ASIGNADA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalCaja').modal('hide');
		$("#savecaja")[0].reset();
          $("#proceso").val("save");
		$('#cajas').html("");
		$("#btn-submit").attr('disabled', false);
		$('#cajas').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#cajas').load("consultas?CargaCajas=si");
		}, 200);
										
						});
					}
				}
			});
			return false;
		}
	     /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE CAJAS PARA VENTAS */















/* FUNCION JQUERY PARA VALIDAR REGISTRO DE ARQUEO DE CAJAS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#saveapertura").validate({
     rules:
	     {
			codcaja: { required: true, },
			fecharegistro: { required: true, },
			montoinicial: { required: true, number : true},
	     },
          messages:
	     {
			codcaja: { required: "Seleccione Caja" },
			fecharegistro:{ required: "Ingrese Hora de Apertura", number: "Ingrese solo digitos con 2 decimales" },
			montoinicial:{ required: "Ingrese Monto Inicial", number: "Ingrese solo digitos con 2 decimales" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#saveapertura").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'aperturas.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> YA TIENE UNA CAJA APERTURADA ACTUALMENTE, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalApertura').modal('hide');
		$("#saveapertura")[0].reset();
		$('#aperturas').html("");
		$("#btn-submit").attr('disabled', false);
		$('#aperturas').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#aperturas').load("consultas?CargaAperturas=si");
		}, 200);
									
						});
					}
				}
			});
			return false;
		}
	     /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE ARQUEO DE CAJAS */

/* FUNCION JQUERY PARA VALIDAR CERRAR ARQUEO DE CAJAS  */	 
$('document').ready(function()
{ 
     /* validation */
	$("#cerrarapertura").validate({
     rules:
	     {
			fecharegistro: { required: true, },
			montoinicial: { required: true, number : true},
			dineroefectivo: { required: true, number : true},
			comentarios: { required: false, },
	     },
          messages:
	     {
			fecharegistro:{ required: "Ingrese Hora de Apertura" },
			montoinicial:{ required: "Ingrese Monto Inicial", number: "Ingrese solo digitos con 2 decimales" },
			dineroefectivo:{ required: "Ingrese Monto en Efectivo", number: "Ingrese solo digitos con 2 decimales" },
			comentarios: { required: "Ingrese Observaci&oacute;n de Cierre" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#cerrarapertura").serialize();
		var dineroefectivo = $('#dineroefectivo').val();

          /*if (dineroefectivo==0.00 || dineroefectivo==0) {
            
			$("#dineroefectivo").focus();
			$('#dineroefectivo').val("");
			$('#dineroefectivo').css('border-color','#f0ad4e');
			swal("Oops", "POR FAVOR INGRESE UN MONTO VALIDO PARA EFECTIVO DISPONIBLE EN CAJA!", "error");
               return false;
 
          } else {*/
			
		$.ajax({
		type : 'POST',
		url  : 'aperturas.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-update").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-update").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR INGRESE UN MONTO VALIDO PARA EFECTIVO DISPONIBLE EN CAJA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-update").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('#myModalCierre').modal('hide');
	     $("#cerrarapertura")[0].reset();
		$('#aperturas').html("");
		$("#btn-update").attr('disabled', false);
		$('#aperturas').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#aperturas').load("consultas?CargaAperturas=si");
		}, 200);
							});
					    }
					}
			    });
			    return false;
		   //}
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR CERRAR ARQUEO DE CAJAS */


















/* FUNCION JQUERY PARA VALIDAR REGISTRO DE MOVIMIENTOS EN CAJAS */	 
$('document').ready(function()
{ 
     /* validation */
	$("#savemovimiento").validate({
     rules:
	     {
			codcaja: { required: true, },
			tipomovimiento: { required: true, },
			descripcionmovimiento: { required: true, },
			montomovimiento: { required: true, number : true },
			mediomovimiento: { required: true, },
	     },
          messages:
	     {
			codcaja:{ required: "Seleccione Caja" },
               tipomovimiento:{ required: "Seleccione Tipo Movimiento" },
			descripcionmovimiento:{ required: "Ingrese Descripci&oacute;n de Movimiento" },
			montomovimiento:{ required: "Ingrese Monto de Movimiento", number: "Ingrese solo digitos con 2 decimales" },
			mediomovimiento:{ required: "Seleccione Medio de Movimiento" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#savemovimiento").serialize();
		var monto = $('#montomovimiento').val();

          if (monto==0.00 || monto==0) {
            
			$("#montomovimiento").focus();
			$('#montomovimiento').val("");
			$('#montomovimiento').css('border-color','#f0ad4e');
			swal("Oops", "POR FAVOR INGRESE UN MONTO VALIDO PARA MOVIMIENTOS!", "error");
               return false;

          } else {
			
		$.ajax({
		type : 'POST',
		url  : 'movimientos.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR INGRESE UN MONTO VALIDO PARA MOVIMIENTO EN CAJA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}     
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> NO PUEDE REALIZAR CAMBIO EN EL TIPO Y MEDIO DE MOVIMIENTO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else if(data==4){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTA CAJA NO SE ENCUENTRA ABIERTA PARA REALIZAR MOVIMIENTOS, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else if(data==5){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL MOVIMIENTO DE EGRESO DEBE DE SER SOLO EFECTIVO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else if(data==6){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL MONTO A RETIRAR EN EFECTIVO NO EXISTE EN CAJA, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else if(data==7){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> ESTE MOVIMIENTO NO PUEDE SER ACTUALIZADO, LA APERTURA ASOCIADA A ESTA CAJA SE ENCUENTRA CERRADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $('body').removeClass('modal-open');
          $('#myModalMovimiento').modal('hide');
		$("#savemovimiento")[0].reset();
          $("#proceso").val("save");	
		$('#movimientos').html("");
		$("#btn-submit").attr('disabled', false);
		$('#movimientos').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#movimientos').load("consultas?CargaMovimientos=si");
		}, 200);			
							});
						}
					}
			     });
			return false;
		     }		
	     }
	    /* form submit */	
     });    
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE MOVIMIENTOS EN CAJAS */















/* FUNCION JQUERY PARA VALIDAR REGISTRO DE PRESTAMOS */	 	 
$('document').ready(function()
{ 
     /* validation */
	$("#saveprestamo").validate({
     rules:
	     {
			search_cliente_activo: { required: false },
			montoprestamo: { required: true, number : true},
			intereses: { required: true, number : true},
			cuotas: { required: true },
			periodopago: { required: true },
			fechapagocuota: { required: true },
			montocuota: { required: true },
			totalintereses2: { required: true, number : true},
			totalpago2: { required: true, number : true},
			observaciones: { required: false },
	     },
          messages:
	     {
               search_cliente_activo:{ required: "Realice la B&uacute;squeda del Cliente" },
			montoprestamo:{ required: "Ingrese Monto de Pr&eacute;stamo", number: "Ingrese solo digitos con 2 decimales" },
			intereses:{ required: "Ingrese Intereses (%)", number: "Ingrese solo digitos con 2 decimales" },
			cuotas:{ required: "Ingrese Nº de Cuotas" },
			periodopago:{ required: "Seleccione Periodo de Pago" },
			fechapagocuota:{ required: "Ingrese Fecha de Pago" },
			totalintereses2:{ required: "Ingrese Monto de Cuota", number: "Ingrese solo digitos con 2 decimales" },
			totalpago2:{ required: "Ingrese Monto de Cuota", number: "Ingrese solo digitos con 2 decimales" },
			observaciones: { required: "Ingrese Observaciones" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
	   			
	     var data = $("#saveprestamo").serialize();
		var Cliente = $('#nrodocumento').val();

		if (Cliente == "" || Cliente == 0) {
	            
              $("#search_cliente_activo").focus();
              $('#search_cliente_activo').css('border-color','#cf1e1e');
              swal("Oops", "POR FAVOR REALICE LA BUSQUEDA DEL CLIENTE CORRECTAMENTE!", "error");
              return false;
	 
	     } else {
	 				
		$.ajax({
		type : 'POST',
		url  : 'forprestamo.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR REALICE LA APERTURA DE CAJA, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}   
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE SELECCIONAR EL PERIODO DE PAGO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
			     else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 8000, });
          //$.get('verifica_meses.php', {'Verifica_Meses_Vencidos': true});
		$("#saveprestamo")[0].reset();
          $("#codcliente").val("");
          $("#nrodocumento").val("");
          $("#muestra_detalles").html("");
          $("#btn-submit").attr('disabled', false);
										
						});
					}
				}
			});
			return false;
			}
		}
	     /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE PRESTAMOS */


/* FUNCION JQUERY PARA VALIDAR PROCESAR PRESTAMO */	 
$('document').ready(function()
{ 
     jQuery.validator.addMethod("lettersonly", function(value, element) {
          return this.optional(element) || /^[a-zA-ZñÑáéíóúÁÉÍÓÚ,. ]+$/i.test(value);
     });

     /* validation */
	$("#saveprocesarprestamo").validate({
     rules:
	     {
			codcliente: { required: false },
			statusprestamo: { required: true, },
	     },
          messages:
	     {
               codcliente:{ required: "Realice la Búsqueda del Cliente" },
			statusprestamo:{ required: "Seleccione Estado" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
                     		
		var data = $("#saveprocesarprestamo").serialize();
		
		$.ajax({
		type : 'POST',
		url  : 'prestamospendientes.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
		    $("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> NO TIENE CAJA APERTURADA PARA PAGO DE PRESTAMO, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}  
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> EL MONTO DISPONIBLE EN CAJA ES MENOR A AL MONTO DE PRESTAMO SOLICITADO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000 });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}  
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 5000, });
          $.get('verifica_meses.php', {'Verifica_Meses_Vencidos': true});
          $('body').removeClass('modal-open');
          $('#myModalProcesarPrestamo').modal('hide');
		$("#saveprocesarprestamo")[0].reset();
          $("#proceso").val("save");	
		$('#codprestamo').val("");	
		$('#codcliente').val("");	
		$('#nrodocumento').val("");
		$("#btn-submit").attr('disabled', false);
		$('#prestamos').html("");
		$('#prestamos').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
		setTimeout(function() {
		 	$('#prestamos').load("consultas?CargaPrestamosPendientes=si");
		}, 200);

						});
				     }
				}
		     });
		     return false;
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR PROCESAR PRESTAMO */









/* FUNCION JQUERY PARA VALIDAR REGISTRO DE PAGOS */	 	 
$('document').ready(function()
{ 
     /* validation */
	$("#savepago").validate({
     rules:
	     {
			search_cliente_general: { required: false },
			metodopago: { required: true },
			comprobante: { required: false },
			observaciones: { required: false },
	     },
          messages:
	     {
               search_cliente_general:{ required: "Realice la B&uacute;squeda del Cliente" },
			metodopago:{ required: "Seleccione Metodo de Pago" },
			comprobante:{ required: "Ingrese Nº de Comprobante" },
			observaciones: { required: "Ingrese Observaciones" },
          },
	     errorElement: "span",
	     submitHandler: function(form) {
	   			
		var data = $("#savepago").serialize();
		var Cliente = $('#nrodocumento').val();
		var check = $("input[type='checkbox']:checked:enabled").length;

		if (Cliente == "" || Cliente == 0) {
	            
               swal("Oops", "POR FAVOR REALICE LA BUSQUEDA DEL CLIENTE CORRECTAMENTE!", "error");
               return false;
	 
	     } else if(check == "0"){
	 
	          swal("Oops", "POR FAVOR SELECCIONE AL MENOS UN MES DE PAGO!", "error");
               return false; 
	 
	     } else {
	 				
		$.ajax({
		type : 'POST',
		url  : 'forpago.php',
	     async : false,
		data : data,
		beforeSend: function()
		{	
			$("#save").fadeOut();
			var n = noty({
			text: "<span class='fa fa-refresh'></span> PROCESANDO INFORMACI&Oacute;N, POR FAVOR ESPERE......",
			theme: 'relax',
			layout: 'topRight',
			type: 'information',
			timeout: 1000, });
			$("#btn-submit").attr('disabled', true);
		},
		success : function(data)
				{						
				if(data==1){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR REALICE LA APERTURA DE CAJA, VERIFIQUE NUEVAMENTE POR FAVOR...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
								
					});
				}   
				else if(data==2){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE COMPLETAR LOS CAMPOS REQUERIDOS, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}    
				else if(data==3){
							
			$("#save").fadeIn(1000, function(){
							
		var n = noty({
          text: "<span class='fa fa-warning'></span> POR FAVOR DEBE DE SELECCIONAR AL MENOS UN MES PARA PAGO, VERIFIQUE NUEVAMENTE POR FAVOR ...!",
          theme: 'relax',
          layout: 'topRight',
          type: 'warning',
          timeout: 5000, });
		$("#btn-submit").attr('disabled', false);
																		
					});
				}
				else{
								
			$("#save").fadeIn(1000, function(){
								
		var n = noty({
		text: '<center> '+data+' </center>',
          theme: 'relax',
          layout: 'topRight',
          type: 'information',
          timeout: 8000, });
          $.get('verifica_meses.php', {'Verifica_Meses_Vencidos': true});
		$("#savepago")[0].reset();
          $("#codcliente").val("");
          $("#nrodocumento").val("");
          $("#muestra_detalles_prestamos").html("");
          $("#muestra_detalles_cuotas").html("");
          $("#btn-submit").attr('disabled', false);
										
							});
						}
				     }
				});
				return false;
			}
		}
	    /* form submit */
     }); 	   
});
/*  FIN DE FUNCION PARA VALIDAR REGISTRO DE PAGOS */