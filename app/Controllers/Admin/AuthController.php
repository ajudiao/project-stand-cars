<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Usuario;
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

    public function signupForm(): void
    {
        // Se já estiver logado, redireciona para o painel
        if (!empty($_SESSION['admin_logged'])) {
            header('Location: /admin');
            exit;
        }

        $this->view('dashboard/signup', [
            'title' => 'Criar Conta - ' . APP_NAME,
        ]);
    }

    public function signup(): void
    {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = $_POST['password'] ?? '';
        $senhaConfirm = $_POST['password_confirm'] ?? '';

        // Validações
        if (empty($nome) || empty($email) || empty($senha) || empty($senhaConfirm)) {
            $this->view('dashboard/signup', ['error' => 'Preencha todos os campos obrigatórios.']);
            return;
        }

        if (strlen($senha) < 6) {
            $this->view('dashboard/signup', ['error' => 'A senha deve ter no mínimo 6 caracteres.']);
            return;
        }

        if ($senha !== $senhaConfirm) {
            $this->view('dashboard/signup', ['error' => 'As senhas não coincidem.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('dashboard/signup', ['error' => 'Email inválido.']);
            return;
        }

        if ($this->usuarioRepo->existsByEmail($email)) {
            $this->view('dashboard/signup', ['error' => 'Este email já está registado.']);
            return;
        }

        // Criar usuário
        $usuario = new Usuario([
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
            'perfil' => 'Cliente',
            'created_at' => date('Y-m-d H:i:s'),
            'foto' => null
        ]);

        $userId = $this->usuarioRepo->create($usuario);

        if ($userId > 0) {
            // Gerar token de reset
            $resetToken = bin2hex(random_bytes(32));
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_token'] = $resetToken;
            
            // Redirecionar para login com mensagem de sucesso
            header('Location: /admin/login?success=1');
            exit;
        } else {
            $this->view('dashboard/signup', ['error' => 'Erro ao criar conta. Tente novamente.']);
        }
    }

    public function forgotPasswordForm(): void
    {
        $this->view('dashboard/forgot-password', [
            'title' => 'Recuperar Senha - ' . APP_NAME,
        ]);
    }

    public function sendResetLink(): void
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $this->view('dashboard/forgot-password', ['error' => 'Insira o seu email.']);
            return;
        }

        $user = $this->usuarioRepo->findByEmail($email);

        if (!$user) {
            // Por segurança, não revelamos se o email existe ou não
            $this->view('dashboard/forgot-password', ['success' => 'Se esse email estiver registado, você receberá um link de recuperação.']);
            return;
        }

        // Gerar token de reset
        $resetToken = bin2hex(random_bytes(32));
        $resetLink = '/admin/reset-password?token=' . $resetToken . '&email=' . urlencode($email);

        // Armazenar token na sessão (em produção, usar banco de dados)
        $_SESSION['reset_token_' . $user->id] = [
            'token' => $resetToken,
            'email' => $email,
            'expires' => time() + 3600 // 1 hora
        ];

        // TODO: Implementar envio de email com o link
        // Por agora, mostrar mensagem de sucesso
        $this->view('dashboard/forgot-password', [
            'success' => 'Se esse email estiver registado, você receberá um link de recuperação em breve.'
        ]);
    }

    public function resetPasswordForm(): void
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        if (empty($token) || empty($email)) {
            $this->view('errors/404');
            return;
        }

        $this->view('dashboard/reset-password', [
            'title' => 'Redefinir Senha - ' . APP_NAME,
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';
        $senhaConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($email) || empty($senha) || empty($senhaConfirm)) {
            $this->view('dashboard/reset-password', [
                'error' => 'Preencha todos os campos.',
                'token' => $token,
                'email' => $email
            ]);
            return;
        }

        if (strlen($senha) < 6) {
            $this->view('dashboard/reset-password', [
                'error' => 'A senha deve ter no mínimo 6 caracteres.',
                'token' => $token,
                'email' => $email
            ]);
            return;
        }

        if ($senha !== $senhaConfirm) {
            $this->view('dashboard/reset-password', [
                'error' => 'As senhas não coincidem.',
                'token' => $token,
                'email' => $email
            ]);
            return;
        }

        $user = $this->usuarioRepo->findByEmail($email);

        if (!$user) {
            $this->view('errors/404');
            return;
        }

        // Validar token (implementação básica)
        $resetData = $_SESSION['reset_token_' . $user->id] ?? null;
        if (!$resetData || $resetData['token'] !== $token || $resetData['expires'] < time()) {
            $this->view('dashboard/reset-password', [
                'error' => 'Token inválido ou expirado.',
                'token' => $token,
                'email' => $email
            ]);
            return;
        }

        // Atualizar senha
        $this->usuarioRepo->update($user->id, [
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'perfil' => $user->perfil,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
            'foto' => $user->foto
        ]);

        // Limpar token
        unset($_SESSION['reset_token_' . $user->id]);

        // Redirecionar para login
        header('Location: /admin/login?success=1');
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
