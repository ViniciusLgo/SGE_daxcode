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
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Disciplinas do professor
     */
    public function disciplinas()
    {
        return $this->belongsToMany(
            Disciplina::class,
            'disciplina_professor',
            'professor_id',
            'disciplina_id'
        )->withTimestamps();
    }

    /**
     * Professores vinculados diretamente por disciplina_turma
     */
    public function disciplinaTurmas()
    {
        return $this->belongsToMany(
            DisciplinaTurma::class,
            'disciplina_turma_professor',
            'professor_id',
            'disciplina_turma_id'
        );
    }

    /**
     * Acessors
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
