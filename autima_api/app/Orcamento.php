<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $table = 'orcamento';

    protected $fillable = [
        'numero_controle',
        'codigo_empresa',
        'codigo_cliente',
        'data_orcamento',
        'valor_orcamento',
        'codigo_vendedor',
        'codigo_usuario',
        'observacao1',
        'dh_sincronizacao'
    ];
}
