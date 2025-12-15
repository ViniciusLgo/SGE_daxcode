<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias_despesas')->onDelete('cascade');
            $table->foreignId('centro_custo_id')->nullable()->constrained('centros_custos')->nullOnDelete();

            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->string('descricao')->nullable();
            $table->string('arquivo')->nullable(); // nota fiscal / recibo
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // quem lançou

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
