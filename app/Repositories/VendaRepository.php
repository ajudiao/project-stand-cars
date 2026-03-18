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
        $stmt = $this->conn->query("
        SELECT v.*, c.nome_completo AS cliente_nome, ve.id_marca AS veiculo_marca, u.nome AS vendedor_nome
        FROM vendas v
        JOIN clientes c ON v.id_cliente = c.id
        JOIN veiculos ve ON v.id_veiculo = ve.id
        JOIN usuarios u ON v.id_vendedor = u.id
        ORDER BY v.data_venda DESC
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

    // ➕ Criar nova venda
    public function create(Venda $venda): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO venda 
            (id_cliente, id_veiculo, id_vendedor, preco_venda, data_venda, observacoes)
            VALUES 
            (:id_cliente, :id_veiculo, :id_vendedor, :preco_venda, :data_venda, :observacoes)
        ");

        return $stmt->execute([
            ':id_cliente'   => $venda->id_cliente,
            ':id_veiculo'   => $venda->id_veiculo,
            ':id_vendedor'  => $venda->id_vendedor,
            ':preco_venda'  => $venda->preco_venda,
            ':data_venda'   => $venda->data_venda,
            ':observacoes'  => $venda->observacoes
        ]);
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

    // ❌ Deletar venda
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM venda WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
