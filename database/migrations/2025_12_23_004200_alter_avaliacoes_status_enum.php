<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('avaliacoes')) {
            Schema::table('avaliacoes', function (Blueprint $table) {
                $table->enum('status', ['aberta', 'encerrada'])
                    ->default('aberta')
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('avaliacoes')) {
            Schema::table('avaliacoes', function (Blueprint $table) {
                $table->string('status')->change();
            });
        }
    }
};
