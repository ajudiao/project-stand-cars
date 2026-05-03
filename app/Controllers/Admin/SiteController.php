<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\SiteSettingRepository;

class SiteController extends Controller
{
    private SiteSettingRepository $settingRepo;

    public function __construct()
    {
        $this->settingRepo = new SiteSettingRepository();
    }

    public function index()
    {
        $this->view('dashboard/website', [
            'title' => 'Website - ' . APP_NAME
        ]);
    }

    private function authorizeAdmin()
    {
        if (!isset($_SESSION['user_perfil']) || $_SESSION['user_perfil'] !== 'Administrador') {
            header('Location: /admin');
            exit;
        }
    }

    public function configuracoes()
    {
        $this->authorizeAdmin();

        $settings = $this->settingRepo->getAll();

        if (empty($settings)) {
            $this->settingRepo->createDefault();
            $settings = $this->settingRepo->getAll();
        }

        // Verificar se é uma requisição AJAX
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            // Retornar JSON para requisições AJAX
            header('Content-Type: application/json');
            echo json_encode(['settings' => $settings]);
            return;
        }

        // Renderizar view para requisições normais
        $this->view('dashboard/configuracoes-site', [
            'title' => 'Configurações do Site - ' . APP_NAME,
            'settings' => $settings,
        ]);
    }

    public function salvarConfiguracao()
    {
        $this->authorizeAdmin();

        // Verificar se é JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        // Remover campos que não são da tabela
        unset($data['_token']); // se houver

        // Validar dados não vazios
        if (empty($data)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Nenhum dado foi enviado.']);
            } else {
                \App\Helpers\Helpers::setFlash('error', 'Nenhum dado foi enviado.');
                header('Location: /admin/website/configuracoes');
            }
            exit;
        }

        // Tentar atualizar
        try {
            if ($this->settingRepo->update($data)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    // AJAX response
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Configurações salvas com sucesso.']);
                } else {
                    \App\Helpers\Helpers::setFlash('success', 'Configurações salvas com sucesso.');
                    header('Location: /admin/website/configuracoes');
                }
            } else {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar configurações no banco de dados.']);
                } else {
                    \App\Helpers\Helpers::setFlash('error', 'Erro ao salvar configurações.');
                    header('Location: /admin/website/configuracoes');
                }
            }
        } catch (\Exception $e) {
            error_log('Erro ao salvar configurações: ' . $e->getMessage());
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
            } else {
                \App\Helpers\Helpers::setFlash('error', 'Erro ao salvar: ' . $e->getMessage());
                header('Location: /admin/website/configuracoes');
            }
        }
        exit;
    }
}