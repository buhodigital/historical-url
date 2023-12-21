<?php
require_once('modelo/conexion.php');

class ModeloGeneral extends Conexion { 

    public function checkConection(){
        $check = $this->verify();
        return $check;
    }

    public function borrar($tabla,$key,$id){
		$this->query="
		UPDATE
		$tabla
		SET
		bDisponible=0
		WHERE
		$key = '$id'
		";
		$result = $this->execute_single_query();
		return $result;
	}

}
?>