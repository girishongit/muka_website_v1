<?php
/**
 * GET /php/utsava-tickets.php
 * Returns ticket categories split by member / non-member status.
 * Data lives in php/data/utsava-tickets.json.
 */

$corsMethod = 'GET';
require __DIR__ . '/_cors.php';

$file = __DIR__ . '/data/utsava-tickets.json';

if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Ticket data unavailable']);
    exit;
}

$data = json_decode(file_get_contents($file), true);

if (!isset($data['member'], $data['nonMember'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Ticket data malformed']);
    exit;
}

echo json_encode($data);
