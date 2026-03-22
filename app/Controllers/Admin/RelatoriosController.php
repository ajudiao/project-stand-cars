<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class RelatoriosController extends Controller
{
    public function index()
    {
        // Lógica para listar os relatórios
        $this->view('dashboard/relatorios', [
            'relatorios' => [] // Aqui você pode passar os dados dos relatórios para a view
        ]);
    }
}