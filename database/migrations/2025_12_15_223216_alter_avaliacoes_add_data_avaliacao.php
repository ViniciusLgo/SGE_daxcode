<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {

            // remove a coluna antiga (se existir)
            if (Schema::hasColumn('avaliacoes', 'data')) {
                $table->dropColumn('data');
            }

            // cria a coluna correta
            $table->date('data_avaliacao')->nullable()->after('peso');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropColumn('data_avaliacao');
            $table->date('data')->nullable();
        });
    }
};
