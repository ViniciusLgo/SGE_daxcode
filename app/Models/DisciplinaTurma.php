<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaTurma extends Model
{
    use HasFactory;

    // Tabela correta
    protected $table = 'disciplina_turmas';

    // Campos permitidos
    protected $fillable = [
        'turma_id',
        'disciplina_id',
        'ano_letivo',
        'observacao'
    ];

    /**
     * Relação: Cada vínculo pertence a UMA Turma
     */
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * Relação: Cada vínculo pertence a UMA Disciplina
     */
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    /**
     * Professores vinculados à disciplina dentro da turma
     *
     * 🔥 IMPORTANTE:
     * - 'disciplina_turma_professor' é o pivot correto
     * - 'disciplina_turma_id' é a FK local
     * - 'professor_id' é a FK da outra tabela
     * - ->with('user') carrega nome/email do professor
     */
    public function professores()
    {
        return $this->belongsToMany(
            Professor::class,
            'disciplina_turma_professor',
            'disciplina_turma_id',
            'professor_id'
        )
            ->with('user') // carrega o usuário do professor
            ->withTimestamps(); // <-- este é o ponto que estava faltando!
    }
}
