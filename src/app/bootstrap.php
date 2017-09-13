<?php

use Organo\v1\Controllers\UsuariosController;
use Silex\Application;

require_once __DIR__ . '/../../vendor/autoload.php';


$app = new Application();


$app->get('/', function() {

    return "Bem vindo ao Organo";
});

$app->mount('', new UsuariosController);


return $app;