<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TabelaPreco extends Model
{
    protected $table = 'prazo_pagamento';

    protected $visible = [
        'codigo',
        'descricao',
        'tipo_venda',
        'ativo',
        'regra_preco',
        'percentual_regra',
        'flag_desconto_volume'
    ];
}
