<?php
if(isset($_GET['ruta']) && $_GET['ruta']=='registrourl'){
    $vista= new ControladorRegistroUrl;
    
    switch ($_GET['action']) {
        case 'agregar':
            //$datos=> sUrl, sNombreCliente, nSupConst, nSupTerr, nPrecioVenta, sNota, kUsuario    #Cliente se convierte en upercase para maximizar coincidencias
            //$cleanChar=new ControlGeneral;
            $nota_fit=substr($_POST['nota'],0,240);
            $datos=array();
            ($_POST['construccion']=="")? $construccion=0: $construccion=$_POST['construccion'];
            ($_POST['terreno']=="")? $terreno=0: $terreno=$_POST['terreno'];
            ($_POST['precio']=="")? $precio=0: $precio=$_POST['precio'];
            array_push($datos,$_POST['url'],strtoupper($_POST['cliente']),$construccion,$terreno,$precio,$nota_fit,$_POST['usuario']);
            $vista->registrarUrl($datos);
            break;
        case 'buscar':
            $vista->buscarUrls();
            break;
        case 'resultado':
            //$datos=> sUrl, sNombreCliente, dtFecha (inicio), dtFecha (fin), usuario #Cliente se convierte en upercase para maximizar coincidencias
            $datos=array();
            array_push($datos,$_POST['url'],strtoupper($_POST['cliente']),$_POST['fecha-inicio'],$_POST['fecha-fin'],$_POST['usuario']);
            $vista->mostarBusqueda($datos);
            break;
        case 'editar':
            $kUrl=$_GET['status'];
            $vista->modificarNotaId($kUrl);
            break;
        case 'modificar':
            $nota_fit=substr($_POST['nota'],0,240);
            $datos=array();
            //$datos=> kUrl, sNombreCliente, nSupConst, nSupTerr, nPrecioVenta, sNota
            ($_POST['construccion']=="")? $construccion=0: $construccion=$_POST['construccion'];
            ($_POST['terreno']=="")? $terreno=0: $terreno=$_POST['terreno'];
            ($_POST['precio']=="")? $precio=0: $precio=$_POST['precio'];
            array_push($datos,$_POST['kUrl'],strtoupper($_POST['cliente']),$construccion,$terreno,$precio,$nota_fit);
            $vista->actualizarNota($datos);
            break;
        case 'agregar_imagen':
            if (isset($_FILES['file'])&&isset($_GET['status'])) {
                $kUrl=$_GET['status'];
                $vista->guardarImagen($_FILES['file'],$kUrl);
            }
            break;
        case 'mostrar_ocultar_imagen':
                $datos=$_GET['status'];
                $vista->imagenMostrarOcultar($datos);
            break;
        case 'ver_imagen':
            $datos=$_GET['status'];
            $vista->verImagen($datos);
            break;
        case 'borrar_imagen':
            $datos=$_GET['status'];
            $vista->borrarImagen($datos);
            break;
        case 'borrar_url':
            $datos=$_GET['status'];
            $vista->borrarUrl($datos);
            break;
        default:
            if(is_numeric($_GET['action'])){
                $vista->mostrarRegistro($_GET['action']);
            }else{
                $vista->formularioUrl();
            }
            break;
    }
}
?>