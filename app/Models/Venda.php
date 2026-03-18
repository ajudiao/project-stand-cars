<?php

namespace App\Models;

class Venda
{
    public ?int $id;
    public int $id_cliente;
    public int $id_veiculo;
    public int $id_vendedor;
    public float $preco_venda;
    public string $data_venda;
    public ?string $observacoes;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->id_cliente = isset($data['id_cliente']) ? (int)$data['id_cliente'] : 0;
        $this->id_veiculo = isset($data['id_veiculo']) ? (int)$data['id_veiculo'] : 0;
        $this->id_vendedor = isset($data['id_vendedor']) ? (int)$data['id_vendedor'] : 0;
        $this->preco_venda = isset($data['preco_venda']) ? (float)$data['preco_venda'] : 0.0;
        $this->data_venda = $data['data_venda'] ?? date('Y-m-d');
        $this->observacoes = $data['observacoes'] ?? null;
    }
}