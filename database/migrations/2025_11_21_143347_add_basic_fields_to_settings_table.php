
<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {

            // Adiciona somente se NÃO existir
            if (!Schema::hasColumn('settings', 'school_name')) {
                $table->string('school_name')->nullable();
            }

            if (!Schema::hasColumn('settings', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('settings', 'address')) {
                $table->string('address')->nullable();
            }

            if (!Schema::hasColumn('settings', 'version')) {
                $table->string('version')->nullable();
            }

            if (!Schema::hasColumn('settings', 'logo')) {
                $table->string('logo')->nullable();
            }

            // EMAIL já existe — não adicionamos novamente
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {

            if (Schema::hasColumn('settings', 'school_name')) {
                $table->dropColumn('school_name');
            }

            if (Schema::hasColumn('settings', 'phone')) {
                $table->dropColumn('phone');
            }

            if (Schema::hasColumn('settings', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('settings', 'version')) {
                $table->dropColumn('version');
            }

            if (Schema::hasColumn('settings', 'logo')) {
                $table->dropColumn('logo');
            }

            // EMAIL permanece
        });
    }
};

