<?php
// session.php - inicializa sesiones con cookie de sesión y timeout por inactividad

// Configurar parámetros de cookie (lifetime=0 => cookie de sesión que se borra al cerrar el navegador)
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https');
$httponly = true;
$samesite = 'Lax';

// Evitar warnings si ya se configuró anteriormente
if (session_status() === PHP_SESSION_NONE) {
    // use session_set_cookie_params con array (PHP 7.3+)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite,
    ]);
    session_start();
}

// Timeout por inactividad (en segundos)
$timeout = 30 * 60; // 30 minutos, ajustar si se desea
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
    // destruir sesión por timeout
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    // iniciar nueva sesión limpia para que el resto del flujo no falle
    session_start();
}

// Actualizar última actividad
$_SESSION['LAST_ACTIVITY'] = time();

// Regenerar id de sesión cada cierto tiempo para prevenir fijación
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 30 * 60) { // regenerar cada 30 minutos
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// Helper opcional para cerrar sesión de forma segura
function secure_logout($redirect = 'index.php')
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    if ($redirect) {
        header('Location: ' . $redirect);
        exit();
    }
}

// CSRF Protection
function generate_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

function csrf_input()
{
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
