<?php

namespace App\Controllers\Admin;

class AuthController
{
    public function loginForm()
    {
        echo '
        <h2>Login Admin</h2>
        <form method="POST" action="/admin/login">
            <input type="text" name="email" placeholder="Email"><br><br>
            <input type="password" name="password" placeholder="Senha"><br><br>
            <button type="submit">Entrar</button>
        </form>
        ';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

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