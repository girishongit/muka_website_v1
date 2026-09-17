<?php
$corsMethod = 'POST';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

// Override Content-Type — multipart uploads don't send JSON
header('Content-Type: application/json; charset=utf-8');

$uploadDir = __DIR__ . '/../public/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$file = $_FILES['file'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    $errMap = [
        UPLOAD_ERR_INI_SIZE   => 'File too large (server limit).',
        UPLOAD_ERR_FORM_SIZE  => 'File too large.',
        UPLOAD_ERR_PARTIAL    => 'Upload incomplete.',
        UPLOAD_ERR_NO_FILE    => 'No file received.',
        UPLOAD_ERR_NO_TMP_DIR => 'No temp directory.',
        UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk.',
        UPLOAD_ERR_EXTENSION  => 'Upload blocked by extension.',
    ];
    $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    http_response_code(422);
    echo json_encode(['error' => $errMap[$code] ?? 'Upload failed.']);
    exit;
}

// Validate MIME type — images only
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
$allowed  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
if (!isset($allowed[$mimeType])) {
    http_response_code(422);
    echo json_encode(['error' => 'Only JPEG, PNG, GIF, and WebP images are allowed.']);
    exit;
}

// Max 8 MB
if ($file['size'] > 8 * 1024 * 1024) {
    http_response_code(422);
    echo json_encode(['error' => 'File must be under 8 MB.']);
    exit;
}

// Build safe filename: timestamp + sanitized original name
$ext      = $allowed[$mimeType];
$origName = pathinfo($file['name'], PATHINFO_FILENAME);
$safeName = preg_replace('/[^a-z0-9_\-]/', '', strtolower($origName));
$safeName = substr($safeName ?: 'image', 0, 40);
$filename = date('Ymd_His') . '_' . $safeName . '.' . $ext;
$destPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save file.']);
    exit;
}

// Derive public URL from the API base URL env var
$apiBase = rtrim(getenv('NUXT_PUBLIC_API_BASE_URL') ?: 'https://api.munichkannadigaru.org/public', '/');
$url     = $apiBase . '/uploads/' . $filename;

echo json_encode(['url' => $url, 'filename' => $filename]);
