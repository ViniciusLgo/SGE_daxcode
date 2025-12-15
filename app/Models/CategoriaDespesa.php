<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDespesa extends Model
{
    use HasFactory;

    // Nome da tabela (opcional, mas deixa explícito)
    protected $table = 'categorias_despesas';

    // Campos que podem ser preenchidos em massa (create/update)
    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Relação com Despesa
     * Uma categoria possui muitas despesas
     */
    public function despesas()
    {
        return $this->hasMany(Despesa::class, 'categoria_id');
    }
}
