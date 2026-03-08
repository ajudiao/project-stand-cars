<?php

use Pecee\SimpleRouter\SimpleRouter;

//SimpleRouter::setBasePath('/stand-cars/public');

// carregar rotas
require ROUTES_PATH . '/web.php';
require ROUTES_PATH . '/admin.php';

// iniciar router
SimpleRouter::start();

