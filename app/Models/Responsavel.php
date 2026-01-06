<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';


    protected $fillable = [
        'user_id',
        'telefone',
        'cpf',
        'grau_parentesco',
    ];

    // 🔗 Vínculo N:N com Alunos
    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'aluno_responsavel')
            ->withTimestamps()
            ->withPivot('observacao');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
