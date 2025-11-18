<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    /**
     * Campos liberados para criação/edição
     */
    protected $fillable = [
        'nome',
        'ano',
        'turno',
        'descricao',
    ];

    /**
     * Relacionamento 1:N com alunos
     */
    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'turma_id');
    }

    /**
     * Relacionamento N:N com disciplinas (através da tabela disciplina_turmas)
     *
     * OBS: Isso NÃO traz professores! Somente as disciplinas.
     */
    public function disciplinas()
    {
        return $this->belongsToMany(
            Disciplina::class,
            'disciplina_turmas',
            'turma_id',
            'disciplina_id'
        )
            ->withPivot(['id', 'ano_letivo', 'observacao'])
            ->withTimestamps();
    }

    /**
     * Relacionamento 1:N com a tabela disciplina_turmas (pivot)
     *
     * ESTE é o relacionamento que permite acessar os professores.
     *
     * exemplo:
     * $turma->disciplinaTurmas->professores
     */
    public function disciplinaTurmas()
    {
        return $this->hasMany(DisciplinaTurma::class, 'turma_id');
    }
}
