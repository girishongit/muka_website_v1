<?php
/**
 * GET /php/utsava-tickets.php
 * Returns { member: [...], nonMember: [...], memberEligibility: {...} }
 * built from ticket-categories.json filtered by the utsava event's ticketCategoryIds.
 * Falls back to utsava-tickets.json if no ticketCategoryIds are configured.
 */
require_once __DIR__ . '/_cors.php';

// Load utsava event to get configured category IDs
$eventsFile  = __DIR__ . '/data/events.json';
$events      = file_exists($eventsFile) ? (json_decode(file_get_contents($eventsFile), true) ?? []) : [];
$utsavaEvent = null;
foreach ($events as $e) {
    if (($e['slug'] ?? '') === 'utsava') { $utsavaEvent = $e; break; }
}

$configuredIds = $utsavaEvent['ticketCategoryIds'] ?? [];

// Fallback: no IDs configured — serve legacy utsava-tickets.json
if (empty($configuredIds)) {
    $legacyFile = __DIR__ . '/data/utsava-tickets.json';
    if (file_exists($legacyFile)) {
        header('Content-Type: application/json');
        echo file_get_contents($legacyFile);
    } else {
        echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
    }
    exit;
}

// Load all categories and filter to configured IDs
$catFile  = __DIR__ . '/data/ticket-categories.json';
$allCats  = file_exists($catFile) ? (json_decode(file_get_contents($catFile), true) ?? []) : [];
$idSet    = array_flip($configuredIds);
$filtered = array_filter($allCats, fn($c) => isset($idSet[$c['id']]));

$member            = [];
$nonMember         = [];
$memberEligibility = [];

foreach ($filtered as $cat) {
    $entry = [
        'id'       => $cat['id'],
        'label'    => $cat['label'],
        'price'    => $cat['price'],
        'currency' => $cat['currency'],
    ];
    if ($cat['type'] === 'member') {
        $member[] = $entry;
        foreach ($cat['memberPlanEligibility'] ?? [] as $plan) {
            $memberEligibility[$plan][] = $cat['id'];
        }
    } else {
        $nonMember[] = $entry;
    }
}

echo json_encode([
    'member'            => $member,
    'nonMember'         => $nonMember,
    'memberEligibility' => $memberEligibility ?: new stdClass(),
]);
