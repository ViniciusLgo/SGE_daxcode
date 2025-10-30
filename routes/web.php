<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    Auth\FirstAccessController
};

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
| Rotas protegidas (usuário autenticado e verificado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Painel Administrativo (Admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->as('admin.')->middleware('is_admin')->group(function () {

        // Dashboard principal do admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD de usuários
        Route::resource('usuarios', UserController::class)
            ->names('usuarios')
            ->parameters(['usuarios' => 'usuario']);

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

        Route::resource('responsaveis', ResponsavelController::class)
            ->names('responsaveis')
            ->parameters(['responsaveis' => 'responsavel']);

        /*
        |--------------------------------------------------------------------------
        | Vínculos: Disciplinas ↔ Turmas ↔ Professores
        |--------------------------------------------------------------------------
        */
        Route::post('turmas/{turma}/disciplinas', [TurmaController::class, 'adicionarDisciplina'])
            ->name('turmas.adicionarDisciplina');

        Route::delete('turmas/{turma}/disciplinas/{vinculo}', [TurmaController::class, 'removerDisciplina'])
            ->name('turmas.removerDisciplina');

        Route::post('disciplinas/{disciplina}/vincular-professor', [DisciplinaController::class, 'vincularProfessor'])
            ->name('disciplinas.vincularProfessor');

        Route::post('disciplinas/{disciplina}/vincular-turma', [DisciplinaController::class, 'vincularTurma'])
            ->name('disciplinas.vincularTurma');

        /*
        |--------------------------------------------------------------------------
        | Registros e Documentos de Alunos
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
        | Configurações do sistema
        |--------------------------------------------------------------------------
        */
        Route::controller(SettingController::class)->group(function () {
            Route::get('settings/edit', 'edit')->name('settings.edit');
            Route::post('settings/update', 'update')->name('settings.update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Rota genérica de Dashboard — redireciona conforme o tipo do usuário
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->tipo) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'professor':
                return redirect()->route('dashboard.professor');
            case 'aluno':
                return redirect()->route('dashboard.aluno');
            case 'responsavel':
                return redirect()->route('dashboard.responsavel');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Dashboards individuais (Professor, Aluno, Responsável)
    |--------------------------------------------------------------------------
    */
    Route::middleware('is_professor')->get('/dashboard/professor', fn() => view('professores.dashboard'))
        ->name('dashboard.professor');

    Route::middleware('is_aluno')->get('/dashboard/aluno', fn() => view('alunos.dashboard'))
        ->name('dashboard.aluno');

    Route::middleware('is_responsavel')->get('/dashboard/responsavel', fn() => view('responsaveis.dashboard'))
        ->name('dashboard.responsavel');
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
| Primeiro acesso (troca de senha)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/first-access', [FirstAccessController::class, 'index'])->name('auth.first_access');
    Route::post('/first-access', [FirstAccessController::class, 'update'])->name('auth.first_access.update');
});

/*
|--------------------------------------------------------------------------
| Autenticação padrão (Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
