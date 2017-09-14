<?php

namespace Organo\Test\Unitario\v1\Repositorios;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Organo\v1\Repositorios\Usuario;
use PHPUnit\Framework\TestCase;


class UsuarioTest extends TestCase
{
 
    public function setUp()
    {
        $this->conn = DriverManager::getConnection([
            'driver' => getenv('ORGANO_DB_DRIVER'),
            'host' => getenv('ORGANO_DB_SERVIDOR'),
            'port' => getenv('ORGANO_DB_PORTA'),
            'dbname' => getenv('ORGANO_DB_NOME'),
            'user' => getenv('ORGANO_DB_USUARIO'),
            'password' => getenv('ORGANO_DB_SENHA')
        ], new Configuration());
    }

    public function testInserirDados()
    {
        $usuario = ['nome' => 'Vinicius Guedes'];
        $resultado = (new Usuario($this->conn))
            ->inserir($usuario);
        
        $this->assertTrue($resultado);
        $this->assertArrayHasKey('id', $usuario);
    }

    public function testErroAoInserirDados()
    {
        $usuario = ['status' => 0];

        $repositorio = new Usuario($this->conn);

        $this->assertFalse($repositorio->inserir($usuario));
    }

    public function testContaDados()
    {
        $repositorio = new Usuario($this->conn);
        $this->assertGreaterThanOrEqual(1, $repositorio->conta());
    }

    public function testConsultaUsuarioPorId()
    {
        $repositorio = new Usuario($this->conn);

        $usuario = $repositorio->obtem(1, 0)[0];
        $usuarioConsultado = $repositorio->obtemPorId($usuario['id']);

        $this->assertEquals($usuario['id'], $usuarioConsultado['id']);
    }

    public function testConsultaUsuarioInexistente()
    {
        $usuario = (new Usuario($this->conn))->obtemPorId(999);        
        $this->assertNull($usuario);
    }

    public function testRemocaoDeDadosPorId()
    {
        $repositorio = new Usuario($this->conn);
        $usuario = $repositorio->obtem(1, 0)[0];

        $this->assertTrue($repositorio->removerPorId($usuario['id']));

        $resultado = $repositorio->remover(["id > ?"], [0]);
    }

}