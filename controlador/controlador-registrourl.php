<?php
include_once('modelo/modelo-registrourl.php');

class ControladorRegistroUrl{
//Método mostrar vista para la clase Registro URL
  private function mostrarVista($diccionario){   
    $template = file_get_contents('vista/plantillas/plantilla-general.html');
    foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
    print $template;
  }

  private function ultimoRegistro(){
    $ultimo_registro = new ModeloRegistroUrl;
    $r=$ultimo_registro->ultimoRegistro();
    return $r;
  }

  private function generarCadenaAleatoria($longitud = 3) {
    // Definir los caracteres posibles en la cadena
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $cantidadCaracteres = strlen($caracteres);
    $cadenaAleatoria = '';

    // Seleccionar un caracter aleatorio del conjunto de caracteres y añadirlo a la cadena
    for ($i = 0; $i < $longitud; $i++) {
        $indiceAleatorio = rand(0, $cantidadCaracteres - 1);
        $cadenaAleatoria .= $caracteres[$indiceAleatorio];
    }

    return $cadenaAleatoria;
  }

   #Registro de nuevo url
   public function formularioUrl(){
    $contenido = <<<END
                    <!--formulario -->
                    <form method="post" action="{$GLOBALS['url']}/option/registrourl/agregar/0" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
                    <h2 class="w3-center">Registro</h2>
                    
                    <div class="w3-row w3-section">
                      <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-internet-explorer"></i></div>
                        <div class="w3-rest">
                          <input class="w3-input w3-border" name="url" type="text" placeholder="Dirección web (Url) P.Ej.: https://anuncio.com">
                        </div>
                    </div>
                    
                    <div class="w3-row w3-section">
                      <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
                        <div class="w3-rest">
                          <input class="w3-input w3-border" name="cliente" type="text" placeholder="Identificador (Máximo 60 caracteres)" style="text-transform:uppercase" maxlength="60">
                        </div>
                    </div>
                    
                    <div class="w3-row w3-section">
                      <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-pencil"></i></div>
                        <div class="w3-rest">
                        <textarea class="w3-input w3-border" name="nota" rows="5" style="resize:none" spellcheck="false" data-ms-editor="true" placeholder="Nota (Máximo 240 caracteres)" maxlength="240"></textarea>
                        </div>
                    </div>
                    <input class="w3-input w3-border hide" name="usuario" type="text" value="{$GLOBALS['usuario_id']}">
                    
                    <button id="myButton" class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding" style="max-width:100px; margin:auto;" >Registrar</button>
                    <div id="loader"></div>
                    </form>
                    <script>
                    document.getElementById('myButton').addEventListener('click', function() {
                      this.style.display = 'none';
                      document.getElementById('loader').style.display='block';
                    });
                    </script>
              END;
    $diccionario = array(
    'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Registrar direccon web (Url)</b></h5>', 
    'contenido'=>$contenido);
    $this->mostrarVista($diccionario);
  }

  public function registrarUrl($datos){
    $lastReg=$this->ultimoRegistro()+1;
    $cadena_aleatoria=$this->generarCadenaAleatoria();
    $url_id=$cadena_aleatoria.$lastReg;

  if(!empty($datos[0])){
    $registrar = new ModeloRegistroUrl;
    //$datos=> sUrl, sNombreCliente, sNota, kUsuario
    $r=$registrar->registrarUrl($datos,$url_id);

    if($r){
      //Guardar imágen
      $apiKey = $GLOBALS['apiKey']; // Sustituye esto con tu clave API
      $url = $datos['0']; // Sustituye esto con la URL de la página que quieres capturar
  
      // Parámetros para la API
      $params = [
          'key' => $apiKey,
          'url' => $url,
          'delay'=> '10000',
          'device'=> 'desktop',
          'dimension' => '800xfull' // Puedes cambiar las dimensiones según tus necesidades
      ];
  
      // Construye la URL de solicitud
      $requestUrl = "http://api.screenshotmachine.com?" . http_build_query($params);
  
      // Obtener y guardar la imagen
      $imageData = file_get_contents($requestUrl);
      if ($imageData === false) {
          die('Error al tomar la captura de pantalla');
      }
  
      $file = 'files/img/img-'.$lastReg.'.png'; // Define el nombre del archivo donde se guardará la imagen
      file_put_contents($file, $imageData); // Guarda la imagen en el servidor

      //mostrar último registro
      $lastReg=$this->ultimoRegistro();

      //confirmación
      header("Location:".$GLOBALS['url']."/option/registrourl/".$lastReg."/2");
    }else{
      header("Location:".$GLOBALS['url']."/option/registrourl/lista/e");
    }

  }else{
    header("Location:".$GLOBALS['url']."/option/registrourl/lista/e");
  }


  }

  public function mostrarRegistro($kUrl){
    //obtener datos del url
    $registro = new ModeloRegistroUrl;
    $r=$registro->mostrarRegistro($kUrl);
    //obtener imágenes adicionales de url
    $imagenes = new ModeloRegistroUrl;
    $r_img=$imagenes->mostrarUrlImagenes($kUrl);
    $contenido='<button class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge w3-right m noprint" onClick="window.print()"><i class="fa fa-print" aria-hidden="true"></i></button>
                <a href="'.$GLOBALS['url'].'/option/registrourl/editar/'.$kUrl.'" class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge w3-right m noprint">Editar Nota/ID</a>
                  <div class="w3-row" style="background-color:#fff;">';
    $contenido.='<div class="w3-col m6  p"><b>Url recortada: </b>
                    <div id="short-url">'.$GLOBALS['url']."/".$r['sShortUrl'].'</div>
                    <button class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge m noprint" type="button" id="copy">Copiar url corto</button>
                 </div>';
    $contenido.= '<div class="w3-col m6  p"><b>Url Original: </b>'.$r['sUrl'].'</div><div class="c">.</div>';
    $contenido.= '<div class="w3-col m6  p"><b>Qr :</b> <div id="codigo-qr"></div> 
                    <button class="w3-btn w3-white w3-border w3-border-blue w3-round-xlarge m noprint" onclick="descargarImagen()">Descargar Qr</button>
                  </div>';
    $contenido.= '<div class="w3-col m6  p"><b>ID: </b>'.$r['sNombreCliente'].'</div>';    
    $contenido.= '<div class="w3-col m6  p"><b>Nota: </b>'.$r['sNota'].'</div>';    
    $contenido.= '<div class="w3-col m6  p"><b>Fecha/hora: </b>'.$r['dtFecha'].'</div>';   
    $contenido.='<div class="w3-col s12">
                  <a href="'.$GLOBALS['url'].'/option/registrourl/mostrar_ocultar_imagen/'.$kUrl.'-'.$r["bImg"].'" class="w3-button w3-yellow w3-right noprint">Imágen automática <b>(Mostrar / Ocultar)</b> </a>';
    if($r["bImg"]=='1'){
    $contenido.='<img style="width:100%;" src="'.$GLOBALS["url"].'/files/img/img-'.$r["kUrl"].'.png" alt=""><div class="c">.</div>';
    }
  //mostrar imágenes
    $contenido.='<div class="c">.</div>
                <div class="container-img">';
  foreach ($r_img as $key => $ri) {
    $contenido.='<figure><img src="'.$GLOBALS["url"].'/'.$ri['sNameImg'].'" alt="img" style="width:100%;"><figcaption><a class="w3-button w3-yellow w3-right noprint" href="'.$GLOBALS["url"].'/option/registrourl/ver_imagen/'.$ri['kUrlImg'].'">Ver</a></figcaption></figure>';
  }
    $contenido.='</div>
                  <div class="w3-panel w3-pale-yellow w3-border p m noprint">
                    <h3><i class="fa fa-info-circle" aria-hidden="true"></i> Información</h3>
                    <p>Las políticas de algúnas páginas web podrían bloquear la captura de la imágen atomática, pero siempre puedes subir tus propias imágenes de referencia </p>
                    <form action="'.$GLOBALS['url'].'/option/registrourl/agregar_imagen/0" enctype="multipart/form-data"  method="post"  id="upload-form">
                        Subir imágen:  <input type="file" name="file" id="file-input" accept="image/png, image/jpeg, image/jpg"><br><small>Archivos permitidos: jpeg, jpg, png</small>
                    </form>
                  </div>
                 </div>';
    $contenido.='</div>';
    $contenido.="<script>
                //genera imagen de qr
                  const codigoQRDiv = document.getElementById('codigo-qr');
                  const codigoQR = new QRCode(codigoQRDiv, {
                    text: '".$GLOBALS['url']."/".$r['sShortUrl']."',
                    width: 128,
                    height: 128
                  });
                  //crear enlace de descarga
                  function descargarImagen() {
                    // Obtener el elemento canvas
                    var contenedor = document.getElementById('codigo-qr');
                    var canvas = contenedor.querySelector('canvas');

                    // Crear un enlace y establecer la URL de la imagen codificada en base64 como href
                    var enlace = document.createElement('a');
                    enlace.href = canvas.toDataURL('image/png'); // También puedes usar 'image/jpeg'

                    // Establecer el atributo download con el nombre de archivo deseado
                    enlace.download = '".$r['sNombreCliente'].".png'; // Cambiar a '.jpg' si estás usando 'image/jpeg'

                    // Simular un clic en el enlace para iniciar la descarga
                    enlace.click();
                  }
                  //comprimir imágen
                  document.getElementById('file-input').addEventListener('change', function() {
                      var file = this.files[0];
                      var reader = new FileReader();
                  
                      reader.onloadend = function() {
                          var img = new Image();
                          img.src = reader.result;
                  
                          img.onload = function() {
                              var canvas = document.createElement('canvas');
                              var ctx = canvas.getContext('2d');
                  
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
                                  formData.append('file', blob, file.name);
                                  
                                  // Realiza la solicitud AJAX para subir la imagen
                                  var xhr = new XMLHttpRequest();
                                  xhr.open('POST', '".$GLOBALS['url']."/option/registrourl/agregar_imagen/".$kUrl."', true);
                                  
                                  xhr.onload = function() {
                                      if (xhr.status === 200) {
                                          window.location.href = '".$GLOBALS['url']."/option/registrourl/".$kUrl."/1';
                                      } else {
                                        window.location.href = '".$GLOBALS['url']."/option/registrourl/".$kUrl."/e';
                                      }
                                  };
                                  
                                  xhr.send(formData);
                              }, file.type);
                          };
                      };
                  
                      reader.readAsDataURL(file);
                  });
                  
                  document.getElementById('upload-form').addEventListener('submit', function(e) {
                      e.preventDefault();
                  });
                  </script>";
    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Datos del Url</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);

  }

  ######## Redirigir URL ###########
  public function redirigirUrl($shortUrl){
    $sShortUrl = $shortUrl;
    //verificar que el enlace no contenga carácteres especiales
    if(preg_match("/^[a-zA-Z0-9]+$/", $sShortUrl)) {
      $url = new ModeloRegistroUrl;
      $r=$url->redirigirUrl($sShortUrl);
        //Verificar que exista la url registrada
        if(!empty($r)){
          header("Location:".$r);
        }else{
          header("Location:".$GLOBALS['url']);
        }
    }else{
      header("Location:".$GLOBALS['url']);
    }
  }

  #Buscar registros url
  public function buscarUrls(){
    $hoy=date("Y-m-d");
    $contenido = <<<END
                    <!--formulario -->
                    <form method="post" action="{$GLOBALS['url']}/option/registrourl/resultado/0" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
                    <h2 class="w3-center">Buscar Urls</h2>
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

                    <input class="w3-input w3-border hide" name="usuario" type="text" value="{$GLOBALS['usuario_id']}">
                    
                    <button class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding" style="max-width:100px; margin:auto;">Buscar</button>
                    
                    </form>
              END;
    $diccionario = array(
    'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Busqueda de Urls previamente registradas</b></h5> <small><i class="fa fa-info-circle"></i> Puedes llenar uno o varios campos para acotar tu busqueda<br><i class="fa fa-hand-o-right"></i>Para mostrar hasta 100 registros realizados en el último año puede presionar el botón de "buscar" dejando todos los campos en blanco</small>', 
    'contenido'=>$contenido);
    $this->mostrarVista($diccionario);
  }

  public function mostarBusqueda($datos){
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
    $busqueda = new ModeloRegistroUrl;
    $variable=$busqueda->mostrarBusqueda($kUrl,$datos[1],$fi,$ff,$usuario);
    //Se muestran los resultados de la búsqueda en una tabla
    $contenido ='<div class="w3-container">
                  <h5>Registros localizados con los parámetros indicados</h5> <small><i class="fa fa-info-circle"></i> En el enlace "info" podrás ver todos los detalles de tu url</small>
                  <div class="scroll">
                  <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                  <tr class="w3-blue">
                    <th>UrlCorto</th>
                    <th>Fecha</th>
                    <th>ID</th>
                    <th>Info</th>
                    <th>Eliminar</th>
                  </tr>';
    foreach ($variable as $key => $value) {
      $contenido.='<tr>
                    
                    <td><a target="_blank" href="'.$GLOBALS['url']."/".$value['sShortUrl'].'">'.$GLOBALS['url']."/".$value['sShortUrl'].'</a></td>
                    <td>'.$value['dtFecha'].'</td>
                     <td>'.$value['sNombreCliente'].'</td>
                     <td><a target="_blank"  href="'.$GLOBALS['url']."/option/registrourl/".$value['kUrl'].'/0">Ver datos de Url <i class="w3-large fa fa-paperclip w3-xlarge"></i></a></td>
                     <td><a onclick="confirmar(\'c-'.$value['kUrl'].'\')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                     <a id="c-'.$value['kUrl'].'" href="'.$GLOBALS['url']."/option/registrourl/borrar_url/".$value['kUrl'].'" class="w3-button w3-red w3-small hide">Confirma eliminar</a>
                     </td>
                  </tr>';
    }
    $contenido.='</table>
                  </div>
                  <br>
                  <a class="w3-button w3-dark-grey" href="'.$GLOBALS['url'].'/option/registrourl/buscar/0">Realizar una nueva búsqueda  <i class="fa fa-arrow-right"></i></a>
                </div>
                <hr>
                <script>
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
      'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Resultado de la búsqueda</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
  }

     #Modificar nota o ID
     public function modificarNotaId($kUrl){
      //obtener datos del registro
      $registro = new ModeloRegistroUrl;
      $r=$registro->mostrarRegistro($kUrl);
      //verificar permiso de modificarlo únicamente por el mismo usuario
    if($GLOBALS['usuario_id']==$r['fkUsuario']){
      $contenido = <<<END
                      <!--formulario -->
                      <form method="post" action="{$GLOBALS['url']}/option/registrourl/modificar/0" class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin">
                      <h2 class="w3-center">Editar</h2>
                      
                      <div class="w3-row w3-section">
                        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-internet-explorer"></i></div>
                          <div class="w3-rest">
                            <input class="w3-input w3-border" type="text" placeholder="Dirección web (Url) P.Ej.: https://anuncio.com" value="{$GLOBALS['url']}/{$r['sShortUrl']}" readonly="" disabled>
                          </div>
                      </div>
                      
                      <div class="w3-row w3-section">
                        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
                          <div class="w3-rest">
                            <input class="w3-input w3-border" name="cliente" type="text" placeholder="Identificador" style="text-transform:uppercase" value="{$r['sNombreCliente']}" >
                          </div>
                      </div>
                      
                      <div class="w3-row w3-section">
                        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-pencil"></i></div>
                          <div class="w3-rest">
                          <textarea class="w3-input w3-border" name="nota" rows="5" style="resize:none" spellcheck="false" data-ms-editor="true" placeholder="Nota (Máximo 240 caracteres)" maxlength="240">{$r['sNota']}</textarea>
                          </div>
                      </div>           
                      <input class="w3-input w3-border hide" name="kUrl" type="text" value="{$kUrl}">
                      <div style="max-width:210px; margin:auto;">
                        <button id="myButton" class="w3-button w3-block w3-section w3-blue w3-ripple w3-padding w3-left" style="max-width:100px; margin-right:1em;" >Actualizar</button>
                        <a href="{$GLOBALS['url']}/option/registrourl/{$kUrl}/0" class="w3-button w3-padding w3-section w3-red w3-left" style="max-width:200px;">Cancelar</a>
                      </div>
                      
                      <div id="loader"></div>
                      
                      </form>
                      <script>
                      document.getElementById('myButton').addEventListener('click', function() {
                        this.style.display = 'none';
                        document.getElementById('loader').style.display='block';
                      });
                      </script>
                END;
    }else{
      $contenido='<div class="w3-panel w3-pale-yellow w3-border">
                    <h3><i class="fa fa-ban" aria-hidden="true"></i>Acceso restringido</h3>
                    <p>No puede editar este registro</p>
                  </div>';
    }
      $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-internet-explorer"></i> Editar Identificador y/o Nota de registro de Url</b></h5>', 
      'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
    }

    public function actualizarNota($datos){
      //$datos=> kUrl, sNombreCliente, sNota
      $actualizar = new ModeloRegistroUrl;
      $resultado=$actualizar->actualizarNota($datos);
      if($resultado){
        header("Location:".$GLOBALS['url']."/option/registrourl/".$datos["0"]."/1");
      }else{
        header("Location:".$GLOBALS['url']."/option/registrourl/".$datos["0"]."/e");
      }

    }

    public function guardarImagen($imagen,$kUrl){
      $errors = [];
      $path = 'files/extra_img/';
      $extensions = ['jpg', 'jpeg', 'png', 'gif'];

      $file_name = $imagen['name'];
      $file_tmp = $imagen['tmp_name'];
      $file_type = $imagen['type'];
      $file_size = $imagen['size'];

      $file_ext = strtolower(end(explode('.', $imagen['name'])));
      $file = $path . $kUrl .'-'. time() . '.' . $file_ext;

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
          array_push($datos,$kUrl,$file);
          $registrar_img= new ModeloRegistroUrl;
          $resultado=$registrar_img->registrarUrlImagen($datos);
          if($resultado){
            header("Location:".$GLOBALS['url']."/option/registrourl/".$kUrl."/1");
          }
      } else {
        header("Location:".$GLOBALS['url']."/option/registrourl/".$kUrl."/e");
      }
    }

    public function imagenMostrarOcultar($datos){
      $r=(explode("-",$datos));
      $datos_x=array();
      $switch=$r[1]==='1'? '0' : '1';
      array_push($datos_x,$r[0],$switch);
      //$datos=> kUrl, bImg
      $actualizar = new ModeloRegistroUrl;
      $resultado=$actualizar->imagenMostrarOcultar($datos_x);
      if($resultado){
        header("Location:".$GLOBALS['url']."/option/registrourl/".$r[0]."/1");
      }else{
        header("Location:".$GLOBALS['url']."/option/registrourl/".$r[0]."/e");
      }
    }

    public function verImagen($datos){
      $ver = new ModeloRegistroUrl;
      $r=$ver->verImagen($datos);

      $contenido='<div class="w3-card-4 w3-dark-grey">
                  <div class="w3-container w3-center">
                    <h3> </h3>
                    <img src="'.$GLOBALS['url'].'/'.$r[0]['sNameImg'].'" alt="Avatar" style="width:80%">
                    <h3> </h3>
                   
                    <a href="'.$GLOBALS['url'].'/option/registrourl/borrar_imagen/'.$r[0]['kUrlImg'].'-'.$r[0]['fkUrl'].'" class="w3-button w3-red">Eliminar</a>
                    <a href="'.$GLOBALS['url'].'/option/registrourl/'.$r[0]['fkUrl'].'/0" class="w3-button w3-green">Cancelar</a>
                  </div>
                  </div>
                 ';

      $diccionario = array(
        'titulo'=>'<h5><b><i class="fa fa-picture-o" aria-hidden="true"></i> Visualizar imágen individual</b></h5>', 
        'contenido'=>$contenido);
      $this->mostrarVista($diccionario);
    }

    public function borrarImagen($datos){
      $r=explode("-",$datos);
      $tabla="t_url_img";
      $key="kUrlImg";
      $id=$r[0];
      $borrar= new ControlGeneral;
      $b=$borrar->borrar($tabla,$key,$id);
      if($b){
        header("Location:".$GLOBALS['url']."/option/registrourl/".$r[1]."/1");
      }else{
        header("Location:".$GLOBALS['url']."/option/registrourl/".$r[1]."/e");
      }
    }

    public function borrarUrl($datos){
      $tabla="t_url";
      $key="kUrl";
      $id=$datos;
      $borrar= new ControlGeneral;
      $b=$borrar->borrar($tabla,$key,$id);
      if($b){
        header("Location:".$GLOBALS['url']."/option/registrourl/buscar/b");
      }else{
        header("Location:".$GLOBALS['url']."/option/registrourl/buscar/e");
      }
    }
  
}
?>
