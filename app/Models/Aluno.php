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
}
