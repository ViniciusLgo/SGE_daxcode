<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    AlunoController,
    AlunoDocumentController,
    ProfessorController,
    DisciplinaController,
    TurmaController,
    Admin\SettingController
};

/*
|--------------------------------------------------------------------------
| Rota Inicial (Pública)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas - Acesso Autenticado e Verificado
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |-----------------------------------
    | Dashboard (Visão Geral)
    |-----------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('auth');
    /*
    |-----------------------------------
    | Módulo Administrativo (/admin)
    |-----------------------------------
    | Aqui ficam todas as rotas do painel administrativo
    | como alunos, professores, turmas e disciplinas.
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // CRUD de Alunos
        Route::resource('alunos', AlunoController::class);

        // CRUD de Professores
        Route::resource('professores', ProfessorController::class);

        // CRUD de Disciplinas
        Route::resource('disciplinas', DisciplinaController::class);

        // CRUD de Turmas
        Route::resource('turmas', TurmaController::class);
    });

    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::resource('aluno_registros', App\Http\Controllers\Admin\AlunoRegistroController::class);
    });

});

/*
|--------------------------------------------------------------------------
| Configurações Gerais do Sistema
|--------------------------------------------------------------------------
| Tela de "Configurações" no menu lateral, onde se define
| nome da instituição, logo, e-mail, telefone etc.
*/

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});

Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('disciplina-turma', App\Http\Controllers\DisciplinaTurmaController::class)
    ->names('admin.disciplina_turma');

/*
|--------------------------------------------------------------------------
| Perfil do Usuário Logado
|--------------------------------------------------------------------------
| Permite editar nome, senha e outras preferências pessoais.
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Documentos de Alunos (Uploads)
|--------------------------------------------------------------------------
| Upload, visualização e exclusão de documentos vinculados a alunos.
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::post('alunos/{aluno}/documentos', [AlunoDocumentController::class, 'store'])
        ->name('alunos.documentos.store');
    Route::delete('documentos/{documento}', [AlunoDocumentController::class, 'destroy'])
        ->name('documentos.destroy');
});

/*
|--------------------------------------------------------------------------
| Autenticação
|--------------------------------------------------------------------------
| Rotas padrão do Breeze / Fortify / Jetstream
*/
require __DIR__.'/auth.php';
