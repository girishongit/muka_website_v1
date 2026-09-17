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
require_once __DIR__ . '/email.php';

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
$subject = 'Munich Kannadigaru Admin — Your Login Code';
$body    = <<<HTML
<div style="font-family:'Manrope',Arial,sans-serif;max-width:480px;margin:0 auto;padding:32px 24px;background:#fff;border-radius:12px;border:1px solid #e8e8e8">
  <p style="margin:0 0 8px;font-size:13px;color:#C41E3A;font-weight:600;letter-spacing:0.05em;text-transform:uppercase">Munich Kannadigaru Admin</p>
  <h1 style="margin:0 0 24px;font-size:22px;color:#1a1a1a">Your one-time login code</h1>
  <div style="background:#F7F4F0;border-radius:10px;padding:24px;text-align:center;margin-bottom:24px">
    <span style="font-size:40px;font-weight:700;letter-spacing:12px;color:#C41E3A;font-family:monospace">[OTP]</span>
  </div>
  <p style="margin:0 0 8px;font-size:14px;color:#555">This code expires in <strong>10 minutes</strong>.</p>
  <p style="margin:0;font-size:13px;color:#999">If you did not request this, you can safely ignore this email.</p>
</div>
HTML;

try {
    error_log('[send-otp] attempting email to ' . $email);
    (new Email())->send($email, $subject, $body, ['OTP' => $otp]);
    error_log('[send-otp] email dispatched successfully to ' . $email);
} catch (\Exception $e) {
    error_log('[send-otp] email FAILED for ' . $email . ': ' . $e->getMessage());
}

echo json_encode(['success' => true]);
