<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ALTERAÇÃO DIRETA DO ENUM
        DB::statement("
            ALTER TABLE avaliacoes
            MODIFY status ENUM('aberta','encerrada')
            DEFAULT 'aberta'
        ");
    }

    public function down(): void
    {
        // rollback seguro
        DB::statement("
            ALTER TABLE avaliacoes
            MODIFY status VARCHAR(20)
        ");
    }
};
