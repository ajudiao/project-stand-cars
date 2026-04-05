<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\VendaRepository;
use App\Models\Venda;

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

        $this->view('dashboard/vendas', [
            'vendas' => $vendas,
            'total_concluidas' => count($total_concluidas),
            'total_pendetes' => count($total_pendetes),
            'clientes' => $clientes->getAll(),
            'veiculos' => $veiculos->getAllWithImages()
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Validação básica
        if (!isset($data['id_veiculo'])) {
            echo "Carro não informado.";
            return;
        }

        $carroId = (int) $data['id_veiculo'];

        // Verifica se o carro já pertence a alguém
        if ($this->vendasRep->carroJaVendido($carroId)) {
            echo "Este carro já está associado a um cliente.";
            return;
        }

        $venda = new Venda($data);

        try {
            $vendaId = $this->vendasRep->create($venda);

            if ($vendaId) {
                header('Location: /admin/vendas');
                exit;
            } else {
                echo "Erro ao criar venda.";
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            echo "Não foi possível criar a venda. Verifique os dados e tente novamente." . $e->getMessage();
        }
    }

    public function buscar()
    {
        $filtros = [
            'nome'   => $_GET['nome'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];

        try {
            $vendas = $this->vendasRep->search($filtros);
        } catch (\PDOException $e) {
            echo "Erro ao buscar vendas: " . $e->getMessage();
            exit;
        }
        $this->view('dashboard/vendas', [
            'vendas'  => $vendas,
            'filtros' => $filtros
        ]);
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            echo "Parâmetro inválido";
            return;
        }

        $venda = $this->vendasRep->findVendaById((int)$id);
        if (!$venda) {
            echo "Venda não encontrada";
            return;
        }

        $this->view('dashboard/detalhes-venda', [
            'venda' => $venda
        ]);
    }
}
