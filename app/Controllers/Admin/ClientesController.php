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
            echo "Parametro invalido";
            return ;
        }
        
        $cliente = $this->clienteRepo->getById($id);
    

        $this->view('dashboard/detalhes-cliente', [
            "cliente" => $cliente,
        ]);

    }
}
