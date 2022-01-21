<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoVerba extends Model
{
    protected $table = 'historico_verba';

    protected $fillable = [
        'numero_controle',
        'codigo_vendedor',
        'codigo_empresa',
        'descricao',
        'saldo_anterior',
        'valor_verba',
        'saldo_atual',
        'dh_inclusao'
    ];
}
