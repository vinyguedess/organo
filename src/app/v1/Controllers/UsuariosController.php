<?php

namespace Organo\v1\Controllers;


use Organo\Core\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;


class UsuariosController extends Controller
{

    protected $routes = [
        'get' => [
            '/api/v1/usuarios' => 'indexAction'
        ]
    ];

    protected function indexAction()
    {
        return new JsonResponse([
            'status' => true,
            'total' => 0,
            'data' => []
        ], JsonResponse::HTTP_OK);
    }

}