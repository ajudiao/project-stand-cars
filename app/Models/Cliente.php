<?php

namespace App\Models;

class Cliente
{
    public ?int $id;
    public string $nome_completo;
    public string $email;
    public string $telefone;
    public string $identidade;
    public string $cidade;
    public string $municipio;
    public string $endereco;
    public string $created_at;

    public function __construct(array $data = [])
    {
        $this->id             = isset($data['id']) ? (int)$data['id'] : null;
        $this->nome_completo  = $data['nome_completo'] ?? '';
        $this->email          = $data['email'] ?? '';
        $this->telefone       = $data['telefone'] ?? '';
        $this->identidade    = $data['identidade'] ?? '';
        $this->cidade         = $data['cidade'] ?? '';
        $this->municipio      = $data['municipio'] ?? '';
        $this->endereco       = $data['endereco'] ?? '';
        $this->created_at     = $data['created_at'] ?? date('Y-m-d H:i:s');
    }
}