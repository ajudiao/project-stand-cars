<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Cliente;
use PDO;

class ClienteRepository
{
    private PDO $conn;

    public function __construct()
    {
        // Usa o singleton do Database
        $this->conn = Database::getInstance();
    }

    /**
     * Retorna todos os clientes
     * @return Cliente[]
     */
    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM clientes ORDER BY id DESC");
        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = new Cliente($row);
        }
        return $clientes;
    }

    public function getTheNumbersClients(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM clientes");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retorna um cliente pelo ID
     */
    public function getById(int $id): ?Cliente
    {
        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Cliente($data) : null;
    }

    public function getHistoricoCompras(int $clienteId): array
    {
        $stmt = $this->conn->prepare("SELECT 
    hc.id AS historico_id,
    c.nome_completo AS cliente,
    
    v.modelo AS carro,
    v.ano,
    m.nome AS marca,
    
    hc.data_compra,
    hc.preco_compra,
    hc.metodo_pagamento

FROM historico_compras hc
JOIN clientes c ON hc.cliente_id = c.id
JOIN veiculos v ON hc.carro_id = v.id
JOIN marcas m ON v.id_marca = m.id

    WHERE hc.cliente_id = :cliente_id
ORDER BY hc.data_compra DESC;
    ");

        $stmt->execute(['cliente_id' => $clienteId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Cria um novo cliente
     * @return int ID do cliente criado
     */
    public function create(Cliente $cliente): int
    {
        $sql = "INSERT INTO clientes (
                    nome_completo, email, telefone, identidade, 
                    cidade, municipio, endereco, created_at
                ) VALUES (
                    :nome_completo, :email, :telefone, :identidade,
                    :cidade, :municipio, :endereco, :created_at
                )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nome_completo' => $cliente->nome_completo,
            'email'         => $cliente->email,
            'telefone'      => $cliente->telefone,
            'identidade'   => $cliente->identidade,
            'cidade'        => $cliente->cidade,
            'municipio'     => $cliente->municipio,
            'endereco'      => $cliente->endereco,
            'created_at'    => $cliente->created_at ?? date('Y-m-d H:i:s')
        ]);

        return (int)$this->conn->lastInsertId();
    }

    public function existsByEmailOrBI(string $email, string $identidade): bool
    {
        $sql = "SELECT COUNT(*) FROM clientes WHERE email = :email OR identidade = :identidade";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'identidade'    => $identidade
        ]);

        // Retorna true se já existir algum registro
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existsByEmailOrBIExcludingId(string $email, string $identidade, int $excludeId): bool
    {
        $sql = "SELECT COUNT(*) FROM clientes WHERE (email = :email OR identidade = :identidade) AND id != :excludeId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'identidade'    => $identidade,
            'excludeId'     => $excludeId
        ]);

        // Retorna true se já existir algum registro (excluindo o ID atual)
        return (int)$stmt->fetchColumn() > 0;   
    }   

    /**
     * Atualiza um cliente existente
     */
    public function update(Cliente $cliente): bool
    {
        if (!$cliente->id) return false;

        $sql = "UPDATE clientes SET
                    nome_completo = :nome_completo,
                    email = :email,
                    telefone = :telefone,
                    identidade = :identidade,
                    cidade = :cidade,
                    municipio = :municipio
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'nome_completo' => $cliente->nome_completo,
            'email'         => $cliente->email,
            'telefone'      => $cliente->telefone,
            'identidade'   => $cliente->identidade,
            'cidade'        => $cliente->cidade,
            'municipio'     => $cliente->municipio,
            'id'            => $cliente->id
        ]);
    }

    public function search(array $filters): array
    {
        $sql = "SELECT * FROM clientes WHERE 1=1";
        $params = [];

        if (!empty($filters['nome_busca'])) {
            $sql .= " AND (nome_completo LIKE :nome_completo OR identidade LIKE :identidade OR cidade LIKE :cidade)";
            $params[':nome_completo'] = '%' . $filters['nome_busca'] . '%';
            $params[':identidade']   = '%' . $filters['nome_busca'] . '%';
            $params[':cidade']       = '%' . $filters['nome_busca'] . '%';
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = new Cliente($row);
        }

        return $clientes;
    }

    /**
     * Deleta um cliente pelo ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
