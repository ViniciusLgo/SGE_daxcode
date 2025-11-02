<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaTurmaProfessor extends Model
{
    use HasFactory;

    protected $table = 'disciplina_turma_professor';

    protected $fillable = [
        'disciplina_turma_id',
        'professor_id',
    ];

    public function disciplinaTurma()
    {
        return $this->belongsTo(DisciplinaTurma::class, 'disciplina_turma_id');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }
}
