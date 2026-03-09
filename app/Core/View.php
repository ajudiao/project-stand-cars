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

            $loader = new \Twig\Loader\FilesystemLoader(BASE_PATH . '/resources/views');

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
            self::$twig->addGlobal('base_url', URL_BASE);

            /*
            |--------------------------------------------------------------------------
            | Funções
            |--------------------------------------------------------------------------
            */

            self::$twig->addFunction(
                new TwigFunction('asset', function ($path) {
                    return URL_BASE . '/assets/' . ltrim($path, '/');
                })
            );

            self::$twig->addFunction(
                new TwigFunction('url', function ($path = '') {
                    return URL_BASE . '/' . ltrim($path, '/');
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