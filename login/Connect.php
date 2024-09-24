<?php

class Connect {
    private const HOST = 'localhost';
    private const DB_NAME = 'formulario';
    private const USER = 'root';
    private const PASS = '';
    private static $instance;
    private static $fail;

    private function __construct() {}

    public static function getConnection(): ?PDO {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(dsn: "mysql:host=".self::HOST.
                ";dbname=".self::DB_NAME,
                username: self::USER,
                password: self::PASS);
            } catch (\PDOException $exception) {
                self::$fail = $exception;
            }
        }
        return self::$instance;
    }
}