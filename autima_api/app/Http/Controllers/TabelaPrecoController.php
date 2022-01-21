<?php

namespace App\Http\Controllers;

use App\TabelaPreco;
use Illuminate\Http\Request;

class TabelaPrecoController extends Controller
{    
    private $tabelas;

    public function __construct(TabelaPreco $tabela){
        $this->tabelas = $tabela;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['tabelas' => $this->tabelas->where('ativo', 1)->get()];

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TabelaPreco  $tabelaPreco
     * @return \Illuminate\Http\Response
     */
    public function show(TabelaPreco $tabelaPreco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TabelaPreco  $tabelaPreco
     * @return \Illuminate\Http\Response
     */
    public function edit(TabelaPreco $tabelaPreco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TabelaPreco  $tabelaPreco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TabelaPreco $tabelaPreco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TabelaPreco  $tabelaPreco
     * @return \Illuminate\Http\Response
     */
    public function destroy(TabelaPreco $tabelaPreco)
    {
        //
    }
}
