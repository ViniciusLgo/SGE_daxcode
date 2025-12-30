<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaHistorico extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula_id',
        'tipo_evento',
        'status_anterior',
        'status_novo',
        'turma_anterior_id',
        'turma_nova_id',
        'motivo',
        'observacao',
        'user_id',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
