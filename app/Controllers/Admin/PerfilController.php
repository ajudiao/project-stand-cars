<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Helpers;
use App\Repositories\UsuarioRepository;
use App\Repositories\SiteSettingRepository;

class PerfilController extends Controller
{
    private UsuarioRepository $usuarioRepo;
    private SiteSettingRepository $settingRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
        $this->settingRepo = new SiteSettingRepository();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            Helpers::redirect('/admin/login');
        }

        $user = $this->usuarioRepo->findById((int) $userId);
        if (!$user) {
            Helpers::redirect('/admin/login');
        }

        $settings = $this->settingRepo->getAll();

        $this->view('dashboard/perfil', [
            'title' => 'Perfil - ' . APP_NAME,
            'user' => $user,
            'settings' => $settings
        ]);
    }

    public function updatePhoto()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            Helpers::redirect('/admin/login');
        }

        $user = $this->usuarioRepo->findById((int) $userId);
        if (!$user) {
            Helpers::redirect('/admin/login');
        }

        if (empty($_FILES['foto']['name'])) {
            Helpers::setFlash('error', 'Selecione uma foto para enviar.');
            Helpers::redirect('/admin/perfil');
        }

        $fotoFilename = $this->uploadFoto($_FILES['foto']);
        if (!$fotoFilename) {
            Helpers::setFlash('error', 'Erro ao enviar foto. Formato permitido: jpg, jpeg, png e até 2MB.');
            Helpers::redirect('/admin/perfil');
        }

        if (!empty($user->foto)) {
            $oldPath = __DIR__ . '/../../../public/uploads/users/' . $user->foto;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $this->usuarioRepo->update((int) $userId, [
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'perfil' => $user->perfil,
            'senha' => $user->senha,
            'foto' => $fotoFilename
        ]);

        $_SESSION['user_foto'] = $fotoFilename;
        Helpers::setFlash('success', 'Foto atualizada com sucesso.');
        Helpers::redirect('/admin/perfil');
    }

    public function changePassword()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            Helpers::redirect('/admin/login');
        }

        $user = $this->usuarioRepo->findById((int) $userId);
        if (!$user) {
            Helpers::redirect('/admin/login');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            Helpers::setFlash('error', 'Preencha todos os campos de senha.');
            Helpers::redirect('/admin/perfil');
        }

        if (!password_verify($currentPassword, $user->senha)) {
            Helpers::setFlash('error', 'Senha atual incorreta.');
            Helpers::redirect('/admin/perfil');
        }

        if ($newPassword !== $confirmPassword) {
            Helpers::setFlash('error', 'A nova senha e a confirmação não coincidem.');
            Helpers::redirect('/admin/perfil');
        }

        $passwordError = $this->validatePasswordStrength($newPassword);
        if ($passwordError !== null) {
            Helpers::setFlash('error', $passwordError);
            Helpers::redirect('/admin/perfil');
        }

        $this->usuarioRepo->update((int) $userId, [
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'perfil' => $user->perfil,
            'senha' => password_hash($newPassword, PASSWORD_DEFAULT),
            'foto' => $user->foto
        ]);

        Helpers::setFlash('success', 'Senha alterada com sucesso.');
        Helpers::redirect('/admin/perfil');
    }

    private function validatePasswordStrength(string $password): ?string
    {
        if (strlen($password) < 10) {
            return 'A senha deve ter pelo menos 10 caracteres.';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return 'A senha deve conter pelo menos uma letra maiúscula.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            return 'A senha deve conter pelo menos uma letra minúscula.';
        }

        if (!preg_match('/\d/', $password)) {
            return 'A senha deve conter pelo menos um número.';
        }

        if (!preg_match('/[\W_]/', $password)) {
            return 'A senha deve conter pelo menos um caractere especial.';
        }

        return null;
    }

    private function uploadFoto($file)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/users/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['error'] !== 0) {
            return false;
        }

        $tmpName = $file['tmp_name'];
        $originalName = $file['name'];
        $size = $file['size'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true) || $size > $maxSize) {
            return false;
        }

        $filename = 'user_' . time() . '_' . uniqid() . '.' . $ext;
        $path = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $path)) {
            return $filename;
        }

        return false;
    }
}
