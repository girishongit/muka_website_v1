<?php
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$input  = $body['tickets'] ?? null;

if (!is_array($input)) {
    http_response_code(422);
    echo json_encode(['error' => 'tickets object required']);
    exit;
}

$clean = [];
foreach ($input as $key => $set) {
    $key = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim((string)$key)));
    if (!$key) continue;

    $member    = [];
    $nonMember = [];

    foreach ((array)($set['member'] ?? []) as $row) {
        $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($row['id'] ?? '')));
        $label = trim($row['label'] ?? '');
        if (!$id || !$label) continue;
        $eligibility = array_values(array_filter(array_map('trim', (array)($row['memberPlanEligibility'] ?? [])), fn($v) => $v !== ''));
        $member[] = ['id' => $id, 'label' => $label, 'price' => max(0, (float)($row['price'] ?? 0)), 'currency' => substr(trim($row['currency'] ?? 'EUR'), 0, 10), 'memberPlanEligibility' => $eligibility];
    }

    foreach ((array)($set['nonMember'] ?? []) as $row) {
        $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($row['id'] ?? '')));
        $label = trim($row['label'] ?? '');
        if (!$id || !$label) continue;
        $nonMember[] = ['id' => $id, 'label' => $label, 'price' => max(0, (float)($row['price'] ?? 0)), 'currency' => substr(trim($row['currency'] ?? 'EUR'), 0, 10)];
    }

    $clean[$key] = ['member' => $member, 'nonMember' => $nonMember];
}

$file = __DIR__ . '/../data/event-tickets.json';
file_put_contents($file, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
echo json_encode(['success' => true]);
