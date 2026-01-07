<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigo_sequencias', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50);
            $table->unsignedInteger('ano');
            $table->unsignedInteger('sequencia');
            $table->timestamps();

            $table->unique(['tipo', 'ano']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigo_sequencias');
    }
};
