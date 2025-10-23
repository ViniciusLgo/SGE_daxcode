<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoRegistro extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'turma_id',
        'responsavel_id',
        'tipo',
        'categoria',
        'descricao',
        'arquivo',
        'data_evento',
        'status',
    ];

    // 🔗 Relações
    public function aluno()
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
