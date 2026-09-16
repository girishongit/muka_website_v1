<?php
/**
 * GET /php/admin/get-events.php
 * Returns all events from events.json.
 * Backfills published:true for legacy entries without the field.
 */
$corsMethod = 'GET';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$file = __DIR__ . '/../data/events.json';
if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}

$events = json_decode(file_get_contents($file), true) ?? [];

// Backfill: treat existing events (no published key) as published
foreach ($events as &$e) {
    if (!array_key_exists('published', $e)) {
        $e['published'] = true;
    }
}
unset($e);

echo json_encode($events);
