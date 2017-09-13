<?php

use Silex\Provider\DoctrineServiceProvider;


$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_pgsql',
        'host' => 'ec2-204-236-236-188.compute-1.amazonaws.com',
        'port' => 5432,
        'dbname' => 'dd237eg3m9o91p',
        'user' => 'dvclxndnfjymaw',
        'password' => '979d7c7c30aa6b6bd5d0b7c92cd6aadd43c78f8aa1d372cf4425ffe4e2a4f7cd'
    ]
]);