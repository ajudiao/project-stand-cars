<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Usuario;
use PDO;

class UsuarioRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Buscar usuário por email (LOGIN)
     */
    public function findByEmail(string $email): ?Usuario
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Usuario($data) : null;
    }

    /**
     * Criar usuário
     */
    public function create(Usuario $usuario): int
    {
        $sql = "INSERT INTO usuarios (
                    nome, email, telefone, senha, perfil, created_at
                ) VALUES (
                    :nome, :email, :telefone, :senha, :perfil, :created_at
                )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nome'       => $usuario->nome,
            'email'      => $usuario->email,
            'telefone'   => $usuario->telefone,
            'senha'      => $usuario->senha,
            'perfil'     => $usuario->perfil,
            'created_at' => $usuario->created_at
        ]);

        return (int)$this->conn->lastInsertId();
    }

    /**
     * Verificar duplicidade (email)
     */
    public function existsByEmail(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        return (int)$stmt->fetchColumn() > 0;
    }
}