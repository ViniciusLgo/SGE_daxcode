<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers públicos
use App\Http\Controllers\{
    ProfileController,
    Auth\FirstAccessController
};

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
    ResponsavelController
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
    | Painel ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->middleware('is_admin')->group(function () {

        // Dashboard do admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | CRUDs principais
        |--------------------------------------------------------------------------
        */
        Route::resource('usuarios', UserController::class)
            ->names('usuarios')
            ->parameters(['usuarios' => 'usuario']);

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

        Route::resource('responsaveis', ResponsavelController::class)
            ->names('responsaveis')
            ->parameters(['responsaveis' => 'responsavel']);

        /*
        |--------------------------------------------------------------------------
        | DISCIPLINA ↔ TURMA ↔ PROFESSOR  (OFICIAL)
        |--------------------------------------------------------------------------
        */
        Route::prefix('turmas/{turma}')->name('turmas.')->group(function () {

            // Tela principal do gerenciamento de vínculos
            Route::get('/disciplinas', [DisciplinaTurmaController::class, 'edit'])
                ->name('disciplinas');

            // Adicionar disciplina
            Route::post('/disciplinas', [DisciplinaTurmaController::class, 'store'])
                ->name('disciplinas.store');

            // Remover disciplina
            Route::delete('/disciplinas/{vinculo}', [DisciplinaTurmaController::class, 'destroy'])
                ->name('disciplinas.destroy');

            // Adicionar professor
            Route::post('/disciplinas/{vinculo}/professores', [DisciplinaTurmaController::class, 'vincularProfessor'])
                ->name('disciplinas.professores.store');

            // Remover professor
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
            ->names('aluno_registros')
            ->parameters(['aluno_registros' => 'aluno_registro']);

        Route::post('alunos/{aluno}/documentos', [AlunoDocumentController::class, 'store'])
            ->name('alunos.documentos.store');

        Route::delete('documentos/{documento}', [AlunoDocumentController::class, 'destroy'])
            ->name('documentos.destroy');

        /*
        |--------------------------------------------------------------------------
        | Configurações
        |--------------------------------------------------------------------------
        */
        Route::controller(SettingController::class)->group(function () {
            Route::get('settings/edit', 'edit')->name('settings.edit');
            Route::post('settings/update', 'update')->name('settings.update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard conforme tipo do usuário
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->tipo === 'admin') return redirect()->route('admin.dashboard');
        if ($user->tipo === 'professor') return redirect()->route('dashboard.professor');
        if ($user->tipo === 'aluno') return redirect()->route('dashboard.aluno');
        if ($user->tipo === 'responsavel') return redirect()->route('dashboard.responsavel');

        return redirect()->route('login');
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
