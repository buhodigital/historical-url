<?php
if(isset($_GET['ruta']) && $_GET['ruta']=='usuario'){
    #Instancia de ctr usuario
    $vista= new ControladorUsuario;
    #según la opción se selecciona el método
    switch ($_GET['action']) {
        #Actualizar datos de Usuario
        case 'actualizar':
            //Prevenir error al refrescar la página, si no tiene datos recibidos de post redirecciona
            isset($_POST['kUsuario']) ? :header("Location:".$GLOBALS['url']."/option/usuario/datos/0");
            //recoje los datos en un array
            $datos=array();
            array_push($datos,$_POST['kUsuario'],$_POST['sNombre'],$_POST['sPassword'],$_POST['sEmail'],$_POST['nRol']);
            $vista->actualizarUsuario($datos);
        case 'nuevousuario':
            #mostrar formulario para nuevo usuario
            $vista->formularioUsuario();
            break;
        case 'lista':
            #mostrar la lista de los usuarios registrados            
            $vista->listaUsuarios();
            break;
        case 'registrarusuario':
            if(isset($_POST['sUsuario'])&&$_POST['sUsuario']!==""){
                //recoje los datos en un array
                //sUsuario, sNombre, nRol, sPassword, sEmail,dateRegistered, bDisponible
                $datos=array();
                array_push($datos,$_POST['sUsuario'],$_POST['sNombre'],$_POST['nRol'],$_POST['sPassword'],$_POST['sEmail']);
                $vista->registrarUsuario($datos);
            }else{
                header("Location:".$GLOBALS['url']."/option/usuario/lista/e");
            }
            break;
        case 'borrarusuario':
            isset($_GET['status'])? $usuario=$_GET['status']: $usuario=null ;
            $vista->borrarUsuario($usuario);
            break;
        default:
            #Mostrar datos de Usuario 
            //selecciona usuario si es master
            $usuario="";
            if(is_numeric($_GET['action'])&&$GLOBALS['usuario_rol']=="0"){
                $usuario=$_GET['action'];
            }
            //muestra datos de usuario loggeado si no es master
                $vista->datosUsuario($usuario);
            break;
    }
}

?>