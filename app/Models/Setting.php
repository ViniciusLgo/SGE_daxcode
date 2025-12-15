<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Se a tabela não seguir o padrão plural, defina:
    // protected $table = 'settings';

    protected $fillable = [
        'school_name',
        'email',
        'phone',
        'address',
        'logo',
        'version',

        // grupos novos (se quiser preencher via fill)
        'academic_settings',
        'document_settings',
        'user_settings',
        'notification_settings',
        'finance_settings',
        'communication_settings',
        'log_settings',
        'backup_settings',
        'advanced_settings',
    ];

    protected $casts = [
        'academic_settings'      => 'array',
        'document_settings'      => 'array',
        'user_settings'          => 'array',
        'notification_settings'  => 'array',
        'finance_settings'       => 'array',
        'communication_settings' => 'array',
        'log_settings'           => 'array',
        'backup_settings'        => 'array',
        'advanced_settings'      => 'array',
    ];
}
