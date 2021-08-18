# Funcional PHP Challenge

## Objetivo
Desenvolver uma API em PHP + Laravel que simule algumas funcionalidades de um banco digital.
Nesta simulação considere que não há necessidade de autenticação.

## Desafio
Você deverá garantir que o usuário conseguirá realizar uma movimentação de sua conta corrente para quitar uma dívida.

## Introdução 

Utilizando PHP + Laravel + Postgres + GraphQL, foi desenvolvido uma API pública que simula uma simples movimentação de conta bancária.

# Configuração

o projeto depende de alguns serviços, como o php-fpm, postgres e nginx, dito isso algumas portas são necessárias para que o projeto funcione corretamente, sendo elas a 8000 e 5432
será necessário rodar alguns comandos no seu cmd/terminal e ter o docker instalado.

no diretório raíz do projeto rode os seguintes comandos no seu cmd/terminal para criarmos o arquivo de variaveis de ambiente do projeto nomedo como ```.env```: 

``` mv .env.example .env ``` 

este comando irá renomear o arquivo de exemplo existente, fazendo com que este seja o arquivo usual do projeto. 
o mesmo ja está configurado para fúncionar com o container que criaremos a seguir.

execute o seguinte snipet em seu cmd/terminal :

``` docker-compose up -d --build ```

feito isso caso não ocorra nenhum problema, o docker irá baixar e configurar as novas dependencias para rodar o projeto.

após o fim da execução em seu navegador, em seu client GraphQL preencha o edereço do servidor com o host : 

```[http://localhost:8000/graphql](http://localhost:8000/graphql)```

caso não tenha um client acesse o seguinte endereço 

```[http://localhost:8000/graphql-playground](http://localhost:8000/graphql-playground)```


agora poderemos testar nossas requisições.

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

