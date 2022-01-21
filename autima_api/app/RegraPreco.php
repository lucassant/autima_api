<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegraPreco extends Model
{
    protected $table = 'regra_preco';

    protected $vibible = [
        'codigo_empresa',
        'codigo_produto',
        'codigo_prazo',
        'regra',
        'valor'        
    ];
}
