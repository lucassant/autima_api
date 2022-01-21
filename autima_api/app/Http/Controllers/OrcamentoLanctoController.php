<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\OrcamentoLancto;

class OrcamentoLanctoController extends Controller
{
    public function store(Request $request)
    {
        $lista = json_decode($request->orcamento);
        $qtdItens = 0;

        foreach ($lista as $model) {

            $data = [
                'nctrl_orcamento' => $request->numero_controle,
                'numero_ordem' => $model->numOrdem,
                'codigo_produto' => $model->codProduto,
                'codigo_embalagem' => $model->codEmbalagem,
                'codigo_prazo' => $model->codPrazo,
                'quantidade' => $model->quantidade,
                'preco_unitario' => $model->precoUnitario,
                'valor_total_desconto' => $model->valorDesconto
            ];

            try {
                if (OrcamentoLancto::insert($data)) {
                    $qtdItens++;
                }
            } catch (\Exception $e) {
                
            }
        }
        
        if($request->quantidade == $qtdItens){
            return json_encode((object)array('quantidade' => $qtdItens, 'response' => 1));
        }else{
            return json_encode((object)array('quantidade' => $qtdItens, 'response' => 0));
        }
    }
}
