<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController
};

use App\Http\Controllers\Admin\{
    DashboardController,
    AlunoController,
    AlunoRegistroController,
    ProfessorController,
    DisciplinaController,
    TurmaController,
    DisciplinaTurmaController,
    SettingController,
    AlunoDocumentController
};

/*
|--------------------------------------------------------------------------
| Página inicial pública
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas protegidas (usuário autenticado e verificado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Painel Administrativo (/admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->group(function () {

        // CRUDs principais
        Route::resource('alunos', AlunoController::class)
            ->names('alunos')
            ->parameters(['alunos' => 'aluno']);

        Route::resource('professores', ProfessorController::class)
            ->names('professores')
            ->parameters(['professores' => 'professor']);

        Route::resource('disciplinas', DisciplinaController::class)
            ->names('disciplinas')
            ->parameters(['disciplinas' => 'disciplina']);

        Route::resource('turmas', TurmaController::class)
            ->names('turmas')
            ->parameters(['turmas' => 'turma']);

        /*
        |--------------------------------------------------------------------------
        | Vínculo: Disciplinas ↔ Turmas ↔ Professores
        |--------------------------------------------------------------------------
        */

        Route::post('turmas/{turma}/disciplinas', [TurmaController::class, 'adicionarDisciplina'])
            ->name('turmas.adicionarDisciplina');
        Route::delete('turmas/{turma}/disciplinas/{vinculo}', [TurmaController::class, 'removerDisciplina'])
            ->name('turmas.removerDisciplina');

        Route::post('disciplinas/{disciplina}/vincular-professor', [DisciplinaController::class, 'vincularProfessor'])->name('disciplinas.vincularProfessor');
        Route::post('disciplinas/{disciplina}/vincular-turma', [DisciplinaController::class, 'vincularTurma'])->name('disciplinas.vincularTurma');

        /*
        |--------------------------------------------------------------------------
        | Registros e Ocorrências de Alunos
        |--------------------------------------------------------------------------
        */
        Route::resource('aluno_registros', AlunoRegistroController::class)
            ->names('aluno_registros')
            ->parameters(['aluno_registros' => 'aluno_registro']);

        /*
        |--------------------------------------------------------------------------
        | Upload e exclusão de documentos de alunos
        |--------------------------------------------------------------------------
        */
        Route::post('alunos/{aluno}/documentos', [AlunoDocumentController::class, 'store'])
            ->name('alunos.documentos.store');
        Route::delete('documentos/{documento}', [AlunoDocumentController::class, 'destroy'])
            ->name('documentos.destroy');

        /*
        |--------------------------------------------------------------------------
        | Configurações do sistema
        |--------------------------------------------------------------------------
        */
        Route::controller(SettingController::class)->group(function () {
            Route::get('settings/edit', 'edit')->name('settings.edit');
            Route::post('settings/update', 'update')->name('settings.update');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Perfil do usuário logado
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Autenticação padrão (Breeze / Fortify / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
