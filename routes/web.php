<?php

use App\Core\View;
use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Middleware\AdminMiddleware;
use Pecee\Http\Request;


//
// ------------------------
// SITE PÚBLICO
// ------------------------
//
Router::group([
    'namespace' => 'App\Controllers\Public'
], function () {

    Router::get('/', 'HomeController@index');
    

    Router::get('/carros', 'CarrosController@index');
    Router::get('/veiculos', 'VeiculosController@index');
    Router::get('/veiculos/{id}', 'VeiculosController@show');

    Router::get('/contato', 'ContatoController@index');
    Router::get('/recomendacao', 'RecomendacaoController@index');
    Router::get('/sobre', 'SobreController@index');
    Router::get('/servicos', 'ServicosController@index');

    Router::get('/blog', 'BlogController@index');

    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/{id}', 'NoticiasController@show');
});

//
// ------------------------
// LOGIN ADMIN
// ------------------------
//
Router::group([
    'prefix' => '/admin',
    'namespace' => 'App\Controllers\Admin'
], function () {

    Router::get('/login', 'AuthController@loginForm');
    Router::post('/login', 'AuthController@login');
});

//
// ------------------------
// PAINEL ADMIN (PROTEGIDO)
// ------------------------
//
Router::group([
    'prefix' => '/admin',
    'namespace' => 'App\Controllers\Admin',
    'middleware' => AdminMiddleware::class
], function () {

    // Dashboard
    Router::get('/', 'DashboardController@index');

    //
    // VEÍCULOS (REST)
    //
    Router::get('/veiculos', 'VeiculosController@index');          // listar
    Router::get('/veiculos/create', 'VeiculosController@create');  // form (opcional)
    Router::post('/veiculos', 'VeiculosController@store');         // salvar
    Router::get('/veiculos/{id}', 'VeiculosController@show');      // detalhes
    Router::get('/veiculos/{id}/edit', 'VeiculosController@edit'); // editar form
    Router::put('/veiculos/{id}', 'VeiculosController@update');    // atualizar
    Router::delete('/veiculos/{id}', 'VeiculosController@delete'); // deletar

    //
    // CLIENTES (REST)
    //
    Router::get('/clientes', 'ClientesController@index');
    Router::post('/clientes', 'ClientesController@store');
    Router::get('/clientes/{id}/edit', 'ClientesController@edit');
    Router::put('/clientes/{id}', 'ClientesController@update');
    Router::delete('/clientes/{id}', 'ClientesController@delete');

    //
    // NOTÍCIAS (REST)
    //
    Router::get('/noticias', 'NoticiasController@index');
    Router::get('/noticias/create', 'NoticiasController@create');
    Router::post('/noticias', 'NoticiasController@store');
    Router::get('/noticias/{id}/edit', 'NoticiasController@edit');
    Router::put('/noticias/{id}', 'NoticiasController@update');
    Router::delete('/noticias/{id}', 'NoticiasController@delete');

    //
    // OUTROS
    //
    Router::get('/vendas', 'VendasController@index');
    Router::post('/vendas', 'VendasController@store');
    Router::get('/relatorios', 'RelatoriosController@index');

    Router::get('/website', 'SiteController@index');
    Router::get('/website/configuracoes', 'SiteController@configuracoes');

    Router::get('/perfil', 'PerfilController@index');

    Router::get('/logout', 'AuthController@logout');
});

//
// ------------------------
// ERROS
// ------------------------
//
Router::error(function (Request $request, \Throwable $exception) {

    // Sempre loga o erro (boa prática)
    error_log($exception->getMessage());

    // MODO DESENVOLVIMENTO
    if (DEBUG) {

        http_response_code(500);

        echo "<h1>Erro:</h1>";
        echo "<p><strong>Mensagem:</strong> " . $exception->getMessage() . "</p>";
        echo "<p><strong>Arquivo:</strong> " . $exception->getFile() . "</p>";
        echo "<p><strong>Linha:</strong> " . $exception->getLine() . "</p>";

        echo "<pre>";
        print_r($exception->getTrace());
        echo "</pre>";

        return;
    }

    // PRODUÇÃO (usuário nunca vê erro técnico)
    if ($exception->getCode() === 404) {
        http_response_code(404);

        View::render('errors/404', [
            'message' => 'Página não encontrada.'
        ]);
    } else {
        http_response_code(500);

        View::render('errors/500', [
            'message' => 'Erro interno. Tente novamente mais tarde.'
        ]);
    }
});