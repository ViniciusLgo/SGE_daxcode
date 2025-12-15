<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despesa extends Model
{
    use HasFactory;

    protected $table = 'despesas';

    protected $fillable = [
        'categoria_id',
        'centro_custo_id',
        'valor',
        'data',
        'descricao',
        'arquivo',
        'user_id',
        'fornecedor',
        'forma_pagamento',
        'status_pagamento',
        'numero_nf',
        'conta',
        'responsavel_id',
    ];

    // Casts para facilitar uso
    protected $casts = [
        'data'  => 'date',
        'valor' => 'decimal:2',
    ];

    /**
     * Categoria da despesa
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaDespesa::class, 'categoria_id');
    }

    /**
     * Centro de custo (opcional)
     */
    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class, 'centro_custo_id');
    }

    /**
     * Usuário que lançou a despesa
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
