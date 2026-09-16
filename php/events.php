<?php
/**
 * GET /php/events.php          → full event list (pinToTop first, then navOrder asc)
 * GET /php/events.php?slug=x   → single event object or 404
 *
 * Read-only endpoint — no Turnstile, no DB.
 * Content lives in php/data/events.json (edit via SSH/FTP).
 */

$corsMethod = 'GET';
require __DIR__ . '/_cors.php';

$file = __DIR__ . '/data/events.json';

if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Event data unavailable']);
    exit;
}

$events = json_decode(file_get_contents($file), true) ?? [];
$events = array_values(array_filter($events, static function ($event) {
    return !array_key_exists('published', $event) || $event['published'] === true;
}));

$slug = trim($_GET['slug'] ?? '');

if ($slug !== '') {
    foreach ($events as $event) {
        if ($event['slug'] === $slug) {
            echo json_encode($event);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['error' => 'Event not found']);
    exit;
}

usort($events, function ($a, $b) {
    $aPin = !empty($a['pinToTop']) ? 0 : 1;
    $bPin = !empty($b['pinToTop']) ? 0 : 1;
    if ($aPin !== $bPin) return $aPin - $bPin;
    return ($a['navOrder'] ?? 99) - ($b['navOrder'] ?? 99);
});

echo json_encode($events);
