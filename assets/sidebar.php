<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white noprint" style="z-index:3;width:300px;  margin-top:10px" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="<?= $datos_sidebar['img-user'] ?>" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span></span><br>
      <a href="<?= $datos_sidebar['usuario'] ?>" class="w3-bar-item w3-button <?= ($current_url == $datos_sidebar['usuario']) ? 'w3-blue' : '' ?>"><i class="fa fa-user"></i></a>
      <a href="<?= $datos_sidebar['lista'] ?>" class="w3-bar-item w3-button <?= ($current_url == $datos_sidebar['lista']) ? 'w3-blue' : '' ?>"<?=$datos_sidebar['admin']?> ><i class="fa fa-cog"></i></a>
      <a href="<?= $datos_sidebar['logout'] ?>" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Menú de Opciones</h5>
  </div>
  <!-- https://fontawesome.com/v4/cheatsheet/ -->
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Cerrar Menú</a>
    <a href="<?=$datos_sidebar['url']?>" class="w3-bar-item w3-button w3-padding <?= ($current_url == $datos_sidebar['url']) ? 'w3-blue' : '' ?>"><i class="fa fa-home" aria-hidden="true"></i>Inicio</a>
    <hr>
    <a href="<?=$datos_sidebar['agregar']?>" class="w3-bar-item w3-button w3-padding <?= ($current_url == $datos_sidebar['agregar']) ? 'w3-blue' : '' ?>"><i class="fa fa-plus-circle"></i>  Agregar Url</a>
    <a href="<?=$datos_sidebar['buscar']?>" class="w3-bar-item w3-button w3-padding <?= ($current_url == $datos_sidebar['buscar']) ? 'w3-blue' : '' ?>"><i class="fa fa-search"></i>  Buscar Url</a>
    <hr>
    <a href="<?=$datos_sidebar['sujeto']?>" class="w3-bar-item w3-button w3-padding <?= ($current_url == $datos_sidebar['sujeto']) ? 'w3-blue' : '' ?>"><i class="fa fa-user-plus"></i>  Agregar Sujeto</a>
    <a href="<?=$datos_sidebar['buscar_sujetos']?>" class="w3-bar-item w3-button w3-padding <?= ($current_url == $datos_sidebar['buscar_sujetos']) ? 'w3-blue' : '' ?>"><i class="fa fa-search"></i>  Buscar Sujeto</a>
    <hr>
    <a href="<?=$datos_sidebar['url']?>assets/img/manual_urlh.pdf" target="_blank" class="w3-bar-item w3-button w3-padding"><i class="fa fa-question-circle-o"></i>  Ayuda</a><br><br>
  </div>
</nav>
