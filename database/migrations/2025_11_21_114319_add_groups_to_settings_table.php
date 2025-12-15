<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            // NÃO usar ->after() porque sua tabela não tem 'version'
            $table->json('academic_settings')->nullable()->comment('Configurações acadêmicas');
            $table->json('document_settings')->nullable()->comment('Configurações de documentos e PDFs');
            $table->json('user_settings')->nullable()->comment('Configurações de usuários e acesso');
            $table->json('notification_settings')->nullable()->comment('Configurações de notificações');
            $table->json('finance_settings')->nullable()->comment('Configurações financeiras');
            $table->json('communication_settings')->nullable()->comment('Configurações de comunicação interna');
            $table->json('log_settings')->nullable()->comment('Configurações de log e auditoria');
            $table->json('backup_settings')->nullable()->comment('Configurações de backup');
            $table->json('advanced_settings')->nullable()->comment('Configurações avançadas');
        });
    }


    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'academic_settings',
                'document_settings',
                'user_settings',
                'notification_settings',
                'finance_settings',
                'communication_settings',
                'log_settings',
                'backup_settings',
                'advanced_settings',
            ]);
        });
    }

};
