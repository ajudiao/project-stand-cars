<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\NewsRepository;

class NoticiasController extends Controller
{
    private NewsRepository $newsRepo;

    public function __construct()
    {
        $this->newsRepo = new NewsRepository();
    }

    private function uploadImagemCapa(array $file)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/news/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
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

        $filename = 'news_' . time() . '_' . uniqid() . '.' . $ext;
        $path = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $path)) {
            return $filename;
        }

        return false;
    }

    public function index()
    {
        $noticias = $this->newsRepo->getAll();

        $this->view('dashboard/noticias', [
            'noticias' => $noticias,
            'title'    => 'Notícias - ' . APP_NAME,
        ]);
    }

    public function create()
    {
        $this->view('dashboard/noticias-form', [
            'title' => 'Nova Notícia - ' . APP_NAME,
        ]);
    }

    public function store()
    {
        $data = $_POST;

        if (empty($data['titulo']) || empty($data['conteudo'])) {
            \App\Helpers\Helpers::setFlash('error', 'Título e conteúdo são obrigatórios.');
            header('Location: /admin/noticias/create');
            exit;
        }

        $data['id_autor'] = $_SESSION['user_id'] ?? null;

        if (!empty($_FILES['imagem_capa_file']['name'])) {
            $uploaded = $this->uploadImagemCapa($_FILES['imagem_capa_file']);
            if ($uploaded) {
                $data['imagem_capa'] = $uploaded;
            }
        }

        if ($this->newsRepo->create($data)) {
            \App\Helpers\Helpers::setFlash('success', 'Notícia criada com sucesso.');
        } else {
            \App\Helpers\Helpers::setFlash('error', 'Erro ao criar notícia.');
        }

        header('Location: /admin/noticias');
        exit;
    }

    public function edit($id)
    {
        $noticia = $this->newsRepo->getById((int)$id);

        if (!$noticia) {
            \App\Helpers\Helpers::setFlash('error', 'Notícia não encontrada.');
            header('Location: /admin/noticias');
            exit;
        }

        $this->view('dashboard/noticias-form', [
            'noticia' => $noticia,
            'title'   => 'Editar Notícia - ' . APP_NAME,
        ]);
    }

    public function update($id)
    {
        $data = $_POST;

        if (empty($data['titulo']) || empty($data['conteudo'])) {
            \App\Helpers\Helpers::setFlash('error', 'Título e conteúdo são obrigatórios.');
            header('Location: /admin/noticias/' . $id . '/edit');
            exit;
        }

        $noticia = $this->newsRepo->getById((int) $id);
        $data['imagem_capa'] = $data['imagem_capa'] ?? null;

        if (!empty($_FILES['imagem_capa_file']['name'])) {
            $uploaded = $this->uploadImagemCapa($_FILES['imagem_capa_file']);
            if ($uploaded) {
                if (!empty($noticia['imagem_capa'])) {
                    $oldPath = __DIR__ . '/../../../public/uploads/news/' . $noticia['imagem_capa'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $data['imagem_capa'] = $uploaded;
            }
        }

        $this->newsRepo->update((int)$id, $data);

        \App\Helpers\Helpers::setFlash('success', 'Notícia atualizada com sucesso.');
        header('Location: /admin/noticias');
        exit;
    }

    public function delete($id)
    {
        $this->newsRepo->delete((int)$id);
        \App\Helpers\Helpers::setFlash('success', 'Notícia excluída com sucesso.');
        header('Location: /admin/noticias');
        exit;
    }
}
