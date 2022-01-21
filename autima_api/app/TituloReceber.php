<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TituloReceber extends Model
{
    protected $table = 'titulo_receber';    

    protected $visible = [
        'numero_titulo',
        'data_vencimento',
        'codigo_participante',
        'valor_titulo',
        'valor_debito',
        'valor_credito',
        'codigo_vendedor',
        'valor_vinculado'
    ];
}
