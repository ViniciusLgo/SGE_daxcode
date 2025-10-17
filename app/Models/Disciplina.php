<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'professor_id',
        'nome',
        'carga_horaria',
        'descricao',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}
