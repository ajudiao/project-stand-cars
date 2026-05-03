<?php

use App\Core\View;
use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Middleware\AdminMiddleware;
use Pecee\Http\Request;

// ------------------------
// SITE PÚBLICO
// ------------------------
Router::group(['namespace' => 'App\Controllers\Public'], function () {
    Router::get('/', 'HomeController@index');
    Router::get('/veiculos', 'VeiculosController@index');
    Router::get('/veiculos/{id}', 'VeiculosController@show');
    Router::get('/contato', 'ContatoController@index');
    Router::get('/recomendacao', 'RecomendacaoController@index');
    Router::get('/sobre', 'SobreController@index');
    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/{id}', 'NoticiasController@show');
});

// ------------------------
// LOGIN ADMIN
// ------------------------
Router::group([
    'prefix'    => '/admin',
    'namespace' => 'App\Controllers\Admin'
], function () {
    Router::get('/login', 'AuthController@loginForm');
    Router::post('/login', 'AuthController@login');
});

// ------------------------
// PAINEL ADMIN (PROTEGIDO)
// ------------------------
Router::group([
    'prefix'     => '/admin',
    'namespace'  => 'App\Controllers\Admin',
    'middleware' => AdminMiddleware::class
], function () {

    // Dashboard
    Router::get('/', 'DashboardController@index');
    Router::get('/dashboard/get-sales-data', 'DashboardController@getSalesData');
    Router::get('/notificacoes', 'NotificationsController@index');

    // VEÍCULOS — estáticas antes de {id}
    Router::get('/automoveis', 'AutomoveisController@index');
    Router::get('/automoveis/busca', 'AutomoveisController@buscar');
    Router::get('/automoveis/show/{id}', 'AutomoveisController@show');
    Router::get('/automoveis/delete/{id}', 'AutomoveisController@delete');
    Router::get('/automoveis/{id}/edit', 'AutomoveisController@edit');
    Router::post('/automoveis', 'AutomoveisController@store');
    Router::post('/automoveis/update/{id}', 'AutomoveisController@update');

    // CLIENTES — estáticas antes de {id}
    Router::get('/clientes', 'ClientesController@index');
    Router::get('/clientes/busca', 'ClientesController@buscar');
    Router::get('/clientes/show/{id}', 'ClientesController@show');
    Router::get('/clientes/delete/{id}', 'ClientesController@delete');
    Router::get('/clientes/{id}/edit', 'ClientesController@edit');
    Router::post('/clientes', 'ClientesController@store');
    Router::post('/clientes/update/{id}', 'ClientesController@update');

    // NOTÍCIAS
    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/create', 'NoticiasController@create');
    Router::get('/noticias/{id}/edit', 'NoticiasController@edit');
    Router::post('/noticias/store', 'NoticiasController@store');
    Router::post('/noticias/{id}/update', 'NoticiasController@update');
    Router::post('/noticias/{id}/delete', 'NoticiasController@delete');

    // VENDAS — estáticas antes de {id}
    Router::get('/vendas', 'VendasController@index');
    Router::get('/vendas/busca', 'VendasController@buscar');
    Router::get('/vendas/show/{id}', 'VendasController@show');
    Router::get('/vendas/delete/{id}', 'VendasController@delete');
    Router::get('/vendas/{id}/recibo', 'VendasController@receipt');
    Router::post('/vendas', 'VendasController@store');
    Router::post('/vendas/update/{id}', 'VendasController@update');

    // RELATÓRIOS
    Router::get('/relatorios', 'RelatoriosController@index');
    Router::get('/relatorios/generate', 'RelatoriosController@generate');
    Router::post('/relatorios/custom-report', 'RelatoriosController@customReport');

    // USUÁRIOS
    Router::get('/usuarios', 'UsuariosController@index');
    Router::post('/usuarios', 'UsuariosController@store');
    Router::post('/usuarios/update/{id}', 'UsuariosController@update');
    Router::get('/usuarios/delete/{id}', 'UsuariosController@delete');

    // CONFIGURAÇÕES
    Router::get('/configuracoes', 'DashboardController@configuracoes');
    Router::post('/configuracoes/backup', 'DashboardController@backup');
    Router::post('/configuracoes/delete-all', 'DashboardController@deleteAllData');
    Router::get('/configuracoes/export', 'DashboardController@exportCsv');
    Router::get('/website/configuracoes', 'SiteController@configuracoes');
    Router::post('/website/configuracoes/salvar', 'SiteController@salvarConfiguracao');

    // PERFIL
    Router::get('/perfil', 'PerfilController@index');
    Router::post('/perfil/foto', 'PerfilController@updatePhoto');
    Router::post('/perfil/senha', 'PerfilController@changePassword');

    Router::get('/logout', 'AuthController@logout');
});

// ------------------------
// ERROS
// ------------------------
Router::error(function (Request $request, \Throwable $exception) {

    error_log('[stand-cars] ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());

    $is404 = $exception->getCode() === 404
        || strpos($exception->getMessage(), 'Route not found') !== false;

    if ($is404) {
        http_response_code(404);
        View::render('errors/404', ['message' => 'Página não encontrada.']);
        return;
    }

    if (defined('DEBUG') && DEBUG) {
        http_response_code(500);
        echo '<h1>Erro 500</h1>';
        echo '<p><strong>Mensagem:</strong> ' . htmlspecialchars($exception->getMessage()) . '</p>';
        echo '<p><strong>Arquivo:</strong> ' . htmlspecialchars($exception->getFile()) . '</p>';
        echo '<p><strong>Linha:</strong> ' . $exception->getLine() . '</p>';
        $trace = $exception->getTrace();
        $limitedTrace = array_slice($trace, 0, 10); // Limita a 10 entradas para evitar uso excessivo de memória
        echo '<pre>' . htmlspecialchars(print_r($limitedTrace, true)) . '</pre>';
        return;
    }

    http_response_code(500);
    View::render('errors/500', ['message' => 'Erro interno. Tente novamente mais tarde.']);
});
