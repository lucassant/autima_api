<?php

namespace App\Http\Controllers;

use App\RegraPreco;
use Illuminate\Http\Request;

class RegraPrecoController extends Controller
{

    private $regras;

    public function __construct(RegraPreco $regra){
        $this->regras = $regra;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $data = ['regras' => $this->regras->all()];

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
     * @param  \App\RegraPreco  $regraPreco
     * @return \Illuminate\Http\Response
     */
    public function show(RegraPreco $regraPreco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RegraPreco  $regraPreco
     * @return \Illuminate\Http\Response
     */
    public function edit(RegraPreco $regraPreco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RegraPreco  $regraPreco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegraPreco $regraPreco)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RegraPreco  $regraPreco
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegraPreco $regraPreco)
    {
        //
    }
}
