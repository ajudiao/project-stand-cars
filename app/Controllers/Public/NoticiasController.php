<?php

namespace App\Controllers\Public;
use App\Core\Controller;

class NoticiasController extends Controller
{
    public function index()
    {
        $this->view('site/noticias', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }

    public function show($id)
    {
        $this->view('site/noticia-detalhes', [
            'message' => "Detalhes da notícia com ID: $id"
        ]);
    }
}