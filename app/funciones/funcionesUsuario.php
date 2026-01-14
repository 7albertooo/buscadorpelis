<?php
include_once __DIR__ . '/../conexion/conexion.php';

function registrarUsuario($usuario, $email, $password, $avatar)
{

    // Acceso a la variable de conexión global
    global $conexion;

    // Hashear la contraseña antes de guardarla
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Preparar y ejecutar la consulta de inserción
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario,email,password,avatar) VALUES (?, ?, ?, ?)");

    // Vincular los parámetros
    $stmt->bind_param("ssss", $usuario, $email, $hash, $avatar);

    // Ejecutar la consulta
    return $stmt->execute();
}



function loginUsuario($usuarioEmail)
{

    // Acceso a la variable de conexión global
    global $conexion;

    // Preparar la consulta de selección
    $sql = ("SELECT * FROM usuarios WHERE usuario = ? OR email = ? LIMIT 1");

    // Preparar la declaración
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("ss", $usuarioEmail, $usuarioEmail);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado en forma de array asociativo
    return $stmt->get_result()->fetch_assoc();
}

//Funcion para obtener los datos del perfil de un usuario

function datosPerfil($username)
{
    // Acceso a la variable de conexión global
    global $conexion;

    // Preparar la consulta de selección
    $sql = ("SELECT usuario, email, avatar FROM usuarios WHERE usuario = ? LIMIT 1");

    // Preparar la declaración
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("s", $username);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado en forma de array asociativo
    return $stmt->get_result()->fetch_assoc();
}

function cambiarAvatar($username, $avatarPath)
{
    // Acceso a la variable de conexión global
    global $conexion;

    // Preparar la consulta de actualización
    $sql = ("UPDATE usuarios SET avatar = ? WHERE usuario = ?");

    // Preparar la declaración
    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("ss", $avatarPath, $username);

    // Ejecutar la consulta
    return $stmt->execute();
}
