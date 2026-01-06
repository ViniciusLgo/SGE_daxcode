<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->update([
            'school_name' => DB::raw('COALESCE(school_name, nome_instituicao)'),
            'phone' => DB::raw('COALESCE(phone, telefone)'),
            'address' => DB::raw('COALESCE(address, endereco)'),
            'version' => DB::raw('COALESCE(version, versao_sistema)'),
        ]);

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'nome_instituicao')) {
                $table->dropColumn('nome_instituicao');
            }
            if (Schema::hasColumn('settings', 'telefone')) {
                $table->dropColumn('telefone');
            }
            if (Schema::hasColumn('settings', 'endereco')) {
                $table->dropColumn('endereco');
            }
            if (Schema::hasColumn('settings', 'versao_sistema')) {
                $table->dropColumn('versao_sistema');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'nome_instituicao')) {
                $table->string('nome_instituicao')->nullable();
            }
            if (!Schema::hasColumn('settings', 'telefone')) {
                $table->string('telefone')->nullable();
            }
            if (!Schema::hasColumn('settings', 'endereco')) {
                $table->string('endereco')->nullable();
            }
            if (!Schema::hasColumn('settings', 'versao_sistema')) {
                $table->string('versao_sistema')->nullable();
            }
        });

        DB::table('settings')->update([
            'nome_instituicao' => DB::raw('COALESCE(nome_instituicao, school_name)'),
            'telefone' => DB::raw('COALESCE(telefone, phone)'),
            'endereco' => DB::raw('COALESCE(endereco, address)'),
            'versao_sistema' => DB::raw('COALESCE(versao_sistema, version)'),
        ]);
    }
};
