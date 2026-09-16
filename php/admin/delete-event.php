<?php
/**
 * POST /php/admin/delete-event.php
 * Body: { "slug": "event-slug" }
 *
 * Deletes a DRAFT event. Published events cannot be deleted.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$slug = trim($body['slug'] ?? '');

if (!$slug) {
    http_response_code(422);
    echo json_encode(['error' => 'slug is required']);
    exit;
}

$file   = __DIR__ . '/../data/events.json';
$events = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];

$coreEventSlugs = ['ugadi', 'food-festival', 'utsava'];

$found = false;
$filtered = [];
foreach ($events as $e) {
    if ($e['slug'] === $slug) {
        if (in_array($slug, $coreEventSlugs, true)) {
            http_response_code(403);
            echo json_encode(['error' => 'Default events cannot be deleted']);
            exit;
        }
        $found = true;
        continue; // skip (delete)
    }
    $filtered[] = $e;
}

if (!$found) {
    http_response_code(404);
    echo json_encode(['error' => 'Event not found']);
    exit;
}

file_put_contents($file, json_encode(array_values($filtered), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(['success' => true]);
