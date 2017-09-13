<?php 

namespace Organo\Core;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;


abstract class Repositorio 
{

    private $conn;

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
            return false;
        }
    }

    public function conta():int
    {
        $resultado = $this->conn->fetchAssoc(
            $this->obterQueryBuilder()
                ->select('COUNT(*) total')
                ->from($this->tabela)
                ->getSql()
        );

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
        
        $resultado = $qb->delete($this->tabela)
            ->where($expr->eq('id', ':id'))
            ->setParameter('id', $id)
            ->execute();

        return $resultado > 0;
    }

    public function remover(array $filtros = [], $valores = [])
    {
        $qb = $this->obterQueryBuilder()
            ->delete($this->tabela);

        foreach ($filtros as $index => $filtro)
            $qb->where($filtro)->setParameter($index, $valores[$index]);

        return $qb->execute() > 0;
    }

    protected function obterQueryBuilder():QueryBuilder
    {
        return $this->conn->createQueryBuilder();
    }

}