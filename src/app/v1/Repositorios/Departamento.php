<?php

namespace Organo\v1\Repositorios;


use Organo\Core\Repositorio;


class Departamento extends Repositorio
{

    public $tabela = 'departamentos';

    public function atrelarUsuario(int $departamento_id, int $usuario_id):bool
    {
        try {
            if (!$this->validarAntesDeAtrelar($departamento_id, $usuario_id))
                return false;

            $qb = $this->obterQueryBuilder();
            $expr = $qb->expr();
            if ($this->usuarioJaFoiAtreladoAntes($usuario_id))
                $resultado = $qb->update('departamentos_usuarios')
                    ->set('id_departamento', ':dpto')->setParameter('dpto', $departamento_id)
                    ->where($expr->eq('id_usuario', ':usuario'))->setParameter('usuario', $usuario_id)
                    ->execute();
            else
                $resultado = $qb->insert('departamentos_usuarios')
                    ->setValue('id_departamento', ':dpto')->setParameter('dpto', $departamento_id)
                    ->setValue('id_usuario', ':usuario')->setParameter('usuario', $usuario_id)
                    ->execute();

            return $resultado > 0;
        } catch (Exception $ex) {
            $this->adicionarErro($ex->getMessage());
            return false;
        } finally {
            $this->desconectar();
        }
    }

    private function validarAntesDeAtrelar(int $departamento_id, int $usuario_id):bool
    {
        $expr = $this->obterQueryBuilder()->expr();

        $existeDepartamento = $this->obterQueryBuilder()
            ->select('COUNT(*) total')
            ->from($this->tabela)
            ->where($expr->eq('id', ':id'))
            ->setParameter('id', $departamento_id)
            ->execute()
            ->fetch();
        if (!$existeDepartamento['total']) {
            $this->adicionarErro('Departamento não existe');
            return false;
        }

        $existeUsuario = $this->obterQueryBuilder()
            ->select('COUNT(*) total')
            ->from('usuario')
            ->where($expr->eq('id', ':id'))
            ->setParameter('id', $usuario_id)
            ->execute()
            ->fetch();
        if (!$existeUsuario['total']) {
            $this->adicionarErro('Usuário não existe');
            return false;
        }

        return true;
    }

    private function usuarioJaFoiAtreladoAntes(int $usuario_id):bool
    {
        $qb = $this->obterQueryBuilder();
        $expr = $qb->expr();

        $usuarioJaFoiAtreladoAntes = $qb->select('COUNT(*) total')
            ->from('departamentos_usuarios')
            ->where($expr->eq('id_usuario', ':id'))
            ->setParameter('id', $usuario_id)
            ->execute()
            ->fetch();

        return $usuarioJaFoiAtreladoAntes['total'] > 0;
    }

}