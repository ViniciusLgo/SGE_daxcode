<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Evita duplicar caso rode novamente
        $alunosSemMatricula = DB::table('alunos')
            ->leftJoin('matriculas', 'matriculas.aluno_id', '=', 'alunos.id')
            ->whereNull('matriculas.id')
            ->select(
                'alunos.id as aluno_id',
                'alunos.turma_id',
                'alunos.matricula',
                'alunos.created_at'
            )
            ->get();

        foreach ($alunosSemMatricula as $aluno) {
            DB::table('matriculas')->insert([
                'aluno_id'        => $aluno->aluno_id,
                'turma_id'        => $aluno->turma_id,
                'codigo'          => $aluno->matricula,
                'status'          => 'ativo',
                'data_status'     => $aluno->created_at ?? now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Rollback seguro: remove apenas matrículas criadas a partir de alunos
        DB::table('matriculas')
            ->whereIn('aluno_id', function ($query) {
                $query->select('id')->from('alunos');
            })
            ->delete();
    }
};
