<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoResultado extends Model
{
    use HasFactory;

    protected $table = 'avaliacao_resultados';

    protected $fillable = [
        'avaliacao_id',
        'aluno_id',
        'nota',
        'arquivo',
        'observacao',
        'entregue',
    ];

    /* =======================
     * RELACIONAMENTOS
     * ======================= */

    public function avaliacao()
    {
        return $this->belongsTo(Avaliacao::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
