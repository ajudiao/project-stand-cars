<?php

namespace App\Helpers;

class Helpers
{
    /**
     * Debug - imprime variável formatada
     */
    public static function dd($data): void
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die();
    }

    /**
     * Escapar HTML
     */
    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Redirecionar
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * URL base do projeto
     */
    public static function baseUrl(string $path = ''): string
    {
        $base = '/stand-cars/public';

        return $base . '/' . ltrim($path, '/');
    }

    /**
     * Verificar método HTTP
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}