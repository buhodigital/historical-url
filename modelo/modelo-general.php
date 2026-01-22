<?php
require_once('modelo/conexion.php');

class ModeloGeneral extends Conexion { 

    public function checkConection(){
        try {
            $this->query("SELECT 1");
            return "Connected successfully";
        } catch (Exception $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function borrar($tabla,$key,$id){
		$sql = "UPDATE $tabla SET bDisponible=0 WHERE $key = ?";
		$this->query($sql, [$id]);
		return true;
	}

}
?>