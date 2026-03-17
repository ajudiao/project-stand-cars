<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    private static $twig;

    private static function init()
    {
        if (self::$twig === null) {

            $loader = new FilesystemLoader(BASE_PATH . '/resources/views');

            self::$twig = new Environment($loader, [
                'cache' => false,
                'debug' => true
            ]);

            /*
            |--------------------------------------------------------------------------
            | Variáveis globais
            |--------------------------------------------------------------------------
            */
            self::$twig->addGlobal('app_name', APP_NAME);
            self::$twig->addGlobal('base_url', URL_DESENVOLVIMENTO);

            /*
            |--------------------------------------------------------------------------
            | Funções
            |--------------------------------------------------------------------------
            */
            // Caminhos para assets estáticos
            self::$twig->addFunction(
                new TwigFunction('asset', function ($path) {
                    return '/assets/images/' . ltrim($path, '/');
                })
            );

            // URL base do sistema
            self::$twig->addFunction(
                new TwigFunction('url', function ($path = '') {
                    return URL_BASE . '/' . ltrim($path, '/');
                })
            );

            // Caminho completo de imagens de veículos
            self::$twig->addFunction(
                new TwigFunction('carImage', function (?string $filename) {
                    $uploadPath = '/uploads/cars/'; // URL pública para navegador
                    $filePath = BASE_PATH . '/public/uploads/cars/' . $filename; // caminho real no servidor

                    if ($filename && file_exists($filePath)) {
                        return $uploadPath . ltrim($filename, '/');
                    }

                    // Fallback caso não exista imagem
                    return 'https://via.placeholder.com/80x60?text=Carro';
                })
            );
        }

        return self::$twig;
    }
    
    public static function render(string $template, array $data = [])
    {
        $twig = self::init();

        echo $twig->render($template . '.twig', $data);
    }
}
