<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoResponsavel extends Model
{
    use HasFactory;

    protected $table = 'aluno_responsavel';

    protected $fillable = [
        'aluno_id',
        'responsavel_id',
        'observacao',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }
}
