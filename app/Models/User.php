<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ======================================================
     |  RELACIONAMENTOS
     |======================================================*/
    public function aluno()
    {
        return $this->hasOne(\App\Models\Aluno::class);
    }


    public function professor()
    {
        return $this->hasOne(Professor::class);
    }

    public function responsavel()
    {
        return $this->hasOne(Responsavel::class);
    }

    /* ======================================================
     |  HELPERS
     |======================================================*/
    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'admin' => 'Administrador',
            'professor' => 'Professor',
            'aluno' => 'Aluno',
            'responsavel' => 'Responsável',
            default => ucfirst($this->tipo ?? 'Indefinido'),
        };
    }
}
