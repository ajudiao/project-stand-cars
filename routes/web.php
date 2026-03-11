<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Middleware\AdminMiddleware;
use Pecee\SimpleRouter\Route\Route;

// ------------------------
// SITE PÚBLICO
// ------------------------

Router::group([
    'prefix' => '/',
    'namespace' => 'App\Controllers\Public'
], function () {

    Router::get('/', 'HomeController@index');
    Router::get('/carros', 'CarrosController@index');
    Router::get('/contato', 'ContatoController@index');
    Router::get('/veiculos', 'VeiculosController@index');
    Router::get('/veiculos/{id}', 'VeiculosController@show');
    Router::get('/recomendacao', 'RecomendacaoController@index');
    Router::get('/sobre', 'SobreController@index');
    Router::get('/servicos', 'ServicosController@index');
    Router::get('/blog', 'BlogController@index');
    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/{id}', 'NoticiasController@show');
});


// ------------------------
// LOGIN ADMIN
// ------------------------

Router::group([
    'prefix' => '/admin',
    'namespace' => 'App\Controllers\Admin'
], function () {

    Router::get('/login', 'AuthController@loginForm');
    Router::post('/login', 'AuthController@login');

});


// ------------------------
// PAINEL ADMIN PROTEGIDO
// ------------------------

Router::group([
    'prefix' => '/admin',
    'namespace' => 'App\Controllers\Admin',
    'middleware' => AdminMiddleware::class
], function () {

    Router::get('/', 'DashboardController@index');
    Router::get('/veiculos', 'VeiculosController@index');
    Router::get('/veiculos/criar', 'VeiculosController@create');
    Router::get('/clientes', 'ClientesController@index');
    Router::get('/vendas', 'VendasController@index');
    Router::get('/relatorios', 'RelatoriosController@index');
    Router::get('/configuracoes', 'ConfiguracoesController@index');
    Router::get('/logout', 'AuthController@logout');
    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/criar', 'NoticiasController@create');
    Router::get('/noticias/editar/{id}', 'NoticiasController@edit');
    Router::get('/noticias/excluir/{id}', 'NoticiasController@delete');
    Router::get('/veiculos/editar/{id}', 'VeiculosController@edit');
    Router::get('/veiculos/excluir/{id}', 'VeiculosController@delete');
    Router::get('/clientes/editar/{id}', 'ClientesController@edit');
    Router::get('/website', 'SiteController@index');
    Router::get('/perfil', 'PerfilController@index');

});


// ------------------------
// ERRO 404
// ------------------------

Router::error(function($exception) {
    http_response_code(404);
    echo "Ops! A página que você tentou acessar não existe.";
});