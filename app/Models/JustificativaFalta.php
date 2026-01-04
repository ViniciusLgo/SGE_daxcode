<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JustificativaFalta extends Model
{
    protected $table = 'justificativa_faltas';

    protected $fillable = [
        'nome',
        'exige_observacao',
        'ativo',
    ];

    protected $casts = [
        'exige_observacao' => 'boolean',
        'ativo'            => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function presencasAlunos()
    {
        return $this->hasMany(PresencaAluno::class, 'justificativa_falta_id');
    }
}
