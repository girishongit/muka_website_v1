<?php
/**
 * POST /php/admin/save-event.php
 * Body: { "event": { ...event fields... } }
 *
 * Creates or updates a DRAFT event in events.json.
 * Rejects writes to published events.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$event = $body['event'] ?? null;

if (!$event || empty($event['slug']) || empty($event['title']) || empty($event['date'])) {
    http_response_code(422);
    echo json_encode(['error' => 'slug, title, and date are required']);
    exit;
}

$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($event['slug'])));
if (!$slug) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid slug']);
    exit;
}
$event['slug'] = $slug;

$file   = __DIR__ . '/../data/events.json';
$events = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];

// Find existing index
$existingIndex = null;
foreach ($events as $i => $e) {
    if ($e['slug'] === $slug) { $existingIndex = $i; break; }
}

// Core events: always published, always editable
$coreEventSlugs = ['ugadi', 'food-festival', 'utsava'];
$isCoreSlugs = in_array($slug, $coreEventSlugs, true);

// Block edits to published events (except core events)
if ($existingIndex !== null && !$isCoreSlugs) {
    $existing = $events[$existingIndex];
    $isPublished = $existing['published'] ?? true;
    if ($isPublished) {
        http_response_code(403);
        echo json_encode(['error' => 'Cannot edit a published event. Clone it to make changes.']);
        exit;
    }
}

// Core events stay published; others are forced draft
if ($isCoreSlugs) {
    $event['published'] = true;
}

// Sanitise: ensure draft state for non-core events
if (!$isCoreSlugs) {
    $event['published'] = false;
}

// Preserve field order / cast types
$event['navOrder']  = isset($event['navOrder'])  ? (int)$event['navOrder']  : 99;
$event['pinToTop']  = !empty($event['pinToTop']);
$event['featured']  = !empty($event['featured']);
$event['past']      = !empty($event['past']);

// galleryImages: accept newline-separated string or array
if (isset($event['galleryImages']) && is_string($event['galleryImages'])) {
    $event['galleryImages'] = array_values(array_filter(
        array_map('trim', explode("\n", $event['galleryImages']))
    ));
}

if ($existingIndex !== null) {
    $events[$existingIndex] = $event;
} else {
    $events[] = $event;
}

file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(['success' => true]);
