<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Orcamento;
use App\OrcamentoLancto;
use App\RepSql;

class OrcamentoController extends Controller
{
    public function store(Request $request)
    {
        //Inicio da parte da capa do pedido
        $orcamento = json_decode($request->orcamento);

        //Obtem o numero de controle antes de inserir
        $numControle = DB::select("select nextval('public.orcamento_nctrl') AS numero_controle");
        $numControle = $numControle[0]->numero_controle;

        $data = [
            'numero_controle' => $numControle,
            'codigo_empresa' => 1,
            'codigo_cliente' => $orcamento->codCliente,
            'data_orcamento' => $orcamento->data,
            'valor_orcamento' => $orcamento->valorTotal,
            'codigo_vendedor' => $request->vendedor,
            'codigo_usuario' => 1,
            'observacao1' => $orcamento->observacao,
            'situacao' => 'E',
            'valor_verba' => $orcamento->valorVerba,
            'codigo_forma_pagamento' => $orcamento->codForma
        ];

        try {
            //Verifica se inseriu a capa do orçamento corretamente
            if (Orcamento::insert($data)) {

                //Grava na rep_sql o insert do pedido
                $rep_sql = [
                    'text_sql' => 'INSERT INTO orcamento(
                        numero_controle, codigo_empresa, codigo_cliente, data_orcamento, valor_orcamento, codigo_vendedor, codigo_usuario, observacao1, situacao, valor_verba
                        ) VALUES(
                            ' . $numControle . ', 
                            1, 
                            ' . $orcamento->codCliente . ', 
                            \'' . $orcamento->data . '\', 
                            ' . $orcamento->valorTotal . ', 
                            ' . $request->vendedor . ', 
                            1, 
                            \'' . $orcamento->observacao . '\', 
                            \'E\', 
                            ' . $orcamento->valorVerba . '
                        )'
                ];

                RepSql::insert($rep_sql);
                //Fim do insert na rep_sql

                $_qtdItens = $this->insereLanctos($request->lanctos, $numControle);

                return json_encode((object) array('numero_controle' => $numControle, 'response' => 1, 'quantidade' => $_qtdItens, 'message' => 'ok'));
            } else {
                return json_encode((object) array('numero_controle' => 0, 'response' => 0, 'quantidade' => 0, 'message' => 'capa'));
            }
        } catch (\Exception $e) {
            return json_encode((object) array('numero_controle' => 0, 'response' => 0, 'quantidade' => 0, 'message' => $e->getMessage()));
        }
    }

    public function insereLanctos(String $lanctos, $numeroControle)
    {

        $lista = json_decode($lanctos);
        $qtdItens = 0;

        //verifica se é pra abater reserva
        $abateReserva = DB::select('SELECT orcamento_checa_estoque FROM parametros_sistema WHERE codigo_empresa = 1');
        $abateReserva = $abateReserva[0]->orcamento_checa_estoque;

        foreach ($lista as $model) {

            $data = [
                'nctrl_orcamento' => $numeroControle,
                'numero_ordem' => $model->numOrdem,
                'codigo_produto' => $model->codProduto,
                'codigo_embalagem' => $model->codEmbalagem,
                'codigo_prazo' => $model->codPrazo,
                'quantidade' => $model->quantidade,
                'preco_unitario' => $model->precoUnitario,
                'valor_total_desconto' => $model->valorDesconto,
                'flag_reserva' => $abateReserva
            ];

            try {
                if (OrcamentoLancto::insert($data)) {

                    //Grava na rep_sql o lancto do pedido
                    $rep_sql = [
                        'text_sql' => 'INSERT INTO orcamento_lanctos(
                            nctrl_orcamento, numero_ordem, codigo_produto, codigo_embalagem, codigo_prazo, quantidade, preco_unitario, valor_total_desconto, flag_reserva
                            ) VALUES(
                                ' . $numeroControle . ',                                 
                                ' . $model->numOrdem . ', 
                                ' . $model->codProduto . ', 
                                ' . $model->codEmbalagem . ', 
                                ' . $model->codPrazo . ', 
                                ' . $model->quantidade . ', 
                                ' . $model->precoUnitario . ', 
                                ' . $model->valorDesconto . ', 
                                ' . $abateReserva . '
                            )'
                    ];
                    RepSql::insert($rep_sql);
                    //Fim do insert na rep_sql


                    if ($abateReserva == 1) {
                        //Atualiza a reserva
                        $reservaAtual = DB::select('SELECT reserva1 FROM produto WHERE codigo = ' . $model->codProduto);
                        //Obtem a reserva atual
                        $reservaAtual = $reservaAtual[0]->reserva1;

                        //Obtem a quantidade da embalagem
                        $qtdEmbalagem = DB::select('SELECT quantidade FROM embalagem WHERE codigo = ' . $model->codEmbalagem);
                        $qtdEmbalagem = $qtdEmbalagem[0]->quantidade;

                        $qtdEmbalagem = $model->quantidade * $qtdEmbalagem;

                        DB::table('produto')->where('codigo', $model->codProduto)->update(['reserva1' => ($qtdEmbalagem + $reservaAtual), 'data_alteracao_estoque' => date("Y-m-d")]);

                        //Grava na rep_sql o update da reserva
                        $rep_sql = [
                            'text_sql' => 'UPDATE produto SET reserva1 = ' . ($qtdEmbalagem + $reservaAtual) . ', data_alteracao_estoque = \'' . date("Y-m-d") . '\' WHERE codigo = ' . $model->codProduto
                        ];
                        RepSql::insert($rep_sql);
                        //Fim do insert na rep_sql
                    }

                    $qtdItens++;
                }
            } catch (\Exception $e) {
            }
        }

        return $qtdItens;
    }
}
