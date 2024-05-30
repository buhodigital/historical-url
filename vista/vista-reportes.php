<?php
if(isset($_GET['ruta']) && $_GET['ruta']=='reportes'){
    #Instancia de ctr reportes
    $vista= new ControladorReportes;
    switch ($_GET['action']) {
        case 'homologacion':
            $d=explode('-',$_GET['status']);
            $vista->reporteHomologacion($d[1]);
            break;
        case 'opinion-valor':
            $d=explode('-',$_GET['status']);
            $vista->reporteOpinionValor($d[1]);
        default:
            # code...
            break;
    }

}
?>