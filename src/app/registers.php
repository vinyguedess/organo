<?php

use Silex\Provider\DoctrineServiceProvider;


if (!function_exists('get_env')) {
    function get_env($chave, $respostaPadrao = null)
    {
        $padrao = "/{$chave}\=(.*)\n/";

        $arquivo = file_get_contents(__DIR__ . '/../../.env');
        preg_match($padrao, $arquivo, $match);

        if ($match)
            return $match[1];

        return $respostaPadrao;
    }
}


$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => get_env('ORGANO_DB_DRIVER'),
        'host' => get_env('ORGANO_DB_SERVIDOR'),
        'port' => get_env('ORGANO_DB_PORTA'),
        'dbname' => get_env('ORGANO_DB_NOME'),
        'user' => get_env('ORGANO_DB_USUARIO'),
        'password' => get_env('ORGANO_DB_SENHA')
    ]
]);