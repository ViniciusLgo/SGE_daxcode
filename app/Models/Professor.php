<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    // Força o nome da tabela para corresponder ao schema em português
    protected $table = 'professores';

    // Campos preenchíveis (opcional, mas recomendado)
    protected $fillable = [
        'nome',
        'email',
        'departamento',
        'user_id',
        'foto_perfil',

    ];

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }
}
