<?php

namespace Organo\Test\Funcional\v1;


use Organo\Test\Funcional\WebTestCase;
use Organo\Test\ProvedorDados\ManipuladorDeDados;
use Symfony\Component\HttpFoundation\JsonResponse;


class DepartamentosControllerTest extends WebTestCase
{

    public function testCriacaoDeDepartamentos()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/v1/departamentos', [
            'departamento' => ['nome' => 'Presidencia']
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);

        ManipuladorDeDados::definir('departamento.id', $resposta['data']['id']);
    }

    public function testErroNaCriacaoDeDepartamentos()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/v1/departamentos', [
            'departamento' => ['nm' => 'Presidencia']
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testAtualizacaoDeDepartamentos()
    {
        $id = ManipuladorDeDados::obter('departamento.id');
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/departamentos/{$id}", [
            'departamento' => ['nome' => 'Diretoria']
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
    }

    public function testErroNaAtualizacaoDeDepartamentoInexistente()
    {
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/departamentos/-8", [
            'departamento' => ['nome' => 'Presidencia']
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testErroNaAtualizacaoDeDepartamentos()
    {
        $id = ManipuladorDeDados::obter('departamento.id');
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/departamentos/{$id}", [
            'departamento' => ['nm' => 'Presidencia']
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testConsultarDepartamentoPorId()
    {
        $id = ManipuladorDeDados::obter('departamento.id');
        $client = $this->createClient();
        $client->request('GET', "/api/v1/departamentos/{$id}");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
    }

    public function testErroAoConsultarDepartamentoPorIdInexistente()
    {
        $client = $this->createClient();
        $client->request('GET', "/api/v1/departamentos/-8");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testExcluirDepartamento()
    {
        $id = ManipuladorDeDados::obter('departamento.id');
        
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/departamentos/{$id}");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
    }

    public function testErroAoExcluirDepartamentoInexistente()
    {
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/departamentos/-8");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testErroAoExcluirDepartamento()
    {
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/departamentos/abc");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

}