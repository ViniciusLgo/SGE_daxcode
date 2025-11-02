<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaTurma extends Model
{
    use HasFactory;

    protected $table = 'disciplina_turmas';

    protected $fillable = ['turma_id', 'disciplina_id', 'ano_letivo', 'observacao'];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'disciplina_turma_professor', 'disciplina_turma_id', 'professor_id')
            ->withTimestamps();
    }
}
