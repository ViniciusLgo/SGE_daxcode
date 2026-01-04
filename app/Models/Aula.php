<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Presenca;

class Aula extends Model
{
    use HasFactory;

    /**
     * CAMPOS LIBERADOS PARA MASS ASSIGNMENT
     */
    protected $fillable = [
        'turma_id',
        'disciplina_id',
        'professor_id',
        'tipo',
        'data',
        'hora_inicio',
        'hora_fim',
        'quantidade_blocos',
        'duracao_minutos',
        'conteudo',
        'observacoes',
        'atividade_casa',
        'user_id_registro',
    ];

    /**
     * CASTS
     */
    protected $casts = [
        'data' => 'date',
        'atividade_casa' => 'boolean',
    ];

    /* =====================================================
     | RELACIONAMENTOS
     ===================================================== */

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

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'user_id_registro');
    }

    /* =====================================================
     | SCOPES (VÃO AJUDAR MUITO DEPOIS)
     ===================================================== */

    /**
     * Apenas atividades que contam como aula pedagógica
     */
    public function scopeAulas($query)
    {
        return $query->where('tipo', 'aula');
    }

    /**
     * Atividades que contam para salário
     */
    public function scopeContaParaSalario($query)
    {
        return $query->whereIn('tipo', [
            'aula',
            'reuniao',
            'evento',
            'conferencia',
        ]);
    }

    /**
     * Filtrar por professor
     */
    public function scopeDoProfessor($query, $professorId)
    {
        return $query->where('professor_id', $professorId);
    }

    public function presenca()
    {
        return $this->hasOne(Presenca::class);
    }

}
