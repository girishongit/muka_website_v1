<?php
/**
 * POST /php/admin/logout.php
 * Destroys the admin session.
 */
require_once __DIR__ . '/../_cors.php';

session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Strict']);
session_start();
session_unset();
session_destroy();

// Clear the hint cookie
setcookie('admin_session_hint', '', ['expires' => time() - 3600, 'path' => '/', 'samesite' => 'Strict']);

echo json_encode(['success' => true]);
