<?php
$corsMethod = 'GET';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$uploadDir = __DIR__ . '/../public/uploads/';
if (!is_dir($uploadDir)) {
    echo json_encode(['images' => []]);
    exit;
}

$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$files   = [];
foreach (new DirectoryIterator($uploadDir) as $f) {
    if ($f->isDot() || $f->isDir()) continue;
    $ext = strtolower($f->getExtension());
    if (!in_array($ext, $allowed, true)) continue;
    $files[] = ['filename' => $f->getFilename(), 'mtime' => $f->getMTime(), 'size' => $f->getSize()];
}

usort($files, fn($a, $b) => $b['mtime'] - $a['mtime']);

$apiBase = rtrim(getenv('NUXT_PUBLIC_API_BASE_URL') ?: 'https://api.munichkannadigaru.org/public', '/');
$images  = array_map(fn($f) => [
    'filename' => $f['filename'],
    'url'      => $apiBase . '/uploads/' . $f['filename'],
    'size'     => $f['size'],
    'mtime'    => $f['mtime'],
], $files);

echo json_encode(['images' => $images]);
