<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once __DIR__ . '/../app/controladores/loginControlador.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/icon.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kablammo&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap');
    </style>
</head>

<body>

    <?php include_once "header.php"; ?>

    <main class="form-signin w-100 m-auto">

        <?php if (isset($_SESSION['mensaje_exito'])) : ?>
            <div class="alert alert-success mt-3" role="alert">
                <p><?php echo $_SESSION['mensaje_exito'];
                    unset($_SESSION['mensaje_exito']); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <?php echo csrf_input(); ?>

            <h1 class="">BIENVENIDO</h1>

            <div class="input-group-custom">
                <label for="floatingInput">Usuario</label>
                <input type="text" class="" id="floatingInput" name="username" placeholder="Ingresa tu usuario" autocomplete="off" required />
            </div>

            <div class="input-group-custom">
                <label for="floatingPassword">Contraseña</label>
                <input type="password" class="" id="floatingPassword" name="password" placeholder="Ingresa tu contraseña" autocomplete="off" required />
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit" name="login">
                Iniciar sesión
            </button>

            <a href="registro.php" class="form-signin-link">¿No tienes cuenta? Regístrate aquí</a>

            <?php if (!empty($_SESSION['errores'])) : ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php foreach ($_SESSION['errores'] as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errores']); ?>
            <?php endif; ?>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>