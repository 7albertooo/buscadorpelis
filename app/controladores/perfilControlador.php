<?php
require_once __DIR__ . '/../funciones/session.php';
include_once __DIR__ . '/../conexion/conexion.php';
include_once __DIR__ . '/../funciones/funcionesUsuario.php';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if (!isset($_SESSION['username'])) {
    header("Location: $baseDir/login.php");
    exit();
}

$username = $_SESSION['username'];
$usuarioData = datosPerfil($username);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_avatar'])) {

    // Verificar CSRF
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        die("Error CSRF: Solicitud no autorizada.");
    }

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['errores'] = ["Error al subir la imagen."];
        header("Location: $baseDir/perfil.php");
        exit();
    }

    $file = $_FILES['avatar'];

    // Validar tamaño (10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        $_SESSION['errores'] = ["La imagen es demasiado grande. Máximo 10MB."];
        header("Location: $baseDir/perfil.php");
        exit();
    }

    // validar tipo real (MIME)
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
    } else {
        // Fallback si finfo no está disponible
        $mime = $file['type'];
    }

    $allowed = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mime, $allowed)) {
        $_SESSION['errores'] = ["Formato de imagen no permitido. Usa JPG, PNG o GIF."];
        header("Location: $baseDir/perfil.php");
        exit();
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $username);
    $filename = $safeName . '_' . time() . '.' . $ext;

    $targetDir = __DIR__ . '/../../public/img/profiles/';

    // Intentar crear el directorio si no existe con permisos más amplios
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            $_SESSION['errores'] = ["No se pudo crear la carpeta de perfiles. Verifica los permisos de 'public/img' en tu hosting."];
            header("Location: $baseDir/perfil.php");
            exit();
        }
    }

    // Verificar si el directorio es escribible
    if (!is_writable($targetDir)) {
        $_SESSION['errores'] = ["La carpeta de perfiles no tiene permisos de escritura (chmod 777)."];
        header("Location: $baseDir/perfil.php");
        exit();
    }

    $targetPath = $targetDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        $error_upload = error_get_last();
        $_SESSION['errores'] = ["No se pudo guardar la imagen en el servidor. " . ($error_upload['message'] ?? '')];
        header("Location: $baseDir/perfil.php");
        exit();
    }

    // Ruta que guardaremos en la BBDD (relativa a la vista)
    $dbPath = 'img/profiles/' . $filename;

    if (cambiarAvatar($username, $dbPath)) {
        $_SESSION['avatar'] = $dbPath;
        $_SESSION['mensaje_exito'] = 'Foto de perfil actualizada.';
    } else {
        $_SESSION['errores'] = ['No se pudo actualizar la foto en la base de datos.'];
    }

    header("Location: $baseDir/perfil.php");
    exit();
}
