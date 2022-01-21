<?php

namespace App\Http\Controllers;

use App\Bairro;
use Illuminate\Http\Request;

class BairroController extends Controller
{
    private $bairros;

    public function __construct(Bairro $bairro){
        $this->bairros = $bairro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dataSinc)
    {
        $lista = Bairro::where('dh_inclusao', '>=', $dataSinc)->get();
        $data = ['bairros' => $lista];

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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function show(Bairro $bairro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function edit(Bairro $bairro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bairro $bairro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bairro $bairro)
    {
        //
    }
}
