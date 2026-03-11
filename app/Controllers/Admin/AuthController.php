<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class AuthController extends Controller
{
    public function loginForm()
    {
        $this->view('deashboad/login', [
            'message' => 'Olá Mundo com Twig'
        ]);
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        var_dump($email, $password); // Verificar os dados recebidos
       

        // Exemplo simples (depois você liga ao banco)
        if ($email === 'admin@stand.com' && $password === '123456') {

            $_SESSION['admin_logged'] = true;

            header('Location: /admin');
            exit;
        }

        echo "Login inválido";
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