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
    $rol=$r[0]['nRol'];
    $m = $s = $u = $c = $o = "";
    switch ($rol) {
      case '0':
        $m='checked=""';
        break;
      case '1':
        $s='checked=""';
        break;
      case '2':
        $u='checked=""';
        break;
      case '3':
        $c='checked=""';
        break;
      case '4':
        $o='checked=""';
        break;        
    }
    $GLOBALS['usuario_rol']=="0" ? $admin='' : $admin='style="display:none;"';
    $contenido = <<<END
                  <!--formulario -->
                    <form method="post" action="{$GLOBALS['url']}/option/usuario/actualizar/0">
                      <input type="text" name="kUsuario" value="{$r[0]['kUsuario']}" class="hide">
                      <div class="w3-row-padding">
                      <div class="w3-half">
                        <label>Usuario</label>
                        <input class="w3-input w3-border" name="sUsuario" type="text" placeholder="Escriba un nombre sin espacios" value="{$r[0]['sUsuario']}" disabled>
                      </div>
                      <div class="w3-half">
                        <label>Nombre</label>
                        <input class="w3-input w3-border" name="sNombre" type="text" placeholder="Escriba su nombre completo"  value="{$r[0]['sNombre']}">
                      </div>
                      <div class="w3-half">
                        <label>Password</label>
                        <input class="w3-input w3-border" name="sPassword" type="password" placeholder="Escriba una contraseña"  value="{$r[0]['sPassword']}">
                      </div>
                      <div class="w3-half">
                        <label>Email</label>
                        <input class="w3-input w3-border" name="sEmail" type="email" placeholder="Escriba un email válido"  value="{$r[0]['sEmail']}">
                      </div>

                      <div class="w3-half w3-margin" $admin>
                      <input class="w3-radio" type="radio" name="nRol" value="0" $m >
                      <label>Master</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="1" $s>
                      <label>Superusuario</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="2" $u>
                      <label>Usuario</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="3" $c>
                      <label>Cobro</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="4" $o>
                      <label>Operación</label>
                      </div>
                      </div>
                      <button class="w3-btn w3-blue-grey w3-margin">Actualizar</button>
                      <a onclick="showConfirmation()" class="w3-btn w3-red w3-margin" $admin>Borrar</a>
                    </form>
                    <!--Confirmación-->
                    <div id="confirmation" class="w3-panel w3-yellow w3-display-container w3-border hide">
                    <span onclick="hideAlert(this.parentElement)" class="w3-button w3-large w3-display-topright">×</span>
                      <h3>Precaución</h3>
                      <p>Borrará permantentemente al usuario, ¿desea continuar?</p>
                    <a href="{$GLOBALS['url']}/option/usuario/borrarusuario/{$r[0]['kUsuario']}" class="w3-btn w3-blue-grey w3-margin">Si</a>
                    <button onclick="hideAlert(this.parentElement)" class="w3-btn w3-red w3-margin">No</button>
                  </div>
                END;
    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-user"></i> Usuario</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
  }
    #Registro de nuevo usuario
    public function formularioUsuario(){
      $contenido = <<<END
                  <!--formulario -->
                    <form method="post" action="{$GLOBALS['url']}/option/usuario/registrarusuario/0">
                      <input type="text" name="kUsuario" value="" class="hide">
                      <div class="w3-row-padding">
                      <div class="w3-half">
                        <label>Usuario</label>
                        <input class="w3-input w3-border" name="sUsuario" type="text" placeholder="Escriba un nombre sin espacios" value="">
                      </div>
                      <div class="w3-half">
                        <label>Nombre</label>
                        <input class="w3-input w3-border" name="sNombre" type="text" placeholder="Escriba su nombre completo"  value="">
                      </div>
                      <div class="w3-half">
                        <label>Password</label>
                        <input class="w3-input w3-border" name="sPassword" type="password" placeholder="Escriba una contraseña"  value="">
                      </div>
                      <div class="w3-half">
                        <label>Email</label>
                        <input class="w3-input w3-border" name="sEmail" type="email" placeholder="Escriba un email válido"  value="">
                      </div>

                      <div class="w3-half w3-margin">
                      <input class="w3-radio" type="radio" name="nRol" value="0" >
                      <label>Master</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="1">
                      <label>Superusuario</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="2" checked="">
                      <label>Usuario</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="3">
                      <label>Cobro</label>
                      
                      <input class="w3-radio" type="radio" name="nRol" value="4">
                      <label>Operación</label>
                      </div>
                      </div>
                      <button class="w3-btn w3-blue-grey w3-margin">Registrar</button>
                    </form>
                END;
      $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-user"></i>Nuevo Usuario</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
    }
  
    #Actualizar usuario
    public function actualizarUsuario($datos){
      $actualizar = new ModeloUsuario;
      $resultado=$actualizar->updateDatosUsuario($datos);
      if($resultado){
        header("Location:".$GLOBALS['url']."/option/usuario/".$datos["0"]."/1");
      }else{
        header("Location:".$GLOBALS['url']."/option/usuario/".$datos["0"]."/e");
      }
    }

    #Registrar usuario
    public function registrarUsuario($datos){
      //Verificar si es master
      if($GLOBALS['usuario_rol']=="0"){
        //Verificar si el usuario no existe
        $verificar = new ModeloUsuario;
        $verificarUsuario = $verificar->verificarUsrName($datos[0]);
        if(empty($verificarUsuario)){
          $registrar = new ModeloUsuario;
          //echo var_dump($datos);
          //sUsuario, sNombre, nRol, sPassword, sEmail,dateRegistered, bDisponible
          $hoy = date("Y-m-d"); 
          array_push($datos,$hoy);
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
        $contenido='<a href="'.$GLOBALS['url'].'/option/usuario/nuevousuario/0" class="w3-btn w3-blue-grey w3-margin ri">Nuevo</a>';
        $contenido.='<table class="w3-table-all">
                      <tr>
                        <th><b>Usuario</b></th>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Editar</th>
                      </tr>';
        foreach ($r as $key => $value) {

          $sRol=$this->rolEnTexto($value['nRol']);
          $contenido.='<tr>
                        <td>'.$value['sUsuario'].'</td>
                        <td>'.$value['sNombre'].'</td>
                        <td>'.$sRol.'</td>
                        <td><a class="w3-text-blue" href="'.$GLOBALS['url'].'/option/usuario/'.$value['kUsuario'].'/0"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                      </tr>';
        }
        $contenido.="</table>";        
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
		$passwordVal=htmlspecialchars($password);
		$selUsuario = new ModeloValidarUsuario;
		$usuario=$selUsuario->get($usuarioVal,$passwordVal);     

	    if(!empty($usuario)){
	     $_SESSION['usuario_id'] = $usuario[0]['kUsuario'];
	     $_SESSION['usuario_nombre'] = $usuario[0]['sNombre'];
	     $_SESSION['usuario_rol'] = $usuario[0]['nRol'];
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