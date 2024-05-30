<?php
include_once('modelo/modelo-sujeto.php');

class ControladorSujeto{
//Método mostrar vista para la clase Registro URL
private function mostrarVista($diccionario){   
    $template = file_get_contents('vista/plantillas/plantilla-general.html');
    foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
    print $template;
  }

  public function objetivosAvaluo($objetivo=null){
    #lista de opciones para el objetivo del avalúo
    $opciones= array(
      'Conocer el valor comercial',
      'Originación de crédito', 
      'Recuperación', 
      'Adjudicación', 
      'Dación de pago', 
      'Otro'
    );
    #si el objetivo es número
    if(is_numeric($objetivo)){
      return $opciones[$objetivo];
    }else {
      $lista="";
      foreach ($opciones as $key => $value) {
        $lista.="<option value='".$key."'>".$value."</option>";
      }
      return $lista;
    }
  }

  public function tipoPropiedad($tipo=null){
    #lista de opciones para el tipo de propiedad
    $opciones= array(
      'Casa habitación',
      'Terreno',
      'Local comercial',
      'Otro'
    );
    #si el tipo es número
    if(is_numeric($tipo)){
      return $opciones[$tipo];
    }else {
      $lista="";
      foreach ($opciones as $key => $value) {
        $lista.="<option value='".$key."'>".$value."</option>";
      }
      return $lista;
    }
  }

  public function tipoVivienda($tipo=null){
    #lista de opciones para el tipo de vivienda
    $opciones= array(
      'No aplica',
      'Económica',
      'Popular',
      'Tradicional',
      'Media',
      'Residencial',
      'Residencial Plus'
    );
    #si el tipo es número
    if(is_numeric($tipo)){
      return $opciones[$tipo];
    }else {
      $lista="";
      foreach ($opciones as $key => $value) {
        $lista.="<option value='".$key."'>".$value."</option>";
      }
      return $lista;
    }
  }

  public function estadoConservacion($estado=null){
    #lista de opciones para el estado de conservación
    $opciones= array(
      'No aplica',
      'Remodelado',
      'Nuevo',
      'Muy bueno',
      'Bueno',
      'Regular',
      'Malo',
      'Ruinoso'
    );
    #si el estado es número
    if(is_numeric($estado)){
      return $opciones[$estado];
    }else {
      $lista="";
      foreach ($opciones as $key => $value) {
        $lista.="<option value='".$key."'>".$value."</option>";
      }
      return $lista;
    }
  }

  // ##########Factores de ajuste de enfoque de mercado #################
  public function enfMercado($tipo,$dato=null){
    #lista de opciones [0]=valor, [1]=Calificación, [2]=Negociación (Demanda) enfMercado("c/d",0) o enfMercado("c/d")
    #0=excelente, 1=muy buena, 2=buena, 3=regular, 4=deficiente, 5=mala, 6=pésima/nula, 7=seleccionar
    #0=Demanda Máxima, 1=Demanda Alta, 2=Demanda Media, 3=Demanda Baja, 4=Demanda Muy baja, 5=Demanda Escasa, 6=Demanda Nula, 7=Seleccionar
    #Dato : "c" = calificación, "d" = demanda, "n" = negociación 
    switch ($tipo) {
      case 'd':
          $i=2;
        break;
      case 'n':
          $i=3;
        break;
      default:
          $i=1;
        break;
    }
    $opciones= array(
      '1,Excelente,Demanda Buena,Venta',
      '0.95,Muy buena,Demanda regular,Demanda Alta',
      '0.90,Buena,Demanda Media,Demanda Media',
      '0.85,Regular,Demanda Baja,Demanda Baja',
      '0.80,Deficiente,Demanda Muy baja,Demanda Muy baja',
      '0.75,Mala,Demanda Escasa,Demanda Escasa',
      '0.70,Pésima/Nula,Demanda Nula,Demanda Nula',
      '0,Seleccionar,Seleccionar,Seleccionar'
    );
    if(is_numeric($dato)){
      $v=explode(',',$opciones[$dato]);
      return "<option value='".$v[0]."'>***".$v[$i]."</option>";
    }else {
      $lista="";
      foreach ($opciones as $key => $value) {
        //se omite el último valor de la lista
        if($key!=7){
        $v=explode(',',$value);
        $lista.="<option value='".$v[0]."'>".$v[$i]."</option>";
        }
      }
      return $lista;
    }
  }

  //Convertir valor de enfMercado a dato numerico
  public function convertirCalificacion($dato){
    switch ($dato) {
      case "1":
          return "0";
      case "0.95":
          return "1";
      case "0.9":
          return "2";
      case "0.85":
          return "3";
      case "0.80":
          return "4";
      case "0.75":
          return "5";
      case "0.70":
          return "6";
      case "0":
          return "7";
      default:
          return "";
    }
  }
//Convertir valor de enfMercado a dato numerico #Dato : "c" = calificación, "d" = demanda, "n" = negociación 
    public function convertirValorTexto($dato,$valor){
      if($valor=="c"){
        switch ($dato) {
          case "1":
              return "Excelente";
          case "0.95":
              return "Muy buena";
          case "0.9":
              return "Buena";
          case "0.85":
              return "Regular";
          case "0.80":
              return "Deficiente";
          case "0.75":
              return "Mala";
          case "0.70":
              return "Pésima/Nula";
          case "0":
              return "-";
          default:
              return "";
        }
      }else if($valor=="d"){
        switch ($dato) {
          case "1":
              return "Demanda Buena";
          case "0.95":
              return "Demanda regular";
          case "0.9":
              return "Demanda Media";
          case "0.85":
              return "Demanda Baja";
          case "0.80":
              return "Demanda Muy baja";
          case "0.75":
              return "Demanda Escasa";
          case "0.70":
              return "Demanda Nula";
          case "0":
              return "-";
          default:
              return "";
        }
      }else if($valor=="n"){
        switch ($dato) {
          case "1":
            return "Venta";
        case "0.95":
            return "Demanda regular";
        case "0.9":
            return "Demanda Media";
        case "0.85":
            return "Demanda Baja";
        case "0.80":
            return "Demanda Muy baja";
        case "0.75":
            return "Demanda Escasa";
        case "0.70":
            return "Demanda Nula";
        case "0":
            return "-";
        default:
            return "";
        }
      }
    }
  
  //calculo de cus y conversion a texto
  public function convertirCus($D14) {
    if ($D14 < 0.2) {
        return "70";
    } else if ($D14 < 0.33) {
        return "80";
    } else if ($D14 < 0.5) {
        return "90";
    } else if ($D14 < 1 || $D14 < 1.5) {
        return "100";
    } else if ($D14 < 2) {
        return "90";
    } else if ($D14 < 3) {
        return "80";
    } else if ($D14 > 3) {
        return "70";
    } else {
        return "Valor no reconocido";
    }
} 
public function cusTexto($B14) {
  switch ($B14) {
      case "70":
      case "75":
      case "80":
          return "DEFICIENTE";
      case "85":
          return "Regular";
      case "90":
          return "Bueno";
      case "95":
          return "Muy Bueno";
      case "100":
          return "Excelente";
      default:
          return "Valor no reconocido";
  }
}
 
  public function inicio(){
    //variable que almacena el día de hoy
    $hoy = date("Y-m-d");
    $contenido= '<form action="'.$GLOBALS['url'].'/option/sujeto/agregar/0" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Datos del Sujeto</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante:</label>
                                    <input type="text" id="nombreSolicitante" name="nombreSolicitante" maxlength="60" style="text-transform:uppercase" required=""><br><br>

                                    <label for="fechaAvaluo">Fecha del avalúo:</label>
                                    <input type="date" id="fechaAvaluo" name="fechaAvaluo" value="'.$hoy.'"><br><br>

                                    <label for="objetivoAvaluo">Objetivo de avalúo:</label>
                                    <select id="objetivoAvaluo" name="objetivoAvaluo">
                                        "'.$this->objetivosAvaluo().'"                                        
                                    </select><br><br>

                                    <label for="domicilio">Domicilio:</label>
                                    <input type="text" id="domicilio" name="domicilio" maxlength="75" required=""><br><br>

                                    <label for="codigoPostal">Código Postal:</label>
                                    <input type="text" id="codigoPostal" name="codigoPostal" max="99999" placeholder="81200" required=""><br><br>

                                    <label for="metrosCuadradosTerreno">Metros cuadrados de terreno:</label>
                                    <input type="number" id="metrosCuadradosTerreno" name="metrosCuadradosTerreno" step="0.01" max="9999.99" required=""><br><br>

                                    <label for="metrosCuadradosConstruccion">Metros cuadrados de construcción:</label>
                                    <input  type="number" id="metrosCuadradosConstruccion" name="metrosCuadradosConstruccion" step="0.01" max="9999.99" required=""><br><br>

                          </div>
                          <div class="w3-col m6  p">


                                    <label for="anioConstruccion">Año de construcción:</label>
                                    <input type="number" id="anioConstruccion" name="anioConstruccion" min="1900" max="2100" placeholder="1990" required=""><br><br>

                                    <label for="tipoPropiedad">Tipo de Propiedad:</label>
                                        <select id="tipoPropiedad" name="tipoPropiedad">
                                            "'.$this->tipoPropiedad().'"
                                        </select><br><br>

                                    <label for="tipoVivienda">Tipo de vivienda:</label>
                                    <select id="tipoVivienda" name="tipoVivienda" title="Vivienda tipo: 
***Económica. Superficie construida en promedio: 40m2, cuentan con 1 baño, cocina, área de usos múltiples.
***Popular. Superficie construida en promedio: 50m2, cuentan con 1 baño, cocina, estancia, comedor, de 1 a 2 recámaras y 1 cajón de estacionamiento.
***Tradicional. Superficie construida en promedio: 71m2, cuentan con 1 y ½ baños, cocina, estancia-comedor, de 2 a 3 recámaras, 1 cajón de estacionamiento.
***Media. Superficie construida en promedio: 102m2, cuentan con 2 baños, cocina, sala, comedor, de 2 a 3 recámaras, cuarto de servicio y 1 a 2 cajones de estacionamiento.
***Residencial. Superficie construida en promedio: 156m2, cuentan con 3 a 4 baños, cocina, sala, comedor, de 3 a 4 recámaras, cuarto de servicio, sala familiar y 2 o 3 cajones de estacionamiento.
***Residencial plus. Superficie construida en promedio: más de 156m2, cuentan con más de 4 baños, cocina, sala, comedor, más de 4 recámaras, cuarto de servicio, sala familiar y más de 3 cajones de estacionamiento.
                                    "> "'.$this->tipoVivienda().'"
                                    </select><br><br>

                                    <label for="estadoConservacion">Estado de conservación:</label>
                                    <select id="estadoConservacion" name="estadoConservacion">
                                        "'.$this->estadoConservacion().'"
                                    </select><br><br>

                                    <label for="generales">Notas Generales:</label><br>
                                    <textarea  id="generales" name="generales" rows="4" style="width:100%;" placeholder="(Máximo 240 caracteres)" maxlength="240"></textarea><br><br>

                                    
                            </div>
                          </div>
                    <div class="p">
                    <input class="w3-button w3-blue" type="submit" value="Registrar"><br>
                    </div>
              </form>

            <script>
              //Verificar que esté seleccionado casa habitación para poder selecionar el tipo de vivienda
              document.getElementById("tipoPropiedad").addEventListener("change", function() {
                    if (this.value !== "0") {
                        document.getElementById("tipoVivienda").value = "0";
                    }
                });
            </script>
          ';
    $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-home"></i> Levantamiento</b></h5>                 
        <small><i class="fa fa-info-circle"></i> Después de registrar el sujeto a valuar, podrá modificarlo y agregar más información, así como, agregar fotografías y vincularlo con las url de los comparables.</small>', 
        'contenido'=>$contenido);
        $this->mostrarVista($diccionario);
  }

  public function agregar($datos){
    $modelo = new ModeloSujeto;
    $r=$modelo->agregar($datos);
    if($r){   
      header('Location: '.$GLOBALS['url'].'/option/sujeto/resultados/1');
    }
  }

  public function buscarSujetos(){
    $contenido = <<<END
                    <!--formulario -->
                    <form method="post" action="{$GLOBALS['url']}/option/sujeto/resultados/0" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
                    <h2 class="w3-center">Buscar sujetos</h2>
                    <div class="w3-row w3-section">
                        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
                        <div class="w3-rest">
                          <input class="w3-input w3-border" name="solicitante" type="text" placeholder="Nombre del solicitante" style="text-transform:uppercase">
                        </div>
                    </div>
                    
                    <div class="w3-row w3-section">
                      <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-map"></i></div>
                        <div class="w3-rest">
                          <input class="w3-input w3-border" name="cp" type="number" placeholder="Código postal" style="text-transform:uppercase">
                        </div>
                    </div>
                    
                  <div class="w3-row w3-section">
                    <label>Fecha inicial</label>
                      <div class="w3-col" style="width:50px;margin-top:1.5em;"><i class="w3-xxlarge fa fa-calendar"></i></div>
                        <div class="w3-rest">
                        
                          <input class="w3-input w3-border" name="fecha-inicio" type="date" value="">
                        </div>
                    </div>

                  <div class="w3-row w3-section">
                    <label>Fecha final</label>
                    <div class="w3-col" style="width:50px;margin-top:1.5em;"><i class="w3-xxlarge fa fa-calendar"></i></div>
                      <div class="w3-rest">
                        <input class="w3-input w3-border" name="fecha-fin" type="date" value="">
                      </div>
                  </div>

                    <input class="w3-input w3-border hide" name="usuario" type="text" value="{$GLOBALS['usuario_id']}">
                    
                    <button class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding" style="max-width:100px; margin:auto;">Buscar</button>
                    
                    </form>
              END;
    $diccionario = array(
    'titulo'=>'<h5><b><i class="fa fa-home"></i> Buscar sujetos registrados</b></h5> 
    <small><i class="fa fa-info-circle"></i> Puedes llenar uno o varios campos para acotar tu busqueda<br>
    <i class="fa fa-hand-o-right"></i>Para mostrar hasta 100 registros realizados en el último año puede presionar el botón de "buscar" dejando todos los campos en blanco</small>', 
    'contenido'=>$contenido);
    $this->mostrarVista($diccionario);
  }

  public function listaSujetos($datos){
    //$datos=> solicitante, cp, fecha-inicio, fecha-fin
    /*Condición de fecha vacía*/
    $hoy = date("Y-m-d"); // Obtener la fecha de hoy como una cadena
    $ma = date("Y-m-d", strtotime($hoy . '+2 day')); // Aumentar un día y formatear la fecha
    $h_s=date($datos[3]);
    $hoy_set = date("Y-m-d", strtotime( $h_s. '+1 day')); // Aumentar un día y formatear la fecha
    $fechaHace30Dias = date('Y-m-d', strtotime('-365 days'));
    $fi=$datos[2]!='' ? $datos[2] : $fechaHace30Dias;
    $ff=$datos[3]!='' ? $hoy_set : $ma;
    /*Fin de condición de fecha vacía*/
    $usuario =$datos[4]==1 ? "LIKE '%%'": "= '".$datos[4]."'";
    $modelo = new ModeloSujeto;
    $r=$modelo->listaSujetos($datos[0],$datos[1],$fi,$ff,$usuario);
    //tabla de w3.css
    $contenido="<div class='scroll'><div class='w3-container'><table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>";
    $contenido.="<tr class='w3-blue'>
                  <th>Nombre del solicitante</th>
                  <th>Fecha del avalúo</th>
                  <th>Objetivo del avalúo</th>
                  <th>Domicilio</th>
                  <th>Código postal</th>
                  <th>Metros cuadrados de terreno</th>
                  <th>Metros cuadrados de construcción</th>
                  <th>Año de construcción</th>
                  <th>Tipo de propiedad</th>
                  <th>Tipo de vivienda</th>
                  <th>Estado de conservación</th>
                  <th>Notas generales</th>
                  <th>Acciones</th>
                  </tr>";
    foreach ($r as $key => $value) {
      $rO=$this -> objetivosAvaluo($value['nObjetoAvaluo']);
      //tipo de propiedad
      $tP=$this -> tipoPropiedad($value['nTipoPropiedad']);
      //tipo de vivienda
      $tV=$this -> tipoVivienda($value['nTipoVivienda']);
      //estado de conservación
      $eC=$this -> estadoConservacion($value['nEstadoConservacion']);
      $contenido.="<tr><td>".$value['sNombreSolicita']."</td><td>".$value['dtFechaAvaluo']."</td><td>".$rO."</td><td>".$value['sDomicilio']."</td><td>".$value['nCP']."</td><td>".$value['nMTerreno']."</td><td>".$value['nMConstruccion']."</td><td>".$value['nAntiguedad']."</td><td>".$tP."</td><td>".$tV."</td><td>".$eC."</td><td>".$value['nNotaGeneral']."</td>
      <td><a target='_blank' href='".$GLOBALS['url']."/option/sujeto/".$value['kSujeto']."/0'><i class='fa fa-edit w3-xlarge'></i></a></td>
      </tr>";
    }
    $contenido.="</table></div></div>";
    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-home"></i> Resultados de búsqueda de sujetos registrados</b></h5>                 
      <small><i class="fa fa-info-circle"></i> Después de registrar el sujeto a valuar, podrá modificarlo y agregar más información, así como, agregar fotografías y vincularlo con las url de los comparables.</small>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
  }

 #mostrar datos de sujeto para editar o agregar información 
  public function mostrarSujeto($kSujeto){
    if($this->validarOpinion($kSujeto)){
      $bloquear="hide";
      $block="";
    }else{
      $block="hide";
    }
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarSujeto($kSujeto);
    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    $vinculos="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr>
                      <th>Comparable</th>
                      <th>Enlace</th>
                      <th>M<sup>2</sup> Construcción</th>
                      <th>M<sup>2</sup> Terreno</th>
                      <th>Precio de venta</th>
                      <th>Acción</th>
                  </tr>";
    foreach ($rE as $key => $value) {
      $vinculos.="
      <tr>
      <td><b>C-".$value['kUrl']."</b><br> <a href='".$GLOBALS['url']."/option/registrourl/".$value['kUrl']."/0' target='_blank'>".$value['sNombreCliente']."</a> </td>
      <td> <a href='".$GLOBALS['url']."/".$value['sShortUrl']."' target='_blank'>".$GLOBALS['url']."/".$value['sShortUrl']."</a> </td>
      <td>".$value['nSupConst']."</td>
      <td>".$value['nSupTerr']."</td>
      <td>$".number_format($value['nPrecioVenta'], 2, '.', ',')."</td>
      <td>  <a onclick='confirmar(\"e-".$value['kEnlaceUS']."\")' class='p pointer ".$bloquear."'><i class='fa fa-trash-o' aria-hidden='true'></i> </a>
            <a id='e-".$value['kEnlaceUS']."' class='w3-button w3-red w3-small hide' href='".$GLOBALS['url']."/option/sujeto/borrar/".$value['kEnlaceUS']."-".$kSujeto."'>Confirma eliminar</a>
            <i class='fa fa-ban ".$block."' style='color:grey'></i>
      </td>";
    }
    $vinculos.="</tr>
              </table></div><br>";
    //termina enlaces a comparables
    //enlace fotografías
    $fotos= new ModeloSujeto;
    $rF=$fotos->fotos($kSujeto);
    $fotografias="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr>
                      <th>Fotografías</th>
                      <th>Acción</th>
                  </tr>";
    foreach ($rF as $key => $value) {
      $fotografias.="
      <tr>
      <td><a href='".$GLOBALS['url'].'/'.$value['sNameImg']."' target='_blank'>".substr($value['sNameImg'], -16)."</a> </td>
      <td> <a onclick='confirmar(\"s-".$value['kSujetoImg']."\")' class='p pointer'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
      <a id='s-".$value['kSujetoImg']."' class='w3-button w3-red w3-small hide' href='".$GLOBALS['url'].'/option/sujeto/borrar-imagen/'.$value['kSujetoImg']."-".$kSujeto."'>Confirma eliminar</a></td>";
    }
    $fotografias.="</tr>
              </table></div><br>";
    //termina enlace fotografías
    //información extra
    $info= new ModeloSujeto;
    $rI=$info->mostrarInformacion($kSujeto);
    $informacion="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr>
                      <th>Título</th>
                      <th>Descripción</th>
                      <th>Acción</th>
                  </tr>";
    foreach ($rI as $key => $value) {
      $informacion.="
      <tr>
      <td>".$value['sTitulo']."</td>
      <td>".$value['sInfo']."</td>
      <td> 
      <a onclick='confirmar(\"i-".$value['kSujetoInfo']."\")' class='p pointer'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
      <a id='i-".$value['kSujetoInfo']."' class='w3-button w3-red w3-small hide' href='".$GLOBALS['url'].'/option/sujeto/borrar-informacion/'.$value['kSujetoInfo']."-".$kSujeto."'>Confirma eliminar</a></td>";
    }
    $informacion.="</tr>
              </table></div><br>";
    //termina información extra
    $contenido='<div class="w3-right w3-white" style="margin-right:2em">
                  <a href="'.$GLOBALS['url'].'/option/sujeto/opinion-captura/s-'.$kSujeto.'">
                    <i class="fa fa-calculator w3-xxxlarge" aria-hidden="true"></i>
                  </a>
                  <a href="'.$GLOBALS['url'].'/option/sujeto/sujeto-xls/s-'.$kSujeto.'">
                    <i class="fa fa-file-excel-o w3-xxxlarge"></i>
                  </a>
                  <a href="'.$GLOBALS['url'].'/option/sujeto/sujeto-pdf/s-'.$kSujeto.'">
                  <i class="fa fa-print w3-xxxlarge" aria-hidden="true"></i>
                  </a>
                </div>';
    $contenido.= '<form action="'.$GLOBALS['url'].'/option/sujeto/modificar/0" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Datos del Sujeto</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante:</label>
                                    <input type="text" id="nombreSolicitante" name="nombreSolicitante" maxlength="60" style="text-transform:uppercase" value="'.$r[0]['sNombreSolicita'].'"><br><br>

                                    <label for="fechaAvaluo">Fecha del avalúo:</label>
                                    <input type="date" id="fechaAvaluo" name="fechaAvaluo" value="'.$r[0]['dtFechaAvaluo'].'"><br><br>

                                    <label for="objetivoAvaluo">Objetivo de avalúo:</label>
                                    <select id="objetivoAvaluo" name="objetivoAvaluo">
                                        <option value="'.$r[0]['nObjetoAvaluo'].'">***'.$this->objetivosAvaluo($r[0]['nObjetoAvaluo']).'</option>
                                        "'.$this->objetivosAvaluo().'"                                        
                                    </select><br><br>

                                    <label for="domicilio">Domicilio:</label>
                                    <input type="text" id="domicilio" name="domicilio" maxlength="75" value="'.$r[0]['sDomicilio'].'"><br><br>

                                    <label for="codigoPostal">Código Postal:</label>
                                    <input type="text" id="codigoPostal" name="codigoPostal" max="99999" placeholder="81200" value="'.$r[0]['nCP'].'"><br><br>

                                    <label for="metrosCuadradosTerreno">Metros cuadrados de terreno:</label>
                                    <input type="number" id="metrosCuadradosTerreno" name="metrosCuadradosTerreno" step="0.01" max="9999.99" value="'.$r[0]['nMTerreno'].'"><br><br>

                                    <label for="metrosCuadradosConstruccion">Metros cuadrados de construcción:</label>
                                    <input  type="number" id="metrosCuadradosConstruccion" name="metrosCuadradosConstruccion" step="0.01" max="9999.99" value="'.$r[0]['nMConstruccion'].'"><br><br>

                                    <label for="anioConstruccion">Año de construcción:</label>
                                    <input type="number" id="anioConstruccion" name="anioConstruccion" min="1900" max="2100" placeholder="1990" value="'.$r[0]['nAntiguedad'].'"><br><br>

                                    <label for="tipoPropiedad">Tipo de Propiedad:</label>
                                        <select id="tipoPropiedad" name="tipoPropiedad">
                                            <option value="'.$r[0]['nTipoPropiedad'].'">***'.$this->tipoPropiedad($r[0]['nTipoPropiedad']).'</option>
                                            "'.$this->tipoPropiedad().'"
                                        </select><br><br>

                                    <label for="tipoVivienda">Tipo de vivienda:</label>
                                    <select id="tipoVivienda" name="tipoVivienda" title="Vivienda tipo: 
***Económica. Superficie construida en promedio: 40m2, cuentan con 1 baño, cocina, área de usos múltiples.
***Popular. Superficie construida en promedio: 50m2, cuentan con 1 baño, cocina, estancia, comedor, de 1 a 2 recámaras y 1 cajón de estacionamiento.
***Tradicional. Superficie construida en promedio: 71m2, cuentan con 1 y ½ baños, cocina, estancia-comedor, de 2 a 3 recámaras, 1 cajón de estacionamiento.
***Media. Superficie construida en promedio: 102m2, cuentan con 2 baños, cocina, sala, comedor, de 2 a 3 recámaras, cuarto de servicio y 1 a 2 cajones de estacionamiento.
***Residencial. Superficie construida en promedio: 156m2, cuentan con 3 a 4 baños, cocina, sala, comedor, de 3 a 4 recámaras, cuarto de servicio, sala familiar y 2 o 3 cajones de estacionamiento.
***Residencial plus. Superficie construida en promedio: más de 156m2, cuentan con más de 4 baños, cocina, sala, comedor, más de 4 recámaras, cuarto de servicio, sala familiar y más de 3 cajones de estacionamiento.
                                    "><option value="'.$r[0]['nTipoVivienda'].'">***'.$this->tipoVivienda($r[0]['nTipoVivienda']).'</option> 
                                      "'.$this->tipoVivienda().'"
                                    </select><br><br>

                                    <label for="estadoConservacion">Estado de conservación:</label>
                                    <select id="estadoConservacion" name="estadoConservacion">
                                        <option value="'.$r[0]['nEstadoConservacion'].'">***'.$this->estadoConservacion($r[0]['nEstadoConservacion']).'</option>
                                        "'.$this->estadoConservacion().'"
                                    </select><br><br>

                                    </div>
                                    <div class="w3-col m6  p">

                                    <label for="generales">Notas Generales:</label><br>
                                    <textarea  id="generales" name="generales" rows="4" style="width:100%;" placeholder="(Máximo 240 caracteres)" maxlength="240">'.$r[0]['nNotaGeneral'].'</textarea><br><br>
                                    <input type="hidden" name="id" value="'.$r[0]['kSujeto'].'">
                                    
                                    <!--formulario para agregar fotografías -->
                                    <form action="'.$GLOBALS['url'].'/option/sujeto/agregar-imagen/0" enctype="multipart/form-data"  method="post"  id="upload-form">
                                    '.$fotografias.'
                                    Subir imágen:  <input type="file" name="file" id="file-input" accept="image/png, image/jpeg, image/jpg"><br><small>Archivos permitidos: jpeg, jpg, png</small>
                                    </form>        
                                    </div>
                            <div class="w3-col m12 p">
                            <input class="w3-button w3-blue" type="submit" value="Actualizar"><br>
                            </div>
                          </div>
              </form>

 <!-- datos extra -->             
              <div class="w3-container w3-card w3-white m">
              <div class="w3-col m12  p">
              <form action="'.$GLOBALS['url'].'/option/sujeto/buscar-url/0" method="post" enctype="multipart/form-data">
              <label class="w3-text-blue">Vincular comparables</label><hr>
              <div class="scroll">
              '.$vinculos.'
              </div>
              <input type="number" name="kSujeto" value="'.$r[0]['kSujeto'].'" class="hide">
              <button class="w3-button w3-green '.$bloquear.'">Agregar</button>
              </form>
              </div>
              </div>

              <div class="w3-container w3-card w3-white m p">
              <div class="w3-col m12  p">
              <form action="'.$GLOBALS['url'].'/option/sujeto/informacion/0" method="post" enctype="multipart/form-data">
              <label class="w3-text-blue">Información adicional</label><hr>
              '.$informacion.'
                <input class="w3-input w3-border"  type="text" name="sTitulo" placeholder="Título"><br>
                <textarea class="w3-input w3-border" name="sInfo" rows="4"  placeholder="Descripción (Máximo 240 caracteres)" maxlength="240"></textarea><br><br>
                <input type="number" name="kSujeto" value="'.$r[0]['kSujeto'].'" class="hide">
                <button class="w3-button w3-green"  type="submit">Agregar</button><br>
              </form>
              </div>
              <div class="p">
              <button id="eliminar" class="w3-button w3-red w3-right" onclick="confirmAction()"> Eliminar </button>
              <div id="myDiv" style="display: none;" class="w3-panel w3-yellow w3-card-4 p w3-center">
                <h3>¿Estás seguro?</h3>
                <a href="'.$GLOBALS['url'].'/option/sujeto/borrar-sujeto/'.$r[0]['kSujeto'].'" class="w3-button w3-green">Aceptar</a>
                <button class="w3-button w3-red" onclick="confirmAction()">Cancelar</button>
              </div>
              </div>
              </div>
<!-- termina datos extra -->

            <script>
              //Verificar que esté seleccionado casa habitación para poder selecionar el tipo de vivienda
              document.getElementById("tipoPropiedad").addEventListener("change", function() {
                    if (this.value !== "0") {
                        document.getElementById("tipoVivienda").value = "0";   
                    }
                });

                //comprimir imágen
                document.getElementById("file-input").addEventListener("change", function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                
                    reader.onloadend = function() {
                        var img = new Image();
                        img.src = reader.result;
                
                        img.onload = function() {
                            var canvas = document.createElement("canvas");
                            var ctx = canvas.getContext("2d");
                
                            // Establece el tamaño deseado
                            var maxWidth = 960;
                            var maxHeight = 960;
                
                            // Redimensiona la imagen
                            var width = img.width;
                            var height = img.height;
                
                            if (width > height) {
                                if (width > maxWidth) {
                                    height *= maxWidth / width;
                                    width = maxWidth;
                                }
                            } else {
                                if (height > maxHeight) {
                                    width *= maxHeight / height;
                                    height = maxHeight;
                                }
                            }
                
                            canvas.width = width;
                            canvas.height = height;
                            ctx.drawImage(img, 0, 0, width, height);
                
                            // Convierte el canvas a un Blob y asigna el Blob al formulario
                            canvas.toBlob(function(blob) {
                                var formData = new FormData();
                                formData.append("file", blob, file.name);
                                
                                // Realiza la solicitud AJAX para subir la imagen
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "'.$GLOBALS['url'].'/option/sujeto/agregar-imagen/'.$r[0]['kSujeto'].'", true);
                                
                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        window.location.href = "'.$GLOBALS['url'].'/option/sujeto/'.$r[0]['kSujeto'].'/1";
                                    } else {
                                      window.location.href = "'.$GLOBALS['url'].'/option/sujeto/'.$r[0]['kSujeto'].'/e";
                                    }
                                };
                                
                                xhr.send(formData);
                            }, file.type);
                        };
                    };
                
                    reader.readAsDataURL(file);
                });
                
                document.getElementById("upload-form").addEventListener("submit", function(e) {
                    e.preventDefault();
                });

            /*Confirmar borrar registro de sujeto*/
                function confirmAction() {
                  var x = document.getElementById("myDiv");
                  if (x.style.display === "none") {
                    x.style.display = "block";
                  } else {
                    x.style.display = "none";
                  }
                  var x = document.getElementById("eliminar");
                  if (x.style.display === "none") {
                    x.style.display = "block";
                  } else {
                    x.style.display = "none";
                  }
              }
              //Confirmar borrar imagen
              function confirmar(id) {
                console.log(id);
                var x = document.getElementById(id);
                if (x.style.display === "none") {
                  x.style.display = "block";
                } else {
                  x.style.display = "none";
                }
              }
              </script>;
          ';
    $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-home"></i> Levantamiento (Visualizar/Editar)</b></h5>                 
        <small><i class="fa fa-info-circle"></i> En la opción Acciones del sujeto a valuar, podrá modificarlo y agregar más información, así como, agregar fotografías y vincularlo con las url de los comparables.</small>', 
        'contenido'=>$contenido);
        $this->mostrarVista($diccionario);
  }

  public function modificarSujeto($datos){
    $modelo = new ModeloSujeto;
    $r=$modelo->modificarSujeto($datos);
    if($r){   
    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$datos['12'].'/1');
    }
  }

  public function buscarUrl($kSujeto){
    //si $kSujeto es igual a "e" redirigir a inicio $GLOBALS['url'] /option/sujeto/buscar/0
    if($kSujeto=="e"){
      header('Location: '.$GLOBALS['url'].'/option/sujeto/buscar/0');
    }
    $contenido = <<<END
          <!--formulario -->
          <form method="post" action="{$GLOBALS['url']}/option/sujeto/resultados-url/0" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
          <h2 class="w3-center">Buscar Url</h2>
          <div class="w3-row w3-section">
            <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-internet-explorer"></i></div>
              <div class="w3-rest">
                <input class="w3-input w3-border" name="url" type="text" placeholder="Dirección corta P.Ej.: https://urlh.org/zx548">
              </div>
          </div>
          
          <div class="w3-row w3-section">
            <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
              <div class="w3-rest">
                <input class="w3-input w3-border" name="cliente" type="text" placeholder="Identificador" style="text-transform:uppercase">
              </div>
          </div>
          
        <div class="w3-row w3-section">
          <label>Fecha inicial</label>
            <div class="w3-col" style="width:50px;margin-top:1.5em;"><i class="w3-xxlarge fa fa-calendar"></i></div>
              <div class="w3-rest">
              
                <input class="w3-input w3-border" name="fecha-inicio" type="date" value="">
              </div>
          </div>

        <div class="w3-row w3-section">
          <label>Fecha final</label>
          <div class="w3-col" style="width:50px;margin-top:1.5em;"><i class="w3-xxlarge fa fa-calendar"></i></div>
            <div class="w3-rest">
              <input class="w3-input w3-border" name="fecha-fin" type="date" value="">
            </div>
        </div>

        <input class="w3-input w3-border hide" name="kSujeto" type="text" value="{$kSujeto}">
        <input class="w3-input w3-border hide" name="usuario" type="text" value="{$GLOBALS['usuario_id']}">
          
          <button class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding" style="max-width:100px; margin:auto;">Buscar</button>
          
          </form>
      END;
      $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Seleccionar Urls para ralizar vinculo con comparable</b></h5> <small><i class="fa fa-info-circle"></i> Puedes llenar uno o varios campos para acotar tu busqueda<br><i class="fa fa-hand-o-right"></i>Para mostrar hasta 100 registros realizados en el último año puede presionar el botón de "buscar" dejando todos los campos en blanco</small>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);

  }
  

  public function listaUrl($datos){
    //$datos=> sUrl, sNombreCliente, dtFecha (inicio), dtFecha (fin), usuario
    $contenido=$datos[0]." - ".$datos[1]." - ".$datos[2]." - ".$datos[3]." - ".$datos[4];
    $hoy = date("Y-m-d"); // Obtener la fecha de hoy como una cadena
    $ma = date("Y-m-d", strtotime($hoy . '+2 day')); // Aumentar un día y formatear la fecha
    $h_s=date($datos[3]);
    $hoy_set = date("Y-m-d", strtotime( $h_s. '+1 day')); // Aumentar un día y formatear la fecha
    /*Obtener datos del enlace */
    $url=explode("/",$datos[0]);
    $kUrl=$url[3];
    /*Condición de fecha vacía*/
    $fechaHace30Dias = date('Y-m-d', strtotime('-365 days'));
    $fi=$datos[2]!='' ? $datos[2] : $fechaHace30Dias;
    $ff=$datos[3]!='' ? $hoy_set : $ma;
    $usuario =$datos[4]==1 ? "LIKE '%%'": "= '".$datos[4]."'";
    $busqueda = new ModeloSujeto;
    $variable=$busqueda->mostrarBusquedaUrl($kUrl,$datos[1],$fi,$ff,$usuario);
    //Se muestran los resultados de la búsqueda en una tabla
    $contenido ='<div class="w3-container">
                  <h5>Registros localizados con los parámetros indicados</h5> <small><i class="fa fa-info-circle"></i> En el enlace "info" podrás ver todos los detalles de tu url<br><i class="fa fa-plus"></i> Para agregar url a su sujeto favor de dar clic en agregar</small>
                  <div class="scroll">
                  <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                  <tr class="w3-blue">
                    <th>Infos</th>
                    <th>UrlCorto</th>
                    <th>Fecha</th>
                    <th>ID</th>
                    <th>Agregar</th>
                  </tr>';
    foreach ($variable as $key => $value) {
      $contenido.='<tr>
                    <td><a  href="'.$GLOBALS['url']."/option/registrourl/".$value['kUrl'].'/0"><i class="w3-large fa fa-paperclip w3-xlarge"></i></a></td>
                    <td><a target="_blank" href="'.$GLOBALS['url']."/".$value['sShortUrl'].'">'.$GLOBALS['url']."/".$value['sShortUrl'].'</a></td>
                    <td>'.$value['dtFecha'].'</td>
                     <td>'.$value['sNombreCliente'].'</td>
                     <td><a  href="'.$GLOBALS['url']."/option/sujeto/vincular-url/".$value['kUrl'].'-'.$datos[5].'"><i class="fa fa-plus-circle w3-xlarge"></i></a></td>
                  </tr>';
    }
    $contenido.='</table>
                  </div>
                </div>
                <hr>';
    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Resultado de la búsqueda</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
  }

  public function vincularUrl($datos){
    //$d[0]=kUrl, $d[1]=kSujeto
    $d=explode("-",$datos);
    $modelo = new ModeloSujeto;
    $r=$modelo->vincularUrl($d[0],$d[1]);
    if($r){   
    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$r.'/1');
    }
  }

  public function guardarImagen($imagen,$kSujeto){
    $errors = [];
    $path = 'files/sujeto_img/';
    $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $file_name = $imagen['name'];
    $file_tmp = $imagen['tmp_name'];
    $file_type = $imagen['type'];
    $file_size = $imagen['size'];

    $file_ext = strtolower(end(explode('.', $imagen['name'])));
    $file = $path . $kSujeto .'-'. time() . '.' . $file_ext;

    if (!in_array($file_ext, $extensions)) {
        $errors[] = 'Extensión no permitida, elige una imagen JPEG o PNG.';
    }

    if ($file_size > 2097152) {
        $errors[] = 'El tamaño del archivo debe ser igual o inferior a 2 MB';
    }

    if (empty($errors)) {
        //guardar imágen
        move_uploaded_file($file_tmp, $file);
        //guardar nombre de la imágen
        //$datos=> fkUrl, sNameImg	
        $datos=array();
        array_push($datos,$kSujeto,$file);
        $registrar_img= new ModeloSujeto;
        $resultado=$registrar_img->registrarImagen($datos);
        if($resultado){
          header("Location:".$GLOBALS['url']."/option/sujeto/".$kSujeto."/1");
        }
    } else {
      header("Location:".$GLOBALS['url']."/option/sujeto/".$kSujeto."/e");
    }
  }

  public function agregarInformacion($datos){
    //$datos=> fkSujeto, sTitulo, sDescripcion
    $modelo = new ModeloSujeto;
    $r=$modelo->agregarInformacion($datos);
    if($r){   
    header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$datos['0'].'/1');
    }
  }

  public function mostrarInformacion($kSujeto){
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarInformacion($kSujeto);
    return $r;
  }

// ############# Exportar datos a excel #######################################
public function reporteSujetoXls($kSujeto){
  $modelo = new ModeloReportes;
  $r=$modelo->reporteSujetoXls($kSujeto);

//enlaces a comparables
$enlaces= new ModeloSujeto;
$rE=$enlaces->enlaces($kSujeto);
$numero_de_enlace=1;
$vinculos="";
foreach ($rE as $key => $value) {
  $vinculos.="<tr><td><b>".$numero_de_enlace.".- Enlace comparable (C-".$value['kUrl'].") </b></td><td>".$GLOBALS['url']."/".$value['sShortUrl']."</td></tr>";
  $vinculos.="<tr><td>".$numero_de_enlace.".- Registro comparable </td><td>".$value['dtFecha']."</td></tr>";
  $vinculos.="<tr><td>".$numero_de_enlace.".- M<sup>2</sup> Construcción comparable </td><td>".$value['nSupConst']."</td></tr>"; 
  $vinculos.="<tr><td>".$numero_de_enlace.".- M<sup>2</sup> Terreno comparable  </td><td> ".$value['nSupTerr']."</td></tr>";
  $vinculos.="<tr><td>".$numero_de_enlace.".- Precio de venta comparable </td><td> ".number_format($value['nPrecioVenta'], 2, '.', ',')." </td></tr>";
  $vinculos.="<tr><td>".$numero_de_enlace.".- Nota comparable </td><td>".$value['sNota']."</td></tr>";
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
// ############# Termina exportar datos a excel ###############################

//############## Exportar datos a PDF ########################################
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
        $vinculos.="<tr><td><b>Comparable ".$no_vinculos.": (C-".$value['kUrl'].") ".$value['sNombreCliente']." </b></td><td>Enlace corto: ".$GLOBALS['url'].'/'.$value['sShortUrl']." </td></tr>";
        $vinculos.="<tr><td>Enlace: ".$value['sUrl']."</td><td> <div class='p w3-white' style='width:160px' id='codigo-qr-".$value['kUrl']."'></div> </td></tr>";
        $vinculos.="<tr><td>Nota: ".$value['sNota'].",  Construcción: ".$value['nSupConst']." M<sup>2</sup>,  Terreno: ".$value['nSupTerr']." M<sup>2</sup>, Precio de venta: ".number_format($value['nPrecioVenta'], 2, '.', ',')."</td><td> Fecha comparable: ".$value['dtFecha']."  </td></tr>";
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
//############## Termina exportar datos a PDF ########################################


// ############# Captura para opinión de valor ################################
  public function capturaOpinion($kSujeto){
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarSujeto($kSujeto);
//calculo de antigüedad del sujeto    
    $antiguedad= date('Y')-$r[0]['nAntiguedad'];
//Inicia revisión de coeficiente de ocupación de suelo
    if($r[0]['nMTerreno']!=0||$r[0]['nMConstruccion']!=0){
      $CUS=$r[0]['nMConstruccion']/$r[0]['nMTerreno'];
    }else{
      $CUS=0;
    }

  $v_CUS=$this->convertirCus($CUS);

//termina revisión de coeficiente de ocupación de suelo

    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    //inicia enlaces a comparables
    $comparables=array();
    $n_comp=0;
    $vinculos="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr> 
                      
                      <th>Info/Enlace</th>
                      <th>Información</th>
                      <th>Homologación de Características</th>
                  </tr>";
    foreach ($rE as $key => $value) {
    //relacion de comparabales
    $n_comp++;
    array_push($comparables,$value['kUrl']);
      if($value['nPrecioVenta']!=0||$value['nSupConst']!=0){
      $vum=$value['nPrecioVenta']/$value['nSupConst'];
      }else{
        $vum=0;
      }
      if($value['nMTerreno']!=0||$value['nSupConst']!=0){
        $cusc=$value['nSupConst']/$value['nSupTerr'];
        $vcusc=$this->convertirCus($cusc);
      }else{  
        $vcusc=0;
      }
      $vinculos.="
      <tr>
      <td><b>C-".$value['kUrl']."</b><br>
      <a href='".$GLOBALS['url']."/option/registrourl/".$value['kUrl']."/0' target='_blank'>".$value['sNombreCliente']."</a> <br>
          <a href='".$GLOBALS['url']."/".$value['sShortUrl']."' target='_blank'>".$GLOBALS['url']."/".$value['sShortUrl']."</a> </td>
      <td>M<sup>2</sup> Construcción: ".$value['nSupConst']."<br>
          M<sup>2</sup> Terreno: ".$value['nSupTerr']."<br>
          Precio de venta: $".number_format($value['nPrecioVenta'], 2, '.', ',')."<br>
          Valor unitario de oferta y demanda (VUM): $".number_format($vum, 2, '.', ',')."<br>
          Coeficiente de utilización del suelo (CUS): ".$vcusc." '".$this->cusTexto($vcusc)."'<br>
      </td>
          
      <td>

      <label>Negociación</label><br>
      <select id='negociacion-".$value['kUrl']."' name='negociacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("n",7)."'
       '".$this->enfMercado("n")."'
      </select><br>

      <label>Ubicación dentro de la colonia</label><br>
      <select id='ubicacion-".$value['kUrl']."' name='ubicacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",7)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Calidad de servicios públicos</label><br>
      <select id='servicios-".$value['kUrl']."' name='servicios-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",7)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Estado de conservación</label><br>
      <select id='conservacion-".$value['kUrl']."' name='conservacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",7)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Calidad del proyecto constructivo</label><br>
      <select id='proyecto-".$value['kUrl']."' name='proyecto-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",7)."'
       '".$this->enfMercado("c")."'
      </select><br>

      
      
      </td>
      ";
    }
    $vinculos.="</tr>
              </table></div><br>";
    //termina enlaces a comparables
   
    $contenido='<div class="w3-right w3-white" style="margin-right:2em"></div>';
    $contenido.= '<form action="'.$GLOBALS['url'].'/option/sujeto/captura-homologacion/s-'.$kSujeto.'" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Características</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante <b>(sujeto): '.$r[0]['sNombreSolicita'].'</b></label>
                                    <br><br>
                                    <label for="fechaAvaluo">Fecha del avalúo: '.$r[0]['dtFechaAvaluo'].'</label>
                                    <br><br>
                                    <label for="domicilio">Domicilio: '.$r[0]['sDomicilio'].'</label>
                                    <br><br>
                                    <label for="metrosCuadradosTerreno">M<sup>2</sup> terreno: '.$r[0]['nMTerreno'].'</label>
                                    <br><br>
                                    <label for="metrosCuadradosConstruccion">M<sup>2</sup> construcción: '.$r[0]['nMConstruccion'].'</label>
                                    <br><br>
                                    <label for="anioConstruccion">Antigüedad: '.$antiguedad.' años</label>
                                    <br><br>
                                    <label for="estadoConservacion">Estado de conservación: '.$this->estadoConservacion($r[0]['nEstadoConservacion']).'</label>
                                    <br><br>
                                    <label for="coeficientOcupación">Coeficiente de utilización de suelo (CUS): '.$v_CUS.' "'.$this->cusTexto($v_CUS).'"</label>
                                    </div>
                                    <div class="w3-col m6  p">

                      <!-- segunda columna -->
                                <label>Ubicación dentro de la colonia</label>
                                <select id="ubicacion" name="ubicacion" title="Texto explicativo">
                                '.$this->enfMercado("c",7).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Calidad de servicios públicos</label>
                                <select id="servicios" name="servicios" title="Texto explicativo">
                                '.$this->enfMercado("c",7).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Estado de conservación estimado</label>
                                <select id="conservacion" name="conservacion" title="Texto explicativo">
                                '.$this->enfMercado("c",7).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Calidad del proyecto constructivo</label>
                                <select id="proyecto" name="proyecto" title="Texto explicativo">
                                '.$this->enfMercado("c",7).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Nivel de oferta y demanda</label>
                                <select id="demanda" name="demanda" title="Texto explicativo">
                                '.$this->enfMercado("d",7).'"
                                 "'.$this->enfMercado("d").'"
                                </select><br><br>
                                <input class="hide" name="cus" value="'.$v_CUS.'">
                                <label>'.$comparacion.' </label>
                            </div>
                            <div class="w3-col m12 p">
                            
                            </div>
                          </div>


 <!-- comparables -->
 <input class="hide" name="n-comp" value="'.$n_comp.'">             
 <input class="hide" name="comparables" value="'.implode(",",$comparables).'">             
              <div class="w3-col m12  p">
              <div class="w3-container  w3-white m p">
              <label class="w3-text-blue">Comparables</label><hr>
              '.$vinculos.'
              </div>
              <button class="w3-button w3-green">Guardar</button>
              </form>
              </div>
              
<!-- termina comparables -->

       
          ';
    $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-home"></i> Captura de información</b></h5>', 
        'contenido'=>$contenido);
        $this->mostrarVista($diccionario);

  }
// ############# Termina captura para opinión de valor ################################

// ############## Captura de homologacion ########################################
    public function capturaHomologacion($datos){
      /*-----------------Datos de sujeto-----------------
      $contenido="Hello desde controlador sujeto: ".$datos[0]." - ".$datos[1]. " - ".$datos[2]. " - ".$datos[3]. " - ".$datos[4]. " - ".$datos[5]." - ".$datos[6]." - ".$datos[7]." - ".$datos[8];
      */
      /*----------- Como acceder a datos de los comparables-------
      $datos_comparables=$datos[7]*5;
      for ($i=0; $i < $datos_comparables; $i++) { 
        $datos_sujeto=9;
        $i_comp=$i+$datos_sujeto;
        $contenido.=" - ".$datos[$i_comp];
      }
      */
      /*$datos: 
              Primeros 7 datos corresponden al sujeto 0-6
                [0]=get(kSujeto), post1([1]=ubicacion, [2]=servicios, [3]=conservacion, [4]=proyecto,[5]=demanda), [6]=cus, 
              los siguientes 2 datos corresponden a la cantidad e identificadores de los comparables
                |-> [7]=n-comp,[8]=kcomparables 7-8
              el resto de información corresponde a los comparables 9- ...
                |-> [9]=post2comparables(negociacio-id, ubicacion-id, servicios-id, conservacion-id, proyecto-id)*/
      $json = json_encode($datos);

      $modelo = new ModeloSujeto;
      $r=$modelo->guardarHomologacion($datos[0],$json);

      if($r){   
      header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$datos['0'].'/1');
      }
    }

// ############# Validar si sujeto ya tiene capturada información para generar opinion de valor
public function validarOpinion($kSujeto){
  $modelo2 = new ModeloSujeto;
  $r2=$modelo2->validarOpinion($kSujeto);
  if($r2[0]['fkSujeto']>0){ $comparacion=true;}else{ $comparacion=false;}
  return $comparacion;
}

// ############# Actualizar opinión de valor ################################
  public function actualizarOpinion($kSujeto){
//obtener datos capturados de caracteristicas de homologación
/*$d: 
              Primeros 7 datos corresponden al sujeto 0-6
                [0]=get(kSujeto), post1([1]=ubicacion, [2]=servicios, [3]=conservacion, [4]=proyecto,[5]=demanda), [6]=cus, 
              los siguientes 2 datos corresponden a la cantidad e identificadores de los comparables
                |-> [7]=n-comp,[8]=kcomparables 7-8
              el resto de información corresponde a los comparables 9- ...
                |-> [9]=post2comparables(negociacio-id, ubicacion-id, servicios-id, conservacion-id, proyecto-id)*/
    $datos_capturados = new ModeloSujeto;
    $dc=$datos_capturados->datosCaracteristicas($kSujeto);
    $d=json_decode($dc[0]['sOpinionValor'],true);
    //termina obtener datos capturados de caracteristicas de homologación
//Obtener datos de sujeto
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarSujeto($kSujeto);
//calculo de antigüedad del sujeto    
    $antiguedad= date('Y')-$r[0]['nAntiguedad'];
//Inicia revisión de coeficiente de ocupación de suelo
    if($r[0]['nMTerreno']!=0||$r[0]['nMConstruccion']!=0){
      $CUS=$r[0]['nMConstruccion']/$r[0]['nMTerreno'];
    }else{
      $CUS=0;
    }

  $v_CUS=$this->convertirCus($CUS);

//termina revisión de coeficiente de ocupación de suelo

    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    //inicia enlaces a comparables
    $comparables=array();
    $n_comp=0;
    $vinculos="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr> 
                      
                      <th>Info/Enlace</th>
                      <th>Información</th>
                      <th>Homologación de Características</th>
                  </tr>";
    //Acceder a calificaciones desde registro 9          
       $i_c=0; 
       $datos_sujeto=9;
       $i_comp=$i_c+$datos_sujeto;
    foreach ($rE as $key => $value) {
    // Preparar calificacion de comparables    
      $c_negociacion=$this->convertirCalificacion($d[$i_comp]);
      $i_comp++;
      $c_ubicacion=$this->convertirCalificacion($d[$i_comp]);
      $i_comp++;
      $c_servicios=$this->convertirCalificacion($d[$i_comp]);
      $i_comp++;
      $c_conservacion=$this->convertirCalificacion($d[$i_comp]);
      $i_comp++;
      $c_proyecto=$this->convertirCalificacion($d[$i_comp]);
      $i_comp++;
    //relacion de comparabales
    $n_comp++;
    array_push($comparables,$value['kUrl']);
      if($value['nPrecioVenta']!=0||$value['nSupConst']!=0){
      $vum=$value['nPrecioVenta']/$value['nSupConst'];
      }else{
        $vum=0;
      }
      if($value['nMTerreno']!=0||$value['nSupConst']!=0){
        $cusc=$value['nSupConst']/$value['nSupTerr'];
        $vcusc=$this->convertirCus($cusc);
      }else{  
        $vcusc=0;
      }
      $vinculos.="
      <tr>
      <td><b>C-".$value['kUrl']."</b><br> <a href='".$GLOBALS['url']."/option/registrourl/".$value['kUrl']."/0' target='_blank'>".$value['sNombreCliente']."</a> <br>
          <a href='".$GLOBALS['url']."/".$value['sShortUrl']."' target='_blank'>".$GLOBALS['url']."/".$value['sShortUrl']."</a> </td>
      <td>M<sup>2</sup> Construcción: ".$value['nSupConst']."<br>
          M<sup>2</sup> Terreno: ".$value['nSupTerr']."<br>
          Precio de venta: $".number_format($value['nPrecioVenta'], 2, '.', ',')."<br>
          Valor unitario de oferta y demanda (VUM): $".number_format($vum, 2, '.', ',')."<br>
          Coeficiente de utilización del suelo (CUS): ".$vcusc." '".$this->cusTexto($vcusc)."'<br>
      </td>
          
      <td>

      <label>Negociación</label><br>
      <select id='negociacion-".$value['kUrl']."' name='negociacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("n",$c_negociacion)."'
       '".$this->enfMercado("n")."'
      </select><br>

      <label>Ubicación dentro de la colonia</label><br>
      <select id='ubicacion-".$value['kUrl']."' name='ubicacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",$c_ubicacion)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Calidad de servicios públicos</label><br>
      <select id='servicios-".$value['kUrl']."' name='servicios-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",$c_servicios)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Estado de conservación</label><br>
      <select id='conservacion-".$value['kUrl']."' name='conservacion-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",$c_conservacion)."'
       '".$this->enfMercado("c")."'
      </select><br>

      <label>Calidad del proyecto constructivo</label><br>
      <select id='proyecto-".$value['kUrl']."' name='proyecto-".$value['kUrl']."' title='Texto explicativo'>
      ".$this->enfMercado("c",$c_proyecto)."'
       '".$this->enfMercado("c")."'
      </select><br>

      
      
      </td>
      ";
    }
    $vinculos.="</tr>
              </table></div><br>";
    //termina enlaces a comparables
   
    $contenido='<div class="w3-right w3-white" style="margin-right:2em">
                  <a href="'.$GLOBALS['url'].'/option/reportes/opinion-valor/s-'.$kSujeto.'">
                  <i class="fa fa-file-text-o w3-xxxlarge" aria-hidden="true"></i>
                  </a>
                </div>';
    $contenido.= '<form action="'.$GLOBALS['url'].'/option/sujeto/actualizar-homologacion/s-'.$kSujeto.'" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Características</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante <b>(sujeto): '.$r[0]['sNombreSolicita'].'</b></label>
                                    <br><br>
                                    <label for="fechaAvaluo">Fecha del avalúo: '.$r[0]['dtFechaAvaluo'].'</label>
                                    <br><br>
                                    <label for="domicilio">Domicilio: '.$r[0]['sDomicilio'].'</label>
                                    <br><br>
                                    <label for="metrosCuadradosTerreno">M<sup>2</sup> terreno: '.$r[0]['nMTerreno'].'</label>
                                    <br><br>
                                    <label for="metrosCuadradosConstruccion">M<sup>2</sup> construcción: '.$r[0]['nMConstruccion'].'</label>
                                    <br><br>
                                    <label for="anioConstruccion">Antigüedad: '.$antiguedad.' años</label>
                                    <br><br>
                                    <label for="estadoConservacion">Estado de conservación: '.$this->estadoConservacion($r[0]['nEstadoConservacion']).'</label>
                                    <br><br>
                                    <label for="coeficientOcupación">Coeficiente de utilización de suelo (CUS): '.$v_CUS.' "'.$this->cusTexto($v_CUS).'"</label>
                                    </div>
                                    <div class="w3-col m6  p">

                      <!-- segunda columna -->
                                <label>Ubicación dentro de la colonia</label>
                                <select id="ubicacion" name="ubicacion" title="Texto explicativo">
                                '.$this->enfMercado("c",$this->convertirCalificacion($d[1])).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Calidad de servicios públicos</label>
                                <select id="servicios" name="servicios" title="Texto explicativo">
                                '.$this->enfMercado("c",$this->convertirCalificacion($d[2])).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Estado de conservación estimado</label>
                                <select id="conservacion" name="conservacion" title="Texto explicativo">
                                '.$this->enfMercado("c",$this->convertirCalificacion($d[3])).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Calidad del proyecto constructivo</label>
                                <select id="proyecto" name="proyecto" title="Texto explicativo">
                                '.$this->enfMercado("c",$this->convertirCalificacion($d[4])).'"
                                 "'.$this->enfMercado("c").'"
                                </select><br><br>

                                <label>Nivel de oferta y demanda</label>
                                <select id="demanda" name="demanda" title="Texto explicativo">
                                '.$this->enfMercado("d",$this->convertirCalificacion($d[5])).'"
                                 "'.$this->enfMercado("d").'"
                                </select><br><br>
                                <input class="hide" name="cus" value="'.$v_CUS.'">
                            </div>
                            <div class="w3-col m12 p">
                            
                            </div>
                          </div>


 <!-- comparables -->
 <input class="hide" name="n-comp" value="'.$n_comp.'">             
 <input class="hide" name="comparables" value="'.implode(",",$comparables).'">             
              <div class="w3-col m12  p">
              <div class="w3-container  w3-white m p">
              <label class="w3-text-blue">Comparables</label><hr>
              '.$vinculos.'
              </div>
              <button class="w3-button w3-green">Actualizar</button>
              <a href="'.$GLOBALS['url'].'/option/sujeto/borrar-opinion/s-'.$kSujeto.'" class="w3-button w3-grey">Restaurar</a>
              </form>
              </div>
              
<!-- termina comparables -->

       
          ';
    $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-home"></i> Actualizar información</b></h5>', 
        'contenido'=>$contenido);
        $this->mostrarVista($diccionario);

  }
// ############# Termina captura para opinión de valor ################################

// ############## Actualizar homologacion ########################################
public function actualizarHomologacion($datos){
  $json = json_encode($datos);
  $modelo = new ModeloSujeto;
  $r=$modelo->actualizarHomologacion($datos[0],$json);
  if($r){   
  header('Location: '.$GLOBALS['url'].'/option/sujeto/'.$datos['0'].'/1');
  }
}

}



?>

