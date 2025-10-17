<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
protected $fillable = ['aluno_id', 'tipo', 'arquivo', 'observacoes', 'data_envio'];

public function aluno()
{
return $this->belongsTo(Aluno::class);
}
}
