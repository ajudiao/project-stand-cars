<?php

namespace App\Repositories;
use App\Core\Database;
use App\Models\Marca;
use PDO;    

class MarcaRepository
{
    private PDO $conn;

    public function __construct()
    {
        // Usa o singleton do Database
        $this->conn = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM marcas ORDER BY id DESC");
        $marcas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $marcas[] = new Marca($row);
        }
        return $marcas;
    }

    public function findById(int $id): ?Marca
    {
        $stmt = $this->conn->prepare("SELECT * FROM marcas WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Marca($data) : null;
    }
}