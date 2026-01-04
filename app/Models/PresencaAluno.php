<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresencaAluno extends Model
{
    protected $table = 'presenca_alunos';

    protected $fillable = [
        'presenca_id',
        'aluno_id',
        'bloco_1',
        'bloco_2',
        'bloco_3',
        'bloco_4',
        'bloco_5',
        'bloco_6',
        'justificativa_falta_id',
        'observacao',
    ];

    protected $casts = [
        'bloco_1' => 'boolean',
        'bloco_2' => 'boolean',
        'bloco_3' => 'boolean',
        'bloco_4' => 'boolean',
        'bloco_5' => 'boolean',
        'bloco_6' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function presenca()
    {
        return $this->belongsTo(Presenca::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function justificativa()
    {
        return $this->belongsTo(JustificativaFalta::class, 'justificativa_falta_id');
    }
}
