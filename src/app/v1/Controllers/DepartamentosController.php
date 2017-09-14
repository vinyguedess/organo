<?php

namespace Organo\v1\Controllers;


use Organo\Core\Controller;
use Organo\v1\Repositorios\Departamento;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DepartamentosController extends Controller
{
    
    public $routes = [
        'post' => [
            '/api/v1/departamentos' => 'createAction'
        ],
        'put' => [
            '/api/v1/departamentos/{id}' => 'updateAction'
        ],
        'get' => [
            '/api/v1/departamentos/{id}' => 'viewAction'
        ],
        'delete' => [
            '/api/v1/departamentos/{id}' => 'deleteAction'
        ]
    ];

    protected function createAction(Application $app, Request $request)
    {
        $departamento = $request->get('departamento', []);

        $repositorio = new Departamento($app['db']);
        if (!$repositorio->inserir($departamento))
            return new JsonResponse([
                'status' => false,
                'message' => $repositorio->obterErros()
            ], JsonResponse::HTTP_BAD_REQUEST);    

        return new JsonResponse([
            'status' => true,
            'data' => $departamento
        ], JsonResponse::HTTP_OK);
    }

    protected function updateAction(Application $app, Request $request)
    {
        $departamento = $request->get('departamento', []);
        $departamento['id'] = $request->get('id');

        $repositorio = new Departamento($app['db']);
        if (!$repositorio->atualizar($departamento))
            return new JsonResponse([
                'status' => false,
                'message' => $repositorio->obterErros()
            ], $repositorio->obterErro(0) === "Erro ao atualizar registro inexistente" ? 
                JsonResponse::HTTP_NOT_FOUND : 
                JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse(['status' => true], JsonResponse::HTTP_OK);
    }

    protected function viewAction(Application $app, Request $request)
    {
        $repositorio = new Departamento($app['db']);
        $departamento = $repositorio->obtemPorId($request->get('id'));
        if (is_null($departamento))
            return new JsonResponse([
                'status' => false, 'message' => ['Usuário não encontrado']
            ], JsonResponse::HTTP_NOT_FOUND);

        return new JsonResponse(['status' => true, 'data' => $departamento], JsonResponse::HTTP_OK);
    }

    protected function deleteAction(Application $app, Request $request)
    {
        $repositorio = new Departamento($app['db']);
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