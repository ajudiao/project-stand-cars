<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\VendaRepository;

class VendasController extends Controller
{
    private VendaRepository $vendasRep;

    public function __construct()
    {
        $this->vendasRep = new VendaRepository();
    }

    public function index()
    {
        $vendas = $this->vendasRep->findAllWithClient(); // já traz nome do cliente, veículo e vendedor
        $total_concluidas = array_filter($vendas, fn($v) => $v['status'] === 'Concluido');
        $total_pendetes = array_filter($vendas, fn($v) => $v['status'] === 'Pendente');
        $clientes = new \App\Repositories\ClienteRepository();
        $veiculos = new \App\Repositories\CarRepository();

        $this->view('deashboad/vendas', [
            'vendas' => $vendas,
            'total_concluidas' => count($total_concluidas),
            'total_pendetes' => count($total_pendetes),
            'clientes' => $clientes->getAll(),
            'veiculos' => $veiculos->getAllWithImages()
        ]);
    }

    public function create()
    {
        // Lógica para exibir o formulário de criação de venda
        echo "Formulário para criar uma nova venda (Admin)";
    }
}