<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'turma_id',
        'nome',
        'user_id',
        'foto_perfil',
        'email',
        'matricula',
        'data_nascimento',
        'telefone',
    ];

    public function documentos()
    {
        return $this->hasMany(UserDocument::class);
    }

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function registros()
    {
        return $this->hasMany(\App\Models\AlunoRegistro::class, 'aluno_id')->orderByDesc('created_at');
    }

    public function responsaveis()
    {
        return $this->belongsToMany(\App\Models\Responsavel::class, 'aluno_responsavel')
            ->withTimestamps()
            ->withPivot('observacao');
    }

}
