<?php
require_once __DIR__ . '/../../controladores/sesion_controlador.php';
$usuario = auth_user();
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?php echo htmlspecialchars($tituloPagina ?? 'Eventos ESCOM'); ?></title>
  <link rel="stylesheet" href="assets/css/estilos.css" />
</head>
<body>
  <header class="topbar">
    <div class="contenedor topbar__fila">
      <a class="marca" href="?vista=home">
        <span class="marca__punto" aria-hidden="true"></span>
        <span>Eventos ESCOM</span>
      </a>

<nav class="nav">
  <a class="nav__link" href="?vista=home">Home</a>

  <?php if ($usuario): ?>
    <a class="nav__link" href="?vista=perfil">Perfil</a>
    <a class="nav__link" href="?vista=logout">Salir</a>
  <?php else: ?>
    <a class="nav__link" href="?vista=login">Login</a>
    <a class="nav__link" href="?vista=register">Registro</a>
  <?php endif; ?>

  <button class="boton boton--suave" id="btnTema" type="button">
    Tema
  </button>
</nav>
</div>
</header>

  <main class="contenedor">
    <?php
      if (isset($vista) && file_exists($vista)) require $vista;
      else echo "<div class='estado'><strong>Error:</strong> No se encontró la vista.</div>";
    ?>
  </main>

  <footer class="footer">
    <div class="contenedor footer__fila">
      <small>Proyecto TDAW · <?php echo date('Y'); ?></small>
      <small class="texto-suave">ESCOM · Plataforma de eventos</small>
    </div>
  </footer>

  <script src="assets/js/app.js"></script>
</body>
</html>
