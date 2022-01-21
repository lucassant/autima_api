<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracoes extends Model
{
    protected $table = 'parametros_sistema';

    protected $visible = [
        'codigo_empresa',
        'codigo_prazo_padrao_saida',
        'permite_estoque_negativo'
    ];
}
