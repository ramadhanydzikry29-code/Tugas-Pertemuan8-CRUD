<?php
/**
 * Koneksi PDO dengan Singleton pattern.
 * Hanya ada 1 koneksi per request; konstruktor private.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $conn;

    private function __construct()
    {
        // Bisa di-override lewat environment variable (tanpa hardcode di source)
        $host = getenv('DB_HOST') ?: 'localhost';
        $name = getenv('DB_NAME') ?: 'inventaris_db';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // error -> exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // hasil array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                  // prepared statement asli
        ];

        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8mb4",
                $user,
                $pass,
                $options
            );
        } catch (PDOException $e) {
            // Jangan bocorkan detail error ke user; catat di log server
            error_log('DB connection error: ' . $e->getMessage());
            http_response_code(500);
            die('Koneksi database gagal. Pastikan MySQL berjalan dan schema.sql sudah diimport.');
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    // Cegah duplikasi instance
    private function __clone() {}
    public function __wakeup()
    {
        throw new Exception('Tidak boleh unserialize singleton.');
    }
}
