<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Venda;
use PDO;

class VendaRepository
{
    private PDO $conn;

    public function __construct()
    {
        // Usa o singleton do Database
        $this->conn = Database::getInstance();
    }


    public function findAllWithClient(): array
    {
        $stmt = $this->conn->query("SELECT 
        cl.nome_completo AS cliente, 
        ma.nome AS marca, 
        ve.modelo, 
        us.nome AS vendedor, 
        ven.preco_venda, 
        ven.data_venda, 
        ven.status, 
        ven.observacoes 
        FROM vendas ven 
        JOIN clientes cl ON ven.id_cliente = cl.id 
        JOIN veiculos ve ON ven.id_veiculo = ve.id 
        JOIN usuarios us ON ven.id_vendedor = us.id 
        JOIN marcas ma ON ma.id = ve.id_marca
        ORDER BY ven.data_venda DESC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔎 Buscar por ID
    public function findById(int $id): ?Venda
    {
        $stmt = $this->conn->prepare("SELECT * FROM venda WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Venda($data) : null;
    }

    // Criar nova venda
    public function create(Venda $venda): int|false
    {
        $stmt = $this->conn->prepare("
        INSERT INTO vendas 
        (id_cliente, id_veiculo, id_vendedor, preco_venda, data_venda, status, observacoes)
        VALUES 
        (:id_cliente, :id_veiculo, :id_vendedor, :preco_venda, :data_venda, :status, :observacoes)
        ");

        $success = $stmt->execute([
            ':id_cliente'   => $venda->id_cliente,
            ':id_veiculo'   => $venda->id_veiculo,
            ':id_vendedor'  => (int)$_SESSION['user_id'],
            ':preco_venda'  => $venda->preco_venda,
            ':data_venda'   => $venda->data_venda,
            ':status' => $venda->status,
            ':observacoes'  => $venda->observacoes
        ]);

        if ($success) {
            return (int) $this->conn->lastInsertId();
        }

        return false;
    }

    // ✏️ Atualizar venda
    public function update(Venda $venda): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE venda SET
                id_cliente = :id_cliente,
                id_veiculo = :id_veiculo,
                id_vendedor = :id_vendedor,
                preco_venda = :preco_venda,
                data_venda = :data_venda,
                observacoes = :observacoes
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'           => $venda->id,
            ':id_cliente'   => $venda->id_cliente,
            ':id_veiculo'   => $venda->id_veiculo,
            ':id_vendedor'  => $venda->id_vendedor,
            ':preco_venda'  => $venda->preco_venda,
            ':data_venda'   => $venda->data_venda,
            ':observacoes'  => $venda->observacoes
        ]);
    }

    public function getTheNumbersSeles(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM vendas");
        return (int) $stmt->fetchColumn();
    }

    // Deletar venda
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM venda WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function carroJaVendido(int $carroId): bool
    {
        $sql = "SELECT COUNT(*) FROM vendas WHERE id_veiculo = :carro_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':carro_id', $carroId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}
