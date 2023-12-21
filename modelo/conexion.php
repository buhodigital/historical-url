<?php 
abstract class Conexion{
	#Datos para acceso a la base de datos 
	private static $db_host="localhost";
	private static $db_user="tusuario";
	private static $db_pass="tupassword";
	protected $db_name= "tubasededatos";
	protected $query;
	protected $rows=array();
	private $conn;
	public $mensaje="Correcto";

	#Metodos para manipulación de informacion
	protected function get(){}
	protected function set(){}
	protected function edit(){}
	protected function delete(){}

	#Metodo para la conexión a la base de datos
	private function open_connection(){
		$this->conn=new mysqli(self::$db_host, self::$db_user, self::$db_pass, $this->db_name);
	}

	#Metodo para desconexion de la base de datos
	private function close_connection(){
		$this->conn->close();
	}

    # Ejecutar un query simple del tipo INSERT, DELETE, UPDATE
    protected function execute_single_query(){
      $this->open_connection();
      $consulta=$this->conn->query($this->query);
      $this->close_connection();
      return $consulta; // el valor si la consulta es exitosa es  "=== TRUE"
    }
    # Traer resultados de una consulta en un Array
    protected function get_results_from_query(){
      $this->open_connection();
      $result=$this->conn->query($this->query);
      while($this->rows[]=$result->fetch_assoc());
      $result->close();
      $this->close_connection();
      array_pop($this->rows);
    }

	protected function verify(){
		// Check connection
		$this->open_connection();
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
		return "Connected successfully";
		$this->close_connection();
	}

	protected function get_results_from_query_secure($query, $params = []){
		$this->open_connection();
		$stmt = $this->conn->prepare($query);
	
		// Bind parameters
		for($i = 0; $i < count($params); $i++) {
			$stmt->bind_param(str_repeat('s', count($params)), ...$params);
		}
	
		$stmt->execute();
		$result = $stmt->get_result();
	
		while ($this->rows[] = $result->fetch_assoc());
		$result->close();
		$this->close_connection();
		array_pop($this->rows);
	}
    
}
?>