<?php 
include_once('modelo/modelo-general.php');
#//////////Variables globales///////////////
  # Datos de hora
  date_default_timezone_set('America/Mazatlan');
  #Generales  
  //$url = "http://" . $_SERVER['SERVER_NAME']."/web/valuacion";
  $url="tuurl.com"; 
  $url_externo = "https://"."";
  $correo_admin="tuemail@tuurl.com"; //correo de administrador

  $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  // Agrega tu clave API >>> consíguela en http://screenshotmachine.com
  $apiKey = "tuapikey"; 

#Inicia Revisión de sesión
if(isset($_SESSION['usuario_id'])) {
    //usuario
    $usuario_id= $_SESSION['usuario_id'];
    $usuario_nombre = $_SESSION['usuario_nombre'];
    $usuario_rol = $_SESSION['usuario_rol'];
    //Condicion de administrador
    $usuario_rol=="0" ? $admin='' : $admin='style="display:none;"';
    //Generales Sidebar | cambiar valores > array_replace()
    $datos_sidebar=array(
      'admin'=>$admin,
      'url'=>$url."/",
      'img-user'=>$url.'/assets/img/img_usr.png',
      'logout'=>$url.'/core/logout',
      'usuario'=>$url.'/option/usuario/datos/0',
      'lista'=>$url.'/option/usuario/lista/0',
      'agregar'=>$url.'/option/registrourl/datos/0',
      'buscar'=>$url.'/option/registrourl/buscar/0',
      'sujeto'=>$url.'/option/sujeto/datos/0',
      'buscar_sujetos'=>$url.'/option/sujeto/buscar/0'
    );

 #//////////cerrar sesion///////////////
  if(isset($_GET['action']) and $_GET['action']=='logout'){
    $usuario_id=   $usuario_nombre =   $usuario_rol =   $usuario_sucursal = 'no especificado';
    $sesion= new ControladorValidarUsuario();
    $sesion->cerrarSesion();
  }
}else{
#//////////valida usuario///////////////
    $diccionario = array(
      'imagen'=>$url.'/assets/img/img_usr.png',
      'correo'=>$correo_admin,
      'img-registro'=>$url.'/assets/img/registro.png');
    $template = file_get_contents('vista/plantillas/validacion.html');
    foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
    //Inicia sesión
    if(isset($_POST['username']) && isset($_POST['password'])){
      $validar= new ControladorValidarUsuario;
      $validar->validarUsuario($_POST['username'],$_POST['password']);
    }else if(isset($_POST['nombre']) && isset($_POST['email'])){
      $validar= new ControladorValidarUsuario;
      $validar->enviarEmail($_POST['nombre'],$_POST['email']);
    }
}
#Termina Revisión de sesión




 #Controles generales
 class ControlGeneral{
   #borrar registro
   #key = Nombre de columna | id = valor de la columna
   public function borrar($tabla,$key,$id){
      $actualizar = new ModeloGeneral;
      $consulta=$actualizar ->borrar($tabla,$key,$id);
      return $consulta;
   }

	public function roles(){
    #usuarios 0=Master, 1=superuser, 2=user, 3=cobro, 4=operacion	
	    $roles=array(
			'Master',
			'Superusuario',
			'Usuario',
      'Cobranza',
			'Operación'
		);
		#listado de roles en opciones
		$array_num = count($roles);
		for ($i = 0; $i < $array_num; ++$i){
			$sRol = ($roles[$i] == '100') ? 'Master' : $roles[$i];
		    print '<option value="'.$roles[$i].'">'.$sRol.'</option>';
		}
	}

  public function yearList(){
    $i=2016;
    $current=date("Y");
    $resp="";
    for ($year=$current; $year >= $i; $year--) { 
      $resp.='<option value="'.$year.'">'.$year.'</option>';
    }
    echo $resp;
  }

  #formatear fecha en texto
  public function fechaTexto($fecha){
    $f=strtotime($fecha);
    $dia=date("d", $f);
    $mes=date("m", $f);
    $ano=date("Y", $f);
    switch ($mes) {
      case '01':
          $m="Enero";
        break;
      case '02':
          $m="Febrero";
        break;
      case '03':
          $m="Marzo";
        break;
      case '04':
          $m="Abril";
        break;
      case '05':
          $m="Mayo";
        break;
      case '06':
          $m="Junio";
        break;
      case '07':
          $m="Julio";
        break;
      case '08':
          $m="Agosto";
        break;
      case '09':
          $m="Septiembre";
        break;
      case '10':
          $m="Octubre";
        break;
      case '11':
          $m="Noviembre";
        break;
      case '12':
          $m="Diciembre";
        break;
      
      default:
        # code...
        break;
    }
    return $dia." de ".$m." del ".$ano;
  }

  //Limpiar caracteres
  public function cleanChar($string){
    $string = str_replace(' ', '-', $string); // Replaces spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }
  
 }
?>