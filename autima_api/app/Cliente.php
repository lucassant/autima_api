<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'participante';

    protected $visible = [
        'codigo',
        'razao_social',
        'nome_fantasia',
        'endereco',
        'cep',
        'codigo_bairro',
        'codigo_cidade',
        'telefone',
        'email',
        'identidade',
        'cpf_cnpj',
        'inscricao_estadual',
        'limite_credito',        
        'situacao',
        'observacao',
        'numero',
        'complemento'
    ];
}
