<?php
/**
 * Class Database: Wrapper Factory Koneksi MySQLi Object
 * Modul Praktikum Minggu 7
 */

class Database
{
    private static ?mysqli $instance = null;

    public static function getConnection(): mysqli
    {
        if (self::$instance === null) {
            // Aktifkan strict mode exception
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $host = '127.0.0.1';
            $user = 'root';
            $pass = '';
            $dbName = 'pemrograman_lanjut';

            try {
                self::$instance = new mysqli($host, $user, $pass, $dbName);
                self::$instance->set_charset('utf8mb4');
            } catch (mysqli_sql_exception $e) {
                error_log("Database connection failed: " . $e->getMessage());
                die("Maaf, koneksi basis data sedang terganggu.");
            }
        }

        return self::$instance;
    }
}
