<?php
require_once('modelo/conexion.php');

class ModeloUsuario extends Conexion { 
    public function getDatosUsuario($usuario){
        if($usuario != ''):
            $sql = "SELECT * FROM t_usuario WHERE kUsuario = ? AND bDisponible = '1'";
            $stmt = $this->query($sql, [$usuario]);
            return $stmt->fetchAll();
        endif;
        return [];
    }

    public function updateDatosUsuario($datos){
        $sql="UPDATE t_usuario SET sNombre = ?, sPassword = ?, sEmail = ?, nRol = ? WHERE kUsuario = ?";
        $params = [$datos['sNombre'], $datos['sPassword'], $datos['sEmail'], $datos['nRol'], $datos['kUsuario']];
        $this->query($sql, $params);
        return true;
    }

    public function getListaUsuarios(){
        $sql="SELECT * FROM t_usuario WHERE bDisponible = '1'";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    public function registrarUsuario($datos){
        $sql="INSERT INTO t_usuario (sUsuario, sNombre, nRol, sPassword, sEmail, dateRegistered, bDisponible ) VALUES (?, ?, ?, ?, ?, ?, '1')";
        $params = [$datos['sUsuario'], $datos['sNombre'], $datos['nRol'], $datos['sPassword'], $datos['sEmail'], $datos['dateRegistered']];
        $this->query($sql, $params);
        return true;
    }

    public function verificarUsrName($usuario){
        $sql = "SELECT * FROM t_usuario WHERE sUsuario = ? AND bDisponible = '1'";
        $stmt = $this->query($sql, [$usuario]);
        return $stmt->fetchAll();
    }

}

####################################
#   Clases Validación de usuario   #
####################################
class ModeloValidarUsuario extends Conexion {

    #metodo solo un registro de usuario
    #Retorna datos del usuario para validación de contraseña en controlador
    public function get($usuario) {
        if($usuario != ''):
            $sql = "SELECT kUsuario, sUsuario, sNombre, nRol, sPassword FROM t_usuario WHERE sUsuario = ? AND bDisponible = '1'";
            $stmt = $this->query($sql, [$usuario]);
            return $stmt->fetchAll();
        endif;
        return [];
    }
    
}