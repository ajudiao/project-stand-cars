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
        ven.id,
        cl.nome_completo AS cliente, 
        ma.nome AS marca, 
        ve.modelo, 
        us.nome AS vendedor, 
        ven.valor_pago, 
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

    public function findVendaById(int $id): ?array
    {
        $sql = "SELECT 
                ven.id,
                cl.nome_completo AS cliente, 
                cl.email,
                cl.telefone,
                cl.identidade,
                ma.nome AS marca, 
                ve.modelo, 
                ve.cor,
                ve.quilometragem,
                ve.transmissao,
                ve.preco AS valor_veiculo,
                us.nome AS vendedor, 
                ven.valor_pago, 
                ven.desconto,
                ven.metodo_pagamento,
                ven.data_venda, 
                ven.status, 
                ven.observacoes 
            FROM vendas ven 
            JOIN clientes cl ON ven.id_cliente = cl.id 
            JOIN veiculos ve ON ven.id_veiculo = ve.id 
            JOIN usuarios us ON ven.id_vendedor = us.id 
            JOIN marcas ma ON ma.id = ve.id_marca
            WHERE ven.id = :id
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    // Criar nova venda
    public function create(Venda $venda): int|false
    {
        $stmt = $this->conn->prepare("
        INSERT INTO vendas 
        (id_cliente, id_veiculo, id_vendedor, valor_pago, desconto, metodo_pagamento, data_venda, status, observacoes)
        VALUES 
        (:id_cliente, :id_veiculo, :id_vendedor, :valor_pago, :desconto, :metodo_pagamento, :data_venda, :status, :observacoes)
        ");

        $success = $stmt->execute([
            ':id_cliente'   => $venda->id_cliente,
            ':id_veiculo'   => $venda->id_veiculo,
            ':id_vendedor'  => (int)$_SESSION['user_id'],
            ':valor_pago'  => $venda->valorPago,
            ':desconto'     => $venda->desconto,
            ':metodo_pagamento' => $venda->metodo_pagamento,
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
                valor_pago = :valor_pago,
                desconto = :desconto,
                metodo_pagamento = :metodo_pagamento,
                data_venda = :data_venda,
                observacoes = :observacoes
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'           => $venda->id,
            ':id_cliente'   => $venda->id_cliente,
            ':id_veiculo'   => $venda->id_veiculo,
            ':id_vendedor'  => $venda->id_vendedor,
            ':valor_pago'  => $venda->valorPago,
            ':desconto'     => $venda->desconto,
            ':metodo_pagamento' => $venda->metodo_pagamento,
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

    public function search(array $filters): array
    {
        $sql = "SELECT 
                hc.id AS historico_id,
                c.nome_completo AS cliente,
                
                v.modelo AS carro,
                v.ano,
                m.nome AS marca,
                
                hc.data_compra,
                hc.preco_compra,
                hc.metodo_pagamento,
                hc.status
            FROM historico_compras hc
            JOIN clientes c ON hc.cliente_id = c.id
            JOIN veiculos v ON hc.carro_id = v.id
            JOIN marcas m ON v.id_marca = m.id
            WHERE 1=1";

        $params = [];

        // Busca por cliente ou carro
        if (!empty($filters['nome'])) {
            $sql .= " AND (
            c.nome_completo LIKE :cliente 
            OR v.modelo LIKE :veiculo
        )";
            $params[':cliente'] = '%' . $filters['nome'] . '%';
            $params[':veiculo'] = '%' . $filters['nome'] . '%';
        }

        // Filtro por status
        if (!empty($filters['status'])) {
            $sql .= " AND hc.status = :status";
            $params[':status'] = $filters['status'];
        }

        $sql .= " ORDER BY hc.data_compra DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
