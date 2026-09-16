<?php
/**
 * POST /php/kannada-kali-enrol.php
 * Accepts Kannada Kali class enrolment requests.
 *
 * Expected JSON body:
 *   {
 *     "fatherName": string, "motherName": string,
 *     "email": string, "phone": string,
 *     "kidName": string, "kidAge": int|string,
 *     "aboutFamily": string,
 *     "preferredDays": string[],   // e.g. ["Monday","Wednesday"]
 *     "disclaimerAccepted": bool,
 *     "turnstileToken": string
 *   }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE kannada_kali_enrolments (
 *   id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   father_name         VARCHAR(120) NOT NULL DEFAULT '',
 *   mother_name         VARCHAR(120) NOT NULL DEFAULT '',
 *   email               VARCHAR(200) NOT NULL,
 *   phone               VARCHAR(30)  NOT NULL DEFAULT '',
 *   kid_name            VARCHAR(120) NOT NULL,
 *   kid_age             TINYINT UNSIGNED NOT NULL DEFAULT 0,
 *   about_family        TEXT,
 *   preferred_days      JSON         NOT NULL,   -- e.g. ["Monday","Wednesday"]
 *   disclaimer_accepted TINYINT(1)   NOT NULL DEFAULT 0,
 *   created_at          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
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
$email   = trim($body['email']   ?? '');
$kidName = trim($body['kidName'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($kidName)) {
    http_response_code(422);
    echo json_encode(['error' => 'A valid email and child\'s name are required.']);
    exit;
}

$preferredDays = $body['preferredDays'] ?? [];
if (empty($preferredDays) || !is_array($preferredDays)) {
    http_response_code(422);
    echo json_encode(['error' => 'Please select at least one preferred day.']);
    exit;
}

if (empty($body['disclaimerAccepted'])) {
    http_response_code(422);
    echo json_encode(['error' => 'You must accept the disclaimer to proceed.']);
    exit;
}

$fatherName  = trim($body['fatherName']  ?? '');
$motherName  = trim($body['motherName']  ?? '');
$phone       = trim($body['phone']       ?? '');
$kidAge      = max(0, (int) ($body['kidAge'] ?? 0));
$aboutFamily = trim($body['aboutFamily'] ?? '');

// Sanitise days to allowed values only
$allowedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$safeDays    = array_values(array_intersect($allowedDays, $preferredDays));

// 3. Insert
try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT INTO kannada_kali_enrolments
           (father_name, mother_name, email, phone, kid_name, kid_age,
            about_family, preferred_days, disclaimer_accepted)
         VALUES
           (:father_name, :mother_name, :email, :phone, :kid_name, :kid_age,
            :about_family, :preferred_days, :disclaimer_accepted)'
    );
    $stmt->execute([
        ':father_name'         => $fatherName,
        ':mother_name'         => $motherName,
        ':email'               => $email,
        ':phone'               => $phone,
        ':kid_name'            => $kidName,
        ':kid_age'             => $kidAge,
        ':about_family'        => $aboutFamily,
        ':preferred_days'      => json_encode($safeDays),
        ':disclaimer_accepted' => 1,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your enrolment. Please try again.']);
}
