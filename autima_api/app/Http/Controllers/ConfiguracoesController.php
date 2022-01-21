<?php

namespace App\Http\Controllers;

use DB;
use App\Configuracoes;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    private $configs;

    public function __construct(Configuracoes $config){
        $this->configs = $config;
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['configs' => $this->configs->all()];

        return response()->json($data);
    }

    public function verificaSenhaVendedor($codigo, $senha){
        $ret = "";
        for($i=0; $i < strlen($senha); $i++){
            $aux = ord($senha{$i}) + 70;
            $ret = $ret . chr($aux);
        }

        $ret = DB::select("SELECT COUNT(senha) FROM usuario_colaborador WHERE codigo = " . $codigo . " AND senha = '" . mb_convert_encoding($ret,'UTF-8','Windows-1252') . "' AND e_vendedor = 1");

        return json_encode((object)array('count' => $ret[0]->count));
    }

    public function decodePassword($codigo){
        $senha = DB::select("SELECT senha FROM usuario_colaborador WHERE codigo = " . $codigo);        
        $senha = $senha[0]->senha;

        $ret = "";

        for($i=0; $i < strlen($senha); $i++){
            $aux = ord($senha{$i}) - 70;
            $ret = $ret . chr($aux);
        }

        return $ret;
    }
}
