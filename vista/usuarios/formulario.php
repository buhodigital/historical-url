<!--formulario -->
<form method="post" action="<?= $actionUrl ?>">
  <input type="text" name="kUsuario" value="<?= $kUsuario ?>" class="hide">
  <div class="w3-row-padding">
  <div class="w3-half">
    <label>Usuario</label>
    <input class="w3-input w3-border" name="sUsuario" type="text" placeholder="Escriba un nombre sin espacios" value="<?= $sUsuario ?>" <?= $isEdit ? 'disabled' : '' ?>>
  </div>
  <div class="w3-half">
    <label>Nombre</label>
    <input class="w3-input w3-border" name="sNombre" type="text" placeholder="Escriba su nombre completo"  value="<?= $sNombre ?>">
  </div>
  <div class="w3-half">
    <label>Password</label>
    <input class="w3-input w3-border" name="sPassword" type="password" placeholder="<?= $passwordPlaceholder ?>" value="">
  </div>
  <div class="w3-half">
    <label>Email</label>
    <input class="w3-input w3-border" name="sEmail" type="email" placeholder="Escriba un email válido"  value="<?= $sEmail ?>">
  </div>

  <div class="w3-half w3-margin" <?= $adminStyle ?>>
      <input class="w3-radio" type="radio" name="nRol" value="0" <?= ($nRol == '0') ? 'checked' : '' ?> >
      <label>Master</label>

      <input class="w3-radio" type="radio" name="nRol" value="1" <?= ($nRol == '1') ? 'checked' : '' ?>>
      <label>Superusuario</label>

      <input class="w3-radio" type="radio" name="nRol" value="2" <?= ($nRol == '2') ? 'checked' : '' ?>>
      <label>Usuario</label>

      <input class="w3-radio" type="radio" name="nRol" value="3" <?= ($nRol == '3') ? 'checked' : '' ?>>
      <label>Cobro</label>

      <input class="w3-radio" type="radio" name="nRol" value="4" <?= ($nRol == '4') ? 'checked' : '' ?>>
      <label>Operación</label>
  </div>
  </div>
  <button class="w3-btn w3-blue-grey w3-margin"><?= $submitBtnText ?></button>
  <?php if($isEdit): ?>
    <a onclick="showConfirmation()" class="w3-btn w3-red w3-margin" <?= $adminStyle ?>>Borrar</a>
  <?php endif; ?>
</form>

<?php if($isEdit): ?>
<!--Confirmación-->
<div id="confirmation" class="w3-panel w3-yellow w3-display-container w3-border hide">
<span onclick="hideAlert(this.parentElement)" class="w3-button w3-large w3-display-topright">×</span>
  <h3>Precaución</h3>
  <p>Borrará permantentemente al usuario, ¿desea continuar?</p>
<a href="<?= $GLOBALS['url'] ?>/option/usuario/borrarusuario/<?= $kUsuario ?>" class="w3-btn w3-blue-grey w3-margin">Si</a>
<button onclick="hideAlert(this.parentElement)" class="w3-btn w3-red w3-margin">No</button>
</div>
<?php endif; ?>