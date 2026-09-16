<?php
/**
 * POST /php/admin/send-otp.php
 * Body: { "email": "user@example.com" }
 *
 * Generates a 6-digit OTP, stores it hashed, and emails it.
 * Always returns 200 {success:true} to avoid leaking whether
 * an email is registered.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../Database.php';

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim(strtolower($body['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
}

$pdo = (new Database())->getConnection();

// Check if this email is an authorised admin
$stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
if (!$stmt->fetch()) {
    // Return 200 regardless — don't reveal non-membership
    echo json_encode(['success' => true]);
    exit;
}

// Invalidate any previous unused OTPs for this email
$pdo->prepare("UPDATE admin_otps SET used_at = NOW() WHERE email = ? AND used_at IS NULL")
    ->execute([$email]);

// Generate and store new OTP
$otp     = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$otpHash = password_hash($otp, PASSWORD_BCRYPT);
$expires = date('Y-m-d H:i:s', time() + 600); // 10 minutes

$pdo->prepare("INSERT INTO admin_otps (email, otp_hash, expires_at) VALUES (?, ?, ?)")
    ->execute([$email, $otpHash, $expires]);

// Send OTP email
$subject = 'Munich Kannadigaru Admin — Your OTP';
$message = "Your one-time password is: $otp\n\nThis code expires in 10 minutes.\n\nIf you did not request this, please ignore.";
$headers = 'From: noreply@munichkannadigaru.org';
mail($email, $subject, $message, $headers);

echo json_encode(['success' => true]);
