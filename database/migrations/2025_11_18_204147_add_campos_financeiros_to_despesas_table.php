<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->string('fornecedor')->nullable()->after('descricao');
            $table->string('forma_pagamento', 50)->nullable()->after('fornecedor'); // pix, dinheiro, etc.
            $table->string('status_pagamento', 50)->default('pendente')->after('forma_pagamento'); // pago, pendente...
            $table->string('numero_nf')->nullable()->after('status_pagamento');
            $table->string('conta')->nullable()->after('numero_nf'); // Fundo Educacional, Projeto Social, etc.
            $table->foreignId('responsavel_id')
                ->nullable()
                ->after('conta')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropForeign(['responsavel_id']);
            $table->dropColumn([
                'fornecedor',
                'forma_pagamento',
                'status_pagamento',
                'numero_nf',
                'conta',
                'responsavel_id',
            ]);
        });
    }
};
