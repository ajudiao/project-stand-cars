<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\ClienteRepository;

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
        $this->view('deashboad/clientes', [
            'clientes' => $clientes // Aqui você pode passar os dados dos clientes para a view
        ]);
    }
}