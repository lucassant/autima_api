<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrcamentoLancto extends Model
{
    protected $table = 'orcamento_lanctos';

    protected $fillable = [
        'nctrl_orcamento',
        'numero_ordem',
        'codigo_produto',
        'codigo_embalagem',
        'codigo_prazo',
        'quantidade',
        'preco_unitario',
        'valor_total_desconto'        
    ];
}
