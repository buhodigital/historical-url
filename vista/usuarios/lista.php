<a href="<?= $GLOBALS['url'] ?>/option/usuario/nuevousuario/0" class="w3-btn w3-blue-grey w3-margin ri">Nuevo</a>
<table class="w3-table-all">
  <tr>
    <th><b>Usuario</b></th>
    <th>Nombre</th>
    <th>Rol</th>
    <th>Editar</th>
  </tr>
  <?php foreach ($usuarios as $u): ?>
  <tr>
    <td><?= htmlspecialchars($u['sUsuario']) ?></td>
    <td><?= htmlspecialchars($u['sNombre']) ?></td>
    <td><?= htmlspecialchars($u['sRolTexto']) ?></td>
    <td><a class="w3-text-blue" href="<?= $GLOBALS['url'] ?>/option/usuario/<?= $u['kUsuario'] ?>/0"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
  </tr>
  <?php endforeach; ?>
</table>