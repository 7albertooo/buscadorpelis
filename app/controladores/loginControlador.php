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

    $_SESSION['username'] = "";
    $_SESSION['login'] = false;
    $_SESSION['rol'] = "";

    $usuario = sanear($_POST['username']);
    $password = sanear($_POST['password']);

    $usuarioData = loginUsuario($usuario);

    if ($usuarioData && password_verify($password, $usuarioData['password'])) {

        $_SESSION['username'] = $usuarioData['usuario'];
        $_SESSION['login'] = true;
        $_SESSION['rol'] = $usuarioData['rol'];
        header("Location: $baseDir/index.php");
        exit();
    } else {

        $_SESSION['errores'] = ["Usuario o contraseña incorrectos."];
        header("Location: $baseDir/login.php");
        exit();
    }
}
