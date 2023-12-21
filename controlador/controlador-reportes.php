<?php
include_once('modelo/modelo-reportes.php');

class ControladorReportes{
    private function mostrarVista($diccionario){   
        $template = file_get_contents('vista/plantillas/plantilla-general.html');
        foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
        print $template;
      }

    public function reporteSujetoXls($kSujeto){
        $modelo = new ModeloReportes;
        $r=$modelo->reporteSujetoXls($kSujeto);

    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    $numero_de_enlace=1;
    $vinculos="";
    foreach ($rE as $key => $value) {
        $vinculos.="<tr><td>Enlace comparable ".$numero_de_enlace."</td><td>".$GLOBALS['url']."/".$value['sShortUrl']."</td></tr>";
        $vinculos.="<tr><td>Nota comparable ".$numero_de_enlace."</td><td>".$value['sNota']."</td></tr>";
        $numero_de_enlace++;
    }
    //termina enlaces a comparables
    //información extra
    $info= new ModeloSujeto;
    $rI=$info->mostrarInformacion($kSujeto);
    $informacion="";
    foreach ($rI as $key => $value) {
        $informacion.="<tr><td>".$value['sTitulo']."</td><td>".$value['sInfo']."</td></tr>";
    }
    //termina información extra
        $contenido='<div class="w3-white p">
                    <button class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge w3-right m noprint" onclick="exportTableToExcel(\'tabla\', \'INFO-'.$r[0]['sNombreSolicita'].'.xlsx\')"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
                        <table id="tabla" class="w3-table w3-striped w3-bordered w3-hoverable">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($r as $key => $value) {
            $claveSujeto=new ControladorSujeto;
            $objeto=$claveSujeto->objetivosAvaluo($value['nObjetoAvaluo']);
            $tipo_propiedad=$claveSujeto->tipoPropiedad($value['nTipoPropiedad']);
            $tipo_vivienda=$claveSujeto->tipoVivienda($value['nTipoVivienda']);
            $estado_conservacion=$claveSujeto->estadoConservacion($value['nEstadoConservacion']);
            $contenido.='<tr><td>Solicita</td><td>'.$value['sNombreSolicita'].'</td></tr>
                        <tr><td>Fecha</td><td>'.$value['dtFechaAvaluo'].'</td></tr>
                        <tr><td>Objeto Valuación</td><td>'.$objeto.'</td></tr>
                        <tr><td>Domicilio</td><td>'.$value['sDomicilio'].'</td></tr>
                        <tr><td>CP</td><td>'.$value['nCP'].'</td></tr>
                        <tr><td>M<sup>2</sup> Terreno</td><td>'.$value['nMTerreno'].'</td></tr>
                        <tr><td>M<sup>2</sup> Construcción</td><td>'.$value['nMConstruccion'].'</td></tr>
                        <tr><td>Antigüedad</td><td>'.$value['nAntiguedad'].'</td></tr>
                        <tr><td>Tipo Propiedad</td><td>'.$tipo_propiedad.'</td></tr>
                        <tr><td>Tipo Vivienda</td><td>'.$tipo_vivienda.'</td></tr>
                        <tr><td>Estado Conservación</td><td>'.$estado_conservacion.'</td></tr>
                        <tr><td>Nota General</td><td>'.$value['nNotaGeneral'].'</td></tr>
                        <tr><td>Fecha Registro</td><td>'.$value['dtFechaRegistro'].'</td></tr>';
        }
        
        $contenido.=$informacion;
        $contenido.=$vinculos;
        $contenido.='</tbody>
                    </table>
                    </div>
                    
                    <script>
                    function exportTableToExcel(tableId, filename = ""){
                        var downloadLink;
                        var dataType = "application/vnd.ms-excel";
                        var tableSelect = document.getElementById(tableId);
                        var workbook = XLSX.utils.table_to_book(tableSelect, {sheet:"Sheet JS"});
                        var wbout = XLSX.write(workbook, {bookType:"xlsx", bookSST:true, type: "binary"});
                    
                        function s2ab(s) { 
                            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
                            var view = new Uint8Array(buf);  //create uint8array as viewer
                            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
                            return buf;    
                        }
                    
                        // Create a download link
                        downloadLink = document.createElement("a");
                    
                        // File name
                        downloadLink.download = filename;
                    
                        // Create a link to the file
                        downloadLink.href = window.URL.createObjectURL(new Blob([s2ab(wbout)], {type: dataType}));
                    
                        // Hide download link
                        downloadLink.style.display = "none";
                    
                        // Add the link to DOM
                        document.body.appendChild(downloadLink);
                    
                        // Click download link
                        downloadLink.click();
                    }
                    </script>';
        
        $diccionario = array(
            'titulo'=>'<h5><b><i class="fa fa-home"></i> Datos de Levantamiento</b></h5>', 
            'contenido'=>$contenido);
            $this->mostrarVista($diccionario);
    }

     #mostrar datos de sujeto para editar o agregar información 
  public function reporteSujetoPdf($kSujeto){
    $codigoTxt=new ControladorSujeto;
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarSujeto($kSujeto);
    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    $vinculos="";
    $no_vinculos=1;
    $qr_create="";
    $qr_unique=array();
    foreach ($rE as $key => $value) {
        //obtener url de imagen
        $imagenes = new ModeloRegistroUrl;
        $r_img=$imagenes->mostrarUrlImagenes($value['kUrl']);
   //Evitar que se muestren comparables repetidos
    if (!in_array($value['kUrl'], $qr_unique)) {
      $vinculos.="<tr><td><b>Comparable ".$no_vinculos.": ".$value['sNombreCliente']." </b></td><td>Enlace corto: ".$GLOBALS['url'].'/'.$value['sShortUrl']." </td></tr>";
      $vinculos.="<tr><td>Enlace: ".$value['sUrl']."</td><td> <div class='p w3-white' style='width:160px' id='codigo-qr-".$value['kUrl']."'></div> </td></tr>";
      $vinculos.="<tr><td>Nota: ".$value['sNota']."</td><td> Fecha comparable: ".$value['dtFecha']."  </td></tr>";
      if($value["bImg"]=='1'){
        $vinculos.="<tr><td><img src='".$GLOBALS["url"].'/files/img/img-'.$value['kUrl'].".png' alt='img' style='width:100%;'></tr></td>";
        }
      foreach ($r_img as $key => $ri) {
        $vinculos.="<tr><td><img src='".$GLOBALS['url']."/".$ri['sNameImg']."' alt='img' style='width:100%;'></tr></td>";
      }
      $no_vinculos++;
       //crear funcion genera imagen de qr
      $qr_create.='createQR("'.$GLOBALS['url'].'/'.$value['sShortUrl'].'", "codigo-qr-'.$value['kUrl'].'");';

    array_push($qr_unique, $value['kUrl']);
    }
    }
    //termina enlaces a comparables
    //enlace fotografías
    $fotos= new ModeloSujeto;
    $rF=$fotos->fotos($kSujeto);
    $fotografias="<tr><td><b>Fotografías del sujeto:</b></td><td></td></tr>";
    foreach ($rF as $key => $value) {
      $lnfixi = $key % 2 != 0 ? "" : "<tr><td>";
      $tablefixm = $key % 2 != 0 ? "" : "</td><td>";
      $fotografias.=$lnfixi."<img src='".$GLOBALS['url'].'/'.$value['sNameImg']."' alt='img' style='width: 100%;'>".$tablefixm;
    }
    //termina enlace fotografías
    //información extra
    $info= new ModeloSujeto;
    $rI=$info->mostrarInformacion($kSujeto);
    $informacion="";
    foreach ($rI as $key => $value) {
      $informacion.="<tr><td>".$value['sTitulo']."</td><td>".$value['sInfo']."</td></tr>";
    }
    //termina información extra
    $contenido='<div class="w3-white p">
                    <button class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge w3-right m noprint" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button>
                        <table id="tabla" class="w3-table w3-striped w3-bordered w3-hoverable">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>';
      $contenido.= '<tr><td>Solicita</td><td>'.$r[0]['sNombreSolicita'].'</td></tr>
                    <tr><td>Fecha</td><td>'.$r[0]['dtFechaAvaluo'].'</td></tr>
                    <tr><td>Objeto Valuación</td><td>'.$codigoTxt->objetivosAvaluo($r[0]['nObjetoAvaluo']).'</td></tr>
                    <tr><td>Domicilio</td><td>'.$r[0]['sDomicilio'].'</td></tr>
                    <tr><td>CP</td><td>'.$r[0]['nCP'].'</td></tr>
                    <tr><td>M<sup>2</sup> Terreno</td><td>'.$r[0]['nMTerreno'].'</td></tr>
                    <tr><td>M<sup>2</sup> Construcción</td><td>'.$r[0]['nMConstruccion'].'</td></tr>
                    <tr><td>Antigüedad</td><td>'.$r[0]['nAntiguedad'].'</td></tr>
                    <tr><td>Tipo Propiedad</td><td>'.$codigoTxt->tipoPropiedad($r[0]['nTipoPropiedad']).'</td></tr>
                    <tr><td>Tipo Vivienda</td><td>'.$codigoTxt->tipoVivienda($r[0]['nTipoVivienda']).'</td></tr>
                    <tr><td>Estado Conservación</td><td>'.$codigoTxt->estadoConservacion($r[0]['nEstadoConservacion']).'</td></tr>
                    <tr><td>Nota General</td><td>'.$r[0]['nNotaGeneral'].'</td></tr>
                    <tr><td>Fecha Registro</td><td>'.$r[0]['dtFechaRegistro'].'</td></tr>

 <!-- datos extra -->  
              '.$informacion.'           
              '.$fotografias.'
              '.$vinculos.'
              
                </tbody>
            </table>
        </div>

<!-- termina datos extra -->
    <script> 
    //genera imagen de qr
    function createQR($url, $id) {
        //genera imagen de qr
        const codigoQRDiv = document.getElementById($id);
        const codigoQR = new QRCode(codigoQRDiv, {
        text: $url,
        width: 128,
        height: 128
        });
    }
    '.$qr_create.'
    </script>
    ';
    $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-home"></i> INFORMACIÓN DE '.$r[0]['sNombreSolicita'].'</b></h5>', 
        'contenido'=>$contenido);
        $this->mostrarVista($diccionario);
  }

}
?>