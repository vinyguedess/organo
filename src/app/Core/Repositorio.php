<?php 

namespace Organo\Core;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;


abstract class Repositorio 
{

    private $conn;
    private $erros = [];

    public function __construct(Connection $db)
    {
        $this->conn = $db;
    }

    public function inserir(&$atributos)
    {
        $qb = $this->obterQueryBuilder()
            ->insert($this->tabela);

        foreach ($atributos as $atributo => $valor)
            $qb->setValue($atributo, ":{$atributo}")
                ->setParameter($atributo, $valor);

        try {
            $qb->execute();
            
            $atributos['id'] = $this->conn->lastInsertId();
            return true;
        } catch (\Exception $ex) {
            $this->adicionarErro($ex->getMessage());
            return false;
        }
    }

    public function atualizar($atributos)
    {
        $qb = $this->obterQueryBuilder()
            ->update($this->tabela);

        foreach ($atributos as $atributo => $valor) { 
            if ($atributo === 'id') {
                $qb->where("id = :{$atributo}")
                    ->setParameter($atributo, $valor);
                continue;
            }

            $qb->set($atributo, ":{$atributo}")
                ->setParameter($atributo, !$valor ? 0 : $valor);
        }

        try {
            if (!$qb->execute() > 0) {
                $this->adicionarErro("Erro ao atualizar registro inexistente");
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            $this->adicionarErro($ex->getMessage());
            return false;
        }
    }

    public function conta():int
    {
        $resultado = $this->obterQueryBuilder()
            ->select('COUNT(*) total')
            ->from($this->tabela)
            ->execute()
            ->fetch();

        return $resultado['total'];
    }

    public function obtem(int $limite = 10, int $apartir = 0):Array
    {
        return $this->conn->fetchAll(
            $this->obterQueryBuilder()
                ->select('*')
                ->from($this->tabela)
                ->setMaxResults($limite)
                ->setFirstResult($apartir)
                ->getSql()
        );
    }

    public function obtemPorId(int $id)
    {
        $qb = $this->obterQueryBuilder();
        $expr = $qb->expr();

        $resultado = $qb->select('*')
            ->from($this->tabela)
            ->where($expr->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$resultado) return null;

        return $resultado;
    }

    public function removerPorId($id)
    {
        $qb = $this->obterQueryBuilder();
        $expr = $qb->expr();
        
        $qb->delete($this->tabela)
            ->where($expr->eq('id', ':id'))
            ->setParameter('id', $id);

        try {
            if (!$qb->execute() > 0) {
                $this->adicionarErro("Erro ao atualizar registro inexistente");
                return false;
            }

            return true;
        } catch (\Exception $ex) {
            $this->adicionarErro($ex->getMessage());
            return false;
        }
    }

    public function remover(array $filtros = [], $valores = [])
    {
        $qb = $this->obterQueryBuilder()
            ->delete($this->tabela);

        foreach ($filtros as $index => $filtro)
            $qb->where($filtro)->setParameter($index, $valores[$index]);

        return $qb->execute() > 0;
    }

    public function adicionarErro(string $mensagem):void
    {
        $this->erros[] = $mensagem;
    }

    public function obterErros():array
    {
        return $this->erros;
    }

    public function obterErro(int $index)
    {
        return isset($this->erros[$index]) ? $this->erros[$index] : null;
    }

    protected function obterQueryBuilder():QueryBuilder
    {
        return $this->conn->createQueryBuilder();
    }

}