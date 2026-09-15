<?php
/**
 * POST /php/utsava-register.php
 * Accepts Utsava ticket registrations.
 *
 * Expected JSON body:
 *   {
 *     "fullName": string,
 *     "email": string, "phone": string,
 *     "isMember": bool,
 *     "membershipId": string,
 *     "membershipType": string|null,
 *     "tickets": [{ "categoryId": string, "label": string, "quantity": int, "priceEach": float }],
 *     "totalAmount": float,
 *     "currency": string,
 *     "turnstileToken": string
 *   }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE utsava_ticket_registrations (
 *   id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   full_name       VARCHAR(160) NOT NULL,
 *   email           VARCHAR(200) NOT NULL,
 *   phone           VARCHAR(30)  NOT NULL DEFAULT '',
 *   is_member       TINYINT(1)   NOT NULL DEFAULT 0,
 *   membership_id   VARCHAR(50)  NOT NULL DEFAULT '',
 *   membership_type VARCHAR(50)  NOT NULL DEFAULT '',
 *   tickets_json    JSON         NOT NULL,
 *   total_amount    DECIMAL(8,2) NOT NULL DEFAULT 0,
 *   currency        VARCHAR(10)  NOT NULL DEFAULT 'EUR',
 *   created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
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

// 2. Required personal fields
$fullName = trim($body['fullName'] ?? '');
$email    = trim($body['email']   ?? '');
$phone    = trim($body['phone']   ?? '');

if (empty($fullName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'Full name and a valid email are required.']);
    exit;
}

// 3. Membership
$isMember       = !empty($body['isMember']);
$membershipId   = trim($body['membershipId']   ?? '');
$membershipType = substr(trim($body['membershipType'] ?? ''), 0, 50);

if ($isMember && empty($membershipId)) {
    http_response_code(422);
    echo json_encode(['error' => 'Membership ID is required for members.']);
    exit;
}

// 4. Tickets: at least one with quantity >= 1
$rawTickets = $body['tickets'] ?? [];

if (!is_array($rawTickets) || count($rawTickets) === 0) {
    http_response_code(422);
    echo json_encode(['error' => 'At least one ticket must be selected.']);
    exit;
}

$tickets = [];
$serverTotal = 0.0;

foreach ($rawTickets as $t) {
    $qty   = max(0, (int)   ($t['quantity']   ?? 0));
    $price = max(0, (float) ($t['priceEach']  ?? 0));
    if ($qty < 1) continue;
    $tickets[] = [
        'categoryId' => substr(trim($t['categoryId'] ?? ''), 0, 60),
        'label'      => substr(trim($t['label']      ?? ''), 0, 120),
        'quantity'   => $qty,
        'priceEach'  => $price,
    ];
    $serverTotal += $qty * $price;
}

if (count($tickets) === 0) {
    http_response_code(422);
    echo json_encode(['error' => 'At least one ticket with quantity ≥ 1 must be selected.']);
    exit;
}

// 5. Validate total (allow ±0.01 for float rounding)
$clientTotal = (float) ($body['totalAmount'] ?? -1);
if (abs($serverTotal - $clientTotal) > 0.01) {
    http_response_code(422);
    echo json_encode(['error' => 'Ticket total mismatch. Please refresh and try again.']);
    exit;
}

$currency = substr(trim($body['currency'] ?? 'EUR'), 0, 10);

// 6. Insert
try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT INTO utsava_ticket_registrations
           (full_name, email, phone, is_member, membership_id, membership_type, tickets_json, total_amount, currency)
         VALUES
           (:full_name, :email, :phone, :is_member, :membership_id, :membership_type, :tickets_json, :total_amount, :currency)'
    );
    $stmt->execute([
        ':full_name'       => $fullName,
        ':email'           => $email,
        ':phone'           => $phone,
        ':is_member'       => $isMember ? 1 : 0,
        ':membership_id'   => $membershipId,
        ':membership_type' => $membershipType,
        ':tickets_json'    => json_encode($tickets),
        ':total_amount'    => round($serverTotal, 2),
        ':currency'        => $currency,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your registration. Please try again.']);
}
