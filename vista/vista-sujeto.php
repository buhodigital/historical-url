<?php
if(isset($_GET['ruta']) && $_GET['ruta']=='sujeto'){
    $vista= new ControladorSujeto;

    switch ($_GET['action']) {
        case 'agregar':
            $datos=array();
            //nombreSolicitante: El nombre del solicitante. fechaAvaluo: La fecha del avalúo. objetivoAvaluo: El objetivo del avalúo. domicilio: El domicilio. codigoPostal: El código postal. metrosCuadradosTerreno: Los metros cuadrados de terreno. metrosCuadradosConstruccion: Los metros cuadrados de construcción. anioConstruccion: El año de construcción. tipoPropiedad: El tipo de propiedad. tipoVivienda: El tipo de vivienda. estadoConservacion: El estado de conservación. generales: Notas generales.
            $usuario = $GLOBALS['usuario_id'];
            array_push($datos, strtoupper($_POST['nombreSolicitante']),$_POST['fechaAvaluo'],$_POST['objetivoAvaluo'],$_POST['domicilio'],$_POST['codigoPostal'],$_POST['metrosCuadradosTerreno'],$_POST['metrosCuadradosConstruccion'],$_POST['anioConstruccion'],$_POST['tipoPropiedad'],$_POST['tipoVivienda'],$_POST['estadoConservacion'],$_POST['generales'],$usuario);
            $vista->agregar($datos);
            //var_dump($datos);
            break;
            case 'buscar':
                $vista->buscarSujetos();
            break;
            case 'resultados':
                $datos=array();
                $usuario = $GLOBALS['usuario_id'];
                array_push($datos,$_POST['solicitante'],$_POST['cp'],$_POST['fecha-inicio'],$_POST['fecha-fin'],$usuario);
                $vista->listaSujetos($datos);
            break;
            case 'modificar':
                $datos=array();
                array_push($datos,strtoupper($_POST['nombreSolicitante']),$_POST['fechaAvaluo'],$_POST['objetivoAvaluo'],$_POST['domicilio'],$_POST['codigoPostal'],$_POST['metrosCuadradosTerreno'],$_POST['metrosCuadradosConstruccion'],$_POST['anioConstruccion'],$_POST['tipoPropiedad'],$_POST['tipoVivienda'],$_POST['estadoConservacion'],$_POST['generales'],$_POST['id']);
                $vista->modificarSujeto($datos);
            break;
            case 'buscar-url':
                $id=isset($_POST['kSujeto'])?$_POST['kSujeto']:"e";
                $vista->buscarUrl($id);
            break;
            case 'resultados-url':
                $datos=array();
                $usuario = $GLOBALS['usuario_id'];
                array_push($datos,$_POST['url'],strtoupper($_POST['cliente']),$_POST['fecha-inicio'],$_POST['fecha-fin'],$_POST['usuario'],$_POST['kSujeto']);
                $vista->listaUrl($datos);
            break;
            case 'vincular-url':
                if(isset($_GET['status'])&&$_GET['status']!=='0'){
                    $vista->vincularUrl($_GET['status']);
                }else{
                    $vista->inicio();
                }
            break;
            case 'sujeto-xls':
                $d=explode('-',$_GET['status']);
                $vista->reporteSujetoXls($d[1]);
                break;                
            case 'sujeto-pdf':
                $d=explode('-',$_GET['status']);
                $vista->reporteSujetoPdf($d[1]);
                break;
            case 'captura-homologacion':
                $d=explode('-',$_GET['status']);
                $datos=array();
                //$datos: [0]=get(kSujeto), post1([1]=ubicacion, [2]=servicios, [3]=conservacion, [4]=proyecto,[5]=demanda), [6]=cus, [7]=n-comp,[8...]=post2comparables(negociacio-id, ubicacion-id, servicios-id, conservacion-id, proyecto-id)
                array_push($datos,$d[1],$_POST['ubicacion'],$_POST['servicios'],$_POST['conservacion'],$_POST['proyecto'],$_POST['demanda'],$_POST['cus'],$_POST['n-comp'],$_POST['comparables']);
                $comparables=explode('-',$_POST['comparables']);
                    for ($i=0; $i < $_POST['n-comp']; $i++) { 
                        $comparables=explode(',',$_POST['comparables']);
                        array_push($datos,$_POST['negociacion-'.$comparables[$i]],$_POST['ubicacion-'.$comparables[$i]],$_POST['servicios-'.$comparables[$i]],$_POST['conservacion-'.$comparables[$i]],$_POST['proyecto-'.$comparables[$i]]);
                    }
                $vista->capturaHomologacion($datos);
                break;
            case 'actualizar-homologacion':
                $d=explode('-',$_GET['status']);
                $datos=array();
                //$datos: [0]=get(kSujeto), post1([1]=ubicacion, [2]=servicios, [3]=conservacion, [4]=proyecto,[5]=demanda), [6]=cus, [7]=n-comp,[8...]=post2comparables(negociacio-id, ubicacion-id, servicios-id, conservacion-id, proyecto-id)
                array_push($datos,$d[1],$_POST['ubicacion'],$_POST['servicios'],$_POST['conservacion'],$_POST['proyecto'],$_POST['demanda'],$_POST['cus'],$_POST['n-comp'],$_POST['comparables']);
                $comparables=explode('-',$_POST['comparables']);
                    for ($i=0; $i < $_POST['n-comp']; $i++) { 
                        $comparables=explode(',',$_POST['comparables']);
                        array_push($datos,$_POST['negociacion-'.$comparables[$i]],$_POST['ubicacion-'.$comparables[$i]],$_POST['servicios-'.$comparables[$i]],$_POST['conservacion-'.$comparables[$i]],$_POST['proyecto-'.$comparables[$i]]);
                    }
                $vista->actualizarHomologacion($datos);
                break;
            case 'borrar':
                //se recibe kEnlaceUS y kSujeto
                $d=explode('-',$_GET['status']);
                $borrar= new ControlGeneral;
                $r=$borrar->borrar('t_enlace_us','kEnlaceUS',$d[0]);
                if($r){
                    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$d[1].'/1');
                }
            break;
            case 'agregar-imagen':
                if (isset($_FILES['file'])&&isset($_GET['status'])) {
                    $kSujeto=$_GET['status'];
                    $vista->guardarImagen($_FILES['file'],$kSujeto);
                }
            break;
            case 'borrar-imagen':
                #Borrar enlace dentro de sujeto#
                //se recibe kEnlaceUS y kSujeto 
                $d=explode('-',$_GET['status']);
                $borrar= new ControlGeneral;
                $r=$borrar->borrar('t_sujeto_img','kSujetoImg',$d[0]);
                if($r){
                    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$d[1].'/1');
                }
            break;
            case 'informacion':
                $datos=array();
                array_push($datos,$_POST['kSujeto'],$_POST['sTitulo'],$_POST['sInfo']);
                $vista->agregarInformacion($datos);
            break;
            case 'borrar-sujeto':
                # borrar sujeto
                $borrar= new ControlGeneral;
                $r=$borrar->borrar('t_sujeto','kSujeto',$_GET['status']);
                if($r){
                    header('Location: '.$GLOBALS['url'].'/option/sujeto/buscar/b');
                }
            break;
            case 'opinion-captura':
                $d=explode('-',$_GET['status']);
                $validar= new ControladorSujeto;
                $v=$validar->validarOpinion($d[1]);
                if($v){
                    $vista->actualizarOpinion($d[1]);
                }else{
                    $vista->capturaOpinion($d[1]);
                }
                
            break;
            case 'borrar-opinion':
                $d=explode('-',$_GET['status']);
                $borrar= new ControlGeneral;
                $r=$borrar->borrar('t_opinion_valor','fkSujeto',$d[1]);
                if($r){
                    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$d[1].'/1');
                }
            break;
            case 'borrar-informacion':
                //se recibe kEnlaceUS y kSujeto
                $d=explode('-',$_GET['status']);
                $borrar= new ControlGeneral;
                $r=$borrar->borrar('t_sujeto_info','kSujetoInfo',$d[0]);
                if($r){
                    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$d[1].'/1');
                }
        
        default:
        if(is_numeric($_GET['action'])){
            $vista->mostrarSujeto($_GET['action']);
        }else{
            $vista->inicio();
        }
        
            break;
    }

}

?>