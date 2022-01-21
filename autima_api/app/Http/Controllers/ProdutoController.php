<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private $produto;

    public function __construct(Produto $produto){
        $this->produto = $produto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dataSinc)
    {
        $lista = Produto::where([
            ['flag_ativo', '=', 1],
            ['data_alteracao', '>=', "'" . $dataSinc . "'"]
        ])
        ->orWhere([
            ['flag_ativo', '=', 1],
            ['data_alteracao_estoque', '>=',  "'" . $dataSinc . "'"]
        ])->get();
        
        $data = [ 'quantidade' => sizeof($lista), 'produtos' => $lista];

        return response()->json($data);
    }

    public function produtoBarra(){
        $retorno = DB::select('SELECT codigo_produto, codigo_embalagem, codigo_barras, codigo_usuario, preco FROM produto_barra');
        $retorno = ['produtos' => $retorno];
        
        return response()->json($retorno);
    }

    public function getEstoque($idProduto){
        $estoque = DB::select('SELECT 
                                    ROUND(((pr.estoque1 - pr.reserva1) / em.quantidade), 2) AS estoque, 
                                    pr.codigo
                                FROM 
                                    produto pr 
                                    INNER JOIN embalagem em ON pr.embalagem_padrao_saida = em.codigo
                                WHERE  
                                    pr.codigo = ' . $idProduto);
        
        $data = json_encode((object)array(
            'estoque' => $estoque            
        ));

        return $data;
    }

    public function allEstoques($dataSinc){
        $estoque = DB::select('SELECT 
                                    ROUND(((pr.estoque1 - pr.reserva1) / em.quantidade), 2) AS estoque, 
                                    pr.codigo
                                FROM 
                                    produto pr 
                                    INNER JOIN embalagem em ON pr.embalagem_padrao_saida = em.codigo
                                WHERE 
                                    pr.data_alteracao >= \'' . $dataSinc . '\' 
                                    OR pr.data_alteracao_estoque >= \'' . $dataSinc . '\'
                                ORDER BY 
                                    pr.codigo');

        $data = json_encode((object)array('estoque' => $estoque));

        return $data;
    }
}
