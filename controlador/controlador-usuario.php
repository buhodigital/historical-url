<?php
include_once('modelo/modelo-usuario.php');

class ControladorUsuario{
  //Método mostrar vista para la clase Usuario
  private function mostrarVista($diccionario){   
    $template = file_get_contents('vista/plantillas/plantilla-general.html');
    foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
    print $template;
  }

  private function rolEnTexto($rol){
    #usuarios 0=Master, 1=superuser, 2=user, 3=cobro, 4=operacion
    switch ($rol) {
      case '0':
        $sRol='Master';
        break;
      case '1':
        $sRol='Superuser';
        break;
      case '2':
        $sRol='User';
        break;
      case '3':
        $sRol='Cobro';
        break;
      case '4':
        $sRol='Operación';
        break;        
    }
    return $sRol;
  }

  #Obtener datos generales de un usuario del sistema 
  public function datosUsuario($usuario=''){
    $sel_usuario=$usuario=="" ? $GLOBALS['usuario_id'] : $usuario ;
    #usuarios 0=Master, 1=superuser, 2=user, 3=cobro, 4=operacion	
    $datos=new ModeloUsuario;
    $r=$datos->getDatosUsuario($sel_usuario);
    if(empty($r)) {
        echo "Usuario no encontrado";
        return;
    }

    // Prepare variables for view
    $kUsuario = htmlspecialchars($r[0]['kUsuario']);
    $sUsuario = htmlspecialchars($r[0]['sUsuario']);
    $sNombre = htmlspecialchars($r[0]['sNombre']);
    $sEmail = htmlspecialchars($r[0]['sEmail']);
    $nRol = $r[0]['nRol'];

    $GLOBALS['usuario_rol']=="0" ? $adminStyle='' : $adminStyle='style="display:none;"';

    $isEdit = true;
    $actionUrl = $GLOBALS['url']."/option/usuario/actualizar/0";
    $passwordPlaceholder = "Dejar en blanco para conservar la actual";
    $submitBtnText = "Actualizar";

    // Capture View
    ob_start();
    include 'vista/usuarios/formulario.php';
    $contenido = ob_get_clean();

    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-user"></i> Usuario</b></h5>', 
      'contenido'=>$contenido);
    $this->mostrarVista($diccionario);
  }

    #Registro de nuevo usuario
    public function formularioUsuario(){
        // Prepare variables for view
        $kUsuario = "";
        $sUsuario = "";
        $sNombre = "";
        $sEmail = "";
        $nRol = "2"; // Default user

        $adminStyle = '';

        $isEdit = false;
        $actionUrl = $GLOBALS['url']."/option/usuario/registrarusuario/0";
        $passwordPlaceholder = "Escriba una contraseña";
        $submitBtnText = "Registrar";

        ob_start();
        include 'vista/usuarios/formulario.php';
        $contenido = ob_get_clean();

      $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-user"></i>Nuevo Usuario</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
    }
  
    #Actualizar usuario
    public function actualizarUsuario($datos){
      // Security Check: Authorization
      // User can only update themselves, unless they are Admin (Role 0)
      if ($GLOBALS['usuario_rol'] != '0' && $datos['kUsuario'] != $GLOBALS['usuario_id']) {
          // Unauthorized attempt to update another user
          header("Location:".$GLOBALS['url']."/option/usuario/lista/e"); // Or access denied
          return;
      }

      // Security Check: Role Escalation
      // Non-admins cannot change role. Force role to their current role.
      if ($GLOBALS['usuario_rol'] != '0') {
          $datos['nRol'] = $GLOBALS['usuario_rol'];
      }

      $actualizar = new ModeloUsuario;

      // Manejo de contraseña
      if (!empty($datos['sPassword'])) {
          $datos['sPassword'] = password_hash($datos['sPassword'], PASSWORD_DEFAULT);
      } else {
          // Recuperar la contraseña actual si no se envió una nueva
          $currentUser = $actualizar->getDatosUsuario($datos['kUsuario']);
          if (!empty($currentUser)) {
              $datos['sPassword'] = $currentUser[0]['sPassword'];
          }
      }

      $resultado=$actualizar->updateDatosUsuario($datos);
      if($resultado){
        header("Location:".$GLOBALS['url']."/option/usuario/".$datos["kUsuario"]."/1");
      }else{
        header("Location:".$GLOBALS['url']."/option/usuario/".$datos["kUsuario"]."/e");
      }
    }

    #Registrar usuario
    public function registrarUsuario($datos){
      //Verificar si es master
      if($GLOBALS['usuario_rol']=="0"){
        //Verificar si el usuario no existe
        $verificar = new ModeloUsuario;
        $verificarUsuario = $verificar->verificarUsrName($datos['sUsuario']);
        if(empty($verificarUsuario)){
          $registrar = new ModeloUsuario;

          // Hash de contraseña
          $datos['sPassword'] = password_hash($datos['sPassword'], PASSWORD_DEFAULT);

          // Date
          $datos['dateRegistered'] = date("Y-m-d");

          $resultado=$registrar->registrarUsuario($datos);
          if($resultado){
            header("Location:".$GLOBALS['url']."/option/usuario/lista/1");
          }else{
            header("Location:".$GLOBALS['url']."/option/usuario/lista/e");
          }
        }else{
            header("Location:".$GLOBALS['url']."/option/usuario/lista/e");
        }
      }
    }

    public function listaUsuarios(){
      if($GLOBALS['usuario_rol']=="0"){
        $lista = new ModeloUsuario;
        $r=$lista->getListaUsuarios();

        // Prepare data
        $usuarios = [];
        foreach ($r as $key => $value) {
            $value['sRolTexto'] = $this->rolEnTexto($value['nRol']);
            $usuarios[] = $value;
        }

        ob_start();
        include 'vista/usuarios/lista.php';
        $contenido = ob_get_clean();

        $diccionario = array(
          'titulo'=>'<h5><b><i class="fa fa-users fa-fw"></i> Usuarios</b></h5>', 
          'contenido'=>$contenido);
          $this->mostrarVista($diccionario);
        }else{
          header("Location:".$GLOBALS['url']);
        }
    }

    function borrarUsuario($usuario){
      if($GLOBALS['usuario_rol']=="0"){
        $borrar=new ControlGeneral;
        //verificar si es el master principal
        if($usuario!='1'){
          $borrar->borrar('t_usuario','kUsuario',$usuario)?
          header("Location:".$GLOBALS['url']."/option/usuario/lista/1"):
          header("Location:".$GLOBALS['url']."/option/usuario/lista/e");    
        }else{
          header("Location:".$GLOBALS['url']."/option/usuario/lista/e"); 
        }
      }else{
        header("Location:".$GLOBALS['url']);
      }  
    }
}

####################################
#   Clases Validación de usuario   #
####################################
class ControladorValidarUsuario{
	public $usuario;

	public function validarUsuario($usuario="",$password=""){
		$usuarioVal=htmlspecialchars($usuario);

		$selUsuario = new ModeloValidarUsuario;
		$datosUsuario = $selUsuario->get($usuarioVal);

        // Validar si existe usuario y contraseña coincide
	    if(!empty($datosUsuario) && password_verify($password, $datosUsuario[0]['sPassword'])){
	     $_SESSION['usuario_id'] = $datosUsuario[0]['kUsuario'];
	     $_SESSION['usuario_nombre'] = $datosUsuario[0]['sNombre'];
	     $_SESSION['usuario_rol'] = $datosUsuario[0]['nRol'];
         header("Location:".$GLOBALS['url']);
	    } else {
	      echo "<script>alert('Usuario y/o contraseña no reconocida');</script>";
          echo "Intente nuevamente...";
	    }
	}

	public function cerrarSesion(){
		// comprobamos que se haya iniciado la sesión
	    if(isset($_SESSION['usuario_id'])) {
	        session_destroy();
          header("Location:".$GLOBALS['url']);
	    }else {
	        echo "Operación incorrecta.";
	    }

	}

  public function enviarEmail($nombre,$email){
    $nombreVal=htmlspecialchars($nombre);
    $emailVal=htmlspecialchars($email);
      if(!empty($emailVal)){
        $to = $GLOBALS['correo_admin'];
        $subject = "Usuario registrado en ".$GLOBALS['url'];
        $message = "Hola, se ha registrado un nuevo usuario en el sistema urlh.org a nombre de: ".$nombreVal.", su correo es ".$emailVal;
        $headers = "From: ".$GLOBALS['url']." <$emailVal>";

        if(mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Los datos han sido registrados, en breve recibirás un correo en tu buzón: ".$emailVal.", con los datos de acceso.');</script>";
        } else {
            echo "Error al enviar el correo.";
        }
        
      } else {
        echo "<script>alert('Usuario y/o email no reconocida');</script>";
        echo "Intente nuevamente...";
      }
  }

}
##########################################
# Finaliza Clases Validación de  Usuario #
##########################################