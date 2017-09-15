# Organo
[![Build Status](https://travis-ci.org/vinyguedess/organo.svg?branch=master)](https://travis-ci.org/vinyguedess/organo)
[![Codecov](https://img.shields.io/codecov/c/github/vinyguedess/organo.svg)](https://codecov.io/gh/vinyguedess/organo)<br />
API para gerenciamento de organograma de uma empresa.<br />
[Organo](http://organo.herokuapp.com)

## Instalação

### Requerido
Ambiente básico para rodar a aplicação
* Apache2
* PHP 7
* PostgreSQL, MySQL ou MariaDB

### Configurações
Primeiro é necessário criar um arquivo .env na raiz do projeto ou adicionar ao ambiente as seguintes variaveis:
* ORGANO_DB_DRIVER - Representa o driver de banco de dados que será utilizado
* ORGANO_DB_SERVIDOR
* ORGANO_DB_PORTA
* ORGANO_DB_USUARIO
* ORGANO_DB_SENHA
* ORGANO_DB_NOME - Representa o nome do banco de dados que será utilizado

### Comandos
```bash
    composer install
```

## Rotas
Rotas contempladas na API


### Departamentos
* **POST** /api/v1/departamentos        Cria um departamento<br />
* **PUT**  /api/v1/departamentos/{id}   Atualiza um departamento selecionado<br />
* **GET**  /api/v1/departamentos/{id}   Exibe os dados do departamento selecionado<br />
* **DEL**  /api/v1/departamentos/{id}   Deleta um departamento selecionado

### Usuários
* **POST** /api/v1/usuarios        Cria um usuário<br />
* **PUT**  /api/v1/usuarios/{id}   Atualiza um usuário selecionado<br />
* **GET**  /api/v1/usuarios        Lista usuários<br />
* **GET**  /api/v1/usuarios/{id}   Exibe os dados do usuário selecionado<br />
* **DEL**  /api/v1/usuarios/{id}   Deleta um usuário selecionado

### Atrelar usuário à departamento
* **POST** /api/v1/departamentos/{dpto_id}/atrelar/{usuario_id} Atrelar o usuário selecionado ao departamento<br />
* **DEL**  /api/v1/departamentos/{dpto_id}/atrelar/{usuario_id} Desatrelar o usuário selecionado do departamento
