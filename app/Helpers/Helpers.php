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
     * Inicia sessão quando necessário
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Define mensagem de flash para a próxima requisição
     */
    public static function setFlash(string $type, string $message): void
    {
        self::startSession();
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * Recupera a mensagem de flash e limpa após leitura
     */
    public static function getFlash(): ?array
    {
        self::startSession();

        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);

            return $flash;
        }

        return null;
    }

    /**
     * Verificar método HTTP
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}