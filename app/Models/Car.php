<?php

namespace App\Models;

class Car
{
    public ?int $id;
    public int $id_marca;
    public int $id_categoria;
    public string $modelo;
    public int $ano;
    public string $cor;
    public float $preco;
    public int $quilometragem;
    public string $combustivel;
    public string $transmissao;
    public string $status;
    public ?string $descricao;
    public int $destaque;
    public ?string $updated_at;
    public string $created_at;

    public string $categoria_nome;
    public string $marca_nome;

    // Propriedades extras para imagens
    public array $imagens = [];
    public ?string $foto = null;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->id_marca = isset($data['id_marca']) ? (int)$data['id_marca'] : 0;
        $this->id_categoria = isset($data['id_categoria']) ? (int)$data['id_categoria'] : 0;
        $this->modelo = $data['modelo'] ?? '';
        $this->ano = isset($data['ano']) ? (int)$data['ano'] : 0;
        $this->cor = $data['cor'] ?? '';
        $this->preco = isset($data['preco']) ? (float)$data['preco'] : 0.0;
        $this->quilometragem = isset($data['quilometragem']) ? (int)$data['quilometragem'] : 0;
        $this->combustivel = $data['combustivel'] ?? '';
        $this->transmissao = $data['transmissao'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->descricao = $data['descricao'] ?? null;
        $this->destaque =  (int)($data['destaque']);
        $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->imagens = $data['imagens'] ?? [];
        $this->foto = $data['foto'] ?? null;
    }
}