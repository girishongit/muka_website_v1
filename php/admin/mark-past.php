<?php
/**
 * POST /php/admin/mark-past.php
 * Body: { "slug": "event-slug" }
 *
 * Sets past:true on a published event so it shows a "concluded" banner.
 * Works only on published events (drafts can set past via save-event.php).
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
        if (!($e['published'] ?? false)) {
            http_response_code(400);
            echo json_encode(['error' => 'Event is not published yet. Edit the draft instead.']);
            exit;
        }
        if ($e['registrationStatus'] === 'closed') {
            http_response_code(409);
            echo json_encode(['error' => 'Registration is already closed for this event']);
            exit;
        }
        $e['registrationStatus'] = 'closed';
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
