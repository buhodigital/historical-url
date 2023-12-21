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
 
  public function inicio(){
    //variable que almacena el día de hoy
    $hoy = date("Y-m-d");
    $contenido= '<form action="'.$GLOBALS['url'].'/option/sujeto/agregar/0" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Datos del Sujeto</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante:</label>
                                    <input type="text" id="nombreSolicitante" name="nombreSolicitante" maxlength="25" style="text-transform:uppercase" required=""><br><br>

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
      <td><a href='".$GLOBALS['url']."/option/sujeto/".$value['kSujeto']."/0'><i class='fa fa-edit w3-xlarge'></i></a></td>
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
    $modelo = new ModeloSujeto;
    $r=$modelo->mostrarSujeto($kSujeto);
    //enlaces a comparables
    $enlaces= new ModeloSujeto;
    $rE=$enlaces->enlaces($kSujeto);
    $vinculos="<div class='scroll'><table class='w3-table w3-bordered w3-striped'>
                  <tr>
                      <th>Archivo</th>
                      <th>Enlace</th>
                      <th>Acción</th>
                  </tr>";
    foreach ($rE as $key => $value) {
      $vinculos.="
      <tr>
      <td><a href='".$GLOBALS['url']."/option/registrourl/".$value['kUrl']."/0' target='_blank'>".$value['sNombreCliente']."</a> </td>
      <td> <a href='".$GLOBALS['url']."/".$value['sShortUrl']."' target='_blank'>".$GLOBALS['url']."/".$value['sShortUrl']."</a> </td>
      <td> <a href='".$GLOBALS['url']."/option/sujeto/borrar/".$value['kEnlaceUS']."-".$kSujeto."'><i class='fa fa-trash'></i></a></td>";
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
      <td> <a href='".$GLOBALS['url'].'/option/sujeto/borrar-imagen/'.$value['kSujetoImg']."-".$kSujeto."'><i class='fa fa-trash'></i></a></td>";
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
      <td> <a href='".$GLOBALS['url'].'/option/sujeto/borrar-informacion/'.$value['kSujetoInfo']."-".$kSujeto."'><i class='fa fa-trash'></i></a></td>";
    }
    $informacion.="</tr>
              </table></div><br>";
    //termina información extra
    $contenido='<div class="w3-right w3-white" style="margin-right:2em">
                  <a href="'.$GLOBALS['url'].'/option/reportes/sujeto-xls/s-'.$kSujeto.'">
                    <i class="fa fa-file-excel-o w3-xxxlarge"></i>
                  </a>
                  <a href="'.$GLOBALS['url'].'/option/reportes/sujeto-pdf/s-'.$kSujeto.'">
                  <i class="fa fa-print w3-xxxlarge" aria-hidden="true"></i>
                  </a>
                </div>';
    $contenido.= '<form action="'.$GLOBALS['url'].'/option/sujeto/modificar/0" method="post" class="w3-container w3-card w3-white m">
                  <h2 class="w3-center w3-text-blue">Datos del Sujeto</h2>
                  <hr>
                      <div class="w3-row">
                        <div class="w3-col m6  p">
                                    <label for="nombreSolicitante">Nombre del solicitante:</label>
                                    <input type="text" id="nombreSolicitante" name="nombreSolicitante" maxlength="25" style="text-transform:uppercase" value="'.$r[0]['sNombreSolicita'].'"><br><br>

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

                          </div>
                          <div class="w3-col m6  p">


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

                                    <label for="generales">Notas Generales:</label><br>
                                    <textarea  id="generales" name="generales" rows="4" style="width:100%;" placeholder="(Máximo 240 caracteres)" maxlength="240">'.$r[0]['nNotaGeneral'].'</textarea><br><br>
                                    <input type="hidden" name="id" value="'.$r[0]['kSujeto'].'">
                            </div>
                          </div>
                    <div class="p">
                    <input class="w3-button w3-blue" type="submit" value="Actualizar"><br>
                    </div>
              </form>

 <!-- datos extra -->             
              <div class="w3-container w3-card w3-white m">
              <div class="w3-col m6  p">
              <!--formulario para agregar fotografías -->
              <form action="'.$GLOBALS['url'].'/option/sujeto/agregar-imagen/0" enctype="multipart/form-data"  method="post"  id="upload-form">
              <label class="w3-text-blue">Agregar fotografías</label><hr>
              '.$fotografias.'
              Subir imágen:  <input type="file" name="file" id="file-input" accept="image/png, image/jpeg, image/jpg"><br><small>Archivos permitidos: jpeg, jpg, png</small>
              </form>
              </div>
              <div class="w3-col m6  p">
              <form action="'.$GLOBALS['url'].'/option/sujeto/buscar-url/0" method="post" enctype="multipart/form-data">
              <label class="w3-text-blue">Vincular comparables</label><hr>
              <div class="scroll">
              '.$vinculos.'
              </div>
              <input type="number" name="kSujeto" value="'.$r[0]['kSujeto'].'" class="hide">
              <button>Agregar</button>
              </form>
              </div>
              </div>

              <div class="w3-container w3-card w3-white m p">
              <form action="'.$GLOBALS['url'].'/option/sujeto/informacion/0" method="post" enctype="multipart/form-data">
              <label class="w3-text-blue">Agregar más información</label><hr>
              '.$informacion.'
                <input type="text" name="sTitulo" placeholder="Título" class="m">
                <textarea class="m"   name="sInfo" rows="4" style="width:90%;" placeholder="Descripción (Máximo 240 caracteres)" maxlength="240"></textarea><br><br>
                <input type="number" name="kSujeto" value="'.$r[0]['kSujeto'].'" class="hide">
                <button class="m"  type="submit">Agregar</button><br>
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
            </script>
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

}

?>

