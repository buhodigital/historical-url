<?php
require_once('modelo/conexion.php');

class ModeloReportes extends Conexion { 

    public function reporteSujetoXls($kSujeto){
        $this->query="SELECT * FROM t_sujeto AS tS

                WHERE  tS.kSujeto = '$kSujeto'";
        $result=$this->get_results_from_query();
        $r= $this->rows;
        return $r;

    }

}
?>