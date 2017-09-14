<?php

namespace Organo\Test\Funcional\v1;


use Organo\Test\Funcional\WebTestCase;
use Organo\Test\ProvedorDados\ManipuladorDeDados;
use Symfony\Component\HttpFoundation\JsonResponse;


class UsuariosControllerTest extends WebTestCase
{

    public $usuario_id;

    public function testInserirUsuarios()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/v1/usuarios', [
            'usuario' => [
                'nome' => 'Vinicius Guedes'
            ]
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
        $this->assertArrayHasKey('id', $resposta['data']);

        ManipuladorDeDados::definir('usuario.id', $resposta['data']['id']);
    }

    public function testErroAoInserirUsuarios()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/v1/usuarios', [
            'usuario' => [
                'n' => 'Vinicius Guedes'
            ]
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testAtualizarUsuario()
    {
        $id = ManipuladorDeDados::obter('usuario.id');
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/usuarios/{$id}", [
            'usuario' => [ 'status' => false ]
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
    }

    public function testErroAoAtualizarUsuarioInexistente()
    {
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/usuarios/1", [
            'usuario' => [ 'status' => false ]
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testErroAoAtualizarUsuario()
    {
        $client = $this->createClient();
        $client->request('PUT', "/api/v1/usuarios/1", [
            'usuario' => [ 'st' => false ]
        ]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testConsultaUsuarios()
    {
        $client = $this->createClient();
        $client->request('GET', '/api/v1/usuarios', ['limit' => 5]);
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
        $this->assertLessThanOrEqual(5, count($resposta['data']));
    }

    public function testConsultaUsuarioPorId()
    {
        $id = ManipuladorDeDados::obter('usuario.id');

        $client = $this->createClient();
        $client->request('GET', "/api/v1/usuarios/{$id}");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
        $this->assertEquals($id, $resposta['data']['id']);
    }

    public function testErroAoConsultarUsuarioPorId()
    {
        $client = $this->createClient();
        $client->request('GET', "/api/v1/usuarios/1");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testExcluirUsuario()
    {
        $id = ManipuladorDeDados::obter('usuario.id');
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/usuarios/{$id}");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertTrue($resposta['status']);
    }

    public function testErroAoExcluirUsuarioInexistente()
    {
        $id = ManipuladorDeDados::obter('usuario.id');
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/usuarios/{$id}");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

    public function testErroAoExcluirUsuario()
    {
        $client = $this->createClient();
        $client->request('DELETE', "/api/v1/usuarios/abc");
        $resposta = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertFalse($resposta['status']);
    }

}