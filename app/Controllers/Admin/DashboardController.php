<?php

namespace App\Controllers\Admin;

class DashboardController
{
    public function index()
    {
        echo "<h1>Painel Admin</h1>";
        echo '<a href="/admin/logout">Sair</a>';
    }
}