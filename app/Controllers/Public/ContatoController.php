<?php

namespace App\Controllers\Public;
use App\Core\Controller;

class ContatoController extends Controller
{
    public function index()
    {
        $this->view('site/contato', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }
}