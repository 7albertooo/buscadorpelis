<?php

$envFile = __DIR__ . '/../../.env';
if (!file_exists($envFile)) {
    die("Error crítico: El archivo .env no existe en el servidor. Asegúrate de haberlo subido a la raíz del proyecto.");
}

$env = parse_ini_file($envFile);

if (!$env) {
    die("Error crítico: No se pudo leer el archivo .env o está vacío.");
}

// Datos de conexión
$host = $env['DB_HOST'] ?? '';
$usuario = $env['DB_USER'] ?? '';
$clave = $env['DB_PASS'] ?? '';
$basedatos = $env['DB_NAME'] ?? '';

try {
    // Intentar establecer la conexión
    $conexion = mysqli_connect($host, $usuario, $clave, $basedatos);

    if (!$conexion) {
        throw new Exception("Error al conectar: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    die("Hubo un problema de conexión a la base de datos: " . $e->getMessage());
} catch (mysqli_sql_exception $e) {
    die("Credenciales incorrectas en el hosting: " . $e->getMessage());
}
