<?php

namespace App\Http\Controllers;

use DB;
use App\Orcamento;
use App\OrcamentoLancto;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    public function retornaTodos($dataIni, $dataFim, $codVendedor, $codCliente, $tipo)
    {
        if ($codCliente == '0') {

            //Verifica o tipo se for todos ele não filtra
            if ($tipo == 'T') {
                $retorno = Orcamento::join('participante', 'orcamento.codigo_cliente', '=', 'participante.codigo')
                    ->select(
                        'participante.codigo',
                        'participante.razao_social',
                        'orcamento.numero_controle',
                        'orcamento.data_orcamento',
                        'orcamento.valor_orcamento',
                        'orcamento.situacao'
                    )
                    ->where([
                        ['orcamento.data_orcamento', '>=', $dataIni],
                        ['orcamento.data_orcamento', '<=', $dataFim],
                        ['orcamento.codigo_vendedor', '=', $codVendedor],
                    ])->orderBy('orcamento.numero_controle', 'desc')->get();
            } else {

                $retorno = Orcamento::join('participante', 'orcamento.codigo_cliente', '=', 'participante.codigo')
                    ->select(
                        'participante.codigo',
                        'participante.razao_social',
                        'orcamento.numero_controle',
                        'orcamento.data_orcamento',
                        'orcamento.valor_orcamento',
                        'orcamento.situacao'
                    )
                    ->where([
                        ['orcamento.data_orcamento', '>=', $dataIni],
                        ['orcamento.data_orcamento', '<=', $dataFim],
                        ['orcamento.codigo_vendedor', '=', $codVendedor],
                        ['orcamento.situacao', '=', $tipo]
                    ])->orderBy('orcamento.numero_controle', 'desc')->get();
            }

            $total = 0;
            $qtd = 0;

            foreach ($retorno as $model) {
                $qtd++;

                //Adiciona a quantidade de itens a cada registro
                $qtdItens = OrcamentoLancto::where('nctrl_orcamento', $model->numero_controle)->count();
                $model->qtd_itens = $qtdItens;

                //Calcula o valor total
                $total += $model->valor_orcamento;
            }
        } else {

            if ($tipo == 'T') {

                $retorno = Orcamento::join('participante', 'orcamento.codigo_cliente', '=', 'participante.codigo')
                    ->select(
                        'participante.codigo',
                        'participante.razao_social',
                        'orcamento.numero_controle',
                        'orcamento.data_orcamento',
                        'orcamento.valor_orcamento',
                        'orcamento.situacao'
                    )
                    ->where([
                        ['orcamento.data_orcamento', '>=', $dataIni],
                        ['orcamento.data_orcamento', '<=', $dataFim],
                        ['orcamento.codigo_vendedor', '=', $codVendedor],
                        ['orcamento.codigo_cliente', '=', $codCliente],
                    ])->orderBy('orcamento.numero_controle', 'desc')->get();
            } else {

                $retorno = Orcamento::join('participante', 'orcamento.codigo_cliente', '=', 'participante.codigo')
                    ->select(
                        'participante.codigo',
                        'participante.razao_social',
                        'orcamento.numero_controle',
                        'orcamento.data_orcamento',
                        'orcamento.valor_orcamento',
                        'orcamento.situacao'
                    )
                    ->where([
                        ['orcamento.data_orcamento', '>=', $dataIni],
                        ['orcamento.data_orcamento', '<=', $dataFim],
                        ['orcamento.codigo_vendedor', '=', $codVendedor],
                        ['orcamento.codigo_cliente', '=', $codCliente],
                        ['orcamento.situacao', '=', $tipo]
                    ])->orderBy('orcamento.numero_controle', 'desc')->get();
            }

            $total = 0;
            $qtd = 0;

            foreach ($retorno as $model) {
                $qtd++;

                //Adiciona a quantidade de itens a cada registro
                $qtdItens = OrcamentoLancto::where('nctrl_orcamento', $model->numero_controle)->count();
                $model->qtd_itens = $qtdItens;

                //Calcula o valor total
                $total += $model->valor_orcamento;
            }
        }

        return json_encode((object) array('valor_total' => $total, 'quantidade' => $qtd, 'relatorio' => $retorno));
    }

    public function retornaLanctos($numPedido){
        $retorno = OrcamentoLancto::join('produto', 'orcamento_lanctos.codigo_produto', 'produto.codigo')
            ->join('embalagem', 'orcamento_lanctos.codigo_embalagem', 'embalagem.codigo')
            ->join('prazo_pagamento', 'orcamento_lanctos.codigo_prazo', 'prazo_pagamento.codigo')
            ->select('orcamento_lanctos.nctrl_orcamento', 'orcamento_lanctos.quantidade', 'orcamento_lanctos.preco_unitario', 
                    'orcamento_lanctos.valor_total_desconto', 'embalagem.descricao AS embalagem', 
                    'produto.descricao AS produto', 'prazo_pagamento.descricao AS prazo', 'orcamento_lanctos.numero_ordem')
            ->where('orcamento_lanctos.nctrl_orcamento', $numPedido)
            ->orderBy('orcamento_lanctos.numero_ordem')
            ->get();

        return json_encode((object) array('lanctos' => $retorno));
    }
}
