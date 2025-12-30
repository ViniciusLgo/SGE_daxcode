<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Matricula;
use Illuminate\Database\Eloquent\Builder;


class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'turma_id',
        'user_id',
        'foto_perfil',
        'matricula',
        'data_nascimento',
        'telefone',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    /**
     * Documentos do aluno
     */
    public function documents()
    {
        return $this->hasMany(
            \App\Models\UserDocument::class,
            'aluno_id', // FK REAL
            'id'        // PK do aluno
        );
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function registros()
    {
        return $this->hasMany(
            \App\Models\AlunoRegistro::class,
            'aluno_id'
        )->orderByDesc('created_at');
    }

    public function responsaveis()
    {
        return $this->belongsToMany(
            \App\Models\Responsavel::class,
            'aluno_responsavel'
        )
            ->withTimestamps()
            ->withPivot('observacao');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function documentos()
    {
        return $this->hasMany(
            \App\Models\UserDocument::class,
            'aluno_id',
            'id'
        );
    }

    public function matriculaModel()
    {
        return $this->hasOne(Matricula::class);
    }


    public function scopeAtivos(Builder $query): Builder
    {
        return $query->whereHas('matriculaModel', function ($q) {
            $q->where('status', 'ativo');
        });
    }
}
