<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    protected $table = 'bairro';

    protected $visible = [
        'codigo',
        'nome'
    ];
}
