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
}
