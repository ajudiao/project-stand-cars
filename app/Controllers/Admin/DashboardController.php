<?php

namespace App\Controllers\Admin;

class DashboardController
{
    public function index()
    {
        echo "<h1>Painel Admin</h1>";
        echo '<a href="/stand-cars/public/admin/logout">Sair</a>';
    }
}