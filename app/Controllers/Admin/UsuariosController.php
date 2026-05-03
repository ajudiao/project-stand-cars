<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\UsuarioRepository;

class UsuariosController extends Controller
{
    private UsuarioRepository $usuarioRepo;

    public function __construct()
    {
        $this->usuarioRepo = new UsuarioRepository();
    }

    // Listar usuários
    public function index()
    {
        $usuarios = $this->usuarioRepo->getAll();

        $this->view('dashboard/usuarios', [
            'usuarios' => $usuarios,
            'title'    => 'Usuários - ' . APP_NAME
        ]);
    }

    public function store()
    {
        $data = $_POST;

        // Validação
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            \App\Helpers\Helpers::setFlash('error', 'Nome, email e senha são obrigatórios.');
            header('Location: /admin/usuarios');
            exit;
        }

        if ($this->usuarioRepo->existsByEmail($data['email'])) {
            \App\Helpers\Helpers::setFlash('error', 'Email já cadastrado.');
            header('Location: /admin/usuarios');
            exit;
        }

        // Upload da foto
        $fotoFilename = null;
        if (!empty($_FILES['foto']['name'])) {
            $fotoFilename = $this->uploadFoto($_FILES['foto']);
            if (!$fotoFilename) {
                \App\Helpers\Helpers::setFlash('error', 'Erro ao fazer upload da foto.');
                header('Location: /admin/usuarios');
                exit;
            }
        }

        // Criar usuário
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        $data['foto'] = $fotoFilename;
        $usuario = new \App\Models\Usuario($data);
        $this->usuarioRepo->create($usuario);

        \App\Helpers\Helpers::setFlash('success', 'Usuário criado com sucesso.');
        header('Location: /admin/usuarios');
        exit;
    }

    private function uploadFoto($file)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/users/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== 0) return false;

        $tmpName = $file['tmp_name'];
        $originalName = $file['name'];
        $size = $file['size'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed) || $size > $maxSize) return false;

        $filename = 'user_' . time() . '_' . uniqid() . '.' . $ext;
        $path = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $path)) {
            return $filename;
        }

        return false;
    }

    public function delete($id)
    {
        $this->usuarioRepo->delete((int)$id);
        \App\Helpers\Helpers::setFlash('success', 'Usuário excluído com sucesso.');
        header('Location: /admin/usuarios');
        exit;
    }

    public function update($id)
    {
        $data = $_POST;
        $id = (int)$id;

        // Validação
        if (empty($data['nome']) || empty($data['email'])) {
            \App\Helpers\Helpers::setFlash('error', 'Nome e email são obrigatórios.');
            header('Location: /admin/usuarios/' . $id . '/edit');
            exit;
        }

        $usuario = $this->usuarioRepo->findById($id);
        if (!$usuario) {
            \App\Helpers\Helpers::setFlash('error', 'Usuário não encontrado.');
            header('Location: /admin/usuarios');
            exit;
        }

        // Verificar email duplicado
        if ($this->usuarioRepo->existsByEmail($data['email']) && $data['email'] !== $usuario->email) {
            \App\Helpers\Helpers::setFlash('error', 'Email já cadastrado.');
            header('Location: /admin/usuarios/' . $id . '/edit');
            exit;
        }

        // Upload da foto se enviada
        $fotoFilename = $usuario->foto;
        if (!empty($_FILES['foto']['name'])) {
            $newFoto = $this->uploadFoto($_FILES['foto']);
            if ($newFoto) {
                // Deletar foto antiga se existir
                if ($usuario->foto) {
                    $oldPath = __DIR__ . '/../../../public/uploads/users/' . $usuario->foto;
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $fotoFilename = $newFoto;
            }
        }

        // Atualizar senha se fornecida
        $senha = $usuario->senha;
        if (!empty($data['senha'])) {
            $senha = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        // Atualizar
        $this->usuarioRepo->update($id, [
            'nome'     => $data['nome'],
            'email'    => $data['email'],
            'telefone' => $data['telefone'],
            'perfil'   => $data['perfil'],
            'senha'    => $senha,
            'foto'     => $fotoFilename
        ]);

        \App\Helpers\Helpers::setFlash('success', 'Usuário atualizado com sucesso.');
        header('Location: /admin/usuarios');
        exit;
    }
}