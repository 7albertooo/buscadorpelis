<?php

include_once __DIR__ . '/../app/controladores/perfilControlador.php';


$username = $usuarioData['usuario'] ?? 'usuarioEjemplo';
$email = $usuarioData['email'] ?? 'usuario@ejemplo.com';
$avatar = (!empty($usuarioData['avatar'])) ? $usuarioData['avatar'] : 'img/default-avatar.jpg';
$reseñas = [];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perfil - <?php echo htmlspecialchars($username); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="img/icon.png">
</head>

<body>

  <?php include_once "header.php"; ?>

  <main class="main-index container app-main d-flex flex-column align-items-center">

    <?php if (isset($_SESSION['mensaje_exito'])) : ?>
      <div class="alert alert-success w-100" style="max-width:1100px;">
        <?php echo $_SESSION['mensaje_exito'];
        unset($_SESSION['mensaje_exito']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])) : ?>
      <div class="alert alert-danger w-100" style="max-width:1100px;">
        <ul class="mb-0">
          <?php foreach ($_SESSION['errores'] as $error) : ?>
            <li><?php echo $error; ?></li>
          <?php endforeach;
          unset($_SESSION['errores']); ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="profile-wrapper w-100" style="max-width:1100px;">

      <div class="row g-4">

    
        <div class="col-12 col-md-4">
          <div class="card profile-card shadow">
            <div class="card-body text-center">
              <div class="avatar-wrap mb-3">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="avatar img-fluid">
              </div>
              <h4 class="mb-1">Username</h4>
              <p class="mb-2">@<?php echo htmlspecialchars($username); ?></p>

              <div class="profile-actions mb-3">
              
                <form method="post" action="perfil.php" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                  <?php echo csrf_input(); ?>
                  <input type="hidden" name="upload_avatar" value="1">
                  <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden onchange="this.form.submit()">
                  <label for="avatarInput" class="btn btn-outline-light btn-sm mb-0" style="width:100%">Cambiar foto</label>
                </form>
              </div>

              <div class="profile-details text-start w-100">
                <h5>Datos:</h5>
                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p class="mb-1"><strong>Miembro desde:</strong> 2024-09-01</p>
              </div>
            </div>
          </div>
        </div>

    
        <div class="col-12 col-md-8">
          <div class="card shadow mb-3 reviews-card">
            <div class="card-body">
              <h5 class="card-title">Reseñas publicadas</h5>

              <div class="reviews-list">
                <?php if (empty($reseñas)) : ?>
                  <p class="text">No has publicado ninguna reseña aún.</p>
                <?php endif; ?>

                <?php if (!empty($reseñas)) : ?>
                  <?php foreach ($reseñas as $resena) : ?>
                    <div class="card review-card mb-3">
                      <div class="card-body d-flex gap-3">
                        <div class="flex-grow-1">
                          <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1"><?php echo htmlspecialchars($resena['titulo']); ?></h6>
                            <small class="text">2025-01-01</small>
                          </div>
                          <p class="mb-1 text small">Valoración: ★★★★☆</p>
                          <p class="mb-0">Comentario de ejemplo: Me gustó mucho esta película porque...</p>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>


              </div>
            </div>
          </div>


        </div>

      </div>

      
      <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="avatarModalLabel">Avatar de <?php echo htmlspecialchars($username); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="img-fluid">
            </div>
          </div>
        </div>

      </div>

  </main>

  <!-- Script para abrir el modal del avatar -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const avatarModal = new bootstrap.Modal(document.getElementById('avatarModal'));
      const avatarImg = document.querySelector('.avatar');
      const modalTrigger = document.querySelector('.avatar-wrap');

      if (modalTrigger) {
        modalTrigger.addEventListener('click', function() {
          avatarModal.show();
        });
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>