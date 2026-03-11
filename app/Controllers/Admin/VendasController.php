<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class VendasController extends Controller
{
    public function index()
    {
        // Lógica para listar as vendas
        $this->view('deashboad/vendas', [
            'vendas' => [] // Aqui você pode passar os dados das vendas para a view
        ]);
    }

    public function create()
    {
        // Lógica para exibir o formulário de criação de venda
        echo "Formulário para criar uma nova venda (Admin)";
    }
}