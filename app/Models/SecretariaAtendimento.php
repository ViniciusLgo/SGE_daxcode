<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretariaAtendimento extends Model
{
    use HasFactory;

    protected $table = 'secretaria_atendimentos';

    protected $fillable = [
        'tipo',
        'aluno_id',
        'responsavel_id',
        'status',
        'observacao',
        'data_atendimento',
    ];

    protected $casts = [
        'data_atendimento' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relacionamentos
    |--------------------------------------------------------------------------
    */

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }
}
