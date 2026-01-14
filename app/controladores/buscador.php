<?php
require_once __DIR__ . '/../funciones/session.php';
include_once __DIR__ . '/../funciones/funcionesBuscador.php';

$apiKey = "e6f96b74";

// Inicializamos la variable
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar CSRF
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
        die("Error CSRF: Solicitud no autorizada.");
    }

    if (isset($_POST['titulo'])) {

        $data = buscador($_POST['titulo'], $apiKey);

        if ($data) {
            $_SESSION['data'] = $data;  // Guardamos siempre
        }
    }

    if (isset($_POST['clear_data'])) {
        unset($_SESSION['data']);
    }
    $redirect = basename($_SERVER['PHP_SELF']) ?: 'buscador.php';
    header("Location: $redirect");
    exit();
}
