<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require BASE_PATH . '/config/database.php';

            // Garante que a base de dados existe antes de conectar
            Schema::ensureDatabaseExists($config);

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            if (!empty($config['port'])) {
                $dsn .= ";port={$config['port']}";
            }

            try {
                self::$instance = new PDO($dsn, $config['user'], $config['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_PERSISTENT         => false,
                ]);
            } catch (PDOException $e) {
                // Loga mas não expõe detalhes ao utilizador
                error_log('[stand-cars] DB connection error: ' . $e->getMessage());
                throw new \RuntimeException('Não foi possível conectar à base de dados.');
            }

            // Cria tabelas se não existirem
            Schema::ensureTables(self::$instance);
        }

        return self::$instance;
    }

    /**
     * Reseta a instância (útil para testes).
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
