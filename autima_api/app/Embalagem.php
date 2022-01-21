<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embalagem extends Model
{
    protected $table = 'embalagem';

    protected $visible = [
        'codigo',
        'descricao',
        'embalagem',
        'abreviatura',
        'quantidade',
        'ativo'        
    ];
}
