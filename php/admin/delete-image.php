<?php
$corsMethod = 'POST';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body     = json_decode(file_get_contents('php://input'), true) ?? [];
$filename = basename(trim($body['filename'] ?? ''));

if (!$filename) {
    http_response_code(422);
    echo json_encode(['error' => 'filename required']);
    exit;
}

$path = __DIR__ . '/../public/uploads/' . $filename;
if (!file_exists($path)) {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}

unlink($path);
echo json_encode(['success' => true]);
