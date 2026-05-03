<?php

namespace App\Controllers\Public;
use App\Core\Controller;

class RecomendacaoController extends Controller
{
    public function index()
    {
        $this->view('site/recomendacao', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }
}