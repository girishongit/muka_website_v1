<?php
/**
 * POST /php/admin/publish-event.php
 * Body: { "slug": "event-slug" }
 *
 * Sets published:true on a draft event. Irreversible.
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

$found = false;
foreach ($events as &$e) {
    if ($e['slug'] === $slug) {
        if ($e['published'] ?? false) {
            http_response_code(409);
            echo json_encode(['error' => 'Event is already published']);
            exit;
        }
        $e['published'] = true;
        $found = true;
        break;
    }
}
unset($e);

if (!$found) {
    http_response_code(404);
    echo json_encode(['error' => 'Event not found']);
    exit;
}

file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(['success' => true]);
