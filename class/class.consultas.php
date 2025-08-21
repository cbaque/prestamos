<?php
session_start();
require_once("classconexion.php");

class conectorDB extends Db
{
	public function __construct()
    {
        parent::__construct();
    } 	
	
	public function EjecutarSentencia($consulta, $valores = array()){  //funcion principal, ejecuta todas las consultas
	$resultado = false;
		
		if($statement = $this->dbh->prepare($consulta)){  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //inserto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[substr($parametro,1)]);
				}
			}
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
					return false;
				}
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->dbh = null; //cerramos la conexión
	} /// Termina funcion consultarBD
}/// Termina clase conectorDB

class Json
{
	private $json;

	################################ BUSQUEDA DE CLIENTES ################################
	public function BuscaClientesActivos($filtro){

		$consulta = "SELECT 
		CONCAT(if(clientes.documcliente='0','DOC.',documentos.documento), ': ',clientes.cedcliente, ': ',clientes.nomcliente) as label,
		clientes.codcliente, 
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.tlfcliente,
		clientes.celcliente,
		accesos.email,
	    accesos.status
		FROM
		clientes
	    INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion 
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		WHERE CONCAT(clientes.cedcliente, '',clientes.nomcliente) LIKE '%".$filtro."%' 
		AND accesos.status = 1 
		ORDER BY clientes.codcliente ASC LIMIT 0,10";
		$conexion = new conectorDB;
		$this->json = $conexion->EjecutarSentencia($consulta);
		return $this->json;
	}
	################################ BUSQUEDA DE CLIENTES ################################

	################################ BUSQUEDA DE CLIENTES ################################
	public function BuscaClientesGeneral($filtro){

		$consulta = "SELECT 
		CONCAT(if(clientes.documcliente='0','DOC.',documentos.documento), ': ',clientes.cedcliente, ': ',clientes.nomcliente) as label,
		clientes.codcliente, 
		clientes.cedcliente,
		clientes.nomcliente,
		clientes.tlfcliente,
		clientes.celcliente,
		accesos.email,
	    accesos.status
		FROM
		clientes
	    INNER JOIN accesos ON clientes.codcliente = accesos.codrelacion 
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		WHERE CONCAT(clientes.cedcliente, '',clientes.nomcliente) LIKE '%".$filtro."%' 
		ORDER BY clientes.codcliente ASC LIMIT 0,10";
		$conexion = new conectorDB;
		$this->json = $conexion->EjecutarSentencia($consulta);
		return $this->json;
	}
	################################ BUSQUEDA DE CLIENTES ################################

}/// TERMINA CLASE  ///
?>