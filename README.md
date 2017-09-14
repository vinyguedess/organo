# Organo
[![Build Status](https://travis-ci.org/vinyguedess/organo.svg?branch=master)](https://travis-ci.org/vinyguedess/organo)<br />
API para gerenciamento de organograma de uma empresa.<br />
[Organo](http://organo.herokuapp.com)

## Rotas
Rotas contempladas na API

### Departamentos
*POST* /api/v1/departamentos        Cria um departamento<br />
*PUT*  /api/v1/departamentos/{id}   Atualiza um departamento selecionado<br />
*GET*  /api/v1/departamentos        Lista departamentos<br />
*GET*  /api/v1/departamentos/{id}   Exibe os dados do departamento selecionado<br />
*DEL*  /api/v1/departamentos/{id}   Deleta um departamento selecionado

### Usuários
*POST* /api/v1/usuarios        Cria um usuário<br />
*PUT*  /api/v1/usuarios/{id}   Atualiza um usuário selecionado<br />
*GET*  /api/v1/usuarios        Lista usuários<br />
*GET*  /api/v1/usuarios/{id}   Exibe os dados do usuário selecionado<br />
*DEL*  /api/v1/usuarios/{id}   Deleta um usuário selecionado
