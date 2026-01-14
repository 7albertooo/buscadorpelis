<?php require_once __DIR__ . '/../app/funciones/session.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FSERCH</title>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark nav-container align-items-center ">
    <div class="container-fluid align-items-center">

      
      <a class="navbar-brand fw-bold" href="index.php">FSERCH</a>

   
      <button class="navbar-toggler" type="button"
        aria-controls="navbarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

     
      <div class="collapse navbar-collapse justify-content-center align-items-center" id="navbarMenu">
        <ul class="navbar-nav text-center">

          <?php if (isset($_SESSION['username'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Inicio</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="perfil.php">Perfil</a>
            </li>
          <?php endif; ?>

          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Admin</a>
            </li>
          <?php endif; ?>
        </ul>
        <div class="d-lg-none mt-3 w-100 text-center">
          <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php" class="btn btn-netflix w-100">Cerrar sesión</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-netflix w-100">Iniciar sesión</a>
          <?php endif; ?>
        </div>
      </div>

      
      <div class="d-none d-lg-block">
        <?php if (isset($_SESSION['username'])): ?>
          <a href="logout.php" class="btn btn-netflix">Cerrar sesión</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-netflix">Iniciar sesión</a>
        <?php endif; ?>
      </div>

    </div>
  </nav>

  
  <div id="navOverlay" class="nav-overlay" aria-hidden="true"></div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Asegurar que el botón toggler siempre cierre/abra el collapse incluso si hay solapamientos CSS
    (function() {
      var toggler = document.querySelector('.navbar-toggler');
      var menu = document.getElementById('navbarMenu');
      var overlay = document.getElementById('navOverlay');
      if (!toggler || !menu) return;

      toggler.addEventListener('click', function(e) {
        // Evitar que otros listeners actúen
        e.stopImmediatePropagation();
        e.preventDefault();
        // Usar la API de Bootstrap para alternar de forma segura
        var collapse = bootstrap.Collapse.getOrCreateInstance(menu);
        collapse.toggle();
      });

      // Mantener aria-expanded sincronizado con el estado del collapse
      menu.addEventListener('shown.bs.collapse', function() {
        toggler.setAttribute('aria-expanded', 'true');
        if (overlay) overlay.classList.add('visible');
      });
      menu.addEventListener('hidden.bs.collapse', function() {
        toggler.setAttribute('aria-expanded', 'false');
        if (overlay) overlay.classList.remove('visible');
      });

      // Si se hace click sobre el overlay, cerrar el menú
      if (overlay) {
        overlay.addEventListener('click', function() {
          var collapse = bootstrap.Collapse.getOrCreateInstance(menu);
          collapse.hide();
        });
      }
    })();
  </script>

</body>

</html>