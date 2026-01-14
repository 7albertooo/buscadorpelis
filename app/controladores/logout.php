<?php
require_once __DIR__ . '/../funciones/session.php';

// Usar helper seguro para destruir sesión
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
secure_logout("$baseDir/index.php");
