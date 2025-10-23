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
        return $this->belongsToMany(Disciplina::class, 'disciplina_turmas', 'turma_id', 'disciplina_id');
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'disciplina_turma_professor', 'turma_id', 'professor_id');
    }
}
