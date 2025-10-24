<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Disciplina extends Model
{
    protected $fillable = ['nome', 'descricao', 'carga_horaria'];

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'disciplina_professor')
            ->withTimestamps();
    }
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor', 'professor_id', 'disciplina_id');
    }

}
