# Funcional PHP Challenge

## Objetivo
Desenvolver uma API em PHP + Laravel que simule algumas funcionalidades de um banco digital.
Nesta simulação considere que não há necessidade de autenticação.

## Desafio
Você deverá garantir que o usuário conseguirá realizar uma movimentação de sua conta corrente para quitar uma dívida.

## Introdução 

Utilizando PHP + Laravel + Postgres + GraphQL, foi desenvolvido uma API pública que simula uma simples movimentação de conta bancária.


## Requisições

Minha primeira vez utilizando GraphQL, seguindo a sujestão do teste, obtive o seguinte resultado :

**Realizando Saque de saldo quando Disponível**
para efetuar um saque, basta enviar obrigatóriamente os parâmetros ```conta(int)```, ```valor(float)``` para a mutation ```sacar```

Requisição:
```
mutation {
  sacar(conta: 54321, valor: 500) {
    conta
    saldo
  }
}
```

Resposta:

```
{
  "data": {
    "sacar": {
      "conta": 54321,
      "saldo": 20
    }
  }
}
```
a mutation irá validar o valor do saque, caso seja menor que o valor disponível em conta, o saldo da conta será subtraido pelo valor informado ao parâmetro, e retornará os dados atualizados.

**Realizando Saque de saldo quando não Disponível**
da mesma forma, quando o parâmetro valor informado for maior, que o valor disponível em conta

Requisição

```
mutation {
  sacar(conta: 54321, valor: 21) {
    conta
    saldo
  }
}
```
Resposta:
 
```
{
  "errors": [
    {
      "message": "Saldo insuficiente.",
      ...
    }
  ]
}
}
```
uma menssagem de erro será retornada, e nenhum registro será atualizado.

**Efetuado Depósito em conta**
para efetuar um deposito, basta enviar obrigatóriamente os parâmetros ```conta(int)```, ```valor(float)``` para a mutation ```deposito``` como no modelo de saque.

Requisição:

```
mutation {
  depositar(conta: 508427, valor: 100) {
    conta
    saldo
  }
}
```

Resposta:

```
{
  "data": {
    "depositar": {
      "conta": 54321,
      "saldo": 121
    }
  }
}
```
a mutation irá efetuar um deposito e somar com o saldo da conta existente, caso ela não exista, irá criar uma, com o saldo igual ao valor informado.

**Consulta de Saldo**
para efetuar a consulta de saldo, basta enviar obrigatóriamente o parâmetros ```conta(int)```, para a query ```saldo```

Requisição:

```
query {
  saldo(conta: 54321)
}
```

Resposta:

```
{
  "data": {
    "saldo": 121
  }
}
```

