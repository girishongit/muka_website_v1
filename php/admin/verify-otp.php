<?php
/**
 * POST /php/admin/verify-otp.php
 * Body: { "email": "user@example.com", "otp": "123456" }
 *
 * Validates the OTP and starts an authenticated session (60-min TTL).
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../Database.php';

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim(strtolower($body['email'] ?? ''));
$otp   = trim($body['otp'] ?? '');

if (!$email || !$otp) {
    http_response_code(422);
    echo json_encode(['error' => 'Email and OTP are required']);
    exit;
}

$pdo = (new Database())->getConnection();

// Fetch latest unused, unexpired OTP for this email
$stmt = $pdo->prepare("
    SELECT id, otp_hash
    FROM admin_otps
    WHERE email = ?
      AND used_at IS NULL
      AND expires_at > NOW()
    ORDER BY id DESC
    LIMIT 1
");
$stmt->execute([$email]);
$row = $stmt->fetch();

if (!$row || !password_verify($otp, $row['otp_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired OTP']);
    exit;
}

// Mark OTP as used
$pdo->prepare("UPDATE admin_otps SET used_at = NOW() WHERE id = ?")
    ->execute([$row['id']]);

// SameSite=None is required for cross-origin cookie sending (localhost dev + remote PHP).
// Secure flag must match: None requires Secure on HTTPS, but localhost is HTTP.
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$sameSite = $isSecure ? 'None' : 'Lax';

session_set_cookie_params([
    'lifetime' => 3600,
    'path'     => '/',
    'secure'   => $isSecure,
    'httponly' => true,
    'samesite' => $sameSite,
]);
session_start();
session_regenerate_id(true);
$_SESSION['admin_email']      = $email;
$_SESSION['logged_in_until']  = time() + 3600;

// Non-HttpOnly hint so the Nuxt middleware can detect session presence client-side
setcookie('admin_session_hint', '1', [
    'expires'  => time() + 3600,
    'path'     => '/',
    'secure'   => $isSecure,
    'httponly' => false,
    'samesite' => $sameSite,
]);

echo json_encode(['success' => true]);
