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
        'delete' => [
            '/api/v1/usuarios/{id}' => 'deleteAction'
        ],
        'get' => [
            '/api/v1/usuarios' => 'indexAction',
            '/api/v1/usuarios/{id}' => 'viewAction'
        ],
        'post' => [
            '/api/v1/usuarios' => 'createAction'
        ],
        'put' => [
            '/api/v1/usuarios/{id}' => 'updateAction'
        ]
    ];

    protected function createAction(Application $app, Request $request)
    {
        $usuario = $request->get('usuario', []);

        $repositorio = new Usuario($app['db']);

        if (!$repositorio->inserir($usuario))
            return new JsonResponse([
                'status' => false,
                'message' => $repositorio->obterErros()
            ], JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse([
            'status' => true,
            'data' => $usuario
        ], JsonResponse::HTTP_OK);
    }

    protected function updateAction(Application $app, Request $request)
    {
        $usuario = $request->get('usuario', []);
        $usuario['id'] = $request->get('id');

        $repositorio = new Usuario($app['db']);

        if (!$repositorio->atualizar($usuario))
            return new JsonResponse([
                'status' => false,
                'message' => $repositorio->obterErros()
            ], $repositorio->obterErro(0) === "Erro ao atualizar registro inexistente" ? 
                JsonResponse::HTTP_NOT_FOUND : 
                JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse([
            'status' => true
        ], JsonResponse::HTTP_OK);
    }

    protected function indexAction(Application $app, Request $request)
    {
        $repositorio = new Usuario($app['db']);

        return new JsonResponse([
            'status' => true,
            'total' => $repositorio->conta(),
            'data' => $repositorio->obtem(
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

    protected function deleteAction(Application $app, Request $request)
    {
        $repositorio = new Usuario($app['db']);
        if (!$repositorio->removerPorId($request->get('id')))
            return new JsonResponse([
                'status' => false,
                'message' => $repositorio->obterErros()
            ], $repositorio->obterErro(0) === "Erro ao atualizar registro inexistente" ? 
                JsonResponse::HTTP_NOT_FOUND : 
                JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse(['status' => true], JsonResponse::HTTP_OK);
    }

}