<?php
/**
 * POST /php/admin/add-admin-user.php
 * Body: { "email": "user@example.com" }
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../Database.php';

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = trim(strtolower($body['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
}

$pdo  = (new Database())->getConnection();
$stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (email) VALUES (?)");
$stmt->execute([$email]);

echo json_encode(['success' => true]);
