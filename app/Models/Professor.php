<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $table = 'professores';

    protected $fillable = [
        'departamento',
        'user_id',
        'foto_perfil',
        'telefone',
        'especializacao',
    ];

    /**
     * Relacionamento com o usuário (dados básicos: nome, email, senha)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Disciplinas associadas ao professor
     * (pivot: disciplina_professor)
     */
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor', 'professor_id', 'disciplina_id')
            ->withTimestamps();
    }

    /**
     * Turmas associadas ao professor
     * (pivot: disciplina_turma_professor)
     */
    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'disciplina_turma_professor', 'professor_id', 'turma_id')
            ->withTimestamps();
    }

    /**
     * Relação direta com a tabela intermediária disciplina_turma
     * (se você usa o model DisciplinaTurma)
     */
    public function disciplinaTurmas()
    {
        return $this->hasMany(DisciplinaTurma::class, 'professor_id');
    }

    /**
     * Accessors para facilitar chamadas no Blade: $professor->nome e $professor->email
     */
    public function getNomeAttribute()
    {
        return $this->user->name ?? null;
    }

    public function getEmailAttribute()
    {
        return $this->user->email ?? null;
    }
}
