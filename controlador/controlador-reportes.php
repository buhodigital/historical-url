<?php
include_once('modelo/modelo-reportes.php');

class PDF extends FPDF
{
//####### manejar html ##########
  protected $B = 0;
  protected $I = 0;
  protected $U = 0;
  protected $HREF = '';
  
  function WriteHTML($html)
  {
      // Intérprete de HTML
      $html = str_replace("\n",' ',$html);
      $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
      foreach($a as $i=>$e)
      {
          if($i%2==0)
          {
              // Text
              if($this->HREF)
                  $this->PutLink($this->HREF,$e);
              else
                  $this->Write(5,$e);
          }
          else
          {
              // Etiqueta
              if($e[0]=='/')
                  $this->CloseTag(strtoupper(substr($e,1)));
              else
              {
                  // Extraer atributos
                  $a2 = explode(' ',$e);
                  $tag = strtoupper(array_shift($a2));
                  $attr = array();
                  foreach($a2 as $v)
                  {
                      if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                          $attr[strtoupper($a3[1])] = $a3[2];
                  }
                  $this->OpenTag($tag,$attr);
              }
          }
      }
  }
  
  function OpenTag($tag, $attr)
  {
      // Etiqueta de apertura
      if($tag=='B' || $tag=='I' || $tag=='U')
          $this->SetStyle($tag,true);
      if($tag=='A')
          $this->HREF = $attr['HREF'];
      if($tag=='BR')
          $this->Ln(5);
  }
  
  function CloseTag($tag)
  {
      // Etiqueta de cierre
      if($tag=='B' || $tag=='I' || $tag=='U')
          $this->SetStyle($tag,false);
      if($tag=='A')
          $this->HREF = '';
  }
  
  function SetStyle($tag, $enable)
  {
      // Modificar estilo y escoger la fuente correspondiente
      $this->$tag += ($enable ? 1 : -1);
      $style = '';
      foreach(array('B', 'I', 'U') as $s)
      {
          if($this->$s>0)
              $style .= $s;
      }
      $this->SetFont('',$style);
  }
  
  function PutLink($URL, $txt)
  {
      // Escribir un hiper-enlace
      $this->SetTextColor(0,0,255);
      $this->SetStyle('U',true);
      $this->Write(5,$txt,$URL);
      $this->SetStyle('U',false);
      $this->SetTextColor(0);
  }
  
//######### numerar pie de página ########
    function Footer()
    {
      $pagina = utf8_decode('Página ');
        // Posición a 1,5 cm del final
        $this->SetY(-15);
        // Arial itálico 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,5,utf8_decode("Elaboró: ".$GLOBALS['usuario_nombre']),0,0,'C');
        $this->Ln();
        $this->Cell(0,5,$pagina.$this->PageNo().'/{nb}',0,0,'C');
    }

//######### Manejo de tablas #########
    // Cargar los datos
function LoadData($file)
{
    // Leer las líneas del fichero
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(',',trim($line));
    return $data;
}

// Tabla simple
function BasicTable($header, $data)
{
    // Cabecera
    foreach($header as $col)
        $this->Cell(27,4,$col,1);
    $this->Ln();
    // Datos
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(27,4,$col,1);
        $this->Ln();
    }
}


}

class ControladorReportes extends FPDF{

  // ############## Reporte estimación de valor ########################################
  public function reporteOpinionValor($sujeto){
    //Obener datos de sujeto
    $info= new ModeloSujeto;
    $isujeto=$info->mostrarSujeto($sujeto);
    //calculo de coeficiente de ocupación de suelo
    if($isujeto[0]['nMTerreno']!=0||$isujeto[0]['nMConstruccion']!=0){
      $CUS=$isujeto[0]['nMConstruccion']/$isujeto[0]['nMTerreno'];
    }else{
      $CUS=0;
    }
    $formato=new ControladorSujeto;
    $v_CUS=$formato->convertirCus($CUS);
    $t_CuS=$formato->cusTexto($v_CUS);
    //termina obtener datos sujeto
    //obtener datos opinion de valor
    //obtener datos capturados de caracteristicas de homologación
/*$d: 
    Primeros 7 datos corresponden al sujeto 0-6
      [0]=get(kSujeto), post1([1]=ubicacion, [2]=servicios, [3]=conservacion, [4]=proyecto,[5]=demanda), [6]=cus, 
    los siguientes 2 datos corresponden a la cantidad e identificadores de los comparables
      |-> [7]=n-comp,[8]=kcomparables 7-8
    el resto de información corresponde a los comparables 9- ...
      |-> [9]=post2comparables(negociacio-id, ubicacion-id, servicios-id, conservacion-id, proyecto-id)*/
      $datos_capturados = new ModeloSujeto;
      $dc=$datos_capturados->datosCaracteristicas($sujeto);
      $d=json_decode($dc[0]['sOpinionValor'],true);
      $id_comp=explode(',',$d[8]);//id comparables
      $d_comp=$d; 
      array_splice($d_comp, 0, 9);//datos comparables

    //termina obtener datos capturados de caracteristicas de homologación
    //termina obtener datos opinion de valor
    $titulo = utf8_decode('Opinión de Valor');
    $nota = utf8_decode('*****************************************************************************************');
    
    $html = utf8_decode('El presente informe se generó por medio de la plataforma <a href="http://www.urlh.org">www.urlh.org</a> y tiene como finalidad proporcionar una estimación de valor mediante el enfoque de mercado del inmueble que se describe a continuación:<br>');
      $fecha = new DateTime($isujeto[0]['dtFechaAvaluo']);
      $fechaAvaluo= $fecha->format('d-m-Y');
      $fehchaAvaluo=new DateTime();
      $nombre=utf8_decode($isujeto[0]['sNombreSolicita']);
      $domicilio=utf8_decode($isujeto[0]['sDomicilio']);
      $m2Terreno=$isujeto[0]['nMTerreno'];
      $m2Construccion=$isujeto[0]['nMConstruccion'];
      $construccion=$isujeto[0]['nAntiguedad'];
      $antiguedad=$fecha->format('Y')-$isujeto[0]['nAntiguedad'];
    //Convertir valor de enfMercado a dato numerico #Dato : "c" = calificación, "d" = demanda, "n" = negociación 
      $ubicacion=$d[1]." '".$formato->convertirValorTexto($d[1],"c")."'";
      $servicios=$d[2]." '".$formato->convertirValorTexto($d[2],"c")."'";
      $conservacionEstimado=$d[3]." '".$formato->convertirValorTexto($d[3],"c")."'";
      $proyecto=$d[4]." '".$formato->convertirValorTexto($d[4],"c")."'";
      $demanda=$d[5]." '".$formato->convertirValorTexto($d[5],"d")."'";
      $tVivienda=$formato->tipoVivienda($isujeto[0]['nTipoVivienda']);
      $tPropiedad=$formato->tipoPropiedad($isujeto[0]['nTipoPropiedad']);
      $conservacion=$formato->estadoConservacion($isujeto[0]['nEstadoConservacion']);
      $consecutivo=$sujeto." / ".$fecha->format('Y');
/* Datos Sujeto */      
      $sujeto=utf8_decode("<b>Solicitante</b> (Sujeto): <b>*** ".$nombre." ***</b><br><b>Fecha del avalúo</b>: ".$fechaAvaluo."<br><b>Domicilio:</b> ".$domicilio."<br><b>M2 terreno:</b> ".$m2Terreno."<br><b>M2 construcción:</b> ".$m2Construccion."<br><b>Construcción:</b> ".$construccion." (Antigüedad: ".$antiguedad." años)<br><b>Tipo de propiedad:</b> ".$tPropiedad."<br><b>Tipo de vivienda:</b> ".$tVivienda."<br><b>Estado de conservación:</b> ".$conservacion."<br>");
/*

."<br>Coeficiente de utilización de suelo (CUS): ".$v_CUS." '$t_CuS'<br>Ubicación dentro de la colonia: ".$ubicacion."<br>Calidad de servicios públicos: ".$servicios."<br>Estado de conservación estimado: ".$conservacionEstimado."<br>Calidad del proyecto constructivo: ".$proyecto."<br>Nivel de oferta y demanda: ".$demanda

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(190,10,$titulo,0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(190,8,$nota,0,1,'C');
    $pdf->Output();

   
*/

$pdf = new PDF();
$pdf->SetTitle(utf8_decode('Reporte: Opinión de valor'));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190, 0,"No. ".$consecutivo, 0, 0, 'R');
$pdf->Ln();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(190,10,$titulo,0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(190,10,$nota,0,1,'C');
// ... Contenido
$pdf->WriteHTML($html);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->WriteHTML($sujeto);
$pdf->Ln();

// probar datos de comparables
$pdf->SetFont('Arial','',12);
//Características
$pdf->WriteHTML(utf8_decode("Características del sujeto y comparables:"));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(18, 7, 'ID', 1, 0, 'C');
$pdf->Cell(46, 7, 'URL', 1, 0, 'C');
$pdf->Cell(25, 7, utf8_decode('SV (m2)'), 1, 0, 'C');
$pdf->Cell(25, 7, utf8_decode('ST (m2)'), 1, 0, 'C');
$pdf->Cell(49, 7, utf8_decode('VUM (m2)'), 1, 0, 'C');
$pdf->Cell(26, 7, utf8_decode('CUS'), 1, 0, 'C');
$pdf->Ln();


$pdf->Cell(18, 5, "Sujeto", 1, 0, 'C');
$pdf->Cell(46, 5, "***", 1, 0, 'C');
$pdf->Cell(25, 5, $m2Construccion, 1, 0, 'C');
$pdf->Cell(25, 5, $m2Terreno, 1, 0, 'C');
$pdf->Cell(49, 5, "***", 1, 0, 'C');
$pdf->Cell(26, 5, $t_CuS, 1, 0, 'C');
$pdf->Ln();


foreach ($id_comp as $key => $values) {
    $c_url_caracteristicas=new ModeloRegistrourl;
    $uc=$c_url_caracteristicas->mostrarRegistro($values);

    /// calculo de precio de venta
    if($uc['nPrecioVenta']>0 && $uc['nSupConst'] > 0){
        $precioventa="$ ".number_format($uc['nPrecioVenta']/$uc['nSupConst'], 2, '.', ',');
    }else{
        $precioventa="-";
    }
    //calculo de Coeficiente de utilización de suelo
    if($uc['nSupConst']>0 && $uc['nSupTerr']>0){
        $CUS_COMP=$uc['nSupConst']/$uc['nSupTerr'];
        $v_CUS_c=$formato->convertirCus($CUS_COMP);
        $t_CuS_c=$formato->cusTexto($v_CUS_c);
    }else{  
        $t_CuS_c="-";
    }
    $pdf->Cell(18, 5, "C-".$values, 1, 0, 'C');
    $pdf->Cell(46, 5, $GLOBALS['url']."/".$uc['sShortUrl'],1, 0, 'C');
    $pdf->Cell(25, 5, $uc['nSupConst'],1, 0, 'C');
    $pdf->Cell(25, 5, $uc['nSupTerr'],1, 0, 'C');
    $pdf->Cell(49, 5, $precioventa,1, 0, 'C');
    $pdf->Cell(26, 5, $t_CuS_c, 1, 0, 'C'); 

    $pdf->Ln(); // Nueva línea para la siguiente fila
}

    //Información de siglas
    $pdf->SetFont('Arial','',7.5);
    $pdf->WriteHTML(utf8_decode('"C: Comparable, SV: Superficie en Venta (construcción), ST: Superficie de Terreno, VUM: Valor Unitario de M2, CUS: Coeficiente de Utilización de Suelo"'));
//$pdf->AddPage();
$pdf->Ln();

//Homologación
$pdf->SetFont('Arial','',12);
$pdf->WriteHTML(utf8_decode("Homologación:"));
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(18, 7, 'ID', 1, 0, 'C');
$pdf->Cell(20, 7, 'NEG', 1, 0, 'C');
$pdf->Cell(20, 7, 'UDC', 1, 0, 'C');
$pdf->Cell(20, 7, 'CSP', 1, 0, 'C');
$pdf->Cell(20, 7, 'EC', 1, 0, 'C');
$pdf->Cell(20, 7, 'PRO', 1, 0, 'C');
$pdf->Cell(20, 7, 'CUS', 1, 0, 'C');
$pdf->Cell(20, 7, 'F.R.', 1, 0, 'C');
$pdf->Cell(31, 7, 'VALOR', 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(18, 5, "Sujeto", 1, 0, 'C');
$pdf->Cell(20, 5, "***", 1, 0, 'C');
$pdf->Cell(20, 5, $d[1], 1, 0, 'C');
$pdf->Cell(20, 5, $d[2], 1, 0, 'C');
$pdf->Cell(20, 5, $d[3], 1, 0, 'C');
$pdf->Cell(20, 5, number_format(round($d[4],2),2), 1, 0, 'C');
$pdf->Cell(20, 5, number_format(round($v_CUS/100,2),2), 1, 0, 'C');
$pdf->Cell(20, 5, "***", 1, 0, 'C');
$pdf->Cell(31, 5, "***", 1, 0, 'C');
$pdf->Ln();

$j=0;
$s=0;
foreach ($id_comp as $key => $values) {
    $pdf->Cell(18, 5, "C-".$values, 1, 0, 'C');
    //obtencio de datos de registros de comparables
    $c_url=new ModeloRegistrourl;
    $u=$c_url->mostrarRegistro($values);
    //calculo de CUS de comparables
    if($u['nSupConst']>0 && $u['nSupTerr']>0){
        $CUS_COMP=$u['nSupConst']/$u['nSupTerr'];
        $v_CUS_c=$formato->convertirCus($CUS_COMP)/100;
    }else{  
        $v_CUS_c=0;
    }

    for ($i = 0; $i < 5; $i += 1){
        //repetir datos de sujeto en cada celda
        if($s==0||$s==5){
            $s==0?$s=0:$s=0;
            $mult=$d_comp[$j];
            $fr=$d_comp[$j];
        }else{
            $mult=$d[$s]/$d_comp[$j];
            $fr*=round($mult,2);
        }
        //calcular homologación
        if($mult>0&&$d_comp[$j]>0){
            $homologacion=number_format(round($mult,2),2);
            //number_format(round($mult,2),2)
        }else{
            $homologacion=0;
        }
        
        //imprimir valor resultante
        $pdf->Cell(20, 5, $homologacion, 1, 0, 'C');
        $j++;
        
        $s++;
        if ($s > 5) {
            $s = 1;
        }
        
    }

    //calculo de factor resultante
    $fr=$fr*round($v_CUS_c,2);

    if($u['nPrecioVenta']>0 && $u['nSupConst'] > 0){
        $valor_c=(round($u['nPrecioVenta']/$u['nSupConst'],2))*round($fr,2);
    }else{
        $valor_c=0;
    }

            
            $pdf->Cell(20, 5, number_format(round($v_CUS_c,2),2),1, 0, 'C');
            $pdf->Cell(20, 5, number_format(round($fr,2),2), 1, 0, 'C');
            $pdf->Cell(31, 5, number_format($valor_c,2),1, 0, 'C');
    $pdf->Ln(); // Nueva línea para la siguiente fila

    //suma de valores
    $suma_valor+=$valor_c;

    
}

//promedio de valores
$promedio_valor=round($suma_valor/$d[7],2);
//Valor unitario de mercado
$valor_mercado=round($promedio_valor*$d[5],2);
//Valor de mercado total
$total=$m2Construccion*$valor_mercado;


//imprimir valores
$pdf->SetFont('Arial','',8);
$pdf->Cell(98, 5, "Si F.R. > 1, entonces el sujeto es mejor que el comparable",0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60, 5, "Valor unitario homologado:",0, 0, 'R');
$pdf->Cell(31, 5, "$ ".number_format($promedio_valor,2),1, 0, 'C');
$pdf->Ln();
$pdf->SetFont('Arial','',8);
$pdf->Cell(98, 5, "Si F.R. < 1, entonces el comparable es mejor que el sujeto",0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(60, 5, "Nivel de ofera y demanda (OYD):",0, 0, 'R');
$pdf->Cell(31, 5, number_format($d[5],2),1, 0, 'C');
$pdf->Ln();
$pdf->Cell(98, 5, "",0);
$pdf->Cell(60, 5, "Valor unitario de mercado:",0, 0, 'R');
$pdf->Cell(31, 5, "$ ".number_format($valor_mercado,2),1, 0, 'C');
$pdf->Ln();

//Valor final
// probar datos de comparables
$pdf->Ln();
$pdf->SetFont('Arial','',11);
$pdf->Cell(40, 7, 'SUPERFICIE', 1, 0, 'C');
$pdf->Cell(30, 7, 'UNIDAD', 1, 0, 'C');
$pdf->Cell(49, 7, 'VALOR UNITARIO', 1, 0, 'C');
$pdf->Cell(70, 7, 'VALOR COMERCIAL', 1, 0, 'C');
$pdf->Ln();
$pdf->Cell(40, 7, $m2Construccion, 1, 0, 'C');
$pdf->Cell(30, 7, "m2", 1, 0, 'C');
$pdf->Cell(49, 7, "$ ".number_format($valor_mercado,2),1, 0, 'C');
$pdf->Cell(70, 7, "$ ".number_format($total,2),1, 0, 'C');
$pdf->Ln();




########################Segunda página############################################

/*Encabezado de información complementaria */      
$informacion=utf8_decode("<b>Información complementaria</b>
<br>En el contexto de la valuación inmobiliaria el <b>'sujeto'</b> se refiere al inmueble que está siendo valuado, el <b>'comparable'</b> se refiere a un inmueble que es similar al sujeto en términos de ubicación, características y superficie y <b>'homologación'</b> se refiere al proceso de ajustar y comparar las características de un inmueble sujeto con las de otros inmuebles similares o comparables. A continuación se enlista el significado de las siglas utilizadas en el presente informe. Adicionalmente, se presentan los parámetros tomados en cuenta para valorar las caracteristicas de los comparables, así como, del sujeto analizado. <br>
<br><b>SV</b>: Superficie vendible.
<br><b>ST</b>: Superficie de terreno.
<br><b>VUM</b>: Valor Unitario de la oferta o venta.
<br><b>NEG</b>: Negociación de la oferta.
<br><b>UDC</b>: Ubicación dentro de la colonia.
<br><b>CSP</b>: Calidad de servicios públicos.
<br><b>EC</b>: Estado de conservación.
<br><b>PRO</b>: Proyecto.
<br><b>CUS</b>: Coeficiente de Utilización del Suelo.
<br><b>OYD</b>: Nivel de oferta y demanda.
<br><b>F.R.</b>: Factor resultante de las diferentes características consideradas en la homoloación.<br>

<br><b>Parámetros considerados en factores aplicables</b></br>
<br>Negociación: considera las negociaciones aplicables (Ej. Precio de anuncios con margen de negociación)
<br>Calidad: considera UDC, CSP, EC, y PRO aplicables y calificación
<br>Demanda: considera oferta y demanda (OYD) aplicables (Viviendas disponibles en la zona)
<br>");
$nota_final=utf8_decode("<br><b>CONSIDERACIONES PREVIAS A LA CONCLUSION</b>													
<br>1.- Los datos y hechos proporcionados en el presente estudio son verdaderos y correctos a nuestro saber y entender. 2.- Son análisis, opiniones y conclusiones de tipo profesional y están solamente limitadas por los supuestos y condiciones limitantes. 3.- Los análisis, opiniones y conclusiones reportados corresponden a un estudio profesional totalmente imparcial. 4.- No existe por nuestra parte ningún interés presente o futuro en la propiedad valuada. 5.- Los honorarios no están relacionados con el hecho de determinar un valor predeterminado o en la dirección que favorezca la causa del cliente, el monto del valor estimado, la obtencion de un resultado estipulado o la ocurrencia de un evento subsecuente. 6.- Personalmente  hice  la  inspección  de  los  bienes  objeto  de  este avaluo y manifestamos que los resultados serán guardados con absoluta confidencialidad.");

$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190, 0,"No. ".$consecutivo, 0, 0, 'R');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML($informacion);
$pdf->Ln();
//Métricas para valoración
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("Factores aplicables:");
$pdf->Ln();
// Títulos de las columnas
$header = array(utf8_decode('*Negociación:'));
// Carga de datos
$data = $pdf->LoadData($GLOBALS['$url'].'assets/factores.txt');
$pdf->SetFont('Arial','',10);
$pdf->BasicTable($header,$data);
//$pdf->AddPage();
$pdf->Ln();

//valoración de características particulares
//datos particulares
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML(utf8_decode("Valoración de características de los comparables:"));
$pdf->Ln();
$pdf->Cell(18, 7, 'ID', 1, 0, 'C');
$pdf->Cell(46, 7, 'PRECIO DE VENTA', 1, 0, 'C');
$pdf->Cell(25, 7, "NEG", 1, 0, 'C');
$pdf->Cell(25, 7, "UDC", 1, 0, 'C');
$pdf->Cell(25, 7, 'CSP', 1, 0, 'C');
$pdf->Cell(25, 7, "EC", 1, 0, 'C');
$pdf->Cell(25, 7, 'PRO', 1, 0, 'C');
$pdf->Ln();

/*
$pdf->Cell(18, 5, "Sujeto", 1, 0, 'C');
$pdf->Cell(46, 5, "***", 1, 0, 'C');
$pdf->Cell(25, 5, "***", 1, 0, 'C');
$pdf->Cell(25, 5, $d[1], 1, 0, 'C');
$pdf->Cell(25, 5, $d[2], 1, 0, 'C');
$pdf->Cell(25, 5, $d[3], 1, 0, 'C');
$pdf->Cell(25, 5, $d[4], 1, 0, 'C');
$pdf->Ln();
*/

$j=0;
foreach ($id_comp as $key => $values) {
    $pdf->Cell(18, 5, "C-".$values, 1, 0, 'C');
    $c_url=new ModeloRegistrourl;
    $u=$c_url->mostrarRegistro($values);
    $pdf->Cell(46, 5, "$ ".number_format($u['nPrecioVenta'],2),1, 0, 'C');

    for ($i = 0; $i < 5; $i += 1){
        $pdf->Cell(25, 5, number_format($d_comp[$j],2), 1, 0, 'C');
        $j++;
    }
    $pdf->Ln(); // Nueva línea para la siguiente fila
} 
$pdf->SetFont('Arial','',8);
$pdf->WriteHTML($nota_final);

$pdf->Output('I', $nombre.'.pdf');

}

}
?>