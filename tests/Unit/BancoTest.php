<?php

namespace Tests\Unit;

class BancoTest extends \Tests\TestCase
{
    private $conta = 58994599;

    public function testDepositarlMutation(): void
    {
        $banco = \App\Models\Banco::where([
            'conta' => $this->conta
        ])->first();

        $valor = rand(1, $banco->saldo);

        $response = $this->graphQL("
            mutation {
                depositar (conta: {$banco->conta}, valor: {$valor}){
                    conta
                    saldo
                }
            }");
        $testValor = ($banco->conta+$valor);
        $this->assertSame([$testValor],$response->json("data.*.valor"));
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
            
        $testValor = ($banco->conta+$valor);
        $this->assertSame([$testValor],$response->json("data.*.valor"));
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

    public function testSaldoQuery(): void
    {
        $banco = \App\Models\Banco::firstOrNew([
            'conta' => $this->conta,
        ]);

        $banco->saldo = rand(10,9999);

        $banco->save();

        $response = $this->graphQL("
            {
                saldo (conta: {$banco->conta})
            }");

        $this->assertSame([$banco->saldo],$response->json("data.saldo"));

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
