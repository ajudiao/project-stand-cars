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

    public function loginForm(): void
    {
        // Se já estiver logado, redireciona para o painel
        if (!empty($_SESSION['admin_logged'])) {
            header('Location: /admin');
            exit;
        }

        $this->view('dashboard/login', [
            'title' => 'Login - ' . APP_NAME,
        ]);
    }

    public function login(): void
    {
        // Sessão já iniciada no index.php
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->view('dashboard/login', ['error' => 'Preencha todos os campos.']);
            return;
        }

        $user = $this->usuarioRepo->findByEmail($email);

        if (!$user || !password_verify($senha, $user->senha)) {
            $this->view('dashboard/login', ['error' => 'Email ou senha inválidos.']);
            return;
        }

        // Regenera ID da sessão por segurança
        session_regenerate_id(true);

        $_SESSION['admin_logged'] = true;
        $_SESSION['user_id']      = $user->id;
        $_SESSION['user_nome']    = $user->nome;
        $_SESSION['user_perfil']  = $user->perfil;
        $_SESSION['user_foto']    = $user->foto;

        header('Location: /admin');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: /admin/login');
        exit;
    }
}
