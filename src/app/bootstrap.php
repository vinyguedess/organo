<?php

use Organo\v1\Controllers\UsuariosController;
use Silex\Application;


require __DIR__ . '/../../vendor/autoload.php';


$app = new Application();

require __DIR__ . '/registers.php';


$app->get('/', function() {
    return "Bem vindo ao Organo";
});
$app->mount('', new UsuariosController);


return $app;