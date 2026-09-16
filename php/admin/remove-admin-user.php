<?php
/**
 * POST /php/admin/remove-admin-user.php
 * Body: { "email": "user@example.com" }
 *
 * Prevents removing yourself.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../Database.php';

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim(strtolower($body['email'] ?? ''));

if (!$email) {
    http_response_code(422);
    echo json_encode(['error' => 'email is required']);
    exit;
}

if ($email === $adminEmail) {
    http_response_code(403);
    echo json_encode(['error' => 'You cannot remove your own account']);
    exit;
}

$pdo  = (new Database())->getConnection();
$stmt = $pdo->prepare("DELETE FROM admin_users WHERE email = ?");
$stmt->execute([$email]);

echo json_encode(['success' => true]);
