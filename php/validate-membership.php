<?php
/**
 * GET /php/validate-membership.php?id=MK-1234
 * Stub: validates a membership ID and returns plan type + eligible ticket categories.
 *
 * Stub routing:
 *   MK-F* → family        → adult_member + child_member
 *   MK-*  → single_adult  → adult_member only
 *   other → invalid
 *
 * Replace stub body with real DB lookup when membership table is ready.
 */

$corsMethod = 'GET';
require __DIR__ . '/_cors.php';

$id = trim($_GET['id'] ?? '');

if ($id === '') {
    http_response_code(400);
    echo json_encode(['error' => 'id parameter required']);
    exit;
}

// ── Stub logic ────────────────────────────────────────────────────────────────
if (preg_match('/^MK-F/i', $id)) {
    echo json_encode([
        'valid'               => true,
        'type'                => 'family',
        'eligibleCategoryIds' => ['adult_member', 'child_member'],
    ]);
} elseif (preg_match('/^MK-/i', $id)) {
    echo json_encode([
        'valid'               => true,
        'type'                => 'single_adult',
        'eligibleCategoryIds' => ['adult_member'],
    ]);
} else {
    echo json_encode(['valid' => false]);
}
