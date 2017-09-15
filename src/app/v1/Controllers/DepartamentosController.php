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
        'delete' => [
            '/api/v1/departamentos/{id}' => 'deletarAction',
            '/api/v1/departamentos/{departamento_id}/atrelar/{usuario_id}' => 'desatrelarUsuarioAction'
        ],
        'get' => [
            '/api/v1/departamentos/{id}' => 'consultarAction',
            '/api/v1/departamentos' => 'listaAction'
        ],
        'post' => [
            '/api/v1/departamentos/{departamento_id}/atrelar/{usuario_id}' => 'atrelarUsuarioAction',
            '/api/v1/departamentos' => 'inserirAction'
        ],
        'put' => [
            '/api/v1/departamentos/{id}' => 'atualizarAction'
        ]
    ];

    protected function atrelarUsuarioAction(Application $app, Request $request)
    {
        $usuario = $request->get('usuario_id');
        $departamento = $request->get('departamento_id');

        $repositorio = new Departamento($app['db']);
        
        if (!$repositorio->atrelarUsuario($departamento, $usuario))
            return new JsonResponse([
                'status' => false
            ], JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse([
            'status' => true
        ], JsonResponse::HTTP_OK);
    }

    protected function desatrelarUsuarioAction(Application $app, Request $request)
    {
        $usuario = $request->get('usuario_id');
        $departamento = $request->get('departamento_id');

        $repositorio = new Departamento($app['db']);
        
        if (!$repositorio->desatrelarUsuario($departamento, $usuario))
            return new JsonResponse([
                'status' => false
            ], JsonResponse::HTTP_BAD_REQUEST);

        return new JsonResponse([
            'status' => true
        ], JsonResponse::HTTP_OK);
    }

    protected function inserirAction(Application $app, Request $request)
    {
        $conteudo = json_decode($request->getContent(), true);
        $departamento = $conteudo['departamento'];

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

    protected function listaAction(Application $app, Request $request)
    {
        $repositorio = new Departamento($app['db']);

        return new JsonResponse([
            'status' => true,
            'resultados' => $repositorio->obtem($request->get('recursive', 0))
        ], JsonResponse::HTTP_OK);
    }

    protected function atualizarAction(Application $app, Request $request)
    {
        $conteudo = json_decode($request->getContent(), true);

        $departamento = $conteudo['departamento'];
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

    protected function consultarAction(Application $app, Request $request)
    {
        $repositorio = new Departamento($app['db']);
        $departamento = $repositorio->obtemPorId($request->get('id'));
        if (is_null($departamento))
            return new JsonResponse([
                'status' => false, 'message' => ['Departamento não encontrado']
            ], JsonResponse::HTTP_NOT_FOUND);

        return new JsonResponse(['status' => true, 'data' => $departamento], JsonResponse::HTTP_OK);
    }

    protected function deletarAction(Application $app, Request $request)
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