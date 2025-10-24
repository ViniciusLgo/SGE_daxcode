<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoRegistro extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'turma_id',
        'tipo',
        'categoria',
        'data_evento',
        'descricao',
        'arquivo',
        'status',
        'responsavel_id',
    ];

    public function aluno()
    {
        return $this->belongsTo(\App\Models\Aluno::class, 'aluno_id');
    }

    public function turma()
    {
        return $this->belongsTo(\App\Models\Turma::class, 'turma_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(\App\Models\User::class, 'responsavel_id');
    }
}
