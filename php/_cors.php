<?php
/**
 * CORS + JSON headers helper.
 * Include at the top of every API endpoint before any output.
 *
 * Callers may set $corsMethod before including this file to override the
 * allowed HTTP method (default: 'POST'). Example:
 *   $corsMethod = 'GET';
 *   require __DIR__ . '/_cors.php';
 */

header('Content-Type: application/json; charset=utf-8');

$allowedOrigins = [
    'https://munichkannadigaru.org',
    'https://new.munichkannadigaru.org',
    'http://localhost:3000',
];

$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($requestOrigin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $requestOrigin);
    header('Access-Control-Allow-Credentials: true');
    header('Vary: Origin');
}

$corsMethod = $corsMethod ?? 'POST';
header('Access-Control-Allow-Methods: ' . $corsMethod . ', OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== $corsMethod) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
