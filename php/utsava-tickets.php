<?php
$corsMethod = 'GET';
require_once __DIR__ . '/_cors.php';

// Find utsava event's ticketKey
$eventsFile  = __DIR__ . '/data/events.json';
$events      = file_exists($eventsFile) ? (json_decode(file_get_contents($eventsFile), true) ?? []) : [];
$ticketKey   = '';
foreach ($events as $e) {
    if (($e['slug'] ?? '') === 'utsava') { $ticketKey = $e['ticketKey'] ?? ''; break; }
}

if (!$ticketKey) {
    echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
    exit;
}

// Look up the ticket set by key
$etFile  = __DIR__ . '/data/event-tickets.json';
$allSets = file_exists($etFile) ? (json_decode(file_get_contents($etFile), true) ?? []) : [];
$set     = $allSets[$ticketKey] ?? null;

if (!$set) {
    echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
    exit;
}

// Build response — strip memberPlanEligibility from output rows, build eligibility map
$member            = [];
$memberEligibility = [];
foreach ($set['member'] ?? [] as $row) {
    $member[] = ['id' => $row['id'], 'label' => $row['label'], 'price' => $row['price'], 'currency' => $row['currency']];
    foreach ($row['memberPlanEligibility'] ?? [] as $plan) {
        $memberEligibility[$plan][] = $row['id'];
    }
}

$nonMember = [];
foreach ($set['nonMember'] ?? [] as $row) {
    $nonMember[] = ['id' => $row['id'], 'label' => $row['label'], 'price' => $row['price'], 'currency' => $row['currency']];
}

echo json_encode([
    'member'            => $member,
    'nonMember'         => $nonMember,
    'memberEligibility' => $memberEligibility ?: new stdClass(),
]);
