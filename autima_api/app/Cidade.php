<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidade';

    protected $visible = [
        'codigo',
        'nome',
        'codigo_estado'    
    ];
}
