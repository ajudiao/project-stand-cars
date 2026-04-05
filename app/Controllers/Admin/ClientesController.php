<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use App\Models\Clientes;

class ClientesController extends Controller
{
    private ClienteRepository $clienteRepo;

    public function __construct()
    {
        $this->clienteRepo = new ClienteRepository();
    }
    public function index()
    {
        // Pegar os clientes do banco de dados (ainda não implementado)
        $clientes = $this->clienteRepo->getAll(); // Aqui você pode implementar a lógica para buscar os clientes do banco de dados
        // Lógica para listar os clientes
        $this->view('dashboard/clientes', [
            'clientes' => $clientes // Aqui você pode passar os dados dos clientes para a view
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Cria o cliente
        $cliente = new Cliente($data);

        // Verifica duplicados (email ou BI)
        if ($this->clienteRepo->existsByEmailOrBI($cliente->email, $cliente->identidade)) {
            echo "Já existe um cliente com este email ou BI.";
            return;
        }

        try {
            // Salva no banco e retorna o ID
            $clienteId = $this->clienteRepo->create($cliente);

            if ($clienteId) {
                // Redireciona para a lista de clientes
                header('Location: /admin/clientes');
                exit;
            } else {
                echo "Erro ao criar cliente.";
            }
        } catch (\PDOException $e) {
            // Loga o erro para debug
            error_log($e->getMessage());

            // Mensagem amigável para o usuário
            echo "Não foi possível criar o cliente. Verifique os dados e tente novamente.";
        }
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            echo "Parâmetro inválido";
            return;
        }

        $cliente = $this->clienteRepo->getById((int)$id);
        if (!$cliente) {
            echo "Cliente não encontrado";
            return;
        }

        $historico = $this->clienteRepo->getHistoricoCompras((int)$id);

        // total de veículos comprados
        $totalVeiculos = count($historico);

        // total gasto
        $totalGasto = array_sum(array_column($historico, 'preco_compra'));

        $this->view('dashboard/detalhes-cliente', [
            "cliente" => $cliente,
            "historico" => $historico,
            "totalVeiculos" => $totalVeiculos,
            "totalGasto" => $totalGasto
        ]);
    }

    public function buscar()
    {
        $filters = [
            'nome_busca' => $_GET['nome_busca'] ?? '',  // Nome, identidade ou cidade
        ];
        try {
            $clientes = $this->clienteRepo->search($filters);
            $this->view('dashboard/clientes', [
                'clientes' => $clientes,
                'filters' => $filters
            ]);
        } catch (\PDOException $e) {
            echo "Erro ao buscar clientes: " . $e->getMessage();
            exit;
        }
    }
    public function update($id)
    {
        $data = $_POST;

        $cliente = new Cliente($data);
        $cliente->id = (int)$id;

         // Verifica duplicados (email ou BI) para outros clientes
         if ($this->clienteRepo->existsByEmailOrBIExcludingId($cliente->email, $cliente->identidade, $cliente->id)) {
            echo "Já existe outro cliente com este email ou BI.";
            return;
        }

        if (!$this->clienteRepo->update($cliente)) {
            echo "Erro ao atualizar cliente.";
            return;
        }

        header('Location: /admin/clientes');
        exit;
    }
}
