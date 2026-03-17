<?php
// app/Controllers/Public/HomeController.php

namespace App\Controllers\Public;
use App\Core\Controller;

class VeiculosController extends Controller
{
    public function index()
    {
        $this->view('site/veiculos', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }

    public function show($id)
    {
        echo "teste";
        exit;
        $this->view('site/veiculo-detalhes', [
            'message' => "Detalhes do veículo com ID: $id"
        ]);
    }
}