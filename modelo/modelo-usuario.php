<?php
require_once('modelo/conexion.php');

class ModeloUsuario extends Conexion { 
    public function getDatosUsuario($usuario){
        if($usuario != ''):
            $this->query = "
            SELECT
            *
            FROM
            t_usuario
            WHERE
            kUsuario = '$usuario' 
            AND
            bDisponible = '1';
            ";
              $result = $this->get_results_from_query();
          endif;
          return $this->rows;
    }

    public function updateDatosUsuario($datos){
        $this->query="
        UPDATE t_usuario
        SET 
        sNombre = '$datos[1]', 
        sPassword = '$datos[2]',
        sEmail ='$datos[3]',
        nRol = '$datos[4]'
        WHERE kUsuario = '$datos[0]';
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function getListaUsuarios(){
        $this->query="
        SELECT
        *
        FROM
        t_usuario
        WHERE
        bDisponible = '1';
        ";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function registrarUsuario($datos){
        //sUsuario, sNombre, nRol, sPassword, sEmail,dateRegistered, bDisponible
        $this->query="
        INSERT INTO t_usuario (sUsuario, sNombre, nRol, sPassword, sEmail,dateRegistered, bDisponible )
        VALUES ('".$datos[0]."','".$datos[1]."','".$datos[2]."','".$datos[3]."','".$datos[4]."','".$datos[5]."','1')
        ";
        $result=$this->execute_single_query();
        return $result;
    }

    public function verificarUsrName($usuario){
        $this->query="
        SELECT
        *
        FROM
        t_usuario
        WHERE
        sUsuario = '$usuario'
        AND
        bDisponible = '1';
        ";
        $this->get_results_from_query();
        return $this->rows;
    }

}

####################################
#   Clases Validación de usuario   #
####################################
class ModeloValidarUsuario extends Conexion {

    #metodo solo un registro de usuario
      #campos utilizados "sUsuario", "sNombre" y "sPassword"
      #utilizando  consultas preparadas
      public function get($usuario="",$password="") {
        if($usuario != ''):
            $this->query = "
            SELECT
            kUsuario, sUsuario, sNombre, nRol
            FROM
            t_usuario
            WHERE
            sUsuario = ? 
            AND
            sPassword = ?
            AND
            bDisponible = '1'
            ";
            $params = array($usuario, $password);
            $result = $this->get_results_from_query_secure($this->query, $params);
        endif;
        return $this->rows;
    }
    
}