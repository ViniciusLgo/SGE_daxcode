<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Altera o tipo do campo para ENUM
            $table->enum('tipo', ['admin', 'professor', 'aluno', 'responsavel'])
                ->default('aluno')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipo', 255)->nullable()->change();
        });
    }
};
