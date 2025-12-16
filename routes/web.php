<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\GestaoAcademica\AvaliacaoController;


// Controllers públicos
use App\Http\Controllers\{
    ProfileController,
    Auth\FirstAccessController
};

use App\Http\Controllers\Admin\FinanceiroDashboardController;
use App\Http\Controllers\Admin\CategoriaDespesaController;
use App\Http\Controllers\Admin\CentroCustoController;
use App\Http\Controllers\Admin\DespesaController;

use App\Http\Controllers\Admin\Secretaria\SecretariaDashboardController;

// Controllers Admin
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    AlunoController,
    AlunoRegistroController,
    ProfessorController,
    DisciplinaController,
    TurmaController,
    DisciplinaTurmaController,
    SettingController,
    AlunoDocumentController,
    ResponsavelController,
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
| Rotas protegidas (Usuário autenticado + verificado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | MÓDULO FINANCEIRO (Fase 1)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/financeiro')->name('admin.financeiro.')->middleware(['is_admin'])->group(function () {

        Route::get('/', [FinanceiroDashboardController::class, 'index'])->name('dashboard');

        Route::resource('categorias', CategoriaDespesaController::class)->names('categorias');
        Route::resource('centros', CentroCustoController::class)->names('centros');
        Route::resource('despesas', DespesaController::class)->names('despesas');

        Route::get('/despesas/{id}/duplicar', [DespesaController::class, 'duplicar'])
            ->name('despesas.duplicar');

        Route::post('/despesas/clonar-mes-anterior', [DespesaController::class, 'clonarMesAnterior'])
            ->name('despesas.clonarMesAnterior');

        Route::delete('/despesas/excluir-multiplas', [DespesaController::class, 'excluirMultiplas'])
            ->name('despesas.excluirMultiplas');
    });

    /*
    |--------------------------------------------------------------------------
    | Painel ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->middleware('is_admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('buscar-turma-aluno/{id}', [AlunoRegistroController::class, 'buscarTurma'])
            ->name('aluno_registros.buscar_turma');

        // CRUDs
        Route::resource('usuarios', UserController::class)->parameters(['usuarios' => 'usuario']);
        Route::resource('alunos', AlunoController::class)->parameters(['alunos' => 'aluno']);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);
        Route::resource('disciplinas', DisciplinaController::class)->parameters(['disciplinas' => 'disciplina']);
        Route::resource('turmas', TurmaController::class)->parameters(['turmas' => 'turma']);
        Route::resource('responsaveis', ResponsavelController::class)->parameters(['responsaveis' => 'responsavel']);

        Route::get('/secretaria', [SecretariaDashboardController::class, 'index'])
            ->name('secretaria.dashboard');


        /*
        |--------------------------------------------------------------------------
        | DISCIPLINA ↔ TURMA ↔ PROFESSOR
        |--------------------------------------------------------------------------
        */
        Route::prefix('turmas/{turma}')->name('turmas.')->group(function () {
            Route::get('/disciplinas', [DisciplinaTurmaController::class, 'edit'])->name('disciplinas');
            Route::post('/disciplinas', [DisciplinaTurmaController::class, 'store'])->name('disciplinas.store');
            Route::delete('/disciplinas/{vinculo}', [DisciplinaTurmaController::class, 'destroy'])->name('disciplinas.destroy');

            Route::post('/disciplinas/{vinculo}/professores', [DisciplinaTurmaController::class, 'vincularProfessor'])
                ->name('disciplinas.professores.store');

            Route::delete('/disciplinas/{vinculo}/professores/{professor}', [DisciplinaTurmaController::class, 'removerProfessor'])
                ->name('disciplinas.professores.destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Vínculo aluno ↔ turma
        |--------------------------------------------------------------------------
        */
        Route::post('turmas/{turma}/atribuir-aluno', [TurmaController::class, 'atribuirAluno'])
            ->name('turmas.atribuirAluno');

        /*
        |--------------------------------------------------------------------------
        | Registro e Documentos de Alunos
        |--------------------------------------------------------------------------
        */
        Route::resource('aluno_registros', AlunoRegistroController::class)
            ->parameters(['aluno_registros' => 'aluno_registro']);

        Route::post('alunos/{aluno}/documentos', [AlunoDocumentController::class, 'store'])
            ->name('alunos.documentos.store');

        Route::delete('documentos/{documento}', [AlunoDocumentController::class, 'destroy'])
            ->name('documentos.destroy');

        /*
        |--------------------------------------------------------------------------
        | Configurações — DEFINITIVO
        |--------------------------------------------------------------------------
        */
        Route::controller(SettingController::class)->group(function () {
            Route::get('settings/edit', 'edit')->name('settings.edit');
            Route::put('settings/update', 'update')->name('settings.update'); // ✔ CORRIGIDO
        });

        Route::prefix('gestao-academica')
            ->name('gestao_academica.')
            ->group(function () {

                Route::resource(
                    'avaliacoes',
                    \App\Http\Controllers\Admin\GestaoAcademica\AvaliacaoController::class
                )->parameters([
                    'avaliacoes' => 'avaliacao'
                ]);


                Route::post(
                    'avaliacoes/{avaliacao}/encerrar',
                    [AvaliacaoController::class, 'encerrar']
                )->name('avaliacoes.encerrar');

                Route::post(
                    'avaliacoes/{avaliacao}/reabrir',
                    [AvaliacaoController::class, 'reabrir']
                )->name('avaliacoes.reabrir');
            });


    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard conforme tipo do usuário
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {

        $user = Auth::user();

        return match ($user->tipo) {
            'admin' => redirect()->route('admin.dashboard'),
            'professor' => redirect()->route('dashboard.professor'),
            'aluno' => redirect()->route('dashboard.aluno'),
            'responsavel' => redirect()->route('dashboard.responsavel'),
            default => redirect()->route('login'),
        };
    })->name('dashboard');

    Route::middleware('is_professor')->get('/dashboard/professor', fn() => view('professores.dashboard'))
        ->name('dashboard.professor');

    Route::middleware('is_aluno')->get('/dashboard/aluno', fn() => view('alunos.dashboard'))
        ->name('dashboard.aluno');

    Route::middleware('is_responsavel')->get('/dashboard/responsavel', fn() => view('responsaveis.dashboard'))
        ->name('dashboard.responsavel');
});

/*
|--------------------------------------------------------------------------
| Perfil
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Primeiro acesso
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/first-access', [FirstAccessController::class, 'index'])->name('auth.first_access');
    Route::post('/first-access', [FirstAccessController::class, 'update'])->name('auth.first_access.update');
});

/*
|--------------------------------------------------------------------------
| Autenticação
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
