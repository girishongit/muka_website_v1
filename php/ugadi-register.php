<?php
/**
 * POST /php/ugadi-register.php
 * Accepts Ugadi 2026 event registrations.
 *
 * Expected JSON body:
 *   { "firstName": string, "lastName": string, "email": string, "phone": string,
 *     "adults": int, "children": int, "dietary": string, "message": string,
 *     "turnstileToken": string }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE ugadi_registrations (
 *   id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   first_name  VARCHAR(80)  NOT NULL,
 *   last_name   VARCHAR(80)  NOT NULL,
 *   email       VARCHAR(200) NOT NULL,
 *   phone       VARCHAR(30)  NOT NULL DEFAULT '',
 *   adults      TINYINT UNSIGNED NOT NULL DEFAULT 1,
 *   children    TINYINT UNSIGNED NOT NULL DEFAULT 0,
 *   dietary     VARCHAR(200) NOT NULL DEFAULT '',
 *   message     TEXT,
 *   created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 * ─────────────────────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/_cors.php';
require_once __DIR__ . '/_turnstile.php';
require_once __DIR__ . '/Database.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];

if (!verifyTurnstile($body['turnstileToken'] ?? '')) {
    http_response_code(400);
    echo json_encode(['error' => 'Security check failed. Please refresh and try again.']);
    exit;
}

$firstName = trim($body['firstName'] ?? '');
$lastName  = trim($body['lastName']  ?? '');
$email     = trim($body['email']     ?? '');
$phone     = trim($body['phone']     ?? '');

if (empty($firstName) || empty($lastName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'First name, last name, and a valid email are required.']);
    exit;
}

$adults   = max(1, (int) ($body['adults']   ?? 1));
$children = max(0, (int) ($body['children'] ?? 0));
$dietary  = trim($body['dietary'] ?? '');
$message  = trim($body['message'] ?? '');

try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT INTO ugadi_registrations
           (first_name, last_name, email, phone, adults, children, dietary, message)
         VALUES
           (:first_name, :last_name, :email, :phone, :adults, :children, :dietary, :message)'
    );
    $stmt->execute([
        ':first_name' => $firstName,
        ':last_name'  => $lastName,
        ':email'      => $email,
        ':phone'      => $phone,
        ':adults'     => $adults,
        ':children'   => $children,
        ':dietary'    => $dietary,
        ':message'    => $message,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your registration. Please try again.']);
}
