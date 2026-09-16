<?php
/**
 * Session guard — include at the top of every admin endpoint.
 * Sets $adminEmail on success; exits with 401 on failure.
 */

$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 3600,
    'path'     => '/',
    'secure'   => $isSecure,
    'httponly' => true,
    'samesite' => $isSecure ? 'None' : 'Lax',
]);
session_start();

$now = time();
if (
    empty($_SESSION['admin_email']) ||
    empty($_SESSION['logged_in_until']) ||
    (int)$_SESSION['logged_in_until'] < $now
) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$adminEmail = $_SESSION['admin_email'];
