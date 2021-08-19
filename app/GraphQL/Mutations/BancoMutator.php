<?php

namespace App\GraphQL\Mutations;

use GraphQL\Error\Error;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BancoMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }

    public function deposito($rootValue, array $args, GraphQLContext $context)
    {
        $conta = \App\Models\Banco::firstOrNew(["conta" => $args['conta']], $args);
        $conta->saldo = ($conta->saldo ?? 0) + $args['valor'];
        $conta->save();

        return $conta;
    }

    public function saque($rootValue, array $args, GraphQLContext $context)
    {
        $conta = \App\Models\Banco::where(["conta" => $args['conta']])->first();
        if(!$conta)
            return new Error('Conta Inexistente');
        
        if($conta->saldo < $args['valor'] || $args['valor'] <= 0)
            return new Error('Saldo insuficiente.');
        
        $conta->saldo = ($conta->saldo - $args['valor']);
        $conta->save();

        return $conta;
    }
}
