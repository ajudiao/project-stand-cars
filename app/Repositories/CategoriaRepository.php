<?php

namespace App\Repositories;
use App\Core\Database;
use App\Models\Categoria;
use PDO;    

class CategoriaRepository
{
    private PDO $conn;

    public function __construct()
    {
        // Usa o singleton do Database
        $this->conn = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM categorias ORDER BY id DESC");
        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorias[] = new Categoria($row);
        }
        return $categorias;
    }

    public function findById(int $id): ?Categoria
    {
        $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Categoria($data) : null;
    }
}