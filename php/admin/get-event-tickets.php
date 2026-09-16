<?php
$corsMethod = 'GET';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$file = __DIR__ . '/../data/event-tickets.json';
$tickets = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
echo json_encode(['tickets' => $tickets]);
