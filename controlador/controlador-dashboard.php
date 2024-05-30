<?php 
include_once('modelo/modelo-dashboard.php');

########################################
#    ctr Vista pantalla principal      #
########################################
class ControlVistaPrincipal{

  private function mostrarVista($diccionario){   
    $template = file_get_contents('vista/plantillas/plantilla-principal.html');
    foreach ($diccionario as $clave=>$valor) { $template = str_replace('{'.$clave.'}', $valor, $template); }
    print $template;
  }
 
  public function dashBoard(){
    $contenido1=$this->ultimosRegistros();
    $contenido2=$this->listaSujetos();
    $diccionario = array(
      'titulo'=>'<h5><b><i class="fa fa-dashboard"></i> Vista general</b></h5>',
      'contenido'=>'',
      'ultimos_registros'=>$contenido1,
      'ultimos_sujetos'=>$contenido2
    );
       //Generación de la vista de la página inicial
    $this->mostrarVista($diccionario);
  }

  public function ultimosRegistros(){
    $usuario = $GLOBALS['usuario_id'];
    $ultimo_registro = new ModeloDashboard;
    $r=$ultimo_registro->ultimosRegistros(6,$usuario);

    $contenido ='<div class="w3-container">
                  <h5>Últimos registros</h5> <small><i class="fa fa-info-circle"></i> En el enlace "info" podrás ver todos los detalles de tu url</small>
                  <div class="scroll">
                  <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                  <tr>
                    <!-- <th>Key</th> -->
                    <th>Info</th>
                    <th>UrlCorto</th>
                    <th>Fecha</th>
                    <th>ID</th>
                    <!-- <th>Nota</th> -->
                  </tr>';
    foreach ($r as $key => $value) {
      $contenido.='<tr>
                   <!-- <td>'.$value['kUrl'].'</td> -->
                   <td><a  href="'.$GLOBALS['url']."/option/registrourl/".$value['kUrl'].'/0"><i class="w3-large fa fa-paperclip"></i></a></td>
                   <td><a target="_blank" href="'.$GLOBALS['url']."/".$value['sShortUrl'].'">'.$GLOBALS['url']."/".$value['sShortUrl'].'</a></td>
                   <td>'.$value['dtFecha'].'</td>
                    <td>'.$value['sNombreCliente'].'</td>
                    <!-- <td>'.$value['sNota'].'</td> -->
                  </tr>';
    }
    
    $contenido.='</table>
                  </div>
                  <br>
                  <a class="w3-button w3-dark-grey" href="'.$GLOBALS['url'].'/option/registrourl/datos/0"><i class="fa fa-plus-circle"></i> URL  <i class="fa fa-arrow-right"></i></a>
                </div>
                <hr>';
        return $contenido;
  }

  public function listaSujetos(){
    $usuario = $GLOBALS['usuario_id'];
    $ultimo_registro = new ModeloDashboard;
    $r=$ultimo_registro->listaSujetos($usuario);
    //tabla de w3.css
    $contenido="<div class='scroll'><div class='w3-container'><small><i class='fa fa-info-circle'></i> En el enlace 'editar' Podrás visualizar y modificar los datos de los sujetos</small>
    <table class='w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white'>";
    $contenido.="<tr>
                  <th>Editar</th>
                  <th>Nombre del solicitante</th>
                  <th>Fecha del avalúo</th>
                  </tr>";
    foreach ($r as $key => $value) {
      $contenido.="<tr><td><a target='_blank' href='".$GLOBALS['url']."/option/sujeto/".$value['kSujeto']."/0'><i class='fa fa-edit'></i></a></td><td>".$value['sNombreSolicita']."</td><td>".$value['dtFechaAvaluo']."</td>
      </tr>";
    }
    $contenido.="</table>
    <br>
    <a class='w3-button w3-dark-grey' href='".$GLOBALS['url']."/option/sujeto/datos/0'><i class='fa fa-user-plus'></i> Sujeto  <i class='fa fa-arrow-right'></i></a>
    </div>
    </div>";

     return $contenido;
  }
  
 }

 ?>