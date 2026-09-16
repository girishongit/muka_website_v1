<?php
/**
 * Run once to create admin tables and seed the first admin user.
 * Usage: php php/admin/migrate.php
 * Or:    visit https://yourdomain.com/php/admin/migrate.php (delete after running!)
 */
require_once __DIR__ . '/../Database.php';

$pdo = (new Database())->getConnection();

$pdo->exec("
CREATE TABLE IF NOT EXISTS admin_users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(255) NOT NULL UNIQUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS admin_otps (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(255) NOT NULL,
  otp_hash   VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at    DATETIME DEFAULT NULL,
  INDEX idx_admin_otps_email (email)
);
");

// Seed first admin — change this email before running
$seedEmail = 'admin@munichkannadigaru.org';
$stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (email) VALUES (?)");
$stmt->execute([$seedEmail]);

echo "Migration complete. Seeded: $seedEmail\n";
