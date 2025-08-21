<?php
isset($_SESSION) or session_start();

//error_reporting(0);//desactivo errores
ini_set('display_errors', 1);
// Motrar todos los errores de PHP
error_reporting(E_ALL);//muestro errores
//evita el error Fatal error: Allowed memory size of X bytes exhausted (tried to allocate Y bytes)...
ini_set('memory_limit', '-1'); 
// es lo mismo que set_time_limit(300) ;
ini_set('max_execution_time', 3800); 
//error_log("display_errors", 3, "error_log.log");

require_once("classconexion.php");
/* Clase principal de PHPMailer */
require("PHPMailer/PHPMailer.php");
/* Clase SMTP, necesaria si quieres usar SMTP */
require("PHPMailer/SMTP.php");
/* Clase Exception */
require("PHPMailer/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

################################## CLASE LOGIN ###################################
class Login extends Db
{

public function __construct()
{
	parent::__construct();
} 	

########################### CLASE LOGUEO ###############################

###################### FUNCION PARA EXPIRAR SESSION ####################
public function ExpiraSession()
{
   if(!isset($_SESSION['usuario'])){// Esta logeado?.
		header("Location: logout.php"); 
	}
	//Verifico el tiempo si esta seteado, caso contrario lo seteo.
   $inactividad = 742000; //(1 hora de cierre sesion )600 equivale a 10 minutos
   $actual      = time();
   $tiempo = (isset($_SESSION['time']) ? $_SESSION['time'] : strtotime(date("Y-m-d H:i:s")));

   if ($actual-$tiempo >= $inactividad) { ?>
   <script type='text/javascript'>
      alert("SU SESSION A EXPIRADO \nPOR FAVOR LOGUEESE DE NUEVO PARA ACCEDER AL SISTEMA");
      document.location.href = "logout";
   </script>
   <?php 
   } else {
      $_SESSION['time'] = $actual;
   }
}
###################### FUNCION PARA EXPIRAR SESSION ####################

#################### FUNCION PARA ACCEDER AL SISTEMA ####################
public function Logueo()
{
	self::SetNames();
	if(empty($_POST["usuario"]) or empty($_POST["password"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT * FROM accesos WHERE usuario = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_POST["usuario"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		echo "2";
		exit;

	} else {
			
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[]=$row;
		}

		if (limpiar($row['status']) == 0)
		{  
			echo "3";
			exit;
		} 
		elseif (password_verify($_POST["password"], $row['password'])) {

			if (limpiar($row['tipousuario']) == 1) {//if tipo administracion	

				################## DATOS DE SESSION ##################
		    	$sql2 = "SELECT
		    	usuarios.*,
		    	accesos.idacceso,
		    	accesos.codrelacion,
		    	accesos.usuario,
		    	accesos.password,
		    	accesos.nivel,
		    	accesos.email,
		    	accesos.status,
		    	accesos.tipousuario,
		    	documentos.documento,
		      provincias.provincia,
	         departamentos.departamento
		    	FROM usuarios 
		    	INNER JOIN accesos ON usuarios.codigo = accesos.codrelacion
		    	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
		      LEFT JOIN provincias ON usuarios.codprovincia = provincias.codprovincia
		    	LEFT JOIN departamentos ON usuarios.coddepartamento = departamentos.coddepartamento
		    	WHERE usuarios.codigo = ? AND accesos.tipousuario = 1";
		    	$stmt = $this->dbh->prepare($sql2);
		    	$stmt->execute(array(limpiar($row['codrelacion'])));
		    	$num = $stmt->rowCount();
		    	if($row2 = $stmt->fetch(PDO::FETCH_ASSOC))
		    	{
		    		$p2[]=$row2;
		    	}
			
				$_SESSION["idacceso"] = $p2[0]["idacceso"];
				$_SESSION["codigo"] = $p2[0]["codigo"];
				$_SESSION["usuario"] = $p2[0]["usuario"];
				$_SESSION["password"] = $p2[0]["password"];
				$_SESSION["nivel"] = $p2[0]["nivel"];
				$_SESSION["email"] = $p2[0]["email"];
				$_SESSION["tipousuario"] = $p2[0]['tipousuario'];

		      $_SESSION["documusuario"] = $p2[0]["documusuario"];
				$_SESSION["documento"] = $p2[0]["documento"];
				$_SESSION["dni"] = $p2[0]["dni"];
				$_SESSION["nombres"] = $p2[0]["nombres"];
				$_SESSION["sexo"] = $p2[0]["sexo"];
				$_SESSION["direccion"] = $p2[0]["direccion"];
				$_SESSION["telefono"] = $p2[0]["telefono"];
				$_SESSION["celular"] = $p2[0]["celular"];
				$_SESSION["coddepartamento"] = $p2[0]["coddepartamento"];
				$_SESSION["departamento"] = $p2[0]["departamento"];
				$_SESSION["codprovincia"] = $p2[0]["codprovincia"];
				$_SESSION["provincia"] = $p2[0]["provincia"];
				$_SESSION["direccion"] = $p2[0]["direccion"];
				$_SESSION["ingreso"] = limpiar(date("Y-m-d H:i:s"));
				################## DATOS DE SESSION ##################

				############### REGISTRO LOGS DE ACCESO ###############
				$query = "INSERT INTO log VALUES (null, ?, ?, ?, ?, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1,$a);
				$stmt->bindParam(2,$b);
				$stmt->bindParam(3,$c);
				$stmt->bindParam(4,$d);
				$stmt->bindParam(5,$e);
				$stmt->bindParam(6,$f);
				$stmt->bindParam(7,$g);

				$a = limpiar($_SERVER['REMOTE_ADDR']);
				$b = limpiar(date("Y-m-d H:i:s"));
				$c = limpiar($_SERVER['HTTP_USER_AGENT']);
				$d = limpiar($_SERVER['PHP_SELF']);
				$e = limpiar($p2[0]["codigo"]);
				$f = limpiar($_POST["usuario"]);
				$g = limpiar("0");
				$stmt->execute();
				############### REGISTRO LOGS DE ACCESO ###############

				$redirect = '/logout';
				switch($_SESSION["nivel"])
				{
					case 'ADMINISTRADOR(A)':
					$_SESSION["acceso"] = "administrador";
					$redirect           = 'panel';
					break;
					case 'CAJERO(A)':
					$_SESSION["acceso"] = "cajero";
					$redirect           = 'panel';
					break;
					case 'VENDEDOR(A)':
					$_SESSION["acceso"] = "vendedor";
					$redirect           = 'panel';
					break;
				}//end switch
            die($redirect);

		   } elseif (limpiar($row['tipousuario']) == 2) {//if tipo cliente	

		   	################## DATOS DE SESSION ##################
				$sql2 = "SELECT
				clientes.*,
				accesos.idacceso,
				accesos.codrelacion,
				accesos.usuario,
				accesos.password,
				accesos.nivel,
				accesos.email,
				accesos.status,
				accesos.tipousuario,
				documentos.documento,
				documentos.descripcion,
				provincias.provincia,
				departamentos.departamento
				FROM clientes 
				INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion
				LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento  
				LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
				LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
		    	WHERE clientes.codcliente = ? AND accesos.tipousuario = 2";
		    	$stmt = $this->dbh->prepare($sql2);
		    	$stmt->execute(array(limpiar($row['codrelacion'])));
		    	$num = $stmt->rowCount();
		    	if($row2 = $stmt->fetch(PDO::FETCH_ASSOC))
		    	{
		    		$p2[]=$row2;
		    	}
				$_SESSION["idacceso"] = $p2[0]["idacceso"];
				$_SESSION["codigo"] = $p2[0]["codcliente"];
				$_SESSION["usuario"] = $p2[0]["usuario"];
				$_SESSION["password"] = $p2[0]["password"];
				$_SESSION["nivel"] = $p2[0]["nivel"];
				$_SESSION["email"] = $p2[0]["email"];
				$_SESSION["tipousuario"] = $p2[0]['tipousuario'];

				$_SESSION["dni"] = $p2[0]["cedcliente"];
				$_SESSION["nombres"] = $p2[0]["nomcliente"];
				$_SESSION["ingreso"] = limpiar(date("Y-m-d H:i:s"));
				################## DATOS DE SESSION ##################

			   ############### REGISTRO LOGS DE ACCESO ###############
				$query = "INSERT INTO log VALUES (null, ?, ?, ?, ?, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1,$a);
				$stmt->bindParam(2,$b);
				$stmt->bindParam(3,$c);
				$stmt->bindParam(4,$d);
				$stmt->bindParam(5,$e);
				$stmt->bindParam(6,$f);
				$stmt->bindParam(7,$g);

				$a = limpiar($_SERVER['REMOTE_ADDR']);
				$b = limpiar(date("Y-m-d H:i:s"));
				$c = limpiar($_SERVER['HTTP_USER_AGENT']);
				$d = limpiar($_SERVER['PHP_SELF']);
				$e = limpiar($p2[0]["codigo"]);
				$f = limpiar($_POST["usuario"]);
				$g = limpiar("0");
				$stmt->execute();
		      ############### REGISTRO LOGS DE ACCESO ###############
				
				$redirect = '/logout';
				switch($_SESSION["nivel"])
				{
					case 'CLIENTE':
					$_SESSION["acceso"] = "cliente";
					$redirect           = 'panel';
					break;
			   }//end switch
            die($redirect);

	   }//end tipo usuario
		    
      } else {

  	      echo "4";
  	      exit;
	   }
   }
}
#################### FUNCION PARA ACCEDER AL SISTEMA ####################

############################ FUNCION DATOS SESSION ID #################################
public function SessionPorId()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT 
		clientes.*,
		accesos.idacceso,
		accesos.codrelacion,
		accesos.usuario,
		accesos.password,
		accesos.nivel,
		accesos.email,
		accesos.status,
		accesos.tipousuario,
		documentos.documento,
		documentos.descripcion,
		provincias.provincia,
		departamentos.departamento
		FROM clientes 
		INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento  
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
		WHERE clientes.codcliente = ? AND accesos.tipousuario = 2";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_SESSION["codigo"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}

	} else {

		$sql = "SELECT
		usuarios.*,
		accesos.idacceso,
		accesos.codrelacion,
		accesos.usuario,
		accesos.password,
		accesos.nivel,
		accesos.email,
		accesos.status,
		accesos.tipousuario,
		documentos.documento,
		provincias.provincia,
		departamentos.departamento
		FROM usuarios 
		INNER JOIN accesos ON usuarios.codigo = accesos.codrelacion
		LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
		LEFT JOIN provincias ON usuarios.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON usuarios.coddepartamento = departamentos.coddepartamento
		WHERE usuarios.codigo = ? AND accesos.tipousuario = 1";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_SESSION["codigo"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
   }
}
############################ FUNCION DATOS SESSION ID #################################

########################### FIN DE CLASE LOGUEO ###############################
















######################## FUNCION RECUPERAR Y ACTUALIZAR PASSWORD #######################

########################### FUNCION PARA RECUPERAR CLAVE #############################
public function RecuperarPassword()
{
	self::SetNames();
	if(empty($_POST["email"]))
	{
		echo "1";
		exit;
	}

	################## DATOS DE CONFIGURACION #####################
	$sql = "SELECT * FROM configuracion";
	foreach ($this->dbh->query($sql) as $row)
	{
	   $this->p[] = $row;
	}
   $cuitsucursal = $row['cuitsucursal'];
   $nomsucursal = $row['nomsucursal'];
   $correosucursal = $row['correosucursal'];
   ################## DATOS DE CONFIGURACION #####################

	################# OBTENGO DATOS DEL USUARIO #################
	$sql = "SELECT * FROM accesos WHERE email = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_POST["email"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "2";
		exit;
	}
	else
	{	
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pa[] = $row;
		}
		$id = $pa[0]["idacceso"];
		$codrelacion = $pa[0]["codrelacion"];
		$nivel = $pa[0]["nivel"];
		$tipousuario = $pa[0]["tipousuario"];
		$email = $pa[0]["email"];
		$pass = strtoupper(generar_clave(10));
	}
	################# OBTENGO DATOS DEL USUARIO #################

	if ($tipousuario == 1) {

		################# OBTENGO DATOS DE USUARIO #################
		$sql2 = "SELECT 
		dni, 
		nombres 
		FROM usuarios WHERE codigo = ?";
		$stmt = $this->dbh->prepare($sql2);
		$stmt->execute(array($codrelacion));
		if($row2 = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pa2[] = $row2;
		}
		$dni = $pa2[0]["dni"];
		$nombres = $pa2[0]["nombres"];
		################# OBTENGO DATOS DE USUARIO #################

	} elseif ($tipousuario == 2) {

		################# OBTENGO DATOS DE CLIENTE #################
		$sql2 = "SELECT 
		dni, 
		nombres 
		FROM clientes WHERE codigo = ?";
		$stmt = $this->dbh->prepare($sql2);
		$stmt->execute(array($codrelacion));
		if($row2 = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pa2[] = $row2;
		}
		$dni = $pa2[0]["dni"];
		$nombres = $pa2[0]["nombres"];
		################# OBTENGO DATOS DE CLIENTE #################
	}

	#################### VALIDACION DE ENVIO DE CORREO CON PHPMAILER ####################
   //Create a new PHPMailer instance
   $mail = new PHPMailer(TRUE);

   $mail->isSMTP();// Set mailer to use SMTP
	$mail->CharSet = 'UTF-8';
	$mail->SMTPAuth = true;// Enable SMTP authentication
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption, `ssl` also accepted

	$mail->Host = 'smtp.gmail.com';// Specify main and backup SMTP servers
	$mail->Port = 587;// TCP port to connect to
	$mail->SMTPDebug = 0;
	$mail->SMTPOptions = array(
	   'ssl' => array(
	      'verify_peer' => false,
	      'verify_peer_name' => false,
	      'allow_self_signed' => true
	   )
	);
	$mail->isHTML(true);// Set email format to HTML

	$mail->Username = 'elsaiya@gmail.com';// SMTP username
	$mail->Password = '********';// SMTP password

	$mail->setFrom($correosucursal, $nomsucursal);//Your application NAME and EMAIL
	$mail->AddReplyTo('no-reply@mycomp.com','no-reply');
	$mail->Subject = 'Nueva Clave de Acceso';//Message subject

	# establecemos un limite de caracteres de anchura
	$mail->WordWrap   = 50; // set word wrap

	# NOTA: Los correos es conveniente enviarlos en formato HTML y Texto para que
	# cualquier programa de correo pueda leerlo.

	# Definimos el contenido HTML del correo
	$contenidoHTML="<head>";
	$contenidoHTML.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
	$contenidoHTML.="</head><body>";
	$contenidoHTML.="<b>Recuperación de Contraseña</b>";
	$contenidoHTML.="<p>$nombres</p>";
	$contenidoHTML.="<p>Su Nueva Contraseña de Acceso es: <b>$pass</b></p>";
	$contenidoHTML.="</body>\n";

	# Definimos el contenido en formato Texto del correo
	$contenidoTexto= " Recuperación de Contraseña";
	$contenidoTexto.="\n\n";
	
	# Indicamos el contenido
	$mail->AltBody=$contenidoTexto; //Text Body
	$mail->MsgHTML($contenidoHTML); //Text body HTML

	$mail->addAddress($email,str_replace(" ", "_",$nombres));// Target email
   $mail->AddAttachment("fotos/logo_principal.png", "img.png");
	#################### VALIDACION DE ENVIO DE CORREO CON PHPMAILER ####################

	if(!$mail->send()) {

	   //Mensaje no pudo ser enviado
	   echo "3";
		exit;

	} else {

		################# ACTUALIZO CLAVE DE USUARIO #################
		$sql = " UPDATE accesos set "
		." password = ? "
		." where "
		." idacceso = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $password);
		$stmt->bindParam(2, $idacceso);

		$idacceso = $id;
		$password = password_hash($pass, PASSWORD_DEFAULT);
		$stmt->execute();
		################# ACTUALIZO CLAVE DE USUARIO #################

	   echo "<span class='fa fa-check-square-o'></span> SU NUEVA CLAVE DE ACCESO LE FUE ENVIADA A SU CORREO ELECTRONICO EXITOSAMENTE";
	   exit;
	}
}	
############################# FUNCION PARA RECUPERAR CLAVE ############################

########################## FUNCION PARA ACTUALIZAR PASSWORD ############################
public function ActualizarPassword()
{
	self::SetNames();
	if(empty($_POST["codigo"]) or empty($_POST["password"]) or empty($_POST["clave"]))
	{
		echo "1";
		exit;
	}
	if(password_hash($_POST["password"], PASSWORD_DEFAULT) == limpiar($_POST["clave"])){
		
		echo "2";
		exit;
	}

	$sql = "UPDATE accesos set "
	." usuario = ?, "
	." password = ? "
	." WHERE "
	." idacceso = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $usuario);
	$stmt->bindParam(2, $password);
	$stmt->bindParam(3, $idacceso);	

	$usuario = limpiar($_POST["usuario"]);
	$password = limpiar(password_hash($_POST["password"], PASSWORD_DEFAULT));
	$idacceso = limpiar(decrypt($_POST["idacceso"]));
	$stmt->execute();

	echo "<span class='fa fa-check-square-o'></span> SU CLAVE DE ACCESO FUE ACTUALIZADA EXITOSAMENTE, SER&Aacute; EXPULSADO DE SU SESI&Oacute;N Y DEBER&Aacute; DE ACCEDER NUEVAMENTE CON SU NUEVA CLAVE";
	?>
	<script>
	function redireccionar(){location.href="logout";}
	setTimeout ("redireccionar()", 3000);
	</script>
	<?php
	exit;
}
########################## FUNCION PARA ACTUALIZAR PASSWORD  ############################

####################### FUNCION RECUPERAR Y ACTUALIZAR PASSWORD ########################



























################################## CLASE USUARIOS #####################################

############################## FUNCION REGISTRAR USUARIOS ##############################
public function RegistrarUsuarios()
{
	self::SetNames();
	if(empty($_POST["dni"]) or empty($_POST["nombres"]) or empty($_POST["sexo"]) or empty($_POST["celular"]) or empty($_POST["direccion"]) or empty($_POST["email"]) or empty($_POST["usuario"]) or empty($_POST["password"]) or empty($_POST["password2"]) or empty($_POST["nivel"]) or empty($_POST["status"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT dni FROM usuarios WHERE dni = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["dni"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "3";
		exit;
	}
	$sql = "SELECT email FROM accesos WHERE email = ? AND tipousuario = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["email"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "4";
		exit;
	}

	$sql = "SELECT usuario FROM accesos WHERE usuario = ? AND tipousuario = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["usuario"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		######################### CODIGO DE USUARIO #########################
		$sql = "SELECT codigo FROM usuarios ORDER BY idusuario DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$id=$row["codigo"];
		}
		if(empty($id))
		{
			$codigo = "U1";

		} else {

			$resto = substr($id, 0, 1);
			$coun = strlen($resto);
			$num     = substr($id, $coun);
			$var     = $num + 1;
			$codigo = "U".$var;
		}
	   ######################### CODIGO DE USUARIO #########################

	   ######################### REGISTRO DE USUARIO #########################
	   $query = "INSERT INTO usuarios values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codigo);
		$stmt->bindParam(2, $documusuario);
		$stmt->bindParam(3, $dni);
		$stmt->bindParam(4, $nombres);
		$stmt->bindParam(5, $sexo);
		$stmt->bindParam(6, $telefono);
		$stmt->bindParam(7, $celular);
		$stmt->bindParam(8, $coddepartamento);
		$stmt->bindParam(9, $codprovincia);
		$stmt->bindParam(10, $direccion);
		$stmt->bindParam(11, $fnacimiento);

		$documusuario = limpiar($_POST['documusuario'] == '' ? "0" : decrypt($_POST['documusuario']));
		$dni = limpiar($_POST["dni"]);
		$nombres = limpiar($_POST["nombres"]);
		$sexo = limpiar($_POST["sexo"]);
		$telefono = limpiar($_POST["telefono"]);
		$celular = limpiar($_POST["celular"]);
		$codprovincia = limpiar($_POST['codprovincia'] == '' ? "0" : decrypt($_POST['codprovincia']));
		$coddepartamento = limpiar($_POST['coddepartamento'] == '' ? "0" : decrypt($_POST['coddepartamento']));
		$direccion = limpiar($_POST["direccion"]);
		$fnacimiento = limpiar($_POST['fnacimiento'] == '' ? "0000-00-00" : date("Y-m-d",strtotime($_POST['fnacimiento'])));
		$stmt->execute();
		######################### REGISTRO DE USUARIO #########################

		###################### REGISTRO DE ACCESOS ######################
		$query = "INSERT INTO accesos values (null, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codigo);
		$stmt->bindParam(2, $usuario);
		$stmt->bindParam(3, $password);
		$stmt->bindParam(4, $nivel);
		$stmt->bindParam(5, $email);
		$stmt->bindParam(6, $status);
		$stmt->bindParam(7, $tipousuario);
		
		$usuario = limpiar($_POST["usuario"]);
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		$nivel = limpiar($_POST["nivel"]);
		$email = limpiar($_POST["email"]);
		$status = limpiar($_POST["status"]);
		$tipousuario = limpiar("1");
		$stmt->execute();
		###################### REGISTRO DE ACCESOS ######################

		################## SUBIR FOTO DE USUARIOS ##################
      //datos del arhivo  
		$nombre_archivo = limpiar(isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "");
	   $tipo_archivo = limpiar(isset($_FILES['imagen']['type']) ? $_FILES['imagen']['type'] : "");
	   $tamano_archivo = limpiar(isset($_FILES['imagen']['size']) ? $_FILES['imagen']['size'] : "");

      //compruebo si las características del archivo son las que deseo  
		if (!empty($_FILES["imagen"]) && (strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<8000000)//1MB 
		{  
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/usuarios/".$nombre_archivo) && rename("fotos/usuarios/".$nombre_archivo,"fotos/usuarios/".$codigo.".jpg"))
			{ 
			## se puede dar un aviso
			}
			## se puede dar otro aviso 
		}
		################## SUBIR FOTO DE USUARIOS ##################

		echo "<span class='fa fa-check-square-o'></span> EL USUARIO HA SIDO REGISTRADO EXITOSAMENTE";
		exit;
	}
	else
	{
		echo "4";
		exit;
	}
}
############################# FUNCION REGISTRAR USUARIOS ###############################

############################# FUNCION LISTAR USUARIOS ################################
public function ListarUsuarios()
{
	self::SetNames();
	$sql = "SELECT 
	usuarios.*,
	accesos.codrelacion,
	accesos.usuario,
	accesos.password,
	accesos.nivel,
	accesos.email,
	accesos.status,
	accesos.tipousuario,
	documentos.documento,
	departamentos.departamento,
	provincias.provincia
	FROM usuarios
	INNER JOIN accesos ON usuarios.codigo = accesos.codrelacion 
	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	LEFT JOIN provincias ON usuarios.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON usuarios.coddepartamento = departamentos.coddepartamento
	GROUP BY usuarios.codigo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
############################## FUNCION LISTAR USUARIOS ################################

############################# FUNCION LISTAR TIPOS USUARIOS ################################
public function ListarUsuariosVendedores()
{
	self::SetNames();
	$sql = "SELECT 
	usuarios.*,
	accesos.codrelacion,
	accesos.usuario,
	accesos.password,
	accesos.nivel,
	accesos.email,
	accesos.status,
	accesos.tipousuario,
	documentos.documento,
	departamentos.departamento,
	provincias.provincia
	FROM usuarios
	INNER JOIN accesos ON usuarios.codigo = accesos.codrelacion 
	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	LEFT JOIN provincias ON usuarios.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON usuarios.coddepartamento = departamentos.coddepartamento
	WHERE accesos.nivel = 'VENDEDOR(A)'
	GROUP BY usuarios.codigo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
############################## FUNCION LISTAR TIPOS USUARIOS ################################

########################## FUNCION BUSQUEDA DE LOGS DE USUARIOS ###############################
public function BusquedaLogs()
{
	self::SetNames();
	$buscar = limpiar($_POST['b']);

	if(empty($buscar)) {
      echo "";
      exit;
   }

   $sql = "SELECT * FROM log WHERE CONCAT(ip, ' ',tiempo, ' ',detalles, ' ',usuario) LIKE '%".$buscar."%' LIMIT 0,60";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0) {

	echo "<center><div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<span class='fa fa-info-circle'></span> NO SE ENCONTRARON REGISTROS PARA TU BUSQUEDA</div></center>";
	exit;
		
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION BUSQUEDA DE LOGS DE USUARIOS ###############################

########################### FUNCION LISTAR LOGS DE USUARIOS ###########################
public function ListarLogs()
{
	self::SetNames();
	$sql = "SELECT * FROM log";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################### FUNCION LISTAR LOGS DE USUARIOS ###########################

############################ FUNCION ID USUARIOS #################################
public function UsuariosPorId()
{
	self::SetNames();
	$sql = "SELECT 
	usuarios.*,
	accesos.codrelacion,
	accesos.usuario,
	accesos.password,
	accesos.nivel,
	accesos.email,
	accesos.status,
	accesos.tipousuario,
	documentos.documento,
	departamentos.departamento,
	provincias.provincia
	FROM usuarios
	INNER JOIN accesos ON usuarios.codigo = accesos.codrelacion 
	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	LEFT JOIN provincias ON usuarios.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON usuarios.coddepartamento = departamentos.coddepartamento
	WHERE usuarios.codigo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codigo"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID USUARIOS #################################

############################ FUNCION ACTUALIZAR USUARIOS ############################
public function ActualizarUsuarios()
{
	self::SetNames();
	if(empty($_POST["dni"]) or empty($_POST["dni"]) or empty($_POST["nombres"]) or empty($_POST["sexo"]) or empty($_POST["celular"]) or empty($_POST["direccion"]) or empty($_POST["email"]) or empty($_POST["usuario"]) or empty($_POST["password"]) or empty($_POST["password2"]) or empty($_POST["nivel"]) or empty($_POST["status"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT * FROM usuarios WHERE codigo != ? AND dni = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codigo"]),$_POST["dni"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "2";
		exit;
	}
	$sql = "SELECT email FROM accesos WHERE codrelacion != ? AND email = ? AND tipousuario = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codigo"]),$_POST["email"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "3";
		exit;
	}

	$sql = "SELECT usuario FROM accesos WHERE codrelacion != ? AND usuario = ? AND tipousuario = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codigo"]),$_POST["usuario"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		######################### ACTUALIZO USUARIO #########################
		$sql = " UPDATE usuarios set "
	   ." documusuario = ?, "
		." dni = ?, "
		." nombres = ?, "
		." sexo = ?, "
		." telefono = ?, "
		." celular = ?, "
		." coddepartamento = ?, "
		." codprovincia = ?, "
		." direccion = ?, "
		." fnacimiento = ? "
		." WHERE "
		." codigo = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $documusuario);
		$stmt->bindParam(2, $dni);
		$stmt->bindParam(3, $nombres);
		$stmt->bindParam(4, $sexo);
		$stmt->bindParam(5, $telefono);
		$stmt->bindParam(6, $celular);
		$stmt->bindParam(7, $coddepartamento);
		$stmt->bindParam(8, $codprovincia);
		$stmt->bindParam(9, $direccion);
		$stmt->bindParam(10, $fnacimiento);
		$stmt->bindParam(11, $codigo);

		$documusuario = limpiar($_POST['documusuario'] == '' ? "0" : decrypt($_POST['documusuario']));
		$dni = limpiar($_POST["dni"]);
		$nombres = limpiar($_POST["nombres"]);
		$sexo = limpiar($_POST["sexo"]);
		$telefono = limpiar($_POST["telefono"]);
		$celular = limpiar($_POST["celular"]);
		$codprovincia = limpiar($_POST['codprovincia'] == '' ? "0" : decrypt($_POST['codprovincia']));
		$coddepartamento = limpiar($_POST['coddepartamento'] == '' ? "0" : decrypt($_POST['coddepartamento']));
		$direccion = limpiar($_POST["direccion"]);
		$fnacimiento = limpiar($_POST['fnacimiento'] == '' ? "0000-00-00" : date("Y-m-d",strtotime($_POST['fnacimiento'])));
		$codigo = limpiar(decrypt($_POST["codigo"]));
		$stmt->execute();
		######################### ACTUALIZO USUARIO #########################

		###################### ACTUALIZO ACCESOS ######################
		$sql = " UPDATE accesos set "
		." usuario = ?, "
		." password = ?, "
		." nivel = ?, "
		." email = ?, "
		." status = ? "
		." WHERE "
		." codrelacion = ? AND tipousuario = 1;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $usuario);
		$stmt->bindParam(2, $password);
		$stmt->bindParam(3, $nivel);
		$stmt->bindParam(4, $email);
		$stmt->bindParam(5, $status);
		$stmt->bindParam(6, $codigo);

		$usuario = limpiar($_POST["usuario"]);
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		$nivel = limpiar($_POST["nivel"]);
		$email = limpiar($_POST["email"]);
		$status = limpiar($_POST["status"]);
		$codigo = limpiar(decrypt($_POST["codigo"]));
		$stmt->execute();
		###################### ACTUALIZO ACCESOS ######################

		################## SUBIR FOTO DE USUARIOS ##################
      //datos del arhivo  
		$nombre_archivo = limpiar(isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "");
	   $tipo_archivo = limpiar(isset($_FILES['imagen']['type']) ? $_FILES['imagen']['type'] : "");
	   $tamano_archivo = limpiar(isset($_FILES['imagen']['size']) ? $_FILES['imagen']['size'] : "");

      //compruebo si las características del archivo son las que deseo  
		if (!empty($_FILES["imagen"]) && (strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<8000000)//1MB 
			{  
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/usuarios/".$nombre_archivo) && rename("fotos/usuarios/".$nombre_archivo,"fotos/usuarios/".$codigo.".jpg"))
			{ 
			## se puede dar un aviso
			}
			## se puede dar otro aviso 
		}
		################## SUBIR FOTO DE USUARIOS ##################

		echo "<span class='fa fa-check-square-o'></span> EL USUARIO HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;
	}
	else
	{
		echo "4";
		exit;
	}
}
############################ FUNCION ACTUALIZAR USUARIOS ############################

############################# FUNCION CAMBIAR STATUS USUARIOS ################################
public function StatusUsuarios()
{
	self::SetNames();
	###################### ACTUALIZO STATUS DE USUARIO ######################
	$sql = "UPDATE accesos set "
	." status = ? "
	." WHERE "
	." codrelacion = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $status);
	$stmt->bindParam(2, $codigo);

	$status = limpiar(decrypt($_GET['status']) == 1 ? 0 : 1);
	$codigo = limpiar(decrypt($_GET["codigo"]));
	$stmt->execute();
	###################### ACTUALIZO STATUS DE USUARIO ######################

	echo "1";
	exit;	
}
############################## FUNCION CAMBIAR STATUS USUARIOS ##############################

############################# FUNCION ELIMINAR USUARIOS ################################
public function EliminarUsuarios()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codigo FROM prestamos WHERE codigo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codigo"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		############# ELIMINO USUARIO #############
		$sql = "DELETE FROM usuarios WHERE codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codigo);
		$codigo = decrypt($_GET["codigo"]);
		$stmt->execute();
		############# ELIMINO USUARIO #############

		############# ELIMINO USUARIO #############
		$sql = "DELETE FROM accesos WHERE codrelacion = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codigo);
		$codigo = decrypt($_GET["codigo"]);
		$stmt->execute();
		############# ELIMINO USUARIO #############

		############# ELIMINO FOTO DE USUARIO #############
		$codigo = decrypt($_GET["codigo"]);
		if (file_exists("fotos/".$codigo.".jpg")){
	   //funcion para eliminar una carpeta con contenido
		$archivos = "fotos/".$codigo.".jpg";		
		unlink($archivos);
		}
		############# ELIMINO FOTO DE USUARIO #############

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
############################## FUNCION ELIMINAR USUARIOS ##############################

############################ FIN DE CLASE USUARIOS ################################
















###################### FUNCION CONFIGURACION GENERAL DEL SISTEMA #######################

######################## FUNCION ID CONFIGURACION DEL SISTEMA #########################
public function ConfiguracionPorId()
{
	self::SetNames();
	$sql = "SELECT 
	configuracion.id,
	configuracion.documsucursal,
	configuracion.cuitsucursal,
	configuracion.nomsucursal,
	configuracion.tlfsucursal,
	configuracion.correosucursal,
	configuracion.codprovincia,
	configuracion.coddepartamento,
	configuracion.direcsucursal,
	configuracion.documencargado,
	configuracion.dniencargado,
	configuracion.nomencargado,
	configuracion.tlfencargado,
	configuracion.codmoneda,
	documentos.documento,
	documentos2.documento AS documento2,
	provincias.provincia,
	departamentos.departamento,
	tiposmoneda.moneda,
	tiposmoneda.siglas,
	tiposmoneda.simbolo
	FROM configuracion 
	LEFT JOIN documentos ON configuracion.documsucursal = documentos.coddocumento
	LEFT JOIN documentos AS documentos2 ON configuracion.documencargado = documentos2.coddocumento  
	LEFT JOIN provincias ON configuracion.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON configuracion.coddepartamento = departamentos.coddepartamento
	LEFT JOIN tiposmoneda ON configuracion.codmoneda = tiposmoneda.codmoneda
	WHERE configuracion.id = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array('1'));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
######################## FUNCION ID CONFIGURACION DEL SISTEMA #########################

######################## FUNCION  ACTUALIZAR CONFIGURACION ##########################
public function ActualizarConfiguracion()
{
	self::SetNames();
	if(empty($_POST["cuitsucursal"]) or empty($_POST["nomsucursal"]) or empty($_POST["tlfsucursal"]) or empty($_POST["correosucursal"]) or empty($_POST["direcsucursal"]) or empty($_POST["dniencargado"]) or empty($_POST["nomencargado"]) or empty($_POST["tlfencargado"]))
	{
		echo "1";
		exit;
	}

	$sql = " UPDATE configuracion set "
	." documsucursal = ?, "
	." cuitsucursal = ?, "
	." nomsucursal = ?, "
	." tlfsucursal = ?, "
	." correosucursal = ?, "
	." codprovincia = ?, "
	." coddepartamento = ?, "
	." direcsucursal = ?, "
	." documencargado = ?, "
	." dniencargado = ?, "
	." nomencargado = ?, "
	." tlfencargado = ?, "
	." codmoneda = ? "
	." WHERE "
	." id = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $documsucursal);
	$stmt->bindParam(2, $cuitsucursal);
	$stmt->bindParam(3, $nomsucursal);
	$stmt->bindParam(4, $tlfsucursal);
	$stmt->bindParam(5, $correosucursal);
	$stmt->bindParam(6, $codprovincia);
	$stmt->bindParam(7, $coddepartamento);
	$stmt->bindParam(8, $direcsucursal);
	$stmt->bindParam(9, $documencargado);
	$stmt->bindParam(10, $dniencargado);
	$stmt->bindParam(11, $nomencargado);
	$stmt->bindParam(12, $tlfencargado);
	$stmt->bindParam(13, $codmoneda);
	$stmt->bindParam(14, $id);

	$documsucursal = limpiar($_POST['documsucursal'] == '' ? "0" : decrypt($_POST['documsucursal']));
	$cuitsucursal = limpiar($_POST["cuitsucursal"]);
	$nomsucursal = limpiar($_POST["nomsucursal"]);
	$tlfsucursal = limpiar($_POST["tlfsucursal"]);
	$correosucursal = limpiar($_POST["correosucursal"]);
	$codprovincia = limpiar($_POST['codprovincia'] == '' ? "0" : decrypt($_POST['codprovincia']));
	$coddepartamento = limpiar($_POST['coddepartamento'] == '' ? "0" : decrypt($_POST['coddepartamento']));
	$direcsucursal = limpiar($_POST["direcsucursal"]);
	$documencargado = limpiar($_POST['documencargado'] == '' ? "0" : decrypt($_POST['documencargado']));
	$dniencargado = limpiar($_POST["dniencargado"]);
	$nomencargado = limpiar($_POST["nomencargado"]);
	$tlfencargado = limpiar($_POST["tlfencargado"]);
	$codmoneda = limpiar(decrypt($_POST['codmoneda']));
	$id = limpiar(decrypt($_POST["id"]));
	$stmt->execute();

	############################## SUBIR LOGO PRINCIPAL #1 ##############################
   //datos del arhivo  
   $nombre_archivo = limpiar(isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "");
	$tipo_archivo = limpiar(isset($_FILES['imagen']['type']) ? $_FILES['imagen']['type'] : "");
	$tamano_archivo = limpiar(isset($_FILES['imagen']['size']) ? $_FILES['imagen']['size'] : "");  
         //compruebo si las características del archivo son las que deseo  
	if (!empty($_FILES["imagen"]) && (strpos($tipo_archivo,'image/png')!==false)&&$tamano_archivo<8000000)//1MB 
	{  
		if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/".$nombre_archivo) && rename("fotos/".$nombre_archivo,"fotos/logo_principal.png"))
		{ 
		 ## se puede dar un aviso
		} 
		## se puede dar otro aviso 
	}
	############################## SUBIR LOGO PRINCIPAL #1 ##############################

   ############################## SUBIR LOGO PDF #1 ##############################
   //datos del arhivo  
   $nombre_archivo2 = limpiar(isset($_FILES['imagen2']['name']) ? $_FILES['imagen2']['name'] : "");
	$tipo_archivo2 = limpiar(isset($_FILES['imagen2']['type']) ? $_FILES['imagen2']['type'] : "");
	$tamano_archivo2 = limpiar(isset($_FILES['imagen2']['size']) ? $_FILES['imagen2']['size'] : "");  
   //compruebo si las características del archivo son las que deseo  
	if (!empty($_FILES["imagen2"]) && (strpos($tipo_archivo2,'image/png')!==false)&&$tamano_archivo2<8000000)//1MB 
	{  
		if (move_uploaded_file($_FILES['imagen2']['tmp_name'], "fotos/".$nombre_archivo2) && rename("fotos/".$nombre_archivo2,"fotos/logo_pdf.png"))
		{ 
		## se puede dar un aviso
		} 
		## se puede dar otro aviso 
	}
	############################## SUBIR LOGO PDF #1 ##############################

	echo "<span class='fa fa-check-square-o'></span> LOS DATOS DE CONFIGURACI&Oacute;N FUERON ACTUALIZADOS EXITOSAMENTE";
	exit;
}
######################## FUNCION  ACTUALIZAR CONFIGURACION #######################

#################### FIN DE FUNCION CONFIGURACION GENERAL DEL SISTEMA ##################
















################################ CLASE TIPOS DE DOCUMENTOS ##############################

########################### FUNCION REGISTRAR TIPO DE DOCUMENTOS ########################
public function RegistrarDocumentos()
{
	self::SetNames();
	if(empty($_POST["documento"]) or empty($_POST["descripcion"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT * FROM documentos WHERE documento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["documento"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = "INSERT INTO documentos values (null, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $documento);
		$stmt->bindParam(2, $descripcion);

		$documento = limpiar($_POST["documento"]);
		$descripcion = limpiar($_POST["descripcion"]);
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL TIPO DE DOCUMENTO HA SIDO REGISTRADO EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
	}
}
############################ FUNCION REGISTRAR TIPO DE DOCUMENTOS ########################

########################## FUNCION LISTAR TIPO DE DOCUMENTOS ################################
public function ListarDocumentos()
{
	self::SetNames();
	$sql = "SELECT * FROM documentos ORDER BY documento ASC";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
######################### FUNCION LISTAR TIPO DE DOCUMENTOS ##########################

######################### FUNCION ID TIPO DE DOCUMENTOS ###############################
public function DocumentosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM documentos WHERE coddocumento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["coddocumento"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION ID TIPO DE DOCUMENTOS #########################

######################### FUNCION ACTUALIZAR TIPO DE DOCUMENTOS ########################
public function ActualizarDocumentos()
{
	self::SetNames();
	if(empty($_POST["coddocumento"]) or empty($_POST["documento"]) or empty($_POST["descripcion"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT documento FROM documentos WHERE coddocumento != ? AND documento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["coddocumento"]),$_POST["documento"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = " UPDATE documentos set "
		." documento = ?, "
		." descripcion = ? "
		." WHERE "
		." coddocumento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $documento);
		$stmt->bindParam(2, $descripcion);
		$stmt->bindParam(3, $coddocumento);

		$documento = limpiar($_POST["documento"]);
		$descripcion = limpiar($_POST["descripcion"]);
		$coddocumento = limpiar(decrypt($_POST["coddocumento"]));
		$stmt->execute();

	   echo "<span class='fa fa-check-square-o'></span> EL TIPO DE DOCUMENTO HA SIDO ACTUALIZADO EXITOSAMENTE";
	   exit;

	} else {

	   echo "2";
	   exit;
   }
}
####################### FUNCION ACTUALIZAR TIPO DE DOCUMENTOS #######################

######################### FUNCION ELIMINAR TIPO DE DOCUMENTOS #########################
public function EliminarDocumentos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT documsucursal FROM configuracion WHERE documsucursal = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["coddocumento"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = "DELETE FROM documentos WHERE coddocumento = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$coddocumento);
		$coddocumento = decrypt($_GET["coddocumento"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
######################## FUNCION ELIMINAR TIPOS DE DOCUMENTOS ###########################

########################### FIN DE CLASE TIPOS DE DOCUMENTOS ###########################















################################ CLASE PROVINCIAS ##################################

########################## FUNCION REGISTRAR PROVINCIAS ###############################
public function RegistrarProvincias()
{
	self::SetNames();
	if(empty($_POST["provincia"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT provincia FROM provincias WHERE provincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["provincia"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = "INSERT INTO provincias values (null, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $provincia);

		$provincia = limpiar($_POST["provincia"]);
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> LA PROVINCIA HA SIDO REGISTRADO EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
   }
}
############################ FUNCION REGISTRAR PROVINCIAS ############################

############################ FUNCION LISTAR PROVINCIAS ################################
public function ListarProvincias()
{
	self::SetNames();
	$sql = "SELECT * FROM provincias";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################### FUNCION LISTAR PROVINCIAS ################################

########################### FUNCION ID PROVINCIAS #################################
public function ProvinciasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM provincias WHERE codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codprovincia"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID PROVINCIAS #################################

############################ FUNCION ACTUALIZAR PROVINCIAS ############################
public function ActualizarProvincias()
{
	self::SetNames();
	if(empty($_POST["codprovincia"]) or empty($_POST["provincia"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT provincia FROM provincias WHERE codprovincia != ? AND provincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codprovincia"]),$_POST["provincia"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = " UPDATE provincias set "
		." provincia = ? "
		." WHERE "
		." codprovincia = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $provincia);
		$stmt->bindParam(2, $codprovincia);

		$provincia = limpiar($_POST["provincia"]);
      $codprovincia = limpiar(decrypt($_POST['codprovincia']));
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> LOS DATOS DE LA PROVINCIA HAN SIDO ACTUALIZADOS EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
	}
}
############################ FUNCION ACTUALIZAR PROVINCIAS ############################

############################ FUNCION ELIMINAR PROVINCIAS ############################
public function EliminarProvincias()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codprovincia FROM departamentos WHERE codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codprovincia"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = "DELETE FROM provincias WHERE codprovincia = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codprovincia);
		$codprovincia = decrypt($_GET["codprovincia"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
############################ FUNCION ELIMINAR PROVINCIAS ##############################

############################## FIN DE CLASE PROVINCIAS ################################












############################### CLASE DEPARTAMENTOS ################################

############################# FUNCION REGISTRAR DEPARTAMENTOS ###########################
public function RegistrarDepartamentos()
{
	self::SetNames();
	if(empty($_POST["departamento"]) or empty($_POST["codprovincia"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT departamento FROM departamentos WHERE departamento = ? AND codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["departamento"],decrypt($_POST["codprovincia"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = "INSERT INTO departamentos values (null, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $departamento);
		$stmt->bindParam(2, $codprovincia);

		$departamento = limpiar($_POST["departamento"]);
		$codprovincia = limpiar(decrypt($_POST['codprovincia']));
		$stmt->execute();

	   echo "<span class='fa fa-check-square-o'></span> EL DEPARTAMENTO HA SIDO REGISTRADO EXITOSAMENTE";
	   exit;

	} else {

		echo "2";
		exit;
   }
}
########################### FUNCION REGISTRAR DEPARTAMENTOS ########################

########################## FUNCION PARA LISTAR DEPARTAMENTOS ##########################
public function ListarDepartamentos()
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos 
	LEFT JOIN provincias ON departamentos.codprovincia = provincias.codprovincia";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
######################### FUNCION PARA LISTAR DEPARTAMENTOS ##########################

########################## FUNCION PARA LISTAR DEPARTAMENTOS DE LA PROVINCIA ##########################
public function ListarDepartamentosAsignados($codprovincia)
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos 
	LEFT JOIN provincias ON departamentos.codprovincia = provincias.codprovincia
	WHERE departamentos.codprovincia = '".limpiar($codprovincia)."'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
######################### FUNCION PARA LISTAR DEPARTAMENTOS DE LA PROVINCIA ##########################

############################ FUNCION ID DEPARTAMENTOS #################################
public function DepartamentosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos 
	LEFT JOIN provincias ON departamentos.codprovincia = provincias.codprovincia 
	WHERE departamentos.coddepartamento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["coddepartamento"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID DEPARTAMENTOS #################################

######################## FUNCION ACTUALIZAR DEPARTAMENTOS ############################
public function ActualizarDepartamentos()
{
	self::SetNames();
	if(empty($_POST["coddepartamento"]) or empty($_POST["departamento"]) or empty($_POST["codprovincia"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT departamento FROM departamentos WHERE coddepartamento != ? AND departamento = ? AND codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["coddepartamento"]),$_POST["departamento"],decrypt($_POST["codprovincia"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = " UPDATE departamentos set "
		." departamento = ?, "
		." codprovincia = ? "
		." WHERE "
		." coddepartamento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $departamento);
		$stmt->bindParam(2, $codprovincia);
		$stmt->bindParam(3, $coddepartamento);

		$departamento = limpiar($_POST["departamento"]);
      $codprovincia = limpiar(decrypt($_POST['codprovincia']));
      $coddepartamento = limpiar(decrypt($_POST['coddepartamento']));
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> LOS DATOS DEL DEPARTAMENTO HAN SIDO ACTUALIZADOS EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
	}
}
############################ FUNCION ACTUALIZAR DEPARTAMENTOS #######################

############################ FUNCION ELIMINAR DEPARTAMENTOS ###########################
public function EliminarDepartamentos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT coddepartamento FROM configuracion WHERE coddepartamento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["coddepartamento"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = "DELETE FROM departamentos WHERE coddepartamento = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$coddepartamento);
		$coddepartamento = decrypt($_GET["coddepartamento"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
########################### FUNCION ELIMINAR DEPARTAMENTOS ############################

###################### FUNCION LISTAR DEPARTAMENTOS POR PROVINCIAS #####################
public function ListarDepartamentosxProvincia() 
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos WHERE codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codprovincia"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<option value='' selected> -- SIN RESULTADOS -- </option>";
		exit;
	}
	else
	{
	while($row = $stmt->fetch())
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
##################### FUNCION LISTAR DEPARTAMENTOS POR PROVINCIAS ######################

################# FUNCION PARA SELECCIONAR DEPARTAMENTOS POR PROVINCIAS #################
public function SeleccionaDepartamento()
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos WHERE codprovincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codprovincia"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<option value=''> -- SIN RESULTADOS -- </option>";
		exit;
	}
	else
	{
		while($row = $stmt->fetch())
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
################# FUNCION PARA SELECCIONAR DEPARTAMENTOS POR PROVINCIAS ################

############################## FIN DE CLASE DEPARTAMENTOS ##############################














############################### CLASE TIPOS DE MONEDAS ##############################

############################ FUNCION REGISTRAR TIPO DE MONEDA ##########################
public function RegistrarTipoMoneda()
{
	self::SetNames();
	if(empty($_POST["moneda"]) or empty($_POST["moneda"]) or empty($_POST["simbolo"]))
	{
		echo "1";
		exit;
	}
	$sql = " SELECT * FROM tiposmoneda WHERE moneda = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["moneda"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = " INSERT INTO tiposmoneda values (null, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $moneda);
		$stmt->bindParam(2, $siglas);
		$stmt->bindParam(3, $simbolo);

		$moneda = limpiar($_POST["moneda"]);
		$siglas = limpiar($_POST["siglas"]);
		$simbolo = limpiar($_POST["simbolo"]);
		$stmt->execute();

	   echo "<span class='fa fa-check-square-o'></span> EL TIPO DE MONEDA HA SIDO REGISTRADO EXITOSAMENTE";
	   exit;

	} else {

	   echo "2";
	   exit;
   }
}
######################### FUNCION REGISTRAR TIPO DE MONEDA #######################

########################## FUNCION LISTAR TIPO DE MONEDA ################################
public function ListarTipoMoneda()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposmoneda";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################### FUNCION LISTAR TIPO DE MONEDA #########################

############################ FUNCION ID TIPO DE MONEDA #################################
public function TipoMonedaPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposmoneda WHERE codmoneda = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmoneda"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID TIPO DE MONEDA #################################

####################### FUNCION ACTUALIZAR TIPO DE MONEDA ###########################
public function ActualizarTipoMoneda()
{
	self::SetNames();
	if(empty($_POST["codmoneda"]) or empty($_POST["moneda"]) or empty($_POST["siglas"]) or empty($_POST["simbolo"]))
	{
		echo "1";
		exit;
	}

	$sql = " SELECT moneda FROM tiposmoneda WHERE codmoneda != ? AND moneda = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codmoneda"]),$_POST["moneda"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = " UPDATE tiposmoneda set "
		." moneda = ?, "
		." siglas = ?, "
		." simbolo = ? "
		." WHERE "
		." codmoneda = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $moneda);
		$stmt->bindParam(2, $siglas);
		$stmt->bindParam(3, $simbolo);
		$stmt->bindParam(4, $codmoneda);

		$moneda = limpiar($_POST["moneda"]);
		$siglas = limpiar($_POST["siglas"]);
		$simbolo = limpiar($_POST["simbolo"]);
		$codmoneda = limpiar(decrypt($_POST["codmoneda"]));
		$stmt->execute();

	   echo "<span class='fa fa-check-square-o'></span> EL TIPO DE MONEDA HA SIDO ACTUALIZADO EXITOSAMENTE";
	   exit;

	} else {

	   echo "2";
	   exit;
	}
}
######################## FUNCION ACTUALIZAR TIPO DE MONEDA ############################

######################### FUNCION ELIMINAR TIPO DE MONEDA ###########################
public function EliminarTipoMoneda()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codmoneda FROM sucursales WHERE codmoneda = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmoneda"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = "DELETE FROM tiposmoneda WHERE codmoneda = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codmoneda);
		$codmoneda = decrypt($_GET["codmoneda"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
########################### FUNCION ELIMINAR TIPOS DE MONEDAS ########################

############################# FIN DE CLASE TIPOS DE MONEDAS #############################















################################ CLASE FORMAS DE PAGOS ##################################

########################## FUNCION REGISTRAR FORMAS DE PAGOS ###############################
public function RegistrarFormasPagos()
{
	self::SetNames();
	if(empty($_POST["formapago"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT formapago FROM formaspagos WHERE formapago = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["formapago"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = "INSERT INTO formaspagos values (null, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $formapago);

		$formapago = limpiar($_POST["formapago"]);
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> LA FORMA DE PAGO HA SIDO REGISTRADA EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
   }
}
############################ FUNCION REGISTRAR FORMAS DE PAGOS ############################

############################ FUNCION LISTAR FORMAS DE PAGOS ################################
public function ListarFormasPagos()
{
	self::SetNames();
	$sql = "SELECT * FROM formaspagos";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################### FUNCION LISTAR FORMAS DE PAGOS ################################

########################### FUNCION ID FORMAS DE PAGOS #################################
public function FormasPagosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM formaspagos WHERE codformapago = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codformapago"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID FORMAS DE PAGOS #################################

############################ FUNCION ACTUALIZAR FORMAS DE PAGOS ############################
public function ActualizarFormasPagos()
{
	self::SetNames();
	if(empty($_POST["codformapago"]) or empty($_POST["formapago"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT formapago FROM formaspagos WHERE codformapago != ? AND formapago = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codformapago"]),$_POST["formapago"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = " UPDATE formaspagos set "
		." formapago = ? "
		." WHERE "
		." codformapago = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $formapago);
		$stmt->bindParam(2, $codformapago);

		$formapago = limpiar($_POST["formapago"]);
      $codformapago = limpiar(decrypt($_POST['codformapago']));
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> LOS DATOS DE LA FORMA DE PAGO HAN SIDO ACTUALIZADOS EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
	}
}
############################ FUNCION ACTUALIZAR FORMAS DE PAGOS ############################

############################ FUNCION ELIMINAR FORMAS DE PAGOS ############################
public function EliminarFormasPagos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codformapago FROM departamentos WHERE codformapago = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codformapago"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$sql = "DELETE FROM formaspagos WHERE codformapago = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codformapago);
		$codformapago = decrypt($_GET["codformapago"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
############################ FUNCION ELIMINAR FORMAS DE PAGOS ##############################

############################## FIN DE CLASE FORMAS DE PAGOS ################################













################################## CLASE CLIENTES ##################################

############################### FUNCION CARGAR CLIENTES ##############################
public function CargarClientes()
{
	self::SetNames();
	if(empty($_FILES["sel_file"]))
	{
		echo "1";
		exit;
	}

   //Aquí es donde seleccionamos nuestro csv
   $fname = $_FILES['sel_file']['name'];
   //echo 'Cargando nombre del archivo: '.$fname.' ';
   $chk_ext = explode(".",$fname);
     
   if(strtolower(end($chk_ext)) == "csv"){

   //OBTENGO ULTIMO CLIENTE REGISTRADO
   $rs = 'SELECT MAX(codcliente) AS codcliente FROM clientes';
   foreach ($this->dbh->query($rs) as $row) {
      $p[] = $row;
   }
   $codcliente = $p[0]['codcliente'] + 1;
   //OBTENGO ULTIMO CLIENTE REGISTRADO

   //si es correcto, entonces damos permisos de lectura para subir
   $filename = $_FILES['sel_file']['tmp_name'];
   $handle = fopen($filename, "r");
   $this->dbh->beginTransaction();
    
   $primera = true;
   while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
   // Evitamos la primer línea
   if ($primera){
      $primera = false;
      continue;
   }
      ####################### REGISTRO CLIENTES #######################
      $query = " INSERT INTO clientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $documcliente);
		$stmt->bindParam(3, $cedcliente);
		$stmt->bindParam(4, $nomcliente);
		$stmt->bindParam(5, $sexocliente);
		$stmt->bindParam(6, $tlfcliente);
		$stmt->bindParam(7, $celcliente);
		$stmt->bindParam(8, $codprovincia);
		$stmt->bindParam(9, $coddepartamento);
		$stmt->bindParam(10, $direccliente);
		$stmt->bindParam(11, $cedreferencia);
		$stmt->bindParam(12, $nomreferencia);
		$stmt->bindParam(13, $celreferencia);

    	$documcliente = limpiar($data[0]);
    	$cedcliente = limpiar($data[1]);
    	$nomcliente = limpiar($data[2]);
    	$sexocliente = limpiar($data[3]);
    	$tlfcliente = limpiar($data[4]);
    	$celcliente = limpiar($data[5]);
    	$codprovincia = limpiar($data[6]);
    	$coddepartamento = limpiar($data[7]);
    	$direccliente = limpiar($data[8]);
    	$cedreferencia = limpiar($data[10]);
    	$nomreferencia = limpiar($data[11]);
    	$celreferencia = limpiar($data[12]);
    	$stmt->execute();
    	####################### REGISTRO CLIENTES #######################

    	###################### REGISTRO DE ACCESOS ######################
		$query = "INSERT INTO tbl_accesos values (null, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $usuario);
		$stmt->bindParam(3, $password);
		$stmt->bindParam(4, $nivel);
		$stmt->bindParam(5, $email);
		$stmt->bindParam(6, $status);
		$stmt->bindParam(7, $tipousuario);
		
		$usuario = limpiar($data[1]);
		$password = password_hash($data[1], PASSWORD_DEFAULT);
		$nivel = limpiar("CLIENTE");
		$email = limpiar($data[9]);
		$status = limpiar($data[13]);
		$tipousuario = limpiar("2");
		$stmt->execute();
		###################### REGISTRO DE ACCESOS ######################

	   $codcliente++;

   }//FIN WHILE
           
      $this->dbh->commit();
      //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
      fclose($handle);
	    
	   echo "<span class='fa fa-check-square-o'></span> LA CARGA MASIVA DE CLIENTES FUE REALIZADA EXITOSAMENTE";
	   exit;    
   }
   else
   {
      //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
      echo "2";
		exit;
   }  
}
############################## FUNCION CARGAR CLIENTES ##############################

############################ FUNCION REGISTRAR CLIENTES ##########################
public function RegistrarClientes()
{
	self::SetNames();
	if(empty($_POST["documcliente"]) or empty($_POST["cedcliente"]) or empty($_POST["nomcliente"]) or empty($_POST["celcliente"]) or empty($_POST["direccliente"]))
	{
		echo "1";
		exit;
	}

	############## CREO CODIGO DE CLIENTE #################
	$sql = "SELECT codcliente FROM clientes ORDER BY idcliente DESC LIMIT 1";
	foreach ($this->dbh->query($sql) as $row){

		$cliente = $row["codcliente"];
	}
	$codcliente = limpiar(empty($cliente) ? "1" : $cliente + 1);
	############## CREO CODIGO DE CLIENTE #################

	$sql = "SELECT cedcliente FROM clientes WHERE cedcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_POST["cedcliente"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
	   ######################### REGISTRO CLIENTE #########################
	   $query = " INSERT INTO clientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $documcliente);
		$stmt->bindParam(3, $cedcliente);
		$stmt->bindParam(4, $nomcliente);
		$stmt->bindParam(5, $sexocliente);
		$stmt->bindParam(6, $tlfcliente);
		$stmt->bindParam(7, $celcliente);
		$stmt->bindParam(8, $codprovincia);
		$stmt->bindParam(9, $coddepartamento);
		$stmt->bindParam(10, $direccliente);
		$stmt->bindParam(11, $cedreferencia);
		$stmt->bindParam(12, $nomreferencia);
		$stmt->bindParam(13, $celreferencia);

		$documcliente = limpiar(decrypt($_POST["documcliente"]));
		$cedcliente = limpiar($_POST['cedcliente']);
		$nomcliente = limpiar($_POST["nomcliente"]);
		$sexocliente = limpiar($_POST["sexocliente"]);
		$tlfcliente = limpiar($_POST["tlfcliente"]);
		$celcliente = limpiar($_POST["celcliente"]);
		$codprovincia = limpiar($_POST['codprovincia'] == '' ? "0" : decrypt($_POST['codprovincia']));
		$coddepartamento = limpiar($_POST['coddepartamento'] == '' ? "0" : decrypt($_POST['coddepartamento']));
		$direccliente = limpiar($_POST["direccliente"]);
		$cedreferencia = limpiar($_POST["cedreferencia"]);
		$nomreferencia = limpiar($_POST["nomreferencia"]);
		$celreferencia = limpiar($_POST["celreferencia"]);
		$stmt->execute();
		######################### REGISTRO CLIENTE #########################

		###################### REGISTRO DE ACCESOS ######################
		$query = "INSERT INTO accesos values (null, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $usuario);
		$stmt->bindParam(3, $password);
		$stmt->bindParam(4, $nivel);
		$stmt->bindParam(5, $email);
		$stmt->bindParam(6, $status);
		$stmt->bindParam(7, $tipousuario);
		
		$usuario = limpiar($_POST["cedcliente"]);
		$password = password_hash($_POST["cedcliente"], PASSWORD_DEFAULT);
		$nivel = limpiar("CLIENTE");
		$email = limpiar($_POST["correocliente"]);
		$status = limpiar("1");
		$tipousuario = limpiar("2");
		$stmt->execute();
		###################### REGISTRO DE ACCESOS ######################
	
	   ###################### SUBIR FOTO DE CLIENTE ######################
	   //datos del arhivo  
	   $nombre_archivo = limpiar(isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "");
		$tipo_archivo = limpiar(isset($_FILES['imagen']['type']) ? $_FILES['imagen']['type'] : "");
		$tamano_archivo = limpiar(isset($_FILES['imagen']['size']) ? $_FILES['imagen']['size'] : "");
	   //compruebo si las características del archivo son las que deseo  
		if (!empty($_FILES["imagen"]) && (strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<8000000)//1MB 
		{
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/clientes/".$nombre_archivo) && rename("fotos/clientes/".$nombre_archivo,"fotos/clientes/".$codcliente.".png"))
			{ 
	      ## se puede dar un aviso
			} 
	      ## se puede dar otro aviso 
		}
	   ###################### SUBIR FOTO DE CLIENTE ######################

		echo "<span class='fa fa-check-square-o'></span> EL CLIENTE HA SIDO REGISTRADO EXITOSAMENTE";
		exit;
	}
	else
	{
		echo "2";
		exit;
	}
}
######################### FUNCION REGISTRAR CLIENTES ###############################

######################## FUNCION LISTAR CLIENTES ###############################
public function ListarClientes()
{
	self::SetNames();
	$sql = "SELECT
	clientes.*,
	accesos.idacceso,
	accesos.codrelacion,
	accesos.usuario,
   accesos.password,
	accesos.nivel,
	accesos.email,
	accesos.status,
	accesos.tipousuario,
	documentos.documento,
	departamentos.departamento,
	provincias.provincia
	FROM clientes 
	INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion 
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	GROUP BY clientes.codcliente";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR CLIENTES ##########################

############################ FUNCION ID CLIENTES #################################
public function ClientesPorId()
{
	self::SetNames();
	$sql = "SELECT
	clientes.*,
	accesos.idacceso,
	accesos.codrelacion,
	accesos.usuario,
   accesos.password,
	accesos.nivel,
	accesos.email,
	accesos.status,
	accesos.tipousuario,
	documentos.documento,
	provincias.provincia,
	departamentos.departamento
	FROM clientes
	INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion  
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	WHERE clientes.codcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcliente"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}		
############################ FUNCION ID CLIENTES #################################

############################ FUNCION ACTUALIZAR CLIENTES ############################
public function ActualizarClientes()
{
	self::SetNames();
	if(empty($_POST["codcliente"]) or empty($_POST["documcliente"]) or empty($_POST["cedcliente"]) or empty($_POST["nomcliente"]) or empty($_POST["celcliente"]) or empty($_POST["direccliente"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT cedcliente FROM clientes  WHERE codcliente != ? AND cedcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcliente"]),limpiar($_POST["cedcliente"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
	   ######################### ACTUALIZO CLIENTE #########################
	   $sql = " UPDATE clientes set "
		." documcliente = ?, "
		." cedcliente = ?, "
		." nomcliente = ?, "
		." sexocliente = ?, "
		." tlfcliente = ?, "
		." celcliente = ?, "
		." codprovincia = ?, "
		." coddepartamento = ?, "
		." direccliente = ?, "
		." cedreferencia = ?, "
		." nomreferencia = ?, "
		." celreferencia = ? "
		." WHERE "
		." codcliente = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $documcliente);
		$stmt->bindParam(2, $cedcliente);
		$stmt->bindParam(3, $nomcliente);
		$stmt->bindParam(4, $sexocliente);
		$stmt->bindParam(5, $tlfcliente);
		$stmt->bindParam(6, $celcliente);
		$stmt->bindParam(7, $codprovincia);
		$stmt->bindParam(8, $coddepartamento);
		$stmt->bindParam(9, $direccliente);
		$stmt->bindParam(10, $cedreferencia);
		$stmt->bindParam(11, $nomreferencia);
		$stmt->bindParam(12, $celreferencia);
		$stmt->bindParam(13, $codcliente);

		$documcliente = limpiar(decrypt($_POST["documcliente"]));
		$cedcliente = limpiar($_POST['cedcliente']);
		$nomcliente = limpiar($_POST["nomcliente"]);
		$sexocliente = limpiar($_POST["sexocliente"]);
		$tlfcliente = limpiar($_POST["tlfcliente"]);
		$celcliente = limpiar($_POST["celcliente"]);
		$coddepartamento = limpiar($_POST['coddepartamento'] == '' ? "0" : decrypt($_POST['coddepartamento']));
		$codprovincia = limpiar($_POST['codprovincia'] == '' ? "0" : decrypt($_POST['codprovincia']));
		$direccliente = limpiar($_POST["direccliente"]);
		$cedreferencia = limpiar($_POST["cedreferencia"]);
		$nomreferencia = limpiar($_POST["nomreferencia"]);
		$celreferencia = limpiar($_POST["celreferencia"]);
		$codcliente = limpiar(decrypt($_POST["codcliente"]));
		$stmt->execute();
		######################### ACTUALIZO CLIENTE #########################

		###################### ACTUALIZO ACCESOS ######################
		$sql = " UPDATE accesos set "
		." email = ? "
		." WHERE "
		." codrelacion = ? AND tipousuario = 2;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $email);
		$stmt->bindParam(2, $codigo);

		$email = limpiar($_POST["correocliente"]);
		$codigo = limpiar(decrypt($_POST["codcliente"]));
		$stmt->execute();
		###################### ACTUALIZO ACCESOS ######################

	   ###################### SUBIR FOTO DE CLIENTE ######################
	   //datos del arhivo  
	   $nombre_archivo = limpiar(isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "");
		$tipo_archivo = limpiar(isset($_FILES['imagen']['type']) ? $_FILES['imagen']['type'] : "");
		$tamano_archivo = limpiar(isset($_FILES['imagen']['size']) ? $_FILES['imagen']['size'] : "");
	   //compruebo si las características del archivo son las que deseo  
		if (!empty($_FILES["imagen"]) && (strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<8000000)//1MB 
		{
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/clientes/".$nombre_archivo) && rename("fotos/clientes/".$nombre_archivo,"fotos/clientes/".$codcliente.".png"))
			{ 
	      ## se puede dar un aviso
			} 
	      ## se puede dar otro aviso 
		}
	   ###################### SUBIR FOTO DE CLIENTE ######################

		echo "<span class='fa fa-check-square-o'></span> EL CLIENTE HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;
	}
	else
	{
		echo "2";
		exit;
	}
}
############################ FUNCION ACTUALIZAR CLIENTES ############################

############################# FUNCION CAMBIAR STATUS CLIENTES ################################
public function StatusClientes()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		###################### ACTUALIZO STATUS DE CLIENTE ######################
		$sql = "UPDATE accesos set "
		." status = ? "
		." WHERE "
		." codrelacion = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statuscliente);
		$stmt->bindParam(2, $codcliente);

		$statuscliente = limpiar(decrypt($_GET['statuscliente']) == 1 ? 0 : 1);
		$codcliente = limpiar(decrypt($_GET["codcliente"]));
		$stmt->execute();
		###################### ACTUALIZO STATUS DE CLIENTE ######################

		echo "1";
		exit;
			
	} else {
		
		echo "2";
		exit;
	}	
}
############################## FUNCION CAMBIAR STATUS CLIENTES ##############################

########################## FUNCION ELIMINAR CLIENTES ########################
public function EliminarClientes()
{
	self::SetNames();
   if($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codcliente FROM prestamos WHERE codcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcliente"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		############# ELIMINO CLIENTE #############
		$sql = "DELETE FROM clientes WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codcliente);
		$codcliente = decrypt($_GET["codcliente"]);
		$stmt->execute();
		############# ELIMINO CLIENTE #############

		############# ELIMINO ACCESOS #############
		$sql = "DELETE FROM accesos WHERE codrelacion = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codigo);
		$codigo = decrypt($_GET["codcliente"]);
		$stmt->execute();
		############# ELIMINO ACCESOS #############

		############# ELIMINO FOTO DE CLIENTE #############
		$codcliente = decrypt($_GET["codcliente"]);
		if (file_exists("fotos/".$codcliente.".jpg")){
	   //funcion para eliminar una carpeta con contenido
		$archivos = "fotos/".$codcliente.".jpg";		
		unlink($archivos);
		}
		############# ELIMINO FOTO DE CLIENTE #############

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
############################ FUNCION ELIMINAR CLIENTES #######################

################################## CLASE CLIENTES ##################################
















################################ CLASE CAJAS DE COBROS ################################

######################### FUNCION REGISTRAR CAJAS DE COBROS #######################
public function RegistrarCajas()
{
	self::SetNames();
	if(empty($_POST["nrocaja"]) or empty($_POST["nomcaja"]) or empty($_POST["codigo"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT nrocaja FROM cajas WHERE nrocaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["nrocaja"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
	   echo "2";
	   exit;
	}
	$sql = "SELECT nomcaja FROM cajas WHERE nomcaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["nomcaja"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "3";
		exit;
	}
		
	$sql = "SELECT codigo FROM cajas WHERE codigo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codigo"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		###################### REGISTRO CAJA ######################
		$query = "INSERT INTO cajas values (null, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $nrocaja);
		$stmt->bindParam(2, $nomcaja);
		$stmt->bindParam(3, $codigo);

		$nrocaja = limpiar($_POST["nrocaja"]);
		$nomcaja = limpiar($_POST["nomcaja"]);
		$codigo = limpiar(decrypt($_POST["codigo"]));
		$stmt->execute();
		###################### REGISTRO CAJA ######################

		echo "<span class='fa fa-check-square-o'></span> LA CAJA HA SIDO REGISTRADA EXITOSAMENTE";
		exit;

	} else {

		echo "4";
		exit;
	}
}
######################### FUNCION REGISTRAR CAJAS DE COBROS #########################

######################### FUNCION LISTAR CAJAS DE COBROS ################################
public function ListarCajas()
{
	self::SetNames();
	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT * FROM cajas INNER JOIN usuarios ON cajas.codigo = usuarios.codigo  
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

		$sql = "SELECT * FROM cajas INNER JOIN usuarios ON cajas.codigo = usuarios.codigo  
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	   WHERE cajas.codigo = '".limpiar($_SESSION["codigo"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
######################### FUNCION LISTAR CAJAS DE COBROS ################################

######################### FUNCION LISTAR CAJAS ABIERTAS ##########################
public function ListarCajasAbiertas()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT * FROM aperturascajas INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
		LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo  
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	   WHERE aperturascajas.statusapertura = 1";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
		   while($row = $stmt->fetch())
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}

	} else if($_SESSION["acceso"] == "cajero") {

	   $sql = "SELECT * FROM aperturascajas INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
		LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo  
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	   WHERE cajas.codigo = ? AND aperturascajas.statusapertura = 1";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_SESSION["codigo"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
		   if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
   }
}
######################### FUNCION LISTAR CAJAS ABIERTAS ##########################

############################ FUNCION ID CAJAS DE COBROS #################################
public function CajasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM cajas 
	INNER JOIN usuarios ON usuarios.codigo = cajas.codigo
	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	WHERE cajas.codcaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcaja"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID CAJAS DE COBROS #################################

#################### FUNCION ACTUALIZAR CAJAS DE COBROS ############################
public function ActualizarCajas()
{
	self::SetNames();
	if(empty($_POST["codcaja"]) or empty($_POST["nrocaja"]) or empty($_POST["nomcaja"]) or empty($_POST["codigo"]))
	{
		echo "1";
		exit;
	}
	$sql = "SELECT nrocaja FROM cajas WHERE codcaja != ? AND nrocaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcaja"]),$_POST["nrocaja"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
	   echo "2";
	   exit;
	}
	$sql = "SELECT nomcaja FROM cajas WHERE codcaja != ? AND nomcaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcaja"]),$_POST["nomcaja"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "3";
		exit;
	}
	$sql = "SELECT codigo FROM cajas WHERE codcaja != ? AND codigo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcaja"]),decrypt($_POST["codigo"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		###################### ACTUALIZO CAJA ######################
		$sql = "UPDATE cajas set "
		." nrocaja = ?, "
		." nomcaja = ?, "
		." codigo = ? "
		." WHERE "
		." codcaja = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $nrocaja);
		$stmt->bindParam(2, $nomcaja);
		$stmt->bindParam(3, $codigo);
		$stmt->bindParam(4, $codcaja);

		$nrocaja = limpiar($_POST["nrocaja"]);
		$nomcaja = limpiar($_POST["nomcaja"]);
		$codigo = limpiar(decrypt($_POST["codigo"]));
		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$stmt->execute();
		###################### ACTUALIZO CAJA ######################

		echo "<span class='fa fa-check-square-o'></span> LA CAJA HA SIDO ACTUALIZADA EXITOSAMENTE";
		exit;

	} else {

		echo "4";
		exit;   
	}
}
#################### FUNCION ACTUALIZAR CAJAS DE COBROS ###########################

####################### FUNCION ELIMINAR CAJAS DE COBROS ########################
public function EliminarCajas()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT codcaja FROM ventas WHERE codcaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcaja"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		###################### ELIMINO CAJA ######################
		$sql = "DELETE FROM cajas WHERE codcaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codcaja);
		$codcaja = decrypt($_GET["codcaja"]);
		$stmt->execute();
		###################### ELIMINO CAJA ######################

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
			
	} else {
		
		echo "3";
		exit;
	}	
}
####################### FUNCION ELIMINAR CAJAS DE COBROS #######################

############################ FIN DE CLASE CAJAS DE COBROS ##############################




























########################## CLASE APERTURAS DE CAJA ###################################

########################## FUNCION PARA REGISTRAR APERTURA DE CAJA ####################
public function RegistrarAperturas()
{
	self::SetNames();
	if(empty($_POST["codcaja"]) or empty($_POST["montoinicial"]) or empty($_POST["fecharegistro"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT codcaja FROM aperturascajas WHERE codcaja = ? AND statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcaja"])));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		######################### REGISTRO APERTURA #########################
		$query = "INSERT INTO aperturascajas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $montoinicial);
		$stmt->bindParam(3, $pagos);
		$stmt->bindParam(4, $prestamos);
		$stmt->bindParam(5, $ingresos);
		$stmt->bindParam(6, $egresos);
		$stmt->bindParam(7, $efectivocaja);
		$stmt->bindParam(8, $dineroefectivo);
		$stmt->bindParam(9, $diferencia);
		$stmt->bindParam(10, $comentarios);
		$stmt->bindParam(11, $fechaapertura);
		$stmt->bindParam(12, $fechacierre);
		$stmt->bindParam(13, $statusapertura);

		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$montoinicial = limpiar($_POST["montoinicial"]);
		$pagos = limpiar("0.00");
		$prestamos = limpiar("0.00");
		$ingresos = limpiar("0.00");
		$egresos = limpiar("0.00");
		$efectivocaja = limpiar("0.00");
		$dineroefectivo = limpiar("0.00");
		$diferencia = limpiar("0.00");
		$comentarios = limpiar('NINGUNO');
		$fechaapertura = limpiar(date("Y-m-d H:i:s",strtotime($_POST['fecharegistro'])));
		$fechacierre = limpiar("0000-00-00 00:00:00");
		$statusapertura = limpiar("1");
		$stmt->execute();
		######################### REGISTRO APERTURA #########################

		echo "<span class='fa fa-check-square-o'></span> LA APERTURA DE CAJA HA SIDO REALIZADA EXITOSAMENTE";
		exit;

	} else {

		echo "2";
		exit;
	}
}
######################## FUNCION PARA REGISTRAR APERTURA DE CAJA #######################

######################## FUNCION PARA LISTAR APERTURAS DE CAJA ########################
public function ListarAperturas()
{
	self::SetNames();
	if($_SESSION['acceso'] == "administrador") {

	   $sql = "SELECT
		aperturascajas.codapertura,
		aperturascajas.codcaja,
		aperturascajas.montoinicial,
		aperturascajas.pagos,
		aperturascajas.prestamos,
		aperturascajas.ingresos,
		aperturascajas.egresos,
		aperturascajas.efectivocaja,
		aperturascajas.dineroefectivo,
		aperturascajas.diferencia,
		aperturascajas.comentarios,
		aperturascajas.fechaapertura,
		aperturascajas.fechacierre,
		aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja,
		cajas.codigo,
		usuarios.dni,
		usuarios.nombres,
		usuarios.telefono,
		documentos.documento,
		IFNULL(pag.montopagado,'0.00') AS montopagado,
		IFNULL(pag.operaciones,'0') AS operaciones,
		IFNULL(pag2.formapago,'0') AS formapago
		FROM aperturascajas 
		INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
		LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   formaspagosxprestamos.codformapago, 
		   (SUM(formaspagosxprestamos.montopagado) - SUM(formaspagosxprestamos.montodevuelto)) AS montopagado,
		   COUNT(formaspagosxprestamos.codformapago) AS operaciones
		   FROM formaspagosxprestamos GROUP BY formaspagosxprestamos.codformapago DESC) pag ON aperturascajas.codapertura = pag.codapertura
	   LEFT JOIN
		   (SELECT
		   formaspagos.codformapago,
		   formaspagos.formapago
		   FROM formaspagos) pag2 ON pag.codformapago = pag2.codformapago
		GROUP BY aperturascajas.codapertura ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

	   $sql = "SELECT
		aperturascajas.codapertura,
		aperturascajas.codcaja,
		aperturascajas.montoinicial,
		aperturascajas.pagos,
		aperturascajas.prestamos,
		aperturascajas.ingresos,
		aperturascajas.egresos,
		aperturascajas.efectivocaja,
		aperturascajas.dineroefectivo,
		aperturascajas.diferencia,
		aperturascajas.comentarios,
		aperturascajas.fechaapertura,
		aperturascajas.fechacierre,
		aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja,
		cajas.codigo,
		usuarios.dni,
		usuarios.nombres,
		usuarios.telefono,
		documentos.documento,
		IFNULL(pag.montopagado,'0.00') AS montopagado,
		IFNULL(pag.operaciones,'0') AS operaciones,
		IFNULL(pag2.formapago,'0') AS formapago
		FROM aperturascajas 
		INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
		LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
	   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   formaspagosxprestamos.codformapago, 
		   (SUM(formaspagosxprestamos.montopagado) - SUM(formaspagosxprestamos.montodevuelto)) AS montopagado,
		   COUNT(formaspagosxprestamos.codformapago) AS operaciones
		   FROM formaspagosxprestamos GROUP BY formaspagosxprestamos.codformapago DESC) pag ON aperturascajas.codapertura = pag.codapertura
	   LEFT JOIN
		   (SELECT
		   formaspagos.codformapago,
		   formaspagos.formapago
		   FROM formaspagos) pag2 ON pag.codformapago = pag2.codformapago
		WHERE cajas.codigo = '".limpiar($_SESSION["codigo"])."'
	   GROUP BY aperturascajas.codapertura ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
######################## FUNCION PARA LISTAR APERTURAS DE CAJA #########################

########################## FUNCION ID APERTURAS DE CAJA #############################
public function AperturasPorId()
{
	self::SetNames();
	$sql = "SELECT
	aperturascajas.codapertura,
	aperturascajas.codcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	aperturascajas.efectivocaja,
	aperturascajas.dineroefectivo,
	aperturascajas.diferencia,
	aperturascajas.comentarios,
	aperturascajas.fechaapertura,
	aperturascajas.fechacierre,
	aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja,
	cajas.codigo,
	usuarios.dni,
	usuarios.nombres,
	usuarios.telefono,
	pag.montopagado,
	pag.operaciones,
	pag2.formapago
	FROM aperturascajas 
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo 
	
	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codapertura,
	   formaspagosxprestamos.codformapago, 
	   (SUM(formaspagosxprestamos.montopagado) - SUM(formaspagosxprestamos.montodevuelto)) AS montopagado,
	   COUNT(formaspagosxprestamos.codformapago) AS operaciones
	   FROM formaspagosxprestamos
	   WHERE formaspagosxprestamos.codapertura = '".decrypt($_GET["numero"])."'
	   GROUP BY formaspagosxprestamos.codformapago DESC) pag ON aperturascajas.codapertura = pag.codapertura

   LEFT JOIN
	   (SELECT
	   formaspagos.codformapago,
	   formaspagos.formapago
	   FROM formaspagos) pag2 ON pag.codformapago = pag2.codformapago

	WHERE aperturascajas.codapertura = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION ID APERTURAS DE CAJA #############################

##################### FUNCION APERTURA POR USUARIO #######################
public function AperturaxUsuario()
{
	self::SetNames();
	$sql = "SELECT * FROM aperturascajas 
   INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
   LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo  
	WHERE usuarios.codigo = ? AND aperturascajas.statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_SESSION["codigo"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
	if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION APERTURA POR USUARIO ###################

######################### FUNCION PARA CERRAR ARQUEO DE CAJA #########################
public function CerrarCaja()
{
	self::SetNames();
	if(empty($_POST["codapertura"]) or empty($_POST["dineroefectivo"]))
	{
		echo "1";
		exit;
	}

	if($_POST["dineroefectivo"] != 0.00 || $_POST["dineroefectivo"] != 0){

		###################### CIERRO APERTURA ######################
		$sql = "UPDATE aperturascajas SET "
		." efectivocaja = ?, "
		." dineroefectivo = ?, "
		." diferencia = ?, "
		." comentarios = ?, "
		." fechacierre = ?, "
		." statusapertura = ? "
		." WHERE "
		." codapertura = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $efectivocaja);
		$stmt->bindParam(2, $dineroefectivo);
		$stmt->bindParam(3, $diferencia);
		$stmt->bindParam(4, $comentarios);
		$stmt->bindParam(5, $fechacierre);
		$stmt->bindParam(6, $statusapertura);
		$stmt->bindParam(7, $codapertura);

		$efectivocaja = limpiar($_POST["efectivocaja"]);
		$dineroefectivo = limpiar($_POST["dineroefectivo"]);
		$diferencia = limpiar($_POST["diferencia"]);
		$comentarios = limpiar($_POST['comentarios']);
		$fechacierre = limpiar(date("Y-m-d H:i:s",strtotime($_POST['fecharegistro2'])));
		$statusapertura = limpiar("0");
		$codapertura = limpiar(decrypt($_POST["codapertura"]));
		$stmt->execute();
		###################### CIERRO APERTURA ######################
  
	   echo "<span class='fa fa-check-square-o'></span> EL CIERRE DE CAJA FUE REALIZADO EXITOSAMENTE <a href='reportepdf?codapertura=".encrypt($codapertura)."&tipo=".encrypt("TICKETCIERRE")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR TICKET</strong></font color></a></div>";

	   echo "<script>VentanaCentrada('reportepdf?numero=".encrypt($codapertura)."&tipo=".encrypt("TICKETCIERRE")."', '', '', '1024', '568', 'true');</script>";
	   exit;
	
	} else {

		echo "2";
		exit;
	}
}
######################### FUNCION PARA CERRAR ARQUEO DE CAJA ######################

###################### FUNCION BUSCAR APERTURAS DE CAJA POR FECHAS ######################
public function BuscarAperturasxFechas() 
{
	self::SetNames();
	$sql = "SELECT
	aperturascajas.codapertura,
	aperturascajas.codcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	aperturascajas.efectivocaja,
	aperturascajas.dineroefectivo,
	aperturascajas.diferencia,
	aperturascajas.comentarios,
	aperturascajas.fechaapertura,
	aperturascajas.fechacierre,
	aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja,
	cajas.codigo,
	usuarios.dni,
	usuarios.nombres,
	usuarios.telefono,
	documentos.documento,
	IFNULL(pag.montopagado,'0.00') AS montopagado,
	IFNULL(pag.operaciones,'0') AS operaciones,
	IFNULL(pag2.formapago,'0') AS formapago
	FROM aperturascajas 
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
   LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codapertura,
	   formaspagosxprestamos.codformapago, 
	   (SUM(formaspagosxprestamos.montopagado) - SUM(formaspagosxprestamos.montodevuelto)) AS montopagado,
	   COUNT(formaspagosxprestamos.codformapago) AS operaciones
	   FROM formaspagosxprestamos GROUP BY formaspagosxprestamos.codformapago DESC) pag ON aperturascajas.codapertura = pag.codapertura
   LEFT JOIN
	   (SELECT
	   formaspagos.codformapago,
	   formaspagos.formapago
	   FROM formaspagos) pag2 ON pag.codformapago = pag2.codformapago
	
	WHERE aperturascajas.codcaja = ? 
	AND DATE_FORMAT(aperturascajas.fechaapertura,'%Y-%m-%d') BETWEEN ? AND ?
	GROUP BY aperturascajas.codapertura ASC";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
	   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
      echo "<center> <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU B&Uacute;SQUEDA REALIZADA</center>";
      echo "</div>";		
	   exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
######################## FUNCION BUSCAR APERTURAS DE CAJA POR FECHAS ####################

########################## CLASE APERTURAS DE CAJA ###################################















############################ CLASE MOVIMIENTOS EN CAJAS ##############################

###################### FUNCION PARA REGISTRAR MOVIMIENTO EN CAJA #######################
public function RegistrarMovimientos()
{
	self::SetNames();
	if(empty($_POST["codcaja"]) or empty($_POST["tipomovimiento"]) or empty($_POST["montomovimiento"]) or empty($_POST["descripcionmovimiento"]) or empty($_POST["mediomovimiento"]))
	{
		echo "1";
		exit;
	}
	elseif($_POST["montomovimiento"] == "" || $_POST["montomovimiento"] == 0 || $_POST["montomovimiento"] == 0.00)
	{
		echo "2";
		exit;
	}
	
	####################### VERIFICO APERTURA DE CAJA #######################
	$sql = "SELECT
	cajas.nrocaja,
	cajas.nomcaja,
	usuarios.dni,
	usuarios.nombres, 
	aperturascajas.codapertura,
	aperturascajas.codcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	IFNULL(pagos.total_efectivo,'0.00') AS total_efectivo
	FROM aperturascajas
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo

	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   SUM(formaspagosxprestamos.montopagado) AS total_efectivo,
		   formaspagos.formapago
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   WHERE formaspagos.formapago = 'EFECTIVO'
		   GROUP BY formaspagosxprestamos.codapertura ASC) pagos ON aperturascajas.codapertura = pagos.codapertura
	WHERE aperturascajas.codcaja = ? AND aperturascajas.statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codcaja"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "1";
		exit;

	} else {
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		$codaperturaBD = $row['codapertura'];
		$codcajaBD     = $row['codcaja'];
		$inicialBD     = $row['montoinicial'];
		$efectivoBD    = $row['total_efectivo'];
		$prestamosBD   = $row['prestamos'];
		$ingresosBD    = $row['ingresos'];
		$egresosBD     = $row['egresos'];
		$TotalCaja     = $inicialBD+$efectivoBD+$ingresosBD-($prestamosBD+$egresosBD);
	}
   ####################### VERIFICO APERTURA DE CAJA #######################

   ################ CREO Nº DE MOVIMIENTO ####################
	$sql = "SELECT numero FROM movimientoscajas
	ORDER BY codmovimiento DESC LIMIT 1";
	foreach ($this->dbh->query($sql) as $row){

		$documento=$row["numero"];
	}
	if(empty($documento))
	{
		$numero = "01";

	} else {

		$num = substr($documento, 0);
		$digitos = $num + 1;
		$numfinal = str_pad($digitos, 2, "0", STR_PAD_LEFT);
		$numero = $numfinal;
	}
   ################ CREO Nº DE MOVIMIENTO ###############

	//REALIZO LA CONDICION SI EL MOVIMIENTO ES UN INGRESO
	if($_POST["tipomovimiento"] == "INGRESO"){ 

		######################## ACTUALIZO DATOS EN ARQUEO ########################
		$sql = " UPDATE aperturascajas SET "
		." ingresos = ? "
		." WHERE "
		." codapertura = ? AND statusapertura = 1;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtEfectivo);
		$stmt->bindParam(2, $codapertura);

		$txtEfectivo = limpiar($_POST["mediomovimiento"] == "EFECTIVO" ? number_format($ingresosBD+$_POST["montomovimiento"], 2, '.', '') : $ingresosBD);
		$codapertura = limpiar($codaperturaBD);
		$stmt->execute();
		######################## ACTUALIZO DATOS EN ARQUEO ########################

		######################## REGISTRO EL MOVIMIENTOS EN CAJA ########################
		$query = "INSERT INTO movimientoscajas values (null, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $numero);
		$stmt->bindParam(2, $codapertura);
		$stmt->bindParam(3, $codcaja);
		$stmt->bindParam(4, $tipomovimiento);
		$stmt->bindParam(5, $descripcionmovimiento);
		$stmt->bindParam(6, $montomovimiento);
		$stmt->bindParam(7, $mediomovimiento);
		$stmt->bindParam(8, $fechamovimiento);

		$codapertura = limpiar($codaperturaBD);
		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$mediomovimiento = limpiar($_POST["mediomovimiento"]);
		$fechamovimiento = limpiar(date("Y-m-d H:i:s",strtotime($_POST['fecharegistro'])));
		$stmt->execute();
		######################## REGISTRO EL MOVIMIENTOS EN CAJA ########################

    //REALIZO LA CONDICION SI EL MOVIMIENTO ES UN EGRESO
	} else {

	   if($_POST["mediomovimiento"]!="EFECTIVO"){

			echo "5";
			exit;

      } else if($_POST["montomovimiento"]>$TotalCaja){

			echo "6";
			exit;

      } else {

		######################## ACTUALIZO DATOS EN ARQUEO ########################
      $sql = "UPDATE aperturascajas SET "
		." egresos = ? "
		." WHERE "
		." codapertura = ? AND statusapertura = 1;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $egresos);
		$stmt->bindParam(2, $codapertura);

		$egresos = number_format($egresosBD+$_POST["montomovimiento"], 2, '.', '');
		$codapertura = limpiar($codaperturaBD);
		$stmt->execute();
		######################## ACTUALIZO DATOS EN ARQUEO ########################

		######################## REGISTRO EL MOVIMIENTOS EN CAJA ########################
		$query = "INSERT INTO movimientoscajas values (null, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $numero);
		$stmt->bindParam(2, $codapertura);
		$stmt->bindParam(3, $codcaja);
		$stmt->bindParam(4, $tipomovimiento);
		$stmt->bindParam(5, $descripcionmovimiento);
		$stmt->bindParam(6, $montomovimiento);
		$stmt->bindParam(7, $mediomovimiento);
		$stmt->bindParam(8, $fechamovimiento);

		$codapertura = limpiar($codaperturaBD);
		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$mediomovimiento = limpiar($_POST["mediomovimiento"]);
		$fechamovimiento = limpiar(date("Y-m-d H:i:s",strtotime($_POST['fecharegistro'])));
		$stmt->execute();
		######################## REGISTRO EL MOVIMIENTOS EN CAJA ########################

	   }
	}

	echo "<span class='fa fa-check-square-o'></span> EL MOVIMIENTO EN CAJA HA SIDO REGISTRADO EXITOSAMENTE";

	echo "<script>VentanaCentrada('reportepdf?numero=".encrypt($numero)."&tipo=".encrypt("TICKETMOVIMIENTO")."', '', '', '1024', '568', 'true');</script>";
	exit;	
}
##################### FUNCION PARA REGISTRAR MOVIMIENTO EN CAJA #######################

###################### FUNCION PARA LISTAR MOVIMIENTO EN CAJA #######################
public function ListarMovimientos()
{
	self::SetNames();
	if($_SESSION['acceso'] == "administrador") {

	   $sql = "SELECT * FROM movimientoscajas 
	   LEFT JOIN aperturascajas ON movimientoscajas.codapertura = aperturascajas.codapertura
	   LEFT JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja
	   LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
		LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

	   $sql = "SELECT * FROM movimientoscajas 
	   LEFT JOIN aperturascajas ON movimientoscajas.codapertura = aperturascajas.codapertura
	   LEFT JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja
	   LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
		LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
		WHERE usuarios.codigo = '".limpiar($_SESSION["codigo"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION PARA LISTAR MOVIMIENTO EN CAJA ######################

########################## FUNCION ID MOVIMIENTO EN CAJA #############################
public function MovimientosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM movimientoscajas 
   LEFT JOIN aperturascajas ON movimientoscajas.codapertura = aperturascajas.codapertura
   LEFT JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja
   LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
   LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	WHERE movimientoscajas.codmovimiento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
	   if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION ID MOVIMIENTO EN CAJA #############################

##################### FUNCION PARA ACTUALIZAR MOVIMIENTOS EN CAJA ##################
public function ActualizarMovimientos()
{
	self::SetNames();
	if(empty($_POST["codmovimiento"]) or empty($_POST["tipomovimiento"]) or empty($_POST["montomovimiento"]) or empty($_POST["mediomovimiento"]) or empty($_POST["codcaja"]))
	{
		echo "1";
		exit;
	}
	elseif($_POST["montomovimiento"] == "" || $_POST["montomovimiento"] == 0 || $_POST["montomovimiento"] == 0.00)
	{
		echo "2";
		exit;
	}
	elseif($_POST["tipomovimiento"] != $_POST["tipomovimientobd"] || $_POST["mediomovimiento"] != $_POST["mediomovimientobd"])
	{
		echo "3";
		exit;
	}

	####################### VERIFICO APERTURA DE CAJA #######################
	$sql = "SELECT
	cajas.nrocaja,
	cajas.nomcaja,
	usuarios.dni,
	usuarios.nombres, 
	aperturascajas.codapertura,
	aperturascajas.codcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	IFNULL(pagos.total_efectivo,'0.00') AS total_efectivo
	FROM aperturascajas
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo

	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   SUM(formaspagosxprestamos.montopagado) AS total_efectivo,
		   formaspagos.formapago
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   WHERE formaspagos.formapago = 'EFECTIVO'
		   GROUP BY formaspagosxprestamos.codapertura ASC) pagos ON aperturascajas.codapertura = pagos.codapertura
	WHERE aperturascajas.codapertura = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codapertura"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "1";
		exit;

	} else {
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		$codaperturaBD = $row['codapertura'];
		$codcajaBD     = $row['codcaja'];
		$inicialBD     = $row['montoinicial'];
		$efectivoBD    = $row['total_efectivo'];
		$prestamosBD   = $row['prestamos'];
		$ingresosBD    = $row['ingresos'];
		$egresosBD     = $row['egresos'];
		$TotalCaja     = $inicialBD+$efectivoBD+$ingresosBD-($prestamosBD+$egresosBD);
	}
   ####################### VERIFICO APERTURA DE CAJA #######################
	
	//REALIZAMOS CALCULO DE CAMPOS
	$numero = limpiar(decrypt($_POST["numero"]));
	$montomovimiento = limpiar($_POST["montomovimiento"]);
	$montomovimientoBD = limpiar($_POST["montomovimientobd"]);
	$totalmovimiento = number_format($montomovimiento-$montomovimientoBD, 2, '.', '');

	if($statusBD == 1) {

	if($_POST["tipomovimiento"] == "INGRESO"){ //REALIZO LA CONDICION SI EL MOVIMIENTO ES UN INGRESO

	   ######################## ACTUALIZO DATOS EN APERTURA ########################
	   $sql = "UPDATE aperturascajas SET "
		." ingresos = ? "
		." WHERE "
		." codapertura = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtEfectivo);
		$stmt->bindParam(2, $codapertura);
		
		$txtEfectivo = limpiar($_POST["mediomovimiento"] == "EFECTIVO" ? number_format($ingresosBD+$totalmovimiento, 2, '.', '') : $ingresosBD);
		$codapertura = limpiar($codaperturaBD);
		$stmt->execute();
		######################## ACTUALIZO DATOS EN APERTURA ########################

	   ############## ACTUALIZO DATOS EN MOVIMIENTO ##############
	   $sql = "UPDATE movimientoscajas SET"
		." codcaja = ?, "
		." tipomovimiento = ?, "
		." descripcionmovimiento = ?, "
		." montomovimiento = ?, "
		." mediomovimiento = ? "
		." WHERE "
		." codmovimiento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $mediomovimiento);
		$stmt->bindParam(6, $codmovimiento);

		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$mediomovimiento = limpiar($_POST["mediomovimiento"]);
		$codmovimiento = limpiar(decrypt($_POST["codmovimiento"]));
		$stmt->execute();
		############## ACTUALIZO DATOS EN MOVIMIENTO ##############

	} else { //REALIZO LA CONDICION SI EL MOVIMIENTO ES UN EGRESO

	   if($_POST["mediomovimiento"] != "EFECTIVO"){

			echo "5";
			exit;

      } else if($totalmovimiento > $TotalCaja){

			echo "6";
			exit;

      } else {

	   ######################## ACTUALIZO DATOS EN APERTURA ########################
      $sql = "UPDATE aperturascajas SET"
		." egresos = ? "
		." WHERE "
		." codapertura = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $egresos);
		$stmt->bindParam(2, $codapertura);

		$egresos = number_format($egresos+$totalmovimiento, 2, '.', '');
		$codapertura = limpiar($codaperturaBD);
		$stmt->execute();
		######################## ACTUALIZO DATOS EN APERTURA ########################

		############## ACTUALIZO DATOS EN MOVIMIENTO ##############
		$sql = "UPDATE movimientoscajas SET"
		." codcaja = ?, "
		." tipomovimiento = ?, "
		." descripcionmovimiento = ?, "
		." montomovimiento = ?, "
		." mediomovimiento = ? "
		." WHERE "
		." codmovimiento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $mediomovimiento);
		$stmt->bindParam(6, $codmovimiento);

		$codcaja = limpiar(decrypt($_POST["codcaja"]));
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$mediomovimiento = limpiar($_POST["mediomovimiento"]);
		$codmovimiento = limpiar($_POST["codmovimiento"]);
		$stmt->execute();
		############## ACTUALIZO DATOS EN MOVIMIENTO ##############

	   }
	}	

	echo "<span class='fa fa-check-square-o'></span> EL MOVIMIENTO EN CAJA HA SIDO ACTUALIZADO EXITOSAMENTE";
   echo "<script>VentanaCentrada('reportepdf?numero=".encrypt($numero)."&tipo=".encrypt("TICKETMOVIMIENTO")."', '', '', '1024', '568', 'true');</script>";
   exit;

	} else {
		   
		echo "7";
		exit;
   }
} 
##################### FUNCION PARA ACTUALIZAR MOVIMIENTOS EN CAJA ####################	

###################### FUNCION PARA ELIMINAR MOVIMIENTOS EN CAJA ######################
public function EliminarMovimientos()
{
	if($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT * FROM movimientoscajas 
	INNER JOIN aperturascajas ON movimientoscajas.codapertura = aperturascajas.codapertura 
	WHERE movimientoscajas.codmovimiento = '".limpiar(decrypt($_GET["codmovimiento"]))."'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	//OBTENEMOS CAMPOS DE MOVIMIENTOS
	$codaperturaBD = $row['codapertura'];
	$tipomovimientoBD = $row['tipomovimiento'];
	$descripcionmovimientoBD = $row['descripcionmovimiento'];
	$montomovimientoBD = $row['montomovimiento'];
	$mediomovimientoBD = $row['mediomovimiento'];
	$fechamovimientoBD = $row['fechamovimiento'];

	//OBTENEMOS CAMPOS DE ARQUEO
	$inicialBD = $row['montoinicial'];
	$efectivo = $row['efectivo'];
	$ingresosBD = $row['ingresos'];
	$egresosBD = $row['egresos'];
	$statusBD = $row['statusapertura'];

   if($statusBD == 1) {

      if($tipomovimientoBD == "INGRESO"){

			############ ACTUALIZAMOS APERTURA EN CAJA ############
			$sql = "UPDATE aperturascajas SET"
			." ingresos = ? "
			." WHERE "
			." codapertura = ? AND statusapertura = 1;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $txtEfectivo);
			$stmt->bindParam(2, $codapertura);

		   $txtEfectivo = limpiar($mediomovimientoBD == "EFECTIVO" ? number_format($ingresosBD-$montomovimientoBD, 2, '.', '') : $ingresos);
		   $codapertura = limpiar($codaperturaBD);
			$stmt->execute();
			############ ACTUALIZAMOS APERTURA EN CAJA ############

      } else {

			############ ACTUALIZAMOS APERTURA EN CAJA ############
			$sql = "UPDATE aperturascajas SET "
			." egresos = ? "
			." WHERE "
			." codapertura = ? AND statusapertura = 1;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $egresos);
			$stmt->bindParam(2, $codapertura);

			$egresos = number_format($egresosBD-$montomovimientoBD, 2, '.', '');
			$codapertura = limpiar($codaperturaBD);
			$stmt->execute();
			############ ACTUALIZAMOS APERTURA EN CAJA ############
      }

		######################## ELIMINO EL MOVIMIENTO EN CAJA ########################
		$sql = "DELETE FROM movimientoscajas WHERE codmovimiento = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codmovimiento);
		$codmovimiento = decrypt($_GET["codmovimiento"]);
		$stmt->execute();
		######################## ELIMINO EL MOVIMIENTO EN CAJA ########################

		echo "1";
		exit;
		   
	} else {
		   
		echo "2";
		exit;
	}
			
	} else {
		
		echo "3";
		exit;
	}	
}
###################### FUNCION PARA ELIMINAR MOVIMIENTOS EN CAJAS ####################

################## FUNCION BUSCAR MOVIMIENTOS DE CAJA POR FECHAS #######################
public function BuscarMovimientosxFechas() 
{
	self::SetNames();
	$sql = "SELECT * FROM movimientoscajas 
   LEFT JOIN aperturascajas ON movimientoscajas.codapertura = aperturascajas.codapertura
   LEFT JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja
   LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
	LEFT JOIN documentos ON usuarios.documusuario = documentos.coddocumento
	WHERE movimientoscajas.codcaja = ? 
	AND DATE_FORMAT(movimientoscajas.fechamovimiento,'%Y-%m-%d') BETWEEN ? AND ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
	   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
      echo "<center> <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU B&Uacute;SQUEDA REALIZADA</center>";
      echo "</div>";		
	   exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
###################### FUNCION BUSCAR MOVIMIENTOS DE CAJA POR FECHAS ###################

############################ CLASE MOVIMIENTOS EN CAJAS ##############################




















############################# CLASE PRESTAMOS ##################################

############################# FUNCION REGISTRAR PRESTAMOS ###############################
public function RegistrarPrestamos()
{
	self::SetNames();
	/*####################### VERIFICO ARQUEO DE CAJA #######################
	$sql = "SELECT * FROM aperturascajas 
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo 
	WHERE usuarios.codigo = ? AND aperturascajas.statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_SESSION["codigo"]));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "1";
		exit;

	} else {
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		$codaperturaBD     = $row['codapertura'];
		$codcajaBD       = $row['codcaja'];
		$pagosBD      = ($row['pagos']== "" ? "0.00" : $row['pagos']);
		$prestamosBD      = ($row['prestamos']== "" ? "0.00" : $row['prestamos']);
		$ingresosBD      = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);
	}*/
   ####################### VERIFICO ARQUEO DE CAJA #######################

	if(empty($_POST["nrodocumento"]) or empty($_POST["montoprestamo"]) or empty($_POST["cuotas"]) or empty($_POST["periodopago"]) or empty($_POST["fechapagocuota"]) or empty($_POST["totalpago"]))
	{
		echo "2";
		exit;
	}

	################### SELECCIONE LOS DATOS DEL CLIENTE ######################
   $sql = "SELECT
	codcliente
   FROM clientes WHERE cedcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_POST['nrodocumento'])));
	$num = $stmt->rowCount();
	if($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$p[] = $row;
	}
   $codcliente = $row['codcliente'];
   ################### SELECCIONE LOS DATOS DEL CLIENTE ######################

	################### CREO CODIGO DE PRESTAMO ###################
	$sql = "SELECT codprestamo FROM prestamos 
	ORDER BY idprestamo DESC LIMIT 1";
	foreach ($this->dbh->query($sql) as $row){

		$prestamo = $row["codprestamo"];
	}
	if(empty($prestamo))
	{
		$codprestamo = "01";

	} else {

		$num = substr($prestamo, 0);
      $dig = $num + 1;
      $codigofinal = str_pad($dig, 2, "0", STR_PAD_LEFT);
      $codprestamo = $codigofinal;
	}
   ################### CREO CODIGO DE PRESTAMO ###################

   ################### CREO CODIGO DE FACTURA ####################
	$sql = "SELECT codfactura FROM prestamos 
	ORDER BY idprestamo DESC LIMIT 1";
	foreach ($this->dbh->query($sql) as $row){

		$factura = $row["codfactura"];
	}
	$tipodocumento = limpiar("FACTURA");
	
	if($tipodocumento == "TICKET") {

      if(empty($factura)){ 
        	$codfactura = '000000001';
      } else {
         $var1       = substr($factura, 0);
         $var2       = strlen($var1);
         $var3       = $var1 + 1;
         $var4       = str_pad($var3, $var2, "0", STR_PAD_LEFT);
         $codfactura = $var4;
      }

	} elseif($tipodocumento == "FACTURA") {

      if(empty($factura)){ 
        	$codfactura = '000000001';
      } else {
         $var1       = substr($factura, 0);
         $var2       = strlen($var1);
         $var3       = $var1 + 1;
         $var4       = str_pad($var3, $var2, "0", STR_PAD_LEFT);
         $codfactura = $var4;
      }
	}
   ################### CREO CODIGO DE FACTURA ####################

   $fecha_actual = date("Y-m-d");
   $fecha1 = date("Y-m-d H:i:s");
	$fecha2 = limpiar(date("Y-m-d",strtotime($_POST['fechapagocuota'])));

   ################### REGISTRO DE PRESTAMO ###################
   $query = "INSERT INTO prestamos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
	$stmt = $this->dbh->prepare($query);
	$stmt->bindParam(1, $codprestamo);
	$stmt->bindParam(2, $codapertura);
	$stmt->bindParam(3, $codcaja);
	$stmt->bindParam(4, $tipodocumento);
	$stmt->bindParam(5, $codfactura);
	$stmt->bindParam(6, $codcliente);
	$stmt->bindParam(7, $montoprestamo);
	$stmt->bindParam(8, $intereses);
	$stmt->bindParam(9, $totalintereses);
	$stmt->bindParam(10, $cuotas);
	$stmt->bindParam(11, $cuotaspagadas);
	$stmt->bindParam(12, $cuotainicial);
	$stmt->bindParam(13, $montoxcuota);
	$stmt->bindParam(14, $periodopago);
	$stmt->bindParam(15, $gastos);
	$stmt->bindParam(16, $totalpago);
	$stmt->bindParam(17, $creditopagado);
	$stmt->bindParam(18, $statusprestamo);
	$stmt->bindParam(19, $statuspagado);
	$stmt->bindParam(20, $fechaprestamo);
	$stmt->bindParam(21, $fechapagocredito);
	$stmt->bindParam(22, $observaciones);
	$stmt->bindParam(23, $codigo);

	$codapertura = limpiar("0");
	$codcaja = limpiar("0");
	$tipodocumento = limpiar("FACTURA");
	$montoprestamo = limpiar($_POST["montoprestamo"]);
   $intereses = limpiar($_POST['intereses']);
	$totalintereses = limpiar($_POST["totalintereses"]);
   $cuotas = limpiar($_POST['cuotas']);
	$cuotaspagadas = limpiar("0");
   $cuotainicial = limpiar("0.00");
   $montoxcuota = limpiar($_POST['montoxcuota']);
   $periodopago = limpiar($_POST['periodopago']);
   $gastos = limpiar("0.00");
   $totalpago = limpiar($_POST["totalpago"]);
   $creditopagado = limpiar("0.00");
   $statusprestamo = limpiar("1");
   $statuspagado = limpiar("0");
   $fechaprestamo = limpiar($fecha1);
   $fechapagocredito = limpiar($fecha2);
	$observaciones = limpiar($_POST["observaciones"]);
	$codigo = limpiar($_SESSION["codigo"]);
	$stmt->execute();
	################### REGISTRO DE PRESTAMO ###################

	$this->dbh->beginTransaction();
   for($i=0;$i<count($_POST['codcuota']);$i++){  //recorro el array
      if (!empty($_POST['codcuota'][$i])) {

	   ################## REGISTRO DETALLES CUOTAS ##################
	   $query = "INSERT INTO detalles_cuotas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
	   $stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcuota);
		$stmt->bindParam(2, $codprestamo);
		$stmt->bindParam(3, $codrecibo);
		$stmt->bindParam(4, $fechapago);
		$stmt->bindParam(5, $saldoinicial);
		$stmt->bindParam(6, $capital);
		$stmt->bindParam(7, $saldofinal);
		$stmt->bindParam(8, $fechapagado);
		$stmt->bindParam(9, $statuspago);
		
		$codcuota     = limpiar($_POST['codcuota'][$i]);
		$codrecibo       = limpiar("0");
		$fechapago    = limpiar($_POST['fechapago'][$i]);
		$saldoinicial = limpiar($_POST['saldoinicial'][$i]);
		$capital      = limpiar($_POST['capital'][$i]);
		$saldofinal   = limpiar($_POST['saldofinal'][$i]);
		$fechapagado  = limpiar($codcuota == 0 ? date("Y-m-d") : "0000-00-00");
		$statuspago   = limpiar($codcuota == 0 ? "1" : "0");
	   $stmt->execute();
	   ################## REGISTRO DETALLES CUOTAS ##################

	   ################## VERIFICA CUOTAS VENCIDAS ##################
	   $sql = " UPDATE detalles_cuotas SET "
		." statuspago = ? "
		." WHERE "
		." codprestamo = '".$codprestamo."' AND fechapago < '".$fecha_actual."' AND statuspago = 0;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statuspago);
		$statuspago = limpiar("2");
		$stmt->execute();
		################## VERIFICA CUOTAS VENCIDAS ##################

	   }//fin if
	}//fin for

	####################### DESTRUYO LA VARIABLE DE SESSION #####################
   $this->dbh->commit();

   echo "<span class='fa fa-check-square-o'></span> EL PRÉSTAMO HA SIDO REGISTRADO EXITOSAMENTE</div>";
   echo "<script>VentanaCentrada('reportepdf?numero=".encrypt($codprestamo)."&tipo=".encrypt($tipodocumento)."', '', '', '1024', '568', 'true');</script>";
   exit;
}
########################### FUNCION REGISTRAR PRESTAMOS ################################

########################### FUNCION BUSQUEDA PRESTAMOS ##########################
public function BusquedaPrestamos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE CONCAT(
		prestamos.codfactura, '',
		if(prestamos.codcaja='0','0',cajas.nrocaja), '',
		if(prestamos.codcaja='0','0',cajas.nomcaja), '',
		prestamos.periodopago, '',
	   prestamos.totalpago, '',
		clientes.cedcliente, '',
		clientes.nomcliente, '') LIKE '%".limpiar($_GET['search_prestamos'])."%'
		AND prestamos.codcliente = '".limpiar($_SESSION["codigo"])."'
		AND prestamos.statusprestamo != 1
		ORDER BY prestamos.codprestamo ASC";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		   echo "<div class='alert alert-danger'>";
			echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
			echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
			echo "</div>";		
			exit;
	   
		} else {
				
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}

	} else {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE CONCAT(
		prestamos.codfactura, '',
		if(prestamos.codcaja='0','0',cajas.nrocaja), '',
		if(prestamos.codcaja='0','0',cajas.nomcaja), '',
		prestamos.periodopago, '',
	   prestamos.totalpago, '',
		clientes.cedcliente, '',
		clientes.nomcliente, '') LIKE '%".limpiar($_GET['search_prestamos'])."%'
		AND prestamos.statusprestamo != 1
		ORDER BY prestamos.codprestamo ASC";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		   echo "<div class='alert alert-danger'>";
			echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
			echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
			echo "</div>";		
			exit;
	   
		} else {
				
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
}
############################ FUNCION BUSQUEDA PRESTAMOS #######################

######################## FUNCION LISTAR PRESTAMOS ###############################
public function ListarPrestamos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "vendedor") {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE prestamos.codigo = '".limpiar($_SESSION["codigo"])."' 
		AND prestamos.statusprestamo != 1 
		AND prestamos.statuspagado = 1
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} elseif ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE prestamos.codcliente = '".limpiar($_SESSION["codigo"])."' 
		AND prestamos.statusprestamo != 1 
		AND prestamos.statuspagado = 1
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

   } else {

	   $sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE prestamos.statusprestamo != 1 
		AND prestamos.statuspagado = 1
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
########################## FUNCION LISTAR PRESTAMOS ##########################

######################## FUNCION LISTAR PRESTAMOS PENDIENTES ###############################
public function ListarPrestamosPendientes()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "vendedor") {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE prestamos.codigo = '".limpiar($_SESSION["codigo"])."' 
		AND (prestamos.statusprestamo = 1 OR prestamos.statusprestamo = 2)
		AND prestamos.statuspagado = 0
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} elseif ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE prestamos.codcliente = '".limpiar($_SESSION["codigo"])."' 
		AND (prestamos.statusprestamo = 1 OR prestamos.statusprestamo = 2)
		AND prestamos.statuspagado = 0
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

   } else {

	   $sql = "SELECT
		prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
	   aperturascajas.statusapertura,
		cajas.nrocaja,
		cajas.nomcaja
		FROM prestamos 
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
	   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
		WHERE (prestamos.statusprestamo = 1 OR prestamos.statusprestamo = 2)
		AND prestamos.statuspagado = 0
		ORDER BY prestamos.codprestamo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
########################## FUNCION LISTAR PRESTAMOS PENDIENTES ##########################

######################## FUNCION LISTAR CUOTAS EN MORA ###############################
public function ListarPrestamosMora()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT
	   prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.codprovincia,
		clientes.coddepartamento,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
	   provincias.provincia,
	   departamentos.departamento,
		detalles_cuotas.codpago,
		detalles_cuotas.codprestamo,
		detalles_cuotas.fechapagado,
		detalles_cuotas.statuspago,
		GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_mora,
		SUM(capital) AS suma_mora,
		COUNT(codcuota) AS cuotas_mora
		FROM detalles_cuotas 
		LEFT JOIN prestamos ON detalles_cuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
		WHERE prestamos.codcliente = '".limpiar($_SESSION["codigo"])."'
		AND prestamos.statusprestamo = 2 
		AND detalles_cuotas.statuspago = 2
		GROUP BY detalles_cuotas.codprestamo";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {
		
	   $sql = "SELECT
	   prestamos.*,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.codprovincia,
		clientes.coddepartamento,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
	   provincias.provincia,
	   departamentos.departamento,
		detalles_cuotas.codpago,
		detalles_cuotas.codprestamo,
		detalles_cuotas.fechapagado,
		detalles_cuotas.statuspago,
		GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_mora,
		SUM(capital) AS suma_mora,
		COUNT(codcuota) AS cuotas_mora
		FROM detalles_cuotas 
		LEFT JOIN prestamos ON detalles_cuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
		WHERE prestamos.statusprestamo = 2 
		AND detalles_cuotas.statuspago = 2
		GROUP BY detalles_cuotas.codprestamo";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION LISTAR CUOTAS EN MORA ##########################

############################ FUNCION ID PRESTAMOS #################################
public function PrestamosPorId()
{
	self::SetNames();
	$sql = "SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.codprovincia,
	clientes.coddepartamento,
	clientes.direccliente,
	clientes.cedreferencia,
	clientes.nomreferencia,
	clientes.celreferencia,
	accesos.email,
	documentos.documento,
	documentos2.documento AS documento_usuario,
	usuarios.documusuario,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia
   FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
	LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo
	LEFT JOIN documentos AS documentos2 ON usuarios.documusuario = documentos2.coddocumento
   WHERE prestamos.codprestamo = ?";
   $stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID PRESTAMOS #################################

########################### FUNCION VER DETALLES CUOTAS ##########################
public function VerDetallesCuotas()
{
	self::SetNames();
	$sql = "SELECT
	codpago,
	codcuota,
	codprestamo,
	fechapago,
	saldoinicial,
	capital,
	saldofinal,
	fechapagado,
	statuspago
	FROM detalles_cuotas
	WHERE codprestamo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"])));
	$num = $stmt->rowCount();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
	return $this->p;
	$this->dbh=null;
}
############################ FUNCION VER DETALLES CUOTAS #######################

############################# FUNCION PARA PORCESAR PRESTAMO ###############################
public function ProcesarPrestamosPendientes()
{
	self::SetNames();
	if($_POST["statuspagado"] == 1){
	####################### VERIFICO APERTURA DE CAJA #######################
	$sql = "SELECT
	cajas.nrocaja,
	cajas.nomcaja,
	usuarios.dni,
	usuarios.nombres, 
	aperturascajas.codapertura,
	aperturascajas.codcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	IFNULL(pagos.total_efectivo,'0.00') AS total_efectivo
	FROM aperturascajas
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo

	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   SUM(formaspagosxprestamos.montopagado) AS total_efectivo,
		   formaspagos.formapago
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   WHERE formaspagos.formapago = 'EFECTIVO'
		   GROUP BY formaspagosxprestamos.codapertura ASC) pagos ON aperturascajas.codapertura = pagos.codapertura
	WHERE usuarios.codigo = ? AND aperturascajas.statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_SESSION["codigo"]));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "1";
		exit;

	} else {
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		$codaperturaBD = $row['codapertura'];
		$codcajaBD     = $row['codcaja'];
		$inicialBD     = $row['montoinicial'];
		$efectivoBD    = $row['total_efectivo'];
		$prestamosBD   = $row['prestamos'];
		$ingresosBD    = $row['ingresos'];
		$egresosBD     = $row['egresos'];
		$TotalCaja         = $inicialBD+$efectivoBD+$ingresosBD-($prestamosBD+$egresosBD);
	}
   ####################### VERIFICO APERTURA DE CAJA #######################
   }

	if(empty($_POST["codprestamo"]) or empty($_POST["codcliente"]))
	{
		echo "2";
		exit;
	}
	elseif($_POST["statuspagado"] == 1 && $_POST["totalpago"] > $TotalCaja){

		echo "3";
		exit;
   }

	################### OBTENGO ESTADO DE PRESTAMO ######################
   $sql = "SELECT statusprestamo FROM prestamos WHERE codprestamo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_POST["codprestamo"])));
	$num = $stmt->rowCount();
	if($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$p[] = $row;
	}
   $estadoBD = $row['statusprestamo'];
   ################### OBTENGO ESTADO DE PRESTAMO ######################

   ################ ACTUALIZO PRESTAMO ################
	$sql = "UPDATE prestamos set "
	." codapertura = ?, "
	." codcaja = ?, "
	." statusprestamo = ?, "
	." statuspagado = ? "
	." WHERE "
	." codprestamo = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $codapertura);
	$stmt->bindParam(2, $codcaja);
	$stmt->bindParam(3, $statusprestamo);
	$stmt->bindParam(4, $statuspagado);
	$stmt->bindParam(5, $codprestamo);

   $codapertura = limpiar($_POST["statuspagado"] == 1 ? $codaperturaBD : "0");
	$codcaja = limpiar($_POST["statuspagado"] == 1 ? $codcajaBD : "0");
	$statusprestamo = limpiar($_SESSION["acceso"] == "administrador" ? $_POST["statusprestamo"] : $estadoBD);
   $statuspagado = limpiar($_POST["statuspagado"]);
	$codprestamo = limpiar(decrypt($_POST["codprestamo"]));
	$stmt->execute();
   ################ ACTUALIZO PRESTAMO ################

   if($_POST["statuspagado"] == 1){

   ################ AGREGAMOS EL MONTO DE PRESTAMO ################
	$sql = "UPDATE aperturascajas set "
	." prestamos = ? "
	." WHERE "
	." codapertura = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $txtPrestamo);
	$stmt->bindParam(2, $codapertura);

	$txtPrestamo = limpiar(number_format($prestamosBD+$_POST["totalpago"], 2, '.', ''));
	$codapertura = limpiar($codaperturaBD);
	$stmt->execute();
   ################ AGREGAMOS EL MONTO DE PRESTAMO ################
   }

   if($_SESSION["acceso"] == "administrador"){

	   if($_POST["statusprestamo"] == 1) { 
	   $estado =  "CONTINUADO PENDIENTE";
	   } elseif($_POST["statusprestamo"] == 2) {
	   $estado =  "SIDO APROBADO";
	   } elseif($_POST["statusprestamo"] == 3) {
	   $estado =  "SIDO RECHAZADO"; 
	   } elseif($_POST["statusprestamo"] == 4) {
	   $estado =  "SIDO CANCELADO"; 
	   }

      echo "<span class='fa fa-check-square-o'></span> EL PRESTAMO HA ".$estado." EXITOSAMENTE</div>";
	   exit;

	} else {

		echo "<span class='fa fa-check-square-o'></span> EL PRESTAMO HA PAGADO AL CLIENTE EXITOSAMENTE</div>";
	   exit;
	}
}
########################### FUNCION PARA PORCESAR PRESTAMO ################################

####################### FUNCION ELIMINAR PRESTAMOS ########################
public function EliminarPrestamos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		####################### OBTENGO DATOS DE PRESTAMO #######################
		$sql = "SELECT  
		codapertura, 
		codcaja, 
		codfactura,
		codcliente,
		totalpago,
		creditopagado
		FROM prestamos WHERE codprestamo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codprestamo"])));
		$num = $stmt->rowCount();

		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[] = $row;
		}
		$codaperturaBD   = $row['codapertura'];
		$codcajaBD       = $row['codcaja'];
		$codfacturaBD    = $row['codfactura'];
		$codclienteBD    = $row['codcliente'];
		$totalpagoBD     = $row['totalpago'];
		$totalpagoBD     = $row['totalpago'];
		$creditopagadoBD = $row['creditopagado'];
		####################### OBTENGO DATOS DE PRESTAMO #######################

		####################### ELIMINO PRESTAMO #######################
		$sql = "DELETE FROM prestamos WHERE codprestamo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codprestamo);
		$codprestamo = decrypt($_GET["codprestamo"]);
		$stmt->execute();
		####################### ELIMINO PRESTAMO #######################

		####################### ELIMINO DETALLE PRESTAMO #######################
		$sql = "DELETE FROM detalles_cuotas WHERE codprestamo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codprestamo);
		$codprestamo = decrypt($_GET["codprestamo"]);
		$stmt->execute();
		####################### ELIMINO DETALLE PRESTAMO #######################

		echo "1";
		exit;

	} else {
		   
		echo "2";
		exit;
	} 
}
####################### FUNCION ELIMINAR PRESTAMOS #######################

###################### FUNCION BUSQUEDA PRESTAMOS POR CAJAS ###########################
public function BuscarPrestamosxCajas() 
{
	self::SetNames();
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE prestamos.codcaja = ? 
	AND DATE_FORMAT(prestamos.fechaprestamo,'%Y-%m-%d') BETWEEN ? AND ? 
	GROUP BY prestamos.codprestamo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION BUSQUEDA PRESTAMOS POR CAJAS ###########################

###################### FUNCION BUSQUEDA PRESTAMOS POR FECHAS ###########################
public function BuscarPrestamosxFechas() 
{
	self::SetNames();
	if(decrypt($_GET['estado_prestamo']) == 6){
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE DATE_FORMAT(prestamos.fechaprestamo,'%Y-%m-%d') BETWEEN ? AND ? 
	GROUP BY prestamos.codprestamo";
	} else {
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE DATE_FORMAT(prestamos.fechaprestamo,'%Y-%m-%d') BETWEEN ? AND ?
	AND statusprestamo = '".decrypt($_GET['estado_prestamo'])."' 
	GROUP BY prestamos.codprestamo";
	}
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION BUSQUEDA PRESTAMOS POR FECHAS ###########################

###################### FUNCION BUSQUEDA PRESTAMOS POR CLIENTES ###########################
public function BuscarPrestamosxClientes() 
{
	self::SetNames();
	if(decrypt($_GET['estado_prestamo']) == 6){
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE prestamos.codcliente = ? 
	GROUP BY prestamos.codprestamo";
	} else {
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE prestamos.codcliente = ?
	AND statusprestamo = '".decrypt($_GET['estado_prestamo'])."' 
	GROUP BY prestamos.codprestamo";
	}
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(limpiar($_GET['codcliente'])));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION BUSQUEDA PRESTAMOS POR CLIENTES ###########################

###################### FUNCION BUSQUEDA PRESTAMOS POR USUARIOS ###########################
public function BuscarPrestamosxUsuarios() 
{
	self::SetNames();
	if(decrypt($_GET['estado_prestamo']) == 6){
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE prestamos.codigo = ? 
	AND DATE_FORMAT(prestamos.fechaprestamo,'%Y-%m-%d') BETWEEN ? AND ? 
	GROUP BY prestamos.codprestamo";
	} else {
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
   aperturascajas.statusapertura,
	cajas.nrocaja,
	cajas.nomcaja
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN aperturascajas ON prestamos.codapertura = aperturascajas.codapertura 
   LEFT JOIN cajas ON prestamos.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON prestamos.codigo = usuarios.codigo 
	WHERE prestamos.codigo = ? 
	AND DATE_FORMAT(prestamos.fechaprestamo,'%Y-%m-%d') BETWEEN ? AND ?
	AND statusprestamo = '".decrypt($_GET['estado_prestamo'])."' 
	GROUP BY prestamos.codprestamo";
	}
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET['codigo'])));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION BUSQUEDA PRESTAMOS POR USUARIOS ###########################

############################# CLASE PRESTAMOS ##################################




















############################# CLASE PAGOS DE PRESTAMOS ##################################

###################### FUNCION BUSQUEDA PRESTAMOS PARA PAGOS POR CLIENTES ###########################
public function BuscarDetallesPrestamosxClientes() 
{
	self::SetNames();
	$sql ="SELECT 
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	documentos.documento 
	FROM prestamos 
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	WHERE prestamos.codcliente = ? 
	AND prestamos.statusprestamo = 2 
	ORDER BY prestamos.codprestamo ASC";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(limpiar($_GET['codcliente'])));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> EL CLIENTE INGRESADO NO TIENE CREDITOS POR PAGAR ACTUALMENTE</center>";
		echo "</div>";		
		exit;
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION BUSQUEDA PRESTAMOS PARA PAGOS POR CLIENTES ###########################

############################# FUNCION REGISTRAR PAGOS ###############################
public function RegistrarPagos()
{
	self::SetNames();
	####################### VERIFICO ARQUEO DE CAJA #######################
	$sql = "SELECT * FROM aperturascajas 
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo 
	WHERE usuarios.codigo = ? AND aperturascajas.statusapertura = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_SESSION["codigo"]));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "1";
		exit;

	} else {
		
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		$codaperturaBD = $row['codapertura'];
		$codcajaBD     = $row['codcaja'];
		$pagosBD       = $row['pagos'];
		$ingresosBD    = $row['ingresos'];
	}
   ####################### VERIFICO ARQUEO DE CAJA #######################

	if(empty($_POST["codprestamo"]) or empty($_POST["nrodocumento"]) or empty($_POST["metodopago"]))
	{
		echo "2";
		exit;
	}
	elseif ($el = !array_filter($_POST['codcuota'])) 
	{
      echo "3";
      exit;
   } 
	
	################### SELECCIONE LOS DATOS DEL CLIENTE ######################
   $sql = "SELECT codcliente FROM clientes WHERE cedcliente = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST['nrodocumento']));
	$num = $stmt->rowCount();
	if($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$p[] = $row;
	}
   $codclienteBD = $row['codcliente'];
   ################### SELECCIONE LOS DATOS DEL CLIENTE ######################

	################### CREO CODIGO DE RECIBO ###################
	$sql = "SELECT codrecibo FROM recibosxpagocuotas 
	ORDER BY idrecibo DESC LIMIT 1";
	foreach ($this->dbh->query($sql) as $row){

		$recibo=$row["codrecibo"];
	}
	if(empty($recibo))
	{
		$codrecibo = "01";

	} else {

		$num = substr($recibo, 0);
      $dig = $num + 1;
      $codigofinal = str_pad($dig, 2, "0", STR_PAD_LEFT);
      $codrecibo = $codigofinal;
	}
   ################### CREO CODIGO DE RECIBO ###################

   ################### CREO NUMERO DE COMPROBANTE ####################
	$sql4 = "SELECT numerorecibo FROM recibosxpagocuotas
	ORDER BY idrecibo DESC LIMIT 1";
	foreach ($this->dbh->query($sql4) as $row4){

		$id = $row4["numerorecibo"];
	}
	if(empty($id))
	{
		$numerorecibo = "000000001";

	} else {

		$var = strlen("");
      $var1 = substr($id, $var);
      $var2 = strlen($var1);
      $var3 = $var1 + 1;
      $var4 = str_pad($var3, $var2, "0", STR_PAD_LEFT);
      $numerorecibo = $var4;
	}
   ################### CREO NUMERO DE COMPROBANTE ####################

	################### REGISTRO DATOS DE RECIBO DE PAGO ###################
	$query = "INSERT INTO recibosxpagocuotas VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
	$stmt = $this->dbh->prepare($query);
	$stmt->bindParam(1,$codrecibo);
	$stmt->bindParam(2,$codprestamo);
	$stmt->bindParam(3,$codapertura);
	$stmt->bindParam(4,$codcaja);
	$stmt->bindParam(5,$numerorecibo);
	$stmt->bindParam(6,$comprobante);
	$stmt->bindParam(7,$totalcuotas);
	$stmt->bindParam(8,$montoxcuota);
	$stmt->bindParam(9,$totalpagado);
	$stmt->bindParam(10,$metodopago);
	$stmt->bindParam(11,$observaciones);
	$stmt->bindParam(12,$fecharecibo);
	$stmt->bindParam(13,$codigo);

	$codprestamo = limpiar(decrypt($_POST["codprestamo"]));
	$codapertura = limpiar($codaperturaBD);
	$codcaja = limpiar($codcajaBD);
	$comprobante = limpiar($_POST["comprobante"]);
	$totalcuotas = limpiar($_POST["total_cuotas"]);
	$montoxcuota = limpiar($_POST["monto_cuota"]);
	$totalpagado = limpiar($_POST["total_pagado"]);
	$metodopago = limpiar(decrypt($_POST["metodopago"]));
	$observaciones = limpiar($_POST["observaciones"]);
	$fecharecibo = limpiar(date("Y-m-d H:i:s"));
	$codigo = limpiar($_SESSION['codigo']);
	$stmt->execute();
	################### REGISTRO DATOS DE RECIBO DE PAGO ###################

	################### REGISTRO FORMA DE PAGO ###################
	$query = "INSERT INTO formaspagosxprestamos VALUES (null, ?, ?, ?, ?, ?, ?, ?);";
	$stmt = $this->dbh->prepare($query);
	$stmt->bindParam(1,$codapertura);
	$stmt->bindParam(2,$codcaja);
	$stmt->bindParam(3,$codrecibo);
	$stmt->bindParam(4,$codprestamo);
	$stmt->bindParam(5,$codformapago);
	$stmt->bindParam(6,$montopagado);
	$stmt->bindParam(7,$montodevuelto);

	$codapertura = limpiar($codaperturaBD);
	$codcaja = limpiar($codcajaBD);
	$codprestamo = limpiar(decrypt($_POST["codprestamo"]));
	$codformapago = limpiar(decrypt($_POST["metodopago"]));
	$montopagado = limpiar($_POST["total_pagado"]);
	$montodevuelto = limpiar("0.00");
	$stmt->execute();
	################### REGISTRO FORMA DE PAGO ###################

	################### REGISTRO PAGOS DE CUOTAS ###################
	$this->dbh->beginTransaction();
   for($i=0;$i<count($_POST['codcuota']);$i++){  //recorro el array
      if (!empty($_POST['codcuota'][$i])) {
	
		$sql = "UPDATE detalles_cuotas SET "
		." codrecibo = ?, "
		." fechapagado = ?, "
		." statuspago = ? "
		." WHERE "
		." codcuota = ? AND codprestamo = ?;
		 ";
	   $stmt = $this->dbh->prepare($sql);
	   $stmt->bindParam(1, $codrecibo);
		$stmt->bindParam(2, $fechapagado);
		$stmt->bindParam(3, $statuspago);
		$stmt->bindParam(4, $codcuota);
		$stmt->bindParam(5, $codprestamo);
		
		$fechapagado = limpiar(date("Y-m-d H:i:s"));
		$statuspago = limpiar("1");
		$codcuota = limpiar($_POST["codcuota"][$i]);
		$codprestamo = limpiar(decrypt($_POST["codprestamo"]));
	   $stmt->execute();

	   }//fin if
	}//fin for
	$this->dbh->commit();
	################### REGISTRO PAGOS DE CUOTAS ###################   

   ####################### SUMO CUOTAS PAGADAS DE PRESTAMO #######################
	$sql = " SELECT
	COUNT(codcuota) AS suma_cuotas,
	SUM(capital) AS suma_capital
	FROM detalles_cuotas 
	WHERE codprestamo = '".limpiar(decrypt($_POST["codprestamo"]))."' 
	AND statuspago = '1'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	$suma_cuotas  = $row['suma_cuotas'];
   $suma_pagados = $row['suma_capital'];
	####################### SUMO CUOTAS PAGADAS DE PRESTAMO #######################

	################ ACTUALIZO CREDITO EN PRESTAMO ################
	if($_POST["cuotas_general"] == $suma_cuotas) {

		$sql = "UPDATE prestamos set "
		." cuotaspagadas = ?, "
		." creditopagado = ?, "
		." statusprestamo = ? "
		." WHERE "
		." codprestamo = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $suma_cuotas);
		$stmt->bindParam(2, $suma_pagados);
		$stmt->bindParam(3, $statusprestamo);
		$stmt->bindParam(4, $codprestamo);

		$statusprestamo = limpiar("5");
	   $codprestamo = limpiar(decrypt($_POST["codprestamo"]));
		$stmt->execute();

	} else {

		$sql = "UPDATE prestamos set "
		." cuotaspagadas = ?, "
		." creditopagado = ? "
		." WHERE "
		." codprestamo = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $suma_cuotas);
		$stmt->bindParam(2, $suma_pagados);
		$stmt->bindParam(3, $codprestamo);

	   $codprestamo = limpiar(decrypt($_POST["codprestamo"]));
		$stmt->execute();
	}
   ################ ACTUALIZO CREDITO EN PRESTAMO ################

   ################ AGREGO PAGO EN APERTURA ################
	$sql = "UPDATE aperturascajas set "
	." pagos = ? "
	." WHERE "
	." codapertura = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $txtPago);
	$stmt->bindParam(2, $codapertura);

	$txtPago = limpiar(number_format($pagosBD+$_POST["total_pagado"], 2, '.', ''));
	$codapertura = limpiar($codaperturaBD);
	$stmt->execute();
   ################ AGREGO PAGO EN APERTURA ################

   echo "<span class='fa fa-check-square-o'></span> EL PAGO DE CUOTAS HA SIDO REGISTRADA EXITOSAMENTE</div>";

   echo "<script>VentanaCentrada('reportepdf?numero=".encrypt($codprestamo)."&numero2=".encrypt($codrecibo)."&tipo=".encrypt("COMPROBANTE")."', '', '', '1024', '568', 'true');</script>";
   exit;
}
########################### FUNCION REGISTRAR PAGOS ################################

######################## FUNCION LISTAR PAGOS ###############################
public function ListarPagos()
{
	self::SetNames();

	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT
	   recibosxpagocuotas.*,
		prestamos.codfactura,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.coddepartamento,
		clientes.codprovincia,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
		cajas.nrocaja,
		cajas.nomcaja,
	   departamentos.departamento,
	   provincias.provincia,
	   pagos.detalles_pagos,
		pagos.detalles_medios,
	   GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
		FROM recibosxpagocuotas
	   LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo 
		LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
		LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

		LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codrecibo,
		   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
		   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   GROUP BY formaspagosxprestamos.codrecibo 
		   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

		GROUP BY recibosxpagocuotas.codrecibo
		ORDER BY recibosxpagocuotas.numerorecibo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

   } else {

   	$sql = "SELECT
	   recibosxpagocuotas.*,
		prestamos.codfactura,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.coddepartamento,
		clientes.codprovincia,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
		cajas.nrocaja,
		cajas.nomcaja,
	   departamentos.departamento,
	   provincias.provincia,
	   pagos.detalles_pagos,
		pagos.detalles_medios,
	   GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
		FROM recibosxpagocuotas
	   LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo 
		LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
		LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

		LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codrecibo,
		   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
		   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   GROUP BY formaspagosxprestamos.codrecibo 
		   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

		WHERE recibosxpagocuotas.codigo = '".limpiar($_SESSION["codigo"])."'
		GROUP BY recibosxpagocuotas.codrecibo
		ORDER BY recibosxpagocuotas.numerorecibo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
########################## FUNCION LISTAR PAGOS ##########################

######################## FUNCION LISTAR PAGOS DEL DIA ###############################
public function ListarPagosxDia()
{
	self::SetNames();

	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT
	   recibosxpagocuotas.*,
		prestamos.codfactura,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.coddepartamento,
		clientes.codprovincia,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
		cajas.nrocaja,
		cajas.nomcaja,
	   departamentos.departamento,
	   provincias.provincia,
	   pagos.detalles_pagos,
		pagos.detalles_medios,
	   GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
		FROM recibosxpagocuotas
	   LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo
		LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
		LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

		LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codrecibo,
		   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
		   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   GROUP BY formaspagosxprestamos.codrecibo 
		   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

		WHERE DATE_FORMAT(recibosxpagocuotas.fecharecibo,'%d-%m-%Y') = '".date("d-m-Y")."'
		GROUP BY recibosxpagocuotas.codrecibo
		ORDER BY recibosxpagocuotas.numerorecibo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

   } else {

   	$sql = "SELECT
	   recibosxpagocuotas.*,
		prestamos.codfactura,
		clientes.documcliente,
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.sexocliente,
		clientes.tlfcliente,
		clientes.celcliente,
		clientes.coddepartamento,
		clientes.codprovincia,
		clientes.direccliente,
		accesos.email,
		documentos.documento,
		usuarios.dni,
		usuarios.nombres,
		cajas.nrocaja,
		cajas.nomcaja,
	   departamentos.departamento,
	   provincias.provincia,
	   pagos.detalles_pagos,
		pagos.detalles_medios,
	   GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
		FROM recibosxpagocuotas
	   LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo
		LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
		LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
		LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
		LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
		LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
		LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

		LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codrecibo,
		   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
		   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   GROUP BY formaspagosxprestamos.codrecibo 
		   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

		WHERE recibosxpagocuotas.codigo = '".limpiar($_SESSION["codigo"])."'
		AND DATE_FORMAT(recibosxpagocuotas.fecharecibo,'%d-%m-%Y') = '".date("d-m-Y")."'
		GROUP BY recibosxpagocuotas.codrecibo
		ORDER BY recibosxpagocuotas.numerorecibo ASC";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
   }
}
########################## FUNCION LISTAR PAGOS DEL DIA ##########################

########################### FUNCION BUSQUEDA PAGOS DE CUOTAS ##########################
public function BusquedaPagos()
{
	self::SetNames();
	$sql = "SELECT
	recibosxpagocuotas.*,
	prestamos.codfactura,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia,
   pagos.detalles_pagos,
	pagos.detalles_medios
	FROM recibosxpagocuotas 
	LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
	LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codrecibo,
	   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
	   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
	   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
	   GROUP BY formaspagosxprestamos.codrecibo 
	   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

	WHERE CONCAT(
	prestamos.codfactura, '',
	clientes.cedcliente, '',
	clientes.nomcliente, '',
	cajas.nrocaja, '',
	cajas.nomcaja, '',
	recibosxpagocuotas.numerorecibo, '',
	recibosxpagocuotas.metodopago, '',
	detalles_medios) LIKE '%".limpiar($_GET['search_pagos'])."%'
	GROUP BY recibosxpagocuotas.codrecibo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS DE CUOTAS #######################

########################### FUNCION PAGOS PORID ##########################
public function PagosporId()
{
	self::SetNames();
	$sql = "SELECT
	recibosxpagocuotas.*,
	prestamos.codfactura,
	prestamos.cuotainicial,
	prestamos.cuotas,
	prestamos.cuotaspagadas,
	prestamos.creditopagado,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia,
   SUM(capital) AS suma_capital,
	COUNT(codcuota) AS cuotas_pagadas,
   pagos.detalles_pagos,
	pagos.detalles_medios
	FROM recibosxpagocuotas
	LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN detalles_cuotas ON prestamos.codprestamo = detalles_cuotas.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
	LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codrecibo,
	   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
	   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
	   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
	   GROUP BY formaspagosxprestamos.codrecibo 
	   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo

	WHERE recibosxpagocuotas.codprestamo = ? 
	AND recibosxpagocuotas.codrecibo = ?
	GROUP BY recibosxpagocuotas.codrecibo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"]),decrypt($_GET["numero2"])));
	$num = $stmt->rowCount();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
	return $this->p;
	$this->dbh=null;
}
############################ FUNCION PAGOS POR ID #######################

########################### FUNCION VER DETALLES CUOTAS ##########################
public function VerDetallesPagos()
{
	self::SetNames();
	$sql = "SELECT
	codpago,
	codcuota,
	codprestamo,
	codrecibo,
	fechapago,
	saldoinicial,
	capital,
	saldofinal,
	fechapagado,
	statuspago
	FROM detalles_cuotas 
	WHERE codprestamo = ? 
	AND codrecibo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["numero"]),decrypt($_GET["numero2"])));
	$num = $stmt->rowCount();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
	return $this->p;
	$this->dbh=null;
}
############################ FUNCION VER DETALLES CUOTAS #######################

########################### FUNCION BUSQUEDA PAGOS POR CAJAS ##########################
public function BuscarPagosxCajas()
{
	self::SetNames();
	$sql = "SELECT
	recibosxpagocuotas.*,
	prestamos.codfactura,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia,
   pagos.detalles_pagos,
	pagos.detalles_medios,
	GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
	FROM recibosxpagocuotas
	LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo 
	LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
	LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codrecibo,
	   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
	   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
	   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
	   GROUP BY formaspagosxprestamos.codrecibo 
	   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo
	WHERE recibosxpagocuotas.codcaja = ? 
	AND DATE_FORMAT(recibosxpagocuotas.fecharecibo,'%Y-%m-%d') BETWEEN ? AND ? 
	GROUP BY recibosxpagocuotas.codrecibo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS POR CAJAS #######################

########################### FUNCION BUSQUEDA PAGOS POR FECHAS ##########################
public function BuscarPagosxFechas()
{
	self::SetNames();
	$sql = "SELECT
	recibosxpagocuotas.*,
	prestamos.codfactura,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia,
   pagos.detalles_pagos,
	pagos.detalles_medios,
	GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
	FROM recibosxpagocuotas
	LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo 
	LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
	LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codrecibo,
	   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
	   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
	   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
	   GROUP BY formaspagosxprestamos.codrecibo 
	   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo
	WHERE DATE_FORMAT(recibosxpagocuotas.fecharecibo,'%Y-%m-%d') BETWEEN ? AND ? 
	GROUP BY recibosxpagocuotas.codrecibo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS POR FECHAS #######################

########################### FUNCION BUSQUEDA PAGOS POR CLIENTES ##########################
public function BuscarPagosxClientes()
{
	self::SetNames();
	$sql = "SELECT
	recibosxpagocuotas.*,
	prestamos.codfactura,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.coddepartamento,
	clientes.codprovincia,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
	usuarios.dni,
	usuarios.nombres,
	cajas.nrocaja,
	cajas.nomcaja,
   departamentos.departamento,
   provincias.provincia,
   pagos.detalles_pagos,
	pagos.detalles_medios,
	GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_pagados
	FROM recibosxpagocuotas
	LEFT JOIN detalles_cuotas ON recibosxpagocuotas.codrecibo = detalles_cuotas.codrecibo 
	LEFT JOIN prestamos ON recibosxpagocuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento 
	LEFT JOIN cajas ON recibosxpagocuotas.codcaja = cajas.codcaja
	LEFT JOIN usuarios ON recibosxpagocuotas.codigo = usuarios.codigo

	LEFT JOIN
	   (SELECT
	   formaspagosxprestamos.idpago,
	   formaspagosxprestamos.codrecibo,
	   GROUP_CONCAT(formaspagos.formapago SEPARATOR ' / ') AS detalles_medios, 
	   GROUP_CONCAT(formaspagosxprestamos.codformapago, '|', formaspagos.formapago, '|',formaspagosxprestamos.montopagado, '|', formaspagosxprestamos.montodevuelto SEPARATOR '<br>') AS detalles_pagos
	   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
	   GROUP BY formaspagosxprestamos.codrecibo 
	   ORDER BY formaspagos.codformapago ASC) pagos ON recibosxpagocuotas.codrecibo = pagos.codrecibo
	WHERE prestamos.codcliente = ? 
	GROUP BY recibosxpagocuotas.codrecibo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_GET["codcliente"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS POR CLIENTES #######################

########################### FUNCION BUSQUEDA PAGOS EN MORA POR FECHAS ##########################
public function BuscarMoraxFechas()
{
	self::SetNames();
	$sql = "SELECT
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.codprovincia,
	clientes.coddepartamento,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
   provincias.provincia,
   departamentos.departamento,
	detalles_cuotas.codpago,
	detalles_cuotas.codprestamo,
	detalles_cuotas.fechapagado,
	detalles_cuotas.statuspago,
	GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_mora,
	SUM(capital) AS suma_mora,
	COUNT(codcuota) AS cuotas_mora
	FROM detalles_cuotas 
	LEFT JOIN prestamos ON detalles_cuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
	WHERE DATE_FORMAT(detalles_cuotas.fechapago,'%Y-%m-%d') BETWEEN ? AND ?
	AND prestamos.statusprestamo = 2 
	AND detalles_cuotas.statuspago = 2
	GROUP BY detalles_cuotas.codprestamo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
	$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS EN MORA POR FECHAS #######################

########################### FUNCION BUSQUEDA PAGOS EN MORA POR CLIENTES ##########################
public function BuscarMoraxClientes()
{
	self::SetNames();
	$sql = "SELECT
	prestamos.*,
	clientes.documcliente,
	clientes.cedcliente,
	clientes.nomcliente,
	clientes.sexocliente,
	clientes.tlfcliente,
	clientes.celcliente,
	clientes.codprovincia,
	clientes.coddepartamento,
	clientes.direccliente,
	accesos.email,
	documentos.documento,
   provincias.provincia,
   departamentos.departamento,
	detalles_cuotas.codpago,
	detalles_cuotas.codprestamo,
	detalles_cuotas.fechapagado,
	detalles_cuotas.statuspago,
	GROUP_CONCAT(fechapago SEPARATOR '<br>') AS meses_mora,
	SUM(capital) AS suma_mora,
	COUNT(codcuota) AS cuotas_mora
	FROM detalles_cuotas 
	LEFT JOIN prestamos ON detalles_cuotas.codprestamo = prestamos.codprestamo
	LEFT JOIN clientes ON prestamos.codcliente = clientes.codcliente
	LEFT JOIN accesos ON clientes.codcliente = accesos.codrelacion
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.codprovincia = provincias.codprovincia
	LEFT JOIN departamentos ON clientes.coddepartamento = departamentos.coddepartamento
	WHERE prestamos.codcliente = ? 
	AND prestamos.statusprestamo = 2 
	AND detalles_cuotas.statuspago = 2
	GROUP BY detalles_cuotas.codprestamo";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_GET["codcliente"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
	   echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-alert-triangle'><path d='M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'></path><line x1='12' y1='9' x2='12' y2='13'></line><line x1='12' y1='17' x2='12.01' y2='17'></line></svg> NO SE ENCONTRARON RESULTADOS PARA TU BUSQUEDA REALIZADA</center>";
		echo "</div>";		
		exit;
   
	} else {
			
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION BUSQUEDA PAGOS EN MORA POR CLIENTES #######################

############################# CLASE PAGOS DE PRESTAMOS ##################################






########################## FUNCION PARA MESES VENCIDOS #################################

#################################### FUNCION VERIFICA MESES VENCIDOS ######################################
public function VerificaMesesVencidos()
{
	self::SetNames();
	$fecha_actual = date('Y-m-d');
	$sql = "UPDATE detalles_cuotas SET "
	." statuspago = ? "
	." WHERE "
	." fechapago < '".$fecha_actual."' AND statuspago = 0;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $statuspago);
	$statuspago = limpiar("2");
	$stmt->execute();				
}
#################################### FUNCION VERIFICA MESES VENCIDOS ######################################

########################## FUNCION PARA MESES VENCIDOS #################################








########################## FUNCION PARA GRAFICOS #################################

########################### FUNCION SUMA DE PRESTAMOS PENDIENTES X MESES #################################
public function SumaPrestamosPendientesxMeses()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_pendiente, 
		SUM(totalpago) totalmes_pendiente
		FROM prestamos 
		WHERE YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 1 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_pendiente, 
		SUM(totalpago) totalmes_pendiente
		FROM prestamos 
		WHERE codigo = '".limpiar($_SESSION["codigo"])."' 
	   AND YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 1 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################### FUNCION SUMA DE PRESTAMOS PENDIENTES X MESES #################################

########################### FUNCION SUMA DE PRESTAMOS APROBADOS X MESES #################################
public function SumaPrestamosAprobadosxMeses()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_aprobado, 
		SUM(totalpago) totalmes_aprobado
		FROM prestamos 
		WHERE YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 2 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_aprobado, 
		SUM(totalpago) totalmes_aprobado
		FROM prestamos 
		WHERE codigo = '".limpiar($_SESSION["codigo"])."' 
	   AND YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 2 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################### FUNCION SUMA DE PRESTAMOS APROBADOS X MESES #################################

########################### FUNCION SUMA DE PRESTAMOS RECHAZADOS X MESES #################################
public function SumaPrestamosRechazadosxMeses()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_rechazado, 
		SUM(totalpago) total_rechazado
		FROM prestamos 
		WHERE YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 3 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_rechazado, 
		SUM(totalpago) total_rechazado
		FROM prestamos 
		WHERE codigo = '".limpiar($_SESSION["codigo"])."' 
	   AND YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 3 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################### FUNCION SUMA DE PRESTAMOS RECHAZADOS X MESES #################################

########################### FUNCION SUMA DE PRESTAMOS PAGADOS X MESES #################################
public function SumaPrestamosPagadosxMeses()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_pagado, 
		SUM(totalpago) total_pagado
		FROM prestamos 
		WHERE YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 5 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else {

		$sql ="SELECT  
		MONTH(fechaprestamo) mes_pagado, 
		SUM(totalpago) total_pagado
		FROM prestamos 
		WHERE codigo = '".limpiar($_SESSION["codigo"])."' 
	   AND YEAR(fechaprestamo) = '".date('Y')."' 
		AND MONTH(fechaprestamo)
		AND statusprestamo = 5 
		GROUP BY MONTH(fechaprestamo) ORDER BY 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################### FUNCION SUMA DE PRESTAMOS PAGADOS X MESES #################################

########################## FUNCION SUMAR PRESTAMOS POR USUARIOS ##########################
public function PrestamosxUsuarios()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {
	   $sql = "SELECT 
	   usuarios.codigo, 
	   usuarios.nombres, 
	   SUM(prestamos.totalpago) as total 
	   FROM (usuarios INNER JOIN prestamos ON usuarios.codigo = prestamos.codigo) 
	   WHERE YEAR(prestamos.fechaprestamo) = '".date('Y')."' 
	   GROUP BY usuarios.codigo";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	} else {
		$sql = "SELECT 
	   usuarios.codigo, 
	   usuarios.nombres, 
	   SUM(prestamos.totalpago) as total 
	   FROM (usuarios INNER JOIN prestamos ON usuarios.codigo = prestamos.codigo) 
	   WHERE codigo = '".limpiar($_SESSION["codigo"])."'
	   AND YEAR(prestamos.fechaprestamo) = '".date('Y')."' 
	   GROUP BY usuarios.codigo";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION SUMAR PRESTAMOS POR USUARIOS #########################

########################## FUNCION DINERO DISPONIBLE EN CAJAS ##########################
public function DineroDisponiblexAperturas()
{
	self::SetNames();
   $sql = "SELECT 
   cajas.nrocaja,
	cajas.nomcaja,
	aperturascajas.montoinicial,
	aperturascajas.pagos,
	aperturascajas.prestamos,
	aperturascajas.ingresos,
	aperturascajas.egresos,
	IFNULL(pagos.total_efectivo,'0.00') AS total_efectivo,
	SUM(aperturascajas.montoinicial+aperturascajas.ingresos+IFNULL(pagos.total_efectivo,'0.00')-(aperturascajas.prestamos-aperturascajas.egresos)) AS total_general
	FROM aperturascajas
	INNER JOIN cajas ON aperturascajas.codcaja = cajas.codcaja 
	INNER JOIN usuarios ON cajas.codigo = usuarios.codigo

	   LEFT JOIN
		   (SELECT
		   formaspagosxprestamos.idpago,
		   formaspagosxprestamos.codapertura,
		   SUM(formaspagosxprestamos.montopagado) AS total_efectivo,
		   formaspagos.formapago
		   FROM formaspagosxprestamos LEFT JOIN formaspagos ON formaspagosxprestamos.codformapago = formaspagos.codformapago
		   WHERE formaspagos.formapago = 'EFECTIVO'
		   GROUP BY formaspagosxprestamos.codapertura ASC) pagos ON aperturascajas.codapertura = pagos.codapertura

   WHERE aperturascajas.statusapertura = 1 
   GROUP BY cajas.codcaja";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION DINERO DISPONIBLE EN CAJAS #########################

##################### FUNCION PARA CONTAR CUOTAS VENCIDAS ############################
public function ContarRegistros()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "vendedor") { 

		$sql = "SELECT
		(SELECT COUNT(*) FROM clientes) as clientes,
		(SELECT COUNT(*) FROM prestamos WHERE codigo = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 1) as pendientes,
		(SELECT COUNT(*) FROM prestamos WHERE codigo = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 2) as aprobados,
		(SELECT COUNT(*) FROM prestamos WHERE codigo = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 3) as rechazados,
		(SELECT COUNT(*) FROM prestamos WHERE codigo = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 4) as cancelados,
		(SELECT COUNT(*) FROM prestamos WHERE codigo = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 2 AND statusprestamo = 5) as pagados,
		(SELECT COUNT(detalles_cuotas.codcuota) FROM prestamos 
		LEFT JOIN detalles_cuotas ON prestamos.codprestamo = detalles_cuotas.codprestamo
		WHERE prestamos.statusprestamo = 2 AND detalles_cuotas.statuspago = 2) as vencidos";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} elseif ($_SESSION['acceso'] == "cliente") {

		$sql = "SELECT
		(SELECT COUNT(*) FROM clientes) as clientes,
		(SELECT COUNT(*) FROM prestamos WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 1) as pendientes,
		(SELECT COUNT(*) FROM prestamos WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 2) as aprobados,
		(SELECT COUNT(*) FROM prestamos WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 3) as rechazados,
		(SELECT COUNT(*) FROM prestamos WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 4) as cancelados,
		(SELECT COUNT(*) FROM prestamos WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND statusprestamo = 2 AND statusprestamo = 5) as pagados,
		(SELECT COUNT(detalles_cuotas.codcuota) FROM prestamos 
		LEFT JOIN detalles_cuotas ON prestamos.codprestamo = detalles_cuotas.codprestamo
		WHERE codcliente = '".limpiar($_SESSION["codigo"])."' AND prestamos.statusprestamo = 2 AND detalles_cuotas.statuspago = 2) as vencidos";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;

	} else { 

		$sql = "SELECT
		(SELECT COUNT(*) FROM clientes) as clientes,
		(SELECT COUNT(*) FROM prestamos WHERE statusprestamo = 1) as pendientes,
		(SELECT COUNT(*) FROM prestamos WHERE statusprestamo = 2) as aprobados,
		(SELECT COUNT(*) FROM prestamos WHERE statusprestamo = 3) as rechazados,
		(SELECT COUNT(*) FROM prestamos WHERE statusprestamo = 4) as cancelados,
		(SELECT COUNT(*) FROM prestamos WHERE statusprestamo = 5) as pagados,
		(SELECT COUNT(detalles_cuotas.codcuota) FROM prestamos 
		LEFT JOIN detalles_cuotas ON prestamos.codprestamo = detalles_cuotas.codprestamo WHERE prestamos.statusprestamo = 2 AND detalles_cuotas.statuspago = 2) as vencidos";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
###################### FUNCION PARA CONTAR CUOTAS VENCIDAS ##################

########################## FUNCION PARA GRAFICOS #################################

}
############## TERMINA LA CLASE LOGIN ######################
?>