<?php

namespace Organo\Test\ProvedorDados;


class ManipuladorDeDados
{

    private static $dados = [];

    public static function definir(string $chave, $valor):void
    {
        self::$dados[$chave] = $valor;
    }

    public static function obter(string $chave, $valorPadrao = null)
    {   
        if (isset(self::$dados[$chave]))
            return self::$dados[$chave];

        return $valorPadrao;
    }

    public static function remover(string $chave):void
    {
        unset(self::$dados[$chave]);
    }

}