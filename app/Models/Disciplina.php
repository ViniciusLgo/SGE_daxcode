<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'carga_horaria'];

    /**
     * Professores da disciplina
     * pivot: disciplina_professor
     */
    public function professores()
    {
        return $this->belongsToMany(
            Professor::class,
            'disciplina_professor',
            'disciplina_id',
            'professor_id'
        )->withTimestamps();
    }
}
