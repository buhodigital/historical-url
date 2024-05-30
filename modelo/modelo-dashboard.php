<?php
require_once('modelo/conexion.php');

class ModeloDashboard extends Conexion { 

    public function ultimosRegistros($n,$usuario){
        $this->query="SELECT * FROM t_url 
                      WHERE fkUsuario = $usuario
                        AND bDisponible = '1'
                      ORDER BY kUrl DESC LIMIT $n";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    
    }

    public function listaSujetos($usuario){
      $this->query="SELECT * FROM t_sujeto 
                    WHERE fkUsuario = $usuario
                      AND bDisponible = '1'
                      ORDER BY kSujeto DESC  LIMIT 3";
      $result=$this->get_results_from_query();
      $r= $this->rows;
      return $r;
  }   

}

?>