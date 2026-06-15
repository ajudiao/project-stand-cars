<?php
// app/Controllers/Public/VeiculosController.php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Repositories\CarRepository;
use App\Repositories\MarcaRepository;

class VeiculosController extends Controller
{
    private CarRepository $carRepository;
    private MarcaRepository $marcaRepository;

    public function __construct()
    {
        $this->carRepository = new CarRepository();
        $this->marcaRepository = new MarcaRepository();
    }

    public function index()
    {
        $modelo = $_GET['modelo'] ?? null;
        $status = $_GET['status'] ?? null;
        $idMarca = isset($_GET['marca']) && $_GET['marca'] !== '' ? (int)$_GET['marca'] : null;
        $precoMaximo = isset($_GET['preco_maximo']) && $_GET['preco_maximo'] !== '' ? (float)$_GET['preco_maximo'] : null;
        $combustivel = $_GET['combustivel'] ?? null;
        $transmissao = $_GET['transmissao'] ?? null;
        $order = $_GET['order'] ?? 'newest';

        $veiculos = $this->carRepository->buscarVeiculos(
            $modelo,
            $status,
            $idMarca,
            $precoMaximo,
            $combustivel,
            $transmissao,
            $order,
            true
        );

        // Obter contagem de modelos
        $modelCount = $this->carRepository->getModelCountByBrand();
        
        // Adicionar contagem a cada veículo
        foreach ($veiculos as $veiculo) {
            $modelKey = $veiculo->marca_nome . ' ' . $veiculo->modelo;
            $veiculo->stock_count = $modelCount[$modelKey] ?? 0;
        }

        $marcas = $this->marcaRepository->getAll();

        $this->view('site/veiculos', [
            'veiculos' => $veiculos,
            'marcas' => $marcas,
            'filtros' => [
                'modelo' => $modelo,
                'status' => $status,
                'idMarca' => $idMarca,
                'precoMaximo' => $precoMaximo,
                'combustivel' => $combustivel,
                'transmissao' => $transmissao,
                'order' => $order,
            ]
        ]);
    }

    public function show($id)
    {
        $veiculo = $this->carRepository->getByIdWithImages((int)$id);

        if (!$veiculo || strtolower($veiculo->status) !== 'publicado') {
            $this->view('errors/404');
            return;
        }

        $this->view('site/veiculo-detalhes', [
            'veiculo' => $veiculo
        ]);
    }
}
