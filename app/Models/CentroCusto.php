<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCusto extends Model
{
    use HasFactory;

    protected $table = 'centros_custos';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Relação com Despesa
     * Um centro de custo possui muitas despesas
     */
    public function despesas()
    {
        return $this->hasMany(Despesa::class, 'centro_custo_id');
    }
}
