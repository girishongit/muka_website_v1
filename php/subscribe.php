<?php
/**
 * POST /php/subscribe.php
 * Accepts newsletter subscription.
 *
 * Expected JSON body:
 *   { "name": string, "email": string, "turnstileToken": string }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE subscribers (
 *   id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   name           VARCHAR(120) NOT NULL,
 *   email          VARCHAR(200) NOT NULL UNIQUE,
 *   subscribed_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
 *   ip             VARCHAR(45)  NOT NULL DEFAULT ''
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 * ─────────────────────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/_cors.php';
require_once __DIR__ . '/_turnstile.php';
require_once __DIR__ . '/Database.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// 1. Turnstile
if (!verifyTurnstile($body['turnstileToken'] ?? '')) {
    http_response_code(400);
    echo json_encode(['error' => 'Security check failed. Please refresh and try again.']);
    exit;
}

// 2. Validate
$name  = trim($body['name']  ?? '');
$email = trim($body['email'] ?? '');

if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'Name and a valid email are required.']);
    exit;
}

// 3. Insert
try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT IGNORE INTO subscribers (name, email, ip) VALUES (:name, :email, :ip)'
    );
    $stmt->execute([
        ':name'  => $name,
        ':email' => $email,
        ':ip'    => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your subscription. Please try again.']);
}
