<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SiteSettingRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Retorna apenas os campos essenciais para o layout (leve).
     * Usado pelo View::injectDynamicGlobals() em cada requisição.
     */
    public function getEssentialSettings(): array
    {
        try {
            $stmt = $this->conn->query(
                'SELECT nome_empresa, logo, telefone, email, endereco FROM site_settings LIMIT 1'
            );
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            return $row ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Retorna todos os campos (usado nas páginas de configuração).
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->conn->query('SELECT * FROM site_settings LIMIT 1');
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : [];
            return $row ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function get(string $key): ?string
    {
        try {
            $stmt = $this->conn->prepare('SELECT `' . $key . '` FROM site_settings LIMIT 1');
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result[$key] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function update(array $data): bool
    {
        try {
            $sets   = [];
            $params = [];
            foreach ($data as $key => $value) {
                $sets[]       = '`' . $key . '` = :' . $key;
                $params[$key] = $value;
            }
            $params['updated_at'] = date('Y-m-d H:i:s');
            $sql  = 'UPDATE site_settings SET ' . implode(', ', $sets) . ', updated_at = :updated_at WHERE id = 1';
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function createDefault(): bool
    {
        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO site_settings (id) VALUES (1) ON DUPLICATE KEY UPDATE id = 1'
            );
            return $stmt->execute();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
