<?php
/**
 * GET /php/admin/get-admin-users.php
 * Returns list of admin user emails.
 */
$corsMethod = 'GET';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../Database.php';

$pdo  = (new Database())->getConnection();
$stmt = $pdo->query("SELECT id, email, created_at FROM admin_users ORDER BY created_at ASC");
echo json_encode($stmt->fetchAll());
