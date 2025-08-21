<?php
include("timezones_class.php");
include_once('funciones_basicas.php');
class Db{
        
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "softprestamos";
    private $_pdoStat;
    protected $p; 
    protected $dbh; 
    
    public function __construct(){
        if(!isset($this->dbh)){
            // Connect to the database
            $arrOptions = array(
            PDO::ATTR_EMULATE_PREPARES   => FALSE, 
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_PERSISTENT         => true,
            );
            try{
                date_default_timezone_set('America/Caracas');
                setlocale(LC_ALL,"es_VE.UTF-8","es_VE","esp");
                $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword,$arrOptions);
                $this->dbh = $conn;
            }catch(PDOException $e){
                die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }
    public function SetNames()
    {
        return $this->dbh->query("SET NAMES 'utf8'");
    }

    public function execute($query = '', $return_rows = 0, $array_valores = array(), $array_tipos= array()){
           $this->_pdoStat = $this->dbh->prepare($query);
           foreach($array_valores as $posicion => &$valor){
                   $tipo_var = 'STR' == $array_tipos[$posicion] ? PDO::PARAM_STR : PDO::PARAM_INT;
                   $this->_pdoStat->bindParam($posicion+1, $valor, $tipo_var);
           }
           $result = $this->_pdoStat->execute();
           if( 0 < $return_rows && $result){
                 return $return_rows == 2 ? $this->_pdoStat->fetch() : $this->_pdoStat->fetchAll();
           }
           return $result;
    }
     
    public function mostrar_error(){
        $array = $this->_pdoStat->errorInfo();
        var_dump($array);
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }
    
    public function disconnect () {
        $this->p->closeCursor();
        $this->p = null;
        $this->dbh = null;
    }
}   
?>