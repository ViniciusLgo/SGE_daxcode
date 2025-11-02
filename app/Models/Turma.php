<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'ano',
        'turno',
        'descricao',
    ];

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'turma_id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_turmas', 'turma_id', 'disciplina_id')
            ->withPivot(['id', 'ano_letivo', 'observacao'])
            ->withTimestamps();
    }

    public function disciplinaTurmas()
    {
        return $this->hasMany(\App\Models\DisciplinaTurma::class, 'turma_id');
    }

    /**
     * Professores da turma — relacionamento indireto via DisciplinaTurma
     */
    public function professores()
    {
        return $this->hasManyThrough(
            \App\Models\Professor::class,
            \App\Models\DisciplinaTurmaProfessor::class,
            'disciplina_turma_id',  // Foreign key on disciplina_turma_professor
            'id',                   // Foreign key on professor (target)
            'id',                   // Local key on turma
            'professor_id'          // Local key on disciplina_turma_professor
        );
    }


}
