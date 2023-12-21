<?php
if(isset($_GET['ruta']) && $_GET['ruta']=='reportes'){
    #Instancia de ctr reportes
    $vista= new ControladorReportes;
    switch ($_GET['action']) {
        case 'sujeto-xls':
            $d=explode('-',$_GET['status']);
            $vista->reporteSujetoXls($d[1]);
            break;
        case 'sujeto-pdf':
            $d=explode('-',$_GET['status']);
            $vista->reporteSujetoPdf($d[1]);
        default:
            # code...
            break;
    }

}
?>