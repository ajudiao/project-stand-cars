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
}