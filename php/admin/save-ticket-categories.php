<?php
/**
 * POST /php/admin/save-ticket-categories.php
 * Body: { "categories": TicketCategory[] }
 *
 * Saves ticket categories to ticket-categories.json.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$categories = $body['categories'] ?? null;

if (!is_array($categories)) {
    http_response_code(422);
    echo json_encode(['error' => 'categories array required']);
    exit;
}

// Validate and sanitise each category
$clean = [];
foreach ($categories as $cat) {
    $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($cat['id'] ?? '')));
    $label = trim($cat['label'] ?? '');
    $price = max(0, (float)($cat['price'] ?? 0));
    $currency = substr(trim($cat['currency'] ?? 'EUR'), 0, 10);
    $type  = in_array($cat['type'] ?? '', ['member', 'nonMember'], true) ? $cat['type'] : 'nonMember';
    $eligibility = array_values(array_filter(array_map('trim', (array)($cat['memberPlanEligibility'] ?? [])), fn($v) => $v !== ''));

    if (!$id || !$label) continue;

    $clean[] = [
        'id'                   => $id,
        'label'                => $label,
        'price'                => $price,
        'currency'             => $currency,
        'type'                 => $type,
        'memberPlanEligibility'=> $eligibility,
    ];
}

// Reject duplicate IDs
$ids = array_column($clean, 'id');
if (count($ids) !== count(array_unique($ids))) {
    http_response_code(422);
    echo json_encode(['error' => 'Duplicate category IDs are not allowed.']);
    exit;
}

$file = __DIR__ . '/../data/ticket-categories.json';
file_put_contents($file, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
echo json_encode(['success' => true]);
