<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\UsuarioRepository;

class AuthController extends Controller
{
    private UsuarioRepository $usuarioRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
    }

    public function loginForm()
    {
         // Se já estiver logado, redireciona para o painel
        if (!empty($_SESSION['admin_logged'])) {
            header('Location: /admin');
            exit;
        }

        // Se já estiver logado, redireciona para o painel
        if (!empty($_SESSION['admin_logged'])) {
            header('Location: /admin');
            exit;
        }
        $this->view('deashboad/login', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }

    public function login()
    {
        session_start();

        $email = $_POST['email'] ?? '';
        $senha = $_POST['password'] ?? '';

        $user = $this->usuarioRepo->findByEmail($email);


        if (!$user || !password_verify($senha, $user->senha)) {
            $error = "Email ou senha inválidos.";
            $this->view('deashboad/login', [
                'error' => $error
            ]);
            unset($error);
            return;
        }

        
        $_SESSION['admin_logged'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_nome'] = $user->nome;
        $_SESSION['user_perfil'] = $user->perfil;


        header('Location: /admin');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        header('Location: /admin/login');
        exit;
    }
}
