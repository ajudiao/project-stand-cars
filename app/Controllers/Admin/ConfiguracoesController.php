<?php

namespace App\Controllers\Admin;
use App\Core\Controller;

class ConfiguracoesController extends Controller
{
    public function index()
    {
        // Lógica para exibir as configurações do sistema
        $this->view('dashboard/configuracoes', [
            // Aqui você pode passar dados de configuração para a view, como opções de personalização, etc.
        ]);
    }
}