<?php

// -----------------------------
// URLS E PATHS
// -----------------------------
define('URL_BASE',         '');                    // '' = raiz do domínio com VirtualHost
define('URL_ASSETS_SITE',  '/assets/site/images');
define('URL_PRODUCAO',     'https://seu-dominio.com');
define('PUBLIC_PATH',      BASE_PATH . '/public');
define('APP_PATH',         BASE_PATH . '/app');
define('ROUTES_PATH',      BASE_PATH . '/routes');
define('VIEWS_PATH',       BASE_PATH . '/resources/views');

// -----------------------------
// INFORMAÇÕES DA EMPRESA
// -----------------------------
define('APP_NAME',         'Saeld Auto');
define('COMPANY_ADDRESS',  'Av. 21 de Janeiro, Ingonbota, Luanda');
define('COMPANY_PHONE',    '+244 923 000 000');
define('COMPANY_EMAIL',    'contato@saeldauto.com');
define('APP_ENV',          'development'); // muda para 'production' no deploy

// -----------------------------
// BASE DE DADOS
// -----------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'stand_cars_bd');
define('DB_USER', 'root');
define('DB_PASS', '');

// -----------------------------
// SESSÃO
// -----------------------------
define('SESSION_NAME',     'standcars_session');
define('SESSION_LIFETIME', 3600);

// -----------------------------
// GERAL
// -----------------------------
define('TIMEZONE',    'Africa/Luanda');
define('DEBUG',       true);   // true em dev, FALSE em produção
define('APP_VERSION', '1.0.0');
