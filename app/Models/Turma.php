<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'turno',
        'ano',
        'descricao',
    ];

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }
}
