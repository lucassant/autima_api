<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    
    protected $visible = [
        'codigo', 
        'descricao', 
        'codigo_grupo', 
        'embalagem_padrao_saida', 
        'preco1', 
        'estoque1', 
        'flag_ativo',
        'desconto_maximo'
    ];
}
