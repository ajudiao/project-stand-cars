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
                    municipio = :municipio,
                    endereco = :endereco
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'nome_completo' => $cliente->nome_completo,
            'email'         => $cliente->email,
            'telefone'      => $cliente->telefone,
            'identidade'   => $cliente->identidade,
            'cidade'        => $cliente->cidade,
            'municipio'     => $cliente->municipio,
            'endereco'      => $cliente->endereco,
            'id'            => $cliente->id
        ]);
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
