<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    // Força o nome da tabela para corresponder ao schema em português
    protected $table = 'professores';

    // Campos preenchíveis (opcional, mas recomendado)
    protected $fillable = [
        'nome',
        'email',
        'departamento',
        'user_id',
        'foto_perfil',
        'telefone',
        'especializacao'

    ];

    public function disciplinaTurmas()
    {
        return $this->belongsToMany(DisciplinaTurma::class, 'disciplina_turma_professor')
            ->withTimestamps();
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor', 'professor_id', 'disciplina_id')
            ->withTimestamps();
    }
}
