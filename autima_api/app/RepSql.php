<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepSql extends Model
{
    protected $table = 'rep_sql';

    protected $fillable = [
        'text_sql'
    ];
}
