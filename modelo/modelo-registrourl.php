<?php
require_once('modelo/conexion.php');

class ModeloRegistroUrl extends Conexion { 
    public function registrarUrl($datos,$url_id){
        //t_url=>fkUsuario, sUrl, sShortUrl, sNombreCliente, sNota, bDisponible	
        //$datos=> sUrl, sNombreCliente, sNota, kUsuario	
        $this->query="
        INSERT INTO t_url (fkUsuario, sUrl, sShortUrl, sNombreCliente, sNota, bImg, bDisponible	 )
        VALUES ('".$datos[3]."','".$datos[0]."','".$url_id."','".$datos[1]."','".$datos[2]."','1','1')
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function ultimoRegistro(){
        $this->query="SELECT * FROM t_url ORDER BY kUrl DESC LIMIT 1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r[0]['kUrl'];
    }

    public function ultimoRegistroPorUsuario($usuario){
        $this->query="SELECT * FROM t_url 
                      WHERE 
                      fkUsuario =  '$usuario'
                      ORDER BY kUrl DESC LIMIT 1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function redirigirURL($sShortUrl){
        $this->query="SELECT sUrl FROM t_url 
        WHERE 
        sShortUrl =  '$sShortUrl'";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r[0]['sUrl'];
    }

    public function mostrarRegistro($kUrl){
        $this->query="SELECT * FROM t_url 
        WHERE 
        kUrl =  '$kUrl'";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r[0];
    }

    public function mostrarBusqueda($url,$cliente,$fi,$ff,$usuario){
        //$datos=> sUrl, sNombreCliente, dtFecha (inicio), dtFecha (fin)
        $this->query="SELECT * FROM t_url 
                      WHERE fkUsuario $usuario
                      AND
                      sShortUrl LIKE '%$url%'
                      AND
                      sNombreCliente LIKE '%$cliente%'
                      AND
                      bDisponible = '1'
                      AND
                      dtFecha BETWEEN '$fi' AND '$ff'
                      ORDER BY kUrl DESC  LIMIT 100";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    
    }

    public function actualizarNota($datos){
        //$datos=> kUrl, sNombreCliente, sNota
        $this->query="
        UPDATE t_url
        SET 
        sNombreCliente = '$datos[1]', 
        sNota = '$datos[2]'
        WHERE kUrl = '$datos[0]';
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function registrarUrlImagen($datos){
        //t_url_img=>kUrlImg, fkUrl, sNameImg, bDisponible
        //$datos=> fkUrl, sNameImg	
        $this->query="
        INSERT INTO t_url_img (fkUrl, sNameImg, bDisponible	 )
        VALUES ('".$datos[0]."','".$datos[1]."','1')
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function mostrarUrlImagenes($fkUrl){
        $this->query="SELECT * FROM t_url_img
                      WHERE fkUrl = '$fkUrl'
                      AND bDisponible = 1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }

    public function imagenMostrarOcultar($datos){
        //$datos=> kUrl, bImg
        $this->query="
        UPDATE t_url
        SET 
        bImg = '$datos[1]'
        WHERE kUrl = '$datos[0]';
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function verImagen($datos){
        $this->query="SELECT * FROM t_url_img
                      WHERE kUrlImg = '$datos'
                      AND bDisponible = 1";
        $this->get_results_from_query();
        $r= $this->rows;
        return $r;
    }
    

}
?>