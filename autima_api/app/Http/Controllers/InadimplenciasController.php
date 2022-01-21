<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InadimplenciasController extends Controller
{    
    public function getInadimplencias($codVendedor)
    {
        $retorno = DB::select('select 
                                tr.codigo_participante,
                                tr.numero_titulo,  
                                tr.valor_titulo,
                                tr.valor_credito, 
                                tr.valor_debito,
                                tr.data_vencimento 
                            from 
                                titulo_receber tr 
                                inner join associa_cliente_vendedor ac on tr.codigo_participante = ac.codigo_cliente and ac.codigo_vendedor = ? 
                            where 
                                tr.valor_credito < tr.valor_debito 
                                and tr.data_vencimento < CURRENT_TIMESTAMP
                            order by 
                                tr.data_vencimento desc', 
                            [$codVendedor]);
        
        return response()->json($retorno);
    }
}
