<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class SiteController extends Controller
{
    public function index()
    {
        // Lógica para exibir o dashboard do site
        $this->view('deashboad/website', [
            // Aqui você pode passar dados para a view, como estatísticas do site, etc.
        ]);
    }

    public function configuracoes()
    {
        $this->view('deashboad/configuracoes-site', [
            // Aqui você pode passar dados para a view, como estatísticas do site, etc.
        ]);
    }
}