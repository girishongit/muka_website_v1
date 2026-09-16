<?php
/**
 * POST /php/membership-register.php
 * Accepts membership registration applications.
 *
 * Expected JSON body:
 *   {
 *     "planType": "student"|"single"|"family",
 *     "firstName": string, "lastName": string,
 *     "phone": string, "whatsapp": string,
 *     "email": string, "dob": string (YYYY-MM-DD),
 *     "citizenship": string, "address": string,
 *     "postcode": string, "city": string,
 *     "country": string,           // always "Germany"
 *     "agreed": bool,
 *     // Student plan only:
 *     "studentId": string,
 *     // Family plan only:
 *     "spouseName": string, "spouseDob": string,
 *     "child1Name": string, "child1Dob": string,
 *     "child2Name": string, "child2Dob": string,
 *     "turnstileToken": string
 *   }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE membership_registrations (
 *   id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   plan_type    ENUM('student','single','family') NOT NULL,
 *   first_name   VARCHAR(80)  NOT NULL,
 *   last_name    VARCHAR(80)  NOT NULL,
 *   phone        VARCHAR(30)  NOT NULL DEFAULT '',
 *   whatsapp     VARCHAR(30)  NOT NULL DEFAULT '',
 *   email        VARCHAR(200) NOT NULL,
 *   dob          DATE,
 *   citizenship  VARCHAR(80)  NOT NULL DEFAULT '',
 *   address      VARCHAR(255) NOT NULL DEFAULT '',
 *   postcode     VARCHAR(20)  NOT NULL DEFAULT '',
 *   city         VARCHAR(80)  NOT NULL DEFAULT '',
 *   country      VARCHAR(80)  NOT NULL DEFAULT 'Germany',
 *   student_id   VARCHAR(80)  NOT NULL DEFAULT '',
 *   spouse_name  VARCHAR(120) NOT NULL DEFAULT '',
 *   spouse_dob   DATE,
 *   child1_name  VARCHAR(80)  NOT NULL DEFAULT '',
 *   child1_dob   DATE,
 *   child2_name  VARCHAR(80)  NOT NULL DEFAULT '',
 *   child2_dob   DATE,
 *   agreed       TINYINT(1)   NOT NULL DEFAULT 0,
 *   created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
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

// 2. Validate core fields
$allowedPlans = ['student', 'single', 'family'];
$planType     = $body['planType'] ?? '';
$firstName    = trim($body['firstName'] ?? '');
$lastName     = trim($body['lastName']  ?? '');
$email        = trim($body['email']     ?? '');

if (!in_array($planType, $allowedPlans, true)) {
    http_response_code(422);
    echo json_encode(['error' => 'Please select a valid membership plan.']);
    exit;
}

if (empty($firstName) || empty($lastName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'First name, last name, and a valid email are required.']);
    exit;
}

if (empty($body['agreed'])) {
    http_response_code(422);
    echo json_encode(['error' => 'You must agree to the terms to complete registration.']);
    exit;
}

// Helper: normalise a nullable date string or return null
function safeDate(?string $value): ?string {
    if (empty($value)) return null;
    $d = DateTime::createFromFormat('Y-m-d', $value);
    return ($d && $d->format('Y-m-d') === $value) ? $value : null;
}

$phone      = trim($body['phone']       ?? '');
$whatsapp   = trim($body['whatsapp']    ?? '');
$dob        = safeDate($body['dob']     ?? '');
$citizenship = trim($body['citizenship'] ?? '');
$address    = trim($body['address']     ?? '');
$postcode   = trim($body['postcode']    ?? '');
$city       = trim($body['city']        ?? '');
$country    = 'Germany'; // always locked

// Plan-conditional fields
$studentId  = ($planType === 'student') ? trim($body['studentId']  ?? '') : '';
$spouseName = ($planType === 'family')  ? trim($body['spouseName'] ?? '') : '';
$spouseDob  = ($planType === 'family')  ? safeDate($body['spouseDob'] ?? '') : null;
$child1Name = ($planType === 'family')  ? trim($body['child1Name'] ?? '') : '';
$child1Dob  = ($planType === 'family')  ? safeDate($body['child1Dob'] ?? '') : null;
$child2Name = ($planType === 'family')  ? trim($body['child2Name'] ?? '') : '';
$child2Dob  = ($planType === 'family')  ? safeDate($body['child2Dob'] ?? '') : null;

// 3. Insert
try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT INTO membership_registrations
           (plan_type, first_name, last_name, phone, whatsapp, email, dob,
            citizenship, address, postcode, city, country,
            student_id, spouse_name, spouse_dob,
            child1_name, child1_dob, child2_name, child2_dob, agreed)
         VALUES
           (:plan_type, :first_name, :last_name, :phone, :whatsapp, :email, :dob,
            :citizenship, :address, :postcode, :city, :country,
            :student_id, :spouse_name, :spouse_dob,
            :child1_name, :child1_dob, :child2_name, :child2_dob, :agreed)'
    );
    $stmt->execute([
        ':plan_type'   => $planType,
        ':first_name'  => $firstName,
        ':last_name'   => $lastName,
        ':phone'       => $phone,
        ':whatsapp'    => $whatsapp,
        ':email'       => $email,
        ':dob'         => $dob,
        ':citizenship' => $citizenship,
        ':address'     => $address,
        ':postcode'    => $postcode,
        ':city'        => $city,
        ':country'     => $country,
        ':student_id'  => $studentId,
        ':spouse_name' => $spouseName,
        ':spouse_dob'  => $spouseDob,
        ':child1_name' => $child1Name,
        ':child1_dob'  => $child1Dob,
        ':child2_name' => $child2Name,
        ':child2_dob'  => $child2Dob,
        ':agreed'      => 1,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your registration. Please try again.']);
}
