<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ClientesController extends Controller
{
    public function index()
    {
        // Lógica para listar os clientes
        $this->view('deashboad/clientes', [
            'clientes' => [] // Aqui você pode passar os dados dos clientes para a view
        ]);
    }
}