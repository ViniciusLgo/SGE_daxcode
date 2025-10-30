<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';


    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'grau_parentesco',
        'usuario_id',
    ];

    // 🔗 Vínculo N:N com Alunos
    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_responsavel')
            ->withTimestamps()
            ->withPivot('observacao');
    }

    // 🔗 Se tiver login vinculado
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
