<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $table = 'presencas';

    protected $fillable = [
        'aula_id',
        'turma_id',
        'disciplina_id',
        'professor_id',
        'data',
        'quantidade_blocos',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // Presença pertence a uma aula
    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    // Uma presença possui vários registros de alunos
    public function alunos()
    {
        return $this->hasMany(PresencaAluno::class);
    }
}
