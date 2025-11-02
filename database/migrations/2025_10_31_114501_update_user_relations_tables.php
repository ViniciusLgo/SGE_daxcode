<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 🔹 ALUNOS
         * - adiciona user_id
         * - remove nome/email duplicados
         */
        Schema::table('alunos', function (Blueprint $table) {
            if (!Schema::hasColumn('alunos', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }


            if (Schema::hasColumn('alunos', 'nome')) {
                $table->dropColumn('nome');
            }

            if (Schema::hasColumn('alunos', 'email')) {
                $table->dropColumn('email');
            }
        });

        /**
         * 🔹 PROFESSORES
         * - adiciona user_id (sem FK por enquanto)
         * - remove nome/email duplicados
         */
        Schema::table('professores', function (Blueprint $table) {
            if (!Schema::hasColumn('professores', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (Schema::hasColumn('professores', 'nome')) {
                $table->dropColumn('nome');
            }

            if (Schema::hasColumn('professores', 'email')) {
                $table->dropColumn('email');
            }
        });

        /**
         * 🔹 RESPONSAVEIS
         * - renomeia usuario_id → user_id
         * - remove nome/email duplicados
         * - ajusta foreign key
         */
        Schema::table('responsaveis', function (Blueprint $table) {
            if (Schema::hasColumn('responsaveis', 'usuario_id')) {
                $table->renameColumn('usuario_id', 'user_id');
            }

            if (Schema::hasColumn('responsaveis', 'nome')) {
                $table->dropColumn('nome');
            }

            if (Schema::hasColumn('responsaveis', 'email')) {
                $table->dropColumn('email');
            }
        });

        // Garante a FK correta (após renomear)
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        /**
         * Reverte as alterações (recria colunas removidas)
         */
        Schema::table('alunos', function (Blueprint $table) {
            if (!Schema::hasColumn('alunos', 'nome')) {
                $table->string('nome')->nullable();
            }
            if (!Schema::hasColumn('alunos', 'email')) {
                $table->string('email')->nullable();
            }
            if (Schema::hasColumn('alunos', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('professores', function (Blueprint $table) {
            if (!Schema::hasColumn('professores', 'nome')) {
                $table->string('nome')->nullable();
            }
            if (!Schema::hasColumn('professores', 'email')) {
                $table->string('email')->nullable();
            }
            if (Schema::hasColumn('professores', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('responsaveis', function (Blueprint $table) {
            if (!Schema::hasColumn('responsaveis', 'nome')) {
                $table->string('nome')->nullable();
            }
            if (!Schema::hasColumn('responsaveis', 'email')) {
                $table->string('email')->nullable();
            }
            if (Schema::hasColumn('responsaveis', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->renameColumn('user_id', 'usuario_id');
            }
        });
    }
};
