<?php
/**
 * POST /php/admin/clone-event.php
 * Body: { "slug": "source-slug", "newSlug": "new-slug" }
 *
 * Copies a (published or draft) event to a new draft with a new slug.
 */
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body    = json_decode(file_get_contents('php://input'), true) ?? [];
$slug    = trim($body['slug'] ?? '');
$newSlug = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($body['newSlug'] ?? '')));

if (!$slug || !$newSlug) {
    http_response_code(422);
    echo json_encode(['error' => 'slug and newSlug are required']);
    exit;
}

$file   = __DIR__ . '/../data/events.json';
$events = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];

// Ensure newSlug is not already taken
foreach ($events as $e) {
    if ($e['slug'] === $newSlug) {
        http_response_code(409);
        echo json_encode(['error' => "Slug '$newSlug' is already in use"]);
        exit;
    }
}

// Find source
$source = null;
foreach ($events as $e) {
    if ($e['slug'] === $slug) { $source = $e; break; }
}

if (!$source) {
    http_response_code(404);
    echo json_encode(['error' => 'Source event not found']);
    exit;
}

$clone             = $source;
$clone['slug']     = $newSlug;
$clone['published'] = false;
$clone['past']     = false;
$clone['navLabel'] = ($source['navLabel'] ?? $source['title']) . ' (copy)';

$events[] = $clone;

file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(['success' => true, 'newSlug' => $newSlug]);
