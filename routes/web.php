<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controllers Públicos
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\{
    ProfileController,
    Auth\FirstAccessController
};

/*
|--------------------------------------------------------------------------
| Controllers Admin – Gerais
|--------------------------------------------------------------------------
*/
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
    ResponsavelController
};

/*
|--------------------------------------------------------------------------
| Controllers Admin – Financeiro
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\FinanceiroDashboardController;
use App\Http\Controllers\Admin\CategoriaDespesaController;
use App\Http\Controllers\Admin\CentroCustoController;
use App\Http\Controllers\Admin\DespesaController;

/*
|--------------------------------------------------------------------------
| Controllers Admin – Secretaria
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\Secretaria\{
    SecretariaDashboardController,
    SecretariaAtendimentoController
};

/*
|--------------------------------------------------------------------------
| Controllers Admin – Gestão Acadêmica
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\GestaoAcademica\AvaliacaoController;
use App\Http\Controllers\Admin\GestaoAcademica\AvaliacaoResultadoController;
use App\Http\Controllers\Admin\GestaoAcademica\BoletimController;

/*
|--------------------------------------------------------------------------
| Página Inicial Pública
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (auth + verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD POR PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return match (Auth::user()->tipo) {
            'admin'        => redirect()->route('admin.dashboard'),
            'professor'    => redirect()->route('dashboard.professor'),
            'aluno'        => redirect()->route('dashboard.aluno'),
            'responsavel'  => redirect()->route('dashboard.responsavel'),
            default        => redirect()->route('login'),
        };
    })->name('dashboard');

    Route::middleware('is_professor')
        ->get('/dashboard/professor', fn () => view('professores.dashboard'))
        ->name('dashboard.professor');

    Route::middleware('is_aluno')
        ->get('/dashboard/aluno', fn () => view('alunos.dashboard'))
        ->name('dashboard.aluno');

    Route::middleware('is_responsavel')
        ->get('/dashboard/responsavel', fn () => view('responsaveis.dashboard'))
        ->name('dashboard.responsavel');

    /*
    |--------------------------------------------------------------------------
    | PAINEL ADMINISTRATIVO
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->as('admin.')
        ->middleware('is_admin')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard Admin
            |--------------------------------------------------------------------------
            */
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | MÓDULO FINANCEIRO
            |--------------------------------------------------------------------------
            */
            Route::prefix('financeiro')->name('financeiro.')->group(function () {

                Route::get('/', [FinanceiroDashboardController::class, 'index'])
                    ->name('dashboard');

                Route::resource('categorias', CategoriaDespesaController::class)
                    ->parameters(['categorias' => 'categoria']);

                Route::resource('centros', CentroCustoController::class)
                    ->parameters(['centros' => 'centro']);

                Route::resource('despesas', DespesaController::class)
                    ->parameters(['despesas' => 'despesa']);

                Route::get('despesas/{despesa}/duplicar', [DespesaController::class, 'duplicar'])
                    ->name('despesas.duplicar');

                Route::post('despesas/clonar-mes-anterior', [DespesaController::class, 'clonarMesAnterior'])
                    ->name('despesas.clonarMesAnterior');

                Route::delete('despesas/excluir-multiplas', [DespesaController::class, 'excluirMultiplas'])
                    ->name('despesas.excluirMultiplas');
            });

            /*
            |--------------------------------------------------------------------------
            | CRUDs PRINCIPAIS
            |--------------------------------------------------------------------------
            */
            Route::resource('usuarios', UserController::class)
                ->parameters(['usuarios' => 'usuario']);

            Route::resource('alunos', AlunoController::class)
                ->parameters(['alunos' => 'aluno']);

            Route::resource('professores', ProfessorController::class)
                ->parameters(['professores' => 'professor']);

            Route::resource('disciplinas', DisciplinaController::class)
                ->parameters(['disciplinas' => 'disciplina']);

            Route::resource('turmas', TurmaController::class)
                ->parameters(['turmas' => 'turma']);

            Route::resource('responsaveis', ResponsavelController::class)
                ->parameters(['responsaveis' => 'responsavel']);

            /*
            |--------------------------------------------------------------------------
            | SECRETARIA ESCOLAR
            |--------------------------------------------------------------------------
            */
            Route::prefix('secretaria')->name('secretaria.')->group(function () {

                Route::get('/', [SecretariaDashboardController::class, 'index'])
                    ->name('dashboard');

                Route::resource('atendimentos', SecretariaAtendimentoController::class);

                Route::get('alunos/{aluno}/responsaveis',
                    [SecretariaAtendimentoController::class, 'responsaveisDoAluno']
                )->name('alunos.responsaveis');
            });

            /*
            |--------------------------------------------------------------------------
            | DISCIPLINA ↔ TURMA ↔ PROFESSOR
            |--------------------------------------------------------------------------
            */
            Route::prefix('turmas/{turma}')
                ->name('turmas.')
                ->group(function () {

                    Route::get('disciplinas', [DisciplinaTurmaController::class, 'edit'])
                        ->name('disciplinas');

                    Route::post('disciplinas', [DisciplinaTurmaController::class, 'store'])
                        ->name('disciplinas.store');

                    Route::delete('disciplinas/{vinculo}', [DisciplinaTurmaController::class, 'destroy'])
                        ->name('disciplinas.destroy');

                    Route::post('disciplinas/{vinculo}/professores',
                        [DisciplinaTurmaController::class, 'vincularProfessor']
                    )->name('disciplinas.professores.store');

                    Route::delete('disciplinas/{vinculo}/professores/{professor}',
                        [DisciplinaTurmaController::class, 'removerProfessor']
                    )->name('disciplinas.professores.destroy');
                });

            /*
            |--------------------------------------------------------------------------
            | VÍNCULO ALUNO ↔ TURMA
            |--------------------------------------------------------------------------
            */
            Route::post('turmas/{turma}/atribuir-aluno',
                [TurmaController::class, 'atribuirAluno']
            )->name('turmas.atribuirAluno');

            /*
            |--------------------------------------------------------------------------
            | REGISTROS E DOCUMENTOS DE ALUNOS
            |--------------------------------------------------------------------------
            */
            Route::resource('aluno_registros', AlunoRegistroController::class)
                ->parameters(['aluno_registros' => 'aluno_registro']);

            Route::post('alunos/{aluno}/documentos',
                [AlunoDocumentController::class, 'store']
            )->name('alunos.documentos.store');

            Route::delete('documentos/{documento}',
                [AlunoDocumentController::class, 'destroy']
            )->name('documentos.destroy');

            /*
 |--------------------------------------------------------------------------
 | GESTÃO ACADÊMICA — AVALIAÇÕES
 |--------------------------------------------------------------------------
 */
            Route::prefix('gestao-academica')
                ->name('gestao_academica.')
                ->group(function () {

                    // CRUD de Avaliações (EVENTO)
                    Route::resource('avaliacoes', AvaliacaoController::class)
                        ->parameters(['avaliacoes' => 'avaliacao']);

                    // Encerrar avaliação
                    Route::patch(
                        'avaliacoes/{avaliacao}/encerrar',
                        [AvaliacaoController::class, 'encerrar']
                    )->name('avaliacoes.encerrar');

                    // Resultados da avaliação (POR ALUNO)
                    Route::get(
                        'avaliacoes/{avaliacao}/resultados',
                        [AvaliacaoResultadoController::class, 'index']
                    )->name('avaliacoes.resultados.index');

                    Route::post(
                        'avaliacoes/{avaliacao}/resultados',
                        [AvaliacaoResultadoController::class, 'store']
                    )->name('avaliacoes.resultados.store');
                });


            /*
|--------------------------------------------------------------------------
| BOLETIM
|--------------------------------------------------------------------------
*/
            Route::prefix('boletim')
                ->name('boletim.')
                ->group(function () {

                    Route::get(
                        'alunos/{aluno}',
                        [BoletimController::class, 'aluno']
                    )->name('aluno');
                });

            Route::prefix('boletim')->name('boletim.')->group(function () {

                Route::get('alunos/{aluno}', [BoletimController::class, 'aluno'])
                    ->name('aluno');

                Route::get('turmas/{turma}', [BoletimController::class, 'turma'])
                    ->name('turma');
            });


            Route::prefix('boletim')
                ->name('boletim.')
                ->group(function () {

                    Route::get('/', function () {
                        return view('admin.gestao_academica.boletim.index');
                    })->name('index');

                    Route::get('alunos/{aluno}', [BoletimController::class, 'aluno'])
                        ->name('aluno');

                    Route::get('turmas/{turma}', [BoletimController::class, 'turma'])
                        ->name('turma');
                });

            Route::get(
                'boletins/turmas/{turma}',
                [BoletimController::class, 'turma']
            )->name('boletins.turmas.show');

            /*
            |--------------------------------------------------------------------------
            | CONFIGURAÇÕES DO SISTEMA
            |--------------------------------------------------------------------------
            */
            Route::controller(SettingController::class)->group(function () {
                Route::get('settings/edit', 'edit')->name('settings.edit');
                Route::put('settings/update', 'update')->name('settings.update');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | PERFIL DO USUÁRIO
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | PRIMEIRO ACESSO
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/first-access', [FirstAccessController::class, 'index'])
            ->name('auth.first_access');

        Route::post('/first-access', [FirstAccessController::class, 'update'])
            ->name('auth.first_access.update');
    });
});

/*
|--------------------------------------------------------------------------
| Autenticação (Laravel Breeze / Jetstream / Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
