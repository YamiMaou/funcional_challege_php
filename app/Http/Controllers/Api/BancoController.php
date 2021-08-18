<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    private $model = \App\Models\Banco::class;
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'conta' => ['conta', 'unique:bancos'],
            'saldo' => ['min:3']
        ]);

        $conta = $this->model::findOrCreate(["conta" => $data['conta']], $data);

        return response()->json(['saldo' => $conta]);
    }
    
    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'conta' => ['conta', 'unique:bancos'],
            'saldo' => ['min:3']
        ]);

        $conta = $this->model::findOrCreate(["conta" => $data['conta']], $data);
        $conta->saldo = ($conta->saldo + $data['valor']);

        return response()->json(['saldo' => $conta]);
    }
}
