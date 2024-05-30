<?php
session_start();
include_once('controlador/controlador-usuario.php');
include_once('controlador/controlador-general.php');
include_once('controlador/controlador-dashboard.php');
include_once('controlador/controlador-registrourl.php');
include_once('controlador/controlador-sujeto.php');
include_once('assets/php/fpdf/fpdf.php');
include_once('controlador/controlador-reportes.php');

/*Verificación de sesion*/
if(isset($_SESSION['usuario_id'])) {
  /*Encabezado*/
  if($_GET['ruta']!=='reportes'){
    include_once('assets/header.php');
    include_once('assets/sidebar.php');
  }
    /*Rutas disponibles*/
    if(isset($_GET['ruta'])){
      #Vistas con ruta
          file_exists('vista/vista-'.$_GET['ruta'].'.php') 
            ? include 'vista/vista-'.$_GET['ruta'].'.php' 
            : header("Location:".$GLOBALS['url']);
    }else{
      //si contiene enlace corto
      if(isset($_GET['enlace'])){
        $redirigir = new ControladorRegistroUrl;
        $redirigir->redirigirUrl($_GET['enlace']);
      }else{
        #Prncipal
        $vista_principal= new ControlVistaPrincipal;
        $vista_principal->dashBoard();
      }
    }
  /*Pie de página*/
  include_once('assets/footer.php');
}else {
  //si contiene enlace corto
  if(isset($_GET['enlace'])){
    $redirigir = new ControladorRegistroUrl;
    $redirigir->redirigirUrl($_GET['enlace']);
  }else{
  /*mostrar login*/
  print $template;
  }
}
?>
