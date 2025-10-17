<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nome_instituicao',
        'email',
        'telefone',
        'endereco',
        'logo',
        'versao_sistema',
    ];
}
