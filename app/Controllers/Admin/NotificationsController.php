<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\NotificationRepository;

class NotificationsController extends Controller
{
    public function index()
    {
        $notificationRepo = new NotificationRepository();
        $notifications = $notificationRepo->getNotifications(15);
        $summary = $notificationRepo->getWeeklySummary();

        echo $this->view('dashboard/notificacoes', [
            'notifications' => $notifications,
            'summary' => $summary,
            'unreadCount' => count($notifications),
            'title' => 'Notificações - ' . APP_NAME,
        ]);
    }

    public function getNotifications()
    {
        // Apenas para requisições AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            http_response_code(403);
            exit;
        }

        // Verificar se está logado
        if (empty($_SESSION['admin_logged'])) {
            http_response_code(401);
            exit;
        }

        try {
            $notificationRepo = new NotificationRepository();
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $notifications = $notificationRepo->getNotifications($limit);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'count' => count($notifications),
                'notifications' => $notifications
            ]);
        } catch (\Throwable $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao carregar notificações'
            ]);
        }
    }
}
