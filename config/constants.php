<?php
// -----------------------------
// CONFIGURAÇÃO DE URL E PATHS
// -----------------------------

define('URL_BASE', '/stand-cars/public'); // URL base do aplicativo
define('URL_DESENVOLVIMENTO', 'http://localhost/stand-cars/public'); // URL para desenvolvimento
define('URL_PRODUCAO', 'https://seu-dominio.com'); // URL
define('PUBLIC_PATH', __DIR__ . '/../public');           // caminho absoluto da pasta public
define('APP_PATH', __DIR__ . '/../app');                 // caminho absoluto da pasta app
define('ROUTES_PATH', __DIR__ . '/../routes');          // pasta das rotas
define('VIEWS_PATH', __DIR__ . '/../../resources/views');         // pasta das views

// -----------------------------
// INFORMAÇÕES DO APLICATIVO
// -----------------------------
define('APP_NAME', 'Stand de Carros');
define('APP_ENV', 'development'); // 'production' quando for deploy

// -----------------------------
// BANCO DE DADOS
// -----------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'stand_cars');
define('DB_USER', 'root');
define('DB_PASS', ''); // senha do MySQL

// -----------------------------
// CONFIGURAÇÕES DE ROTEAMENTO
// -----------------------------
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_METHOD', 'index');

// -----------------------------
// CONFIGURAÇÕES DE SESSÃO
// -----------------------------
define('SESSION_NAME', 'standcars_session');
define('SESSION_LIFETIME', 3600); // 1 hora

// -----------------------------
// OUTRAS CONFIGURAÇÕES ÚTEIS
// -----------------------------
define('TIMEZONE', 'Africa/Luanda');   // fuso horário
define('DEBUG', true);                 // true para desenvolvimento
define('APP_VERSION', '1.0.0');        // versão do aplicativo