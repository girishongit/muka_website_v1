<?php
/**
 * Database connection helper.
 * Configure via environment variables or edit the constants below.
 *
 * Environment variables (preferred):
 *   DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET
 */
class Database
{
    private string $host;
    private string $dbName;
    private string $user;
    private string $pass;
    private string $charset;

    public function __construct()
    {
        $this->host    = getenv('DB_HOST')    ?: '195.35.53.97';
        $this->dbName  = getenv('DB_NAME')    ?: 'u976954618_muka_website';
        $this->user    = getenv('DB_USER')    ?: 'u976954618_root';
        $this->pass    = getenv('DB_PASS')    ?: 'MKRocks2026!';
        $this->charset = getenv('DB_CHARSET') ?: 'utf8mb4';
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $this->user, $this->pass, $options);
    }
}
