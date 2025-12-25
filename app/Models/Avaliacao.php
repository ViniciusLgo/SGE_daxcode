<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'turma_id',
        'disciplina_id',
        'titulo',
        'tipo',
        'data_avaliacao',
        'status',
    ];

    protected $casts = [
        'data_avaliacao' => 'date',
    ];

    /* ================= RELACIONAMENTOS ================= */

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }


    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function resultados()
    {
        return $this->hasMany(AvaliacaoResultado::class);
    }
}
