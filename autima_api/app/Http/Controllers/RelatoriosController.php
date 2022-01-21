<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\HistoricoVerba;

class RelatoriosController extends Controller
{
    public function historicoVerba($dataIni, $dataFinal, $codVendedor)
    {
        $historico = HistoricoVerba::where([
            ['dh_inclusao', '>=', $dataIni],
            ['dh_inclusao', '<=', $dataFinal],
            ['codigo_vendedor', '=', $codVendedor]
        ])
            ->orderBy('numero_controle', 'DESC')
            ->get();

        $retorno = json_encode((object) array('historico' => $historico));

        return $retorno;
    }

    public function rankingCliente($dataIni, $dataFinal, $codVendedor)
    {
        $ranking = DB::select('SELECT 
                                    SUM(sv.valor_orcamento) AS total, 
                                    COUNT(sv.numero_controle) AS quantidade, 
                                    sv.codigo_cliente AS codigo,  
                                    cl.razao_social AS descricao  
                                FROM 
                                    orcamento sv 
                                    INNER JOIN participante cl ON cl.codigo = sv.codigo_cliente 
                                WHERE 
                                    sv.codigo_vendedor = ' . $codVendedor . ' 
                                    AND sv.data_orcamento >= \' ' . $dataIni . '\' 
                                    AND sv.data_orcamento <= \' ' . $dataFinal . '\' 
                                    AND sv.situacao = \'F\' 
                                GROUP BY 
                                    sv.codigo_cliente, 
                                    cl.razao_social
                                ORDER BY 
                                    total DESC');

        return json_encode((object) array('ranking' => $ranking));
    }

    public function rankingProduto($dataIni, $dataFinal, $codVendedor)
    {
        $ranking = DB::select('SELECT 
                                    SUM(sv.valor_orcamento) AS total, 
                                    COUNT(sv.numero_controle) AS quantidade, 
                                    svl.codigo_produto AS codigo,  
                                    it.descricao AS descricao  
                                FROM 
                                    orcamento sv 
                                    INNER JOIN orcamento_lanctos svl ON sv.numero_controle = svl.nctrl_orcamento
						            INNER JOIN produto it ON it.codigo = svl.codigo_produto
                                WHERE 
                                    sv.codigo_vendedor = ' . $codVendedor . ' 
                                    AND sv.data_orcamento >= \' ' . $dataIni . '\' 
                                    AND sv.data_orcamento <= \' ' . $dataFinal . '\' 
                                    AND sv.situacao = \'F\' 
                                GROUP BY 
                                    svl.codigo_produto, 
                                    it.descricao
                                ORDER BY 
                                    total DESC');

        return json_encode((object) array('ranking' => $ranking));
    }

    public function faturadosMensal()
    {
        $data = DB::select("SELECT 
                    date_part('year', data_orcamento::date) as ano, 
                    date_part('month', data_orcamento::date) as mes,                    
                    SUM(valor_orcamento) as total
                FROM 
                    orcamento 
                WHERE 
                    situacao = 'F'
                GROUP BY 
                    ano, mes
                ORDER BY 
                    ano, mes ASC ");

        return json_encode((object) array('faturamento' => $data));
    }
}
