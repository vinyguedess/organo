<?php

namespace Organo\v1\Controllers;


use Organo\Core\Controller;
use Organo\v1\Repositorios\Usuario;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UsuariosController extends Controller
{

    protected $routes = [
        'get' => [
            '/api/v1/usuarios' => 'indexAction',
            '/api/v1/usuarios/{id}' => 'viewAction'
        ]
    ];

    protected function createAction(Application $app, Request $request)
    {

    }

    protected function indexAction(Application $app, Request $request)
    {
        $usuario = new Usuario($app['db']);

        return new JsonResponse([
            'status' => true,
            'total' => $usuario->conta(),
            'data' => $usuario->obtem(
                    $request->get('limit', 100), 
                    $request->get('offset', 0)
                )
        ], JsonResponse::HTTP_OK);
    }

    protected function viewAction(Application $app, Request $request)
    {
        $usuario = (new Usuario($app['db']))
            ->obtemPorId($request->get('id'));

        if (!$usuario)
            return new JsonResponse([
                'status' => false, 'message' => 'Usuário não encontrado'
            ], JsonResponse::HTTP_NOT_FOUND);

        return new JsonResponse([
            'status' => true,
            'data' => $usuario
        ], JsonResponse::HTTP_OK);
    }

}