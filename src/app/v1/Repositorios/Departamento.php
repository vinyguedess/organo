<?php

namespace Organo\v1\Repositorios;


use Organo\Core\Repositorio;


class Departamento extends Repositorio
{

    public $tabela = 'departamentos';

    public function obtem(int $recursivo = 0, int $apartir = 0):array
    {
        $departamentos = parent::obtem(100, $apartir);
        if (!$recursivo)
            return $departamentos;

        return $this->retornaDepartamentosFilhoDaLista($departamentos);
    }

    public function obtemPorId(int $id)
    {
        $departamento = parent::obtemPorId($id);
        if (is_null($departamento))
            return $departamento;

        $qb = $this->obterQueryBuilder();
        $expr = $qb->expr();

        $departamento['pai'] = $qb->select('*')
            ->from($this->tabela)
            ->where($expr->eq('id_departamento_pai', ':id'))
            ->setParameter('id', $departamento['id_departamento_pai'])
            ->execute()->fetch();

        $departamento['filhos'] = $this->obterQueryBuilder()
            ->select('*')
            ->from($this->tabela)
            ->where($expr->eq('id_departamento_pai', ':id'))
            ->setParameter('id', $id)
            ->execute()->fetchAll();

        $departamento['usuarios'] = $this->obterQueryBuilder()
                ->select('u.*')
                ->from('departamentos_usuarios')
                ->innerJoin('departamentos_usuarios', 'usuario', 'u', 'departamentos_usuarios.id_usuario = u.id')
                ->where($expr->eq('departamentos_usuarios.id_departamento', ':departamento'))
                ->setParameter('departamento', $id)
                ->execute()->fetchAll();

        return $departamento;
    }

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

    public function desatrelarUsuario(int $departamento_id, int $usuario_id):bool
    {
        try {
            if (!$this->validarAntesDeAtrelar($departamento_id, $usuario_id))
                return false;

            $qb = $this->obterQueryBuilder();
            $expr = $qb->expr();

            $qb->delete('departamentos_usuarios')
                ->where(
                    $expr->andX(
                        $expr->eq('id_departamento', ':dpto'),
                        $expr->eq('id_usuario', ':usuario')
                    )
                )
                ->setParameter('dpto', $departamento_id)
                ->setParameter('usuario', $usuario_id)
                ->execute();

            return true;
        } catch (Exception $ex) {
            $this->adicionarErro($ex->getMessage());
            return false;
        } finally {
            $this->desconectar();
        }
    }

    private function retornaDepartamentosFilhoDaLista(array $departamentos, int $id_pai = null)
    {
        $resultados = [];

        foreach ($departamentos as $departamento)
            if ($departamento['id_departamento_pai'] === $id_pai) {
                $departamento['filhos'] = $this->retornaDepartamentosFilhoDaLista($departamentos, $departamento['id']);
                $resultados[] = $departamento;
            }

        return $resultados;
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