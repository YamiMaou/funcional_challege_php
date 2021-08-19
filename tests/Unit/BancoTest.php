<?php

namespace Tests\Unit;

use App\Models\Banco;

class BancoTest extends \Tests\TestCase
{
    private $conta = 58994599;

    public function testDepositarMutation(): void
    {
        $valor = rand(1,9999);
        
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $response = $this->graphQL("
            mutation {
                depositar (conta: {$this->conta}, valor: {$valor}){
                    conta
                    saldo
                }
            }");

            $testSaldo = ($banco->saldo+$valor);

            if(!$banco){
                $banco = \App\Models\Banco::where([
                    'conta' => $this->conta
                ])->first();

                $testSaldo = ($banco->saldo+$valor);;
            }

        $this->assertEquals([$testSaldo],$response->json("data.*.saldo"));

        $this->assertEquals([$banco->conta],$response->json("data.*.conta"));
    }

    public function testSaqueDisponivelMutation(): void
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $valor = rand(1, $banco->saldo);

        $response = $this->graphQL("
            mutation {
                sacar (conta: {$banco->conta}, valor: {$valor}){
                    conta
                    saldo
                }
            }");
            
        $testValor = ($banco->saldo-$valor);
        $this->assertSame([$testValor],$response->json("data.*.saldo"));
        $this->assertSame([$banco->conta],$response->json("data.*.conta"));
    }

    public function testSaqueIndisponivelMutation(): void
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $valor = rand(($banco->saldo+1), ($banco->saldo*2));

        $response = $this->graphQL("
            mutation {
                sacar (conta: {$banco->conta}, valor: {$valor}){
                    conta
                    saldo
                }
            }");
            
        $this->assertSame(["Saldo insuficiente."],$response->json("errors.*.message"));
    }

    public function testSaqueInvalidoMutation(): void
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $valor = -3;

        $response = $this->graphQL("
            mutation {
                sacar (conta: {$banco->conta}, valor: {$valor}){
                    conta
                    saldo
                }
            }");
            
        $this->assertSame(["Valor para saque informado é inválido."],$response->json("errors.*.message"));
    }

    public function testSaldoQuery(): void
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta,
        ])->first();

        $response = $this->graphQL("
            {
                saldo (conta: {$banco->conta})
            }");

        $this->assertEquals([$banco->saldo],$response->json("*.saldo"));
    }
    public function testRemoverContaTeste()
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $banco->delete();

        $this->assertTrue(true);
    }
}
