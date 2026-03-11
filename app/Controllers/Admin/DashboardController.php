<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        echo $this->view('deashboad/index', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }
}