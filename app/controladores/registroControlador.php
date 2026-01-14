<?php
require_once __DIR__ . '/../funciones/session.php';
include_once __DIR__ . '/../conexion/conexion.php';
include_once __DIR__ . '/../funciones/funcionesUsuario.php';
include_once __DIR__ . '/../funciones/funciones.php';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if (!empty($_SESSION['username'])) {
    header("Location: $baseDir/index.php");
    exit();
}

if (!isset($_SESSION['errores'])) {
    $_SESSION['errores'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar CSRF
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        die("Error CSRF: Solicitud no autorizada.");
    }

    $datos_limpios = [
        'username' => sanear($_POST['username'] ?? ''),
        'email' => sanear($_POST['email'] ?? ''),
        'password' => sanear($_POST['password'] ?? ''),
    ];

    $avatar = 'img/default-avatar.jpg'; // Avatar por defecto

    $errores = validarFormulario($datos_limpios);

    if (empty($errores)) {
        $registro_exitoso = registrarUsuario($datos_limpios['username'], $datos_limpios['email'], $datos_limpios['password'], $avatar);

        if ($registro_exitoso) {
            $_SESSION['mensaje_exito'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: $baseDir/login.php");
            exit();
        } else {
            $errores[] = "Error al registrar el usuario. Inténtalo de nuevo.";
        }
    }
}
