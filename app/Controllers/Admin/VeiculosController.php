<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class VeiculosController extends Controller
{
    public function index()
    {
        // Lógica para listar os veículos
        $this->view('deashboad/veiculos', [
            'veiculos' => [] // Aqui você pode passar os dados dos veículos para a view
        ]);
    }
    
    public function create()
    {
        // Lógica para exibir o formulário de criação de veículo
        echo "Formulário para criar um novo veículo (Admin)";
    }
}