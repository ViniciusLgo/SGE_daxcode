<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'titulo',
        'turma_id',
        'disciplina_id',
        'professor_id',
        'tipo',
        'peso',
        'data_avaliacao',
        'status',
    ];

    protected $casts = [
        'data_avaliacao' => 'date',
    ];


    /* =========================
       RELACIONAMENTOS
    ========================= */

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

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
