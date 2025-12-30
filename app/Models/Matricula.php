<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'turma_id',
        'codigo',
        'status',
        'data_status',
        'motivo',
        'observacao',
        'user_id_alteracao',
    ];

    /* =========================
     * Relacionamentos
     * ========================= */

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function historicos()
    {
        return $this->hasMany(MatriculaHistorico::class);
    }

    /* =========================
     * Regras de negócio
     * ========================= */

    public function desistir(string $motivo, ?string $observacao, int $userId): void
    {
        $this->update([
            'status' => 'desistente',
            'data_status' => now(),
            'motivo' => $motivo,
            'observacao' => $observacao,
            'user_id_alteracao' => $userId,
        ]);

        $this->historicos()->create([
            'status' => 'desistente',
            'data_status' => now(),
            'motivo' => $motivo,
            'observacao' => $observacao,
            'user_id_alteracao' => $userId,
        ]);
    }


    public function reativar(?string $motivo = null, ?string $observacao = null, ?int $userId = null): void
    {
        $this->registrarMudancaStatus('ativo', $motivo, $observacao, $userId);
    }

    public function trocarTurma(int $novaTurmaId, ?string $motivo = null, ?string $observacao = null, ?int $userId = null): void
    {
        $turmaAnterior = $this->turma_id;

        if ($turmaAnterior == $novaTurmaId) {
            return;
        }

        $this->update([
            'turma_id' => $novaTurmaId,
            'user_id_alteracao' => $userId,
        ]);

        $this->historicos()->create([
            'tipo_evento'        => 'troca_turma',
            'turma_anterior_id'  => $turmaAnterior,
            'turma_nova_id'      => $novaTurmaId,
            'motivo'             => $motivo,
            'observacao'         => $observacao,
            'user_id'            => $userId,
        ]);
    }

    protected function registrarMudancaStatus(
        string $novoStatus,
        ?string $motivo,
        ?string $observacao,
        ?int $userId
    ): void {
        $statusAnterior = $this->status;

        if ($statusAnterior === $novoStatus) {
            return;
        }

        $this->update([
            'status' => $novoStatus,
            'data_status' => now(),
            'motivo' => $motivo,
            'observacao' => $observacao,
            'user_id_alteracao' => $userId,
        ]);

        $this->historicos()->create([
            'tipo_evento'      => 'status',
            'status_anterior'  => $statusAnterior,
            'status_novo'      => $novoStatus,
            'motivo'           => $motivo,
            'observacao'       => $observacao,
            'user_id'          => $userId,
        ]);
    }
}
