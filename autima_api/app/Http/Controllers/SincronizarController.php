<?php

namespace App\Http\Controllers;

use DB;
use App\Bairro;
use App\Cidade;
use App\Cliente;
use App\Configuracoes;
use App\Embalagem;
use App\Estado;
use App\Produto;
use App\RegraPreco;
use App\TabelaPreco;
use App\TituloReceber;
use Illuminate\Http\Request;

class SincronizarController extends Controller
{
    //Função que vai retornar todos os dados da sincronização, cada tabela será um array dentro do json
    public function retornaDados($codVendedor, $dataSinc)
    {
        //Bairros
        $bairros = Bairro::where('dh_inclusao', '>=', $dataSinc)->get();

        //Cidades
        $cidades = Cidade::where('dh_inclusao', '>=', $dataSinc)->get();

        //Clientes
        $clientes = Cliente::where('flag_ativo', '1')
            ->join('associa_cliente_vendedor', 'participante.codigo', 'associa_cliente_vendedor.codigo_cliente')
            ->where([
                ['e_cliente', 1],
                ['data_alteracao', '>=', "'" . $dataSinc . "'"],
                ['associa_cliente_vendedor.codigo_vendedor', $codVendedor]
            ])
            ->orWhere([
                ['e_cliente', 1],
                ['associa_cliente_vendedor.codigo_vendedor', $codVendedor],
                ['associa_cliente_vendedor.dh_inclusao', '>=', "'" . $dataSinc . "'"]
            ])
            ->orderBy('codigo')
            ->get();

        //Configurações
        $configs = Configuracoes::where('codigo_empresa', 1)->get();

        //Embalagem
        $embalagens = Embalagem::where('ativo', 1)->get();

        //Estados
        $estados = Estado::where('dh_inclusao', '>=', $dataSinc)->get();

        //Produtos
        $produtos = DB::select('SELECT 
                                    pr.codigo, 
                                    pr.descricao, 
                                    pr.codigo_grupo, 
                                    pr.embalagem_padrao_saida, 
                                    pr.preco1, 
                                    ((pr.estoque1 - pr.reserva1) / em.quantidade) AS estoque1, 
                                    pr.flag_ativo, 
                                    pr.desconto_maximo,
                                    pr.flag_fracao_movimento,
                                    pr.referencia 
                                FROM 
                                    produto pr
                                    INNER JOIN embalagem em ON pr.embalagem_padrao_saida = em.codigo
                                WHERE 
                                    pr.data_alteracao >= \'' . $dataSinc . '\' 
                                    OR pr.data_alteracao_estoque >= \'' . $dataSinc . '\'
                                ORDER BY 
                                    pr.codigo');

        //Produtos barra
        $produtos_barra = DB::select('SELECT codigo_produto, codigo_embalagem, codigo_barras, codigo_usuario, preco FROM produto_barra');

        //Regras de preço
        $regras = RegraPreco::where('codigo_empresa', 1)->get();

        //Tabelas de preço
        $tabelas = TabelaPreco::where('ativo', 1)->get();

        //Estoque
        $estoque = DB::select('SELECT 
                                    ROUND(((pr.estoque1 - pr.reserva1) / em.quantidade), 2) AS estoque, 
                                    pr.codigo
                                FROM 
                                    produto pr 
                                    INNER JOIN embalagem em ON pr.embalagem_padrao_saida = em.codigo
                                ORDER BY 
                                    pr.codigo');

        //Saldo verba
        $saldo = DB::select('SELECT numero_controle, saldo_atual FROM historico_verba 
                                WHERE numero_controle = (SELECT MAX(numero_controle) FROM historico_verba WHERE codigo_vendedor = ' . $codVendedor . ')');

        //Forma de pagamento
        $formas = DB::select('SELECT codigo, descricao, tipo_operacao, ativo FROM forma_pagamento');

        //Inadimplencias
        $inadimplencias = DB::select('SELECT DISTINCT  
                                        tr.numero_controle,
                                        tr.codigo_participante,
                                        tr.numero_titulo,  
                                        tr.valor_titulo,
                                        tr.valor_credito, 
                                        tr.valor_debito,
                                        tr.valor_vinculado,
                                        tr.data_vencimento 
                                    FROM  
                                        titulo_receber tr 
                                        inner join associa_cliente_vendedor ac on tr.codigo_participante = ac.codigo_cliente and ac.codigo_vendedor = ? 
                                    WHERE  
                                        tr.valor_credito < tr.valor_debito 
                                        and tr.data_vencimento < CURRENT_TIMESTAMP
                                    ORDER BY 
                                        tr.data_vencimento desc', 
                                    [$codVendedor]);

        //Vendedor
        $vendedor = DB::select("SELECT codigo, desconto_extra FROM usuario_colaborador WHERE codigo = " . $codVendedor);

        //Monta o json de retorno
        $data = json_encode((object) array(
            'bairros' => $bairros,
            'cidades' => $cidades,
            'clientes' => $clientes,
            'configuracoes' => $configs,
            'embalagens' => $embalagens,
            'estados' => $estados,
            'produtos' => $produtos,
            'produtos_barra' => $produtos_barra,
            'regras_preco' => $regras,
            'tabelas_preco' => $tabelas,
            'estoque' => $estoque,
            'saldo' => $saldo,
            'inadimplencias' => $inadimplencias,
            'vendedor' => $vendedor,
            'formas_pagamento' => $formas
        ));

        return $data;
    }
}
