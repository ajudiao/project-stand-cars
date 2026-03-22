<?php 

namespace App\Controllers\Admin;
use App\Core\Controller;

class PerfilController extends Controller
{
    public function index()
    {
        // Lógica para exibir o perfil do administrador
        $this->view('dashboard/perfil', [
            // Aqui você pode passar dados do perfil para a view, como nome, email, etc.
        ]);
    }
}