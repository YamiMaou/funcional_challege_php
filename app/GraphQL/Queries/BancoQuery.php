<?php

namespace App\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BancoQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }

    public function saldo($rootValue, array $args, GraphQLContext $context)
    {
        $conta = \App\Models\Banco::where(["conta" => $args['conta']])->first();

        return $conta->saldo;
    }
}
