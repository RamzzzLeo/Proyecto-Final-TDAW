<?php
// Vista: Perfil
// Variables esperadas: $user (array con datos del usuario)

if (!$user) {
  // Si por alguna razón entran sin sesión, solo mostramos aviso
  echo "<div class='estado'><strong>Error:</strong> No hay sesión activa.</div>";
  return;
}
?>

<section class="seccion" style="max-width:640px;margin:0 auto;">
  <h1 class="h2">Perfil de usuario</h1>
  <p class="texto-suave">Información de tu cuenta.</p>

  <div class="panel" style="margin-top:14px;">
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre']); ?></p>
    <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['correo']); ?></p>
    <p><strong>Rol:</strong> <?php echo htmlspecialchars($user['rol']); ?></p>
  </div>

  <div style="margin-top:14px;">
    <a class="boton boton--suave" href="index.php?vista=home">Volver</a>
    <a class="boton" href="index.php?vista=logout" style="margin-left:8px;">Cerrar sesión</a>
  </div>
</section>
