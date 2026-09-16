<?php
/**
 * GET /php/admin/get-ticket-categories.php
 * Returns all ticket categories from ticket-categories.json.
 */
$corsMethod = 'GET';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$file = __DIR__ . '/../data/ticket-categories.json';
$categories = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
echo json_encode(['categories' => $categories]);
