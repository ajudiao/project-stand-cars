<?php

namespace App\Core;

use App\Helpers\Helpers;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    private static ?Environment $twig = null;
    private static bool $initialized = false;
    private static bool $dynamicInjected = false;

    private static function init(): Environment
    {
        if (self::$initialized) {
            return self::$twig;
        }

        $loader = new FilesystemLoader(BASE_PATH . '/resources/views');

        self::$twig = new Environment($loader, [
            'cache' => false,
            'debug' => defined('DEBUG') && DEBUG,
        ]);

        // Globais leves — sem base de dados
        self::$twig->addGlobal('app_name', APP_NAME);
        self::$twig->addGlobal('base_url', URL_BASE);
        self::$twig->addGlobal('companyLogo', URL_ASSETS_SITE . '/logotipo.png');
        self::$twig->addGlobal('localImage', URL_ASSETS_SITE . '/local.png');
        self::$twig->addGlobal('flashMessage', Helpers::getFlash());
        self::$twig->addGlobal('currentPath', parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

        // Funções Twig
        self::$twig->addFunction(new TwigFunction('asset', function ($path) {
            return '/assets/images/' . ltrim($path, '/');
        }));
    
        self::$twig->addFunction(new TwigFunction('url', function ($path = '') {
            return '/' . ltrim($path, '/');
        }));

        self::$twig->addFunction(new TwigFunction('userImage', function (?string $filename) {
            $filePath = BASE_PATH . '/public/uploads/users/' . $filename;
            
            if ($filename && file_exists($filePath)) {
                return '/uploads/users/' . ltrim($filename, '/');
            }
            return 'https://i.pravatar.cc/50?img=12';
        }));

        self::$twig->addFunction(new TwigFunction('carImage', function (?string $filename) {
            $filePath = BASE_PATH . '/public/uploads/cars/' . $filename;
            if ($filename && file_exists($filePath)) {
                return '/uploads/cars/' . ltrim($filename, '/');
            }
            return 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300&fit=crop';
        }));
    
        // Sessão — leve, sem DB
        self::$twig->addGlobal('userAvatar', isset($_SESSION['user_foto'])
            ? '/uploads/users/' . $_SESSION['user_foto']
            : 'https://i.pravatar.cc/50?img=12');
        self::$twig->addGlobal('userName', $_SESSION['user_nome'] ?? 'Administrador');
        self::$twig->addGlobal('userPerfil', $_SESSION['user_perfil'] ?? '');
        self::$twig->addGlobal('isAdmin', isset($_SESSION['user_perfil']) && $_SESSION['user_perfil'] === 'Administrador');
        
        // Defaults seguros para globais dinâmicos
        self::$twig->addGlobal('notifications', []);
        self::$twig->addGlobal('notificationsCount', 0);
        self::$twig->addGlobal('settings', []);
        
        self::$initialized = true;
        return self::$twig;
    }

    /**
     * Injeta dados do banco APENAS quando há sessão admin activa.
     * Chamado UMA única vez por requisição.
     */
    private static function injectDynamicGlobals(Environment $twig): void
    {
        if (self::$dynamicInjected) {
            return;
        }
        self::$dynamicInjected = true;

        // Só carrega do banco se estiver logado
        if (empty($_SESSION['admin_logged'])) {
            return;
        }

        try {
            $notifications = (new \App\Repositories\NotificationRepository())->getNotifications(5);
        } catch (\Throwable $e) {
            $notifications = [];
        }

        try {
            $settings = (new \App\Repositories\SiteSettingRepository())->getEssentialSettings();
        } catch (\Throwable $e) {
            $settings = [];
        }

        $twig->addGlobal('notifications', $notifications);
        $twig->addGlobal('notificationsCount', count($notifications));
        $twig->addGlobal('settings', $settings);
    }

    public static function render(string $template, array $data = []): void
    {
        $twig = self::init();
        self::injectDynamicGlobals($twig);
        echo $twig->render($template . '.twig', $data);
    }
}
