<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\User;
use App\Models\Responsavel;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\CodeGenerator;

class AlunoController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * Listagem de alunos com filtros e status da matricula
     * ============================================================
     */
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $status   = $request->input('status');
        $turmaId  = $request->input('turma_id');
        $ano      = $request->input('ano');
        $turno    = $request->input('turno');

        $alunos = Aluno::with(['user', 'turma', 'matriculaModel'])

            // BUSCA POR NOME / EMAIL / MATRICULA
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                        ->orWhere('matricula', 'like', "%{$search}%");
                });
            })

            // FILTRO POR STATUS DA MATRICULA
            ->when($status, function ($query) use ($status) {
                $query->whereHas('matriculaModel', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })

            // FILTRO POR TURMA (VIA MATRICULA)
            ->when($turmaId, function ($query) use ($turmaId) {
                $query->whereHas('matriculaModel', function ($q) use ($turmaId) {
                    $q->where('turma_id', $turmaId);
                });
            })

            // FILTRO POR ANO
            ->when($ano, function ($query) use ($ano) {
                $query->whereHas('turma', function ($q) use ($ano) {
                    $q->where('ano', $ano);
                });
            })

            // FILTRO POR TURNO
            ->when($turno, function ($query) use ($turno) {
                $query->whereHas('turma', function ($q) use ($turno) {
                    $q->where('turno', $turno);
                });
            })

            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $turmas = Turma::orderBy('nome')->get();

        return view('admin.alunos.index', compact(
            'alunos',
            'turmas',
            'search',
            'status',
            'turmaId',
            'ano',
            'turno'
        ));
    }

    /**
     * ============================================================
     * CREATE
     * Tela de cadastro do aluno (complemento do usuario)
     * ============================================================
     */
    public function create(Request $request)
    {
        $user = null;

        // Quando vem do cadastro de usuario
        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);

            // Evita criar aluno duplicado para o mesmo usuario
            if ($user->aluno) {
                return redirect()
                    ->route('admin.alunos.edit', $user->aluno->id)
                    ->with('info', 'Este usuario ja possui cadastro de aluno.');
            }
        }

        $turmas = Turma::orderBy('nome')->get();

        return view('admin.alunos.create', compact('user', 'turmas'));
    }

    /**
     * ============================================================
     * STORE
     * Cria:
     * 1) Aluno
     * 2) Matricula academica (status)
     * ============================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|exists:users,id',
            'turma_id'        => 'required|exists:turmas,id',
            'telefone'        => 'nullable|string|max:20',
            'foto_perfil'     => 'nullable|image|max:2048',
            'data_nascimento' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request, $validated) {
            /**
             * ------------------------------------------------------------
             * GERA CODIGO DO ALUNO (IDENTIDADE)
             * Ex: ALU-2025-00009
             * ------------------------------------------------------------
             */
            $codigoAluno = CodeGenerator::next('aluno');

            /**
             * ------------------------------------------------------------
             * UPLOAD DA FOTO (SE EXISTIR)
             * ------------------------------------------------------------
             */
            $foto = null;
            if ($request->hasFile('foto_perfil')) {
                $foto = $request->file('foto_perfil')
                    ->store('alunos/fotos', 'public');
            }

            /**
             * ------------------------------------------------------------
             * CRIA O ALUNO
             * ------------------------------------------------------------
             */
            $aluno = Aluno::create([
                'user_id'         => $validated['user_id'],
                'turma_id'        => $validated['turma_id'],
                'data_nascimento' => $validated['data_nascimento'] ?? null,
                'matricula'       => $codigoAluno,
                'telefone'        => $validated['telefone'] ?? null,
                'foto_perfil'     => $foto,
            ]);

            /**
             * ------------------------------------------------------------
             * GERA CODIGO DA MATRICULA (OBRIGATORIO NO BANCO)
             * Ex: MAT-2025-00001
             * ------------------------------------------------------------
             */
            $codigoMatricula = CodeGenerator::next('matricula');

            /**
             * ------------------------------------------------------------
             * CRIA MATRICULA ACADEMICA
             * ------------------------------------------------------------
             */
            $aluno->matriculaModel()->create([
                'codigo'            => $codigoMatricula,
                'turma_id'          => $validated['turma_id'],
                'status'            => 'ativo',
                'data_status'       => now(),
                'motivo'            => null,
                'observacao'        => 'Matricula inicial criada automaticamente',
                'user_id_alteracao' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * ============================================================
     * EDIT
     * ============================================================
     */
    public function edit($id)
    {
        $aluno = Aluno::with([
            'user',
            'turma',
            'responsaveis',
            'matriculaModel'
        ])->findOrFail($id);

        $turmas = Turma::all();
        $responsaveis = Responsavel::with('user')->get();

        return view('admin.alunos.edit', compact(
            'aluno',
            'turmas',
            'responsaveis'
        ));
    }

    /**
     * ============================================================
     * UPDATE
     * ============================================================
     */
    public function update(Request $request, $id)
    {
        $aluno = Aluno::with('user')->findOrFail($id);

        $validated = $request->validate([
            'telefone'    => 'nullable|string|max:20',
            'turma_id'    => 'required|exists:turmas,id',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        // Atualiza foto se enviada
        if ($request->hasFile('foto_perfil')) {
            if ($aluno->foto_perfil) {
                Storage::disk('public')->delete($aluno->foto_perfil);
            }

            $aluno->foto_perfil = $request->file('foto_perfil')
                ->store('alunos/fotos', 'public');
        }

        // Atualiza dados editaveis do aluno
        $aluno->update([
            'telefone'    => $validated['telefone'] ?? null,
            'turma_id'    => $validated['turma_id'],
            'foto_perfil' => $aluno->foto_perfil,
        ]);

        // Sincroniza responsaveis (se houver)
        $aluno->responsaveis()->sync($request->responsaveis ?? []);

        return redirect()
            ->back()
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * ============================================================
     * SHOW
     * ============================================================
     */
    public function show($id)
    {
        $aluno = Aluno::with([
            'user',
            'turma',
            'responsaveis',
            'documentos',
            'registros',
            'matriculaModel',
        ])->findOrFail($id);

        return view('admin.alunos.show', compact('aluno'));
    }

    /**
     * ============================================================
     * DESTROY
     * ============================================================
     */
    public function destroy($id)
    {
        $aluno = Aluno::with([
            'user',
            'matriculaModel',
            'responsaveis',
        ])->findOrFail($id);

        // Remove foto do aluno, se existir
        if ($aluno->foto_perfil) {
            Storage::disk('public')->delete($aluno->foto_perfil);
        }

        // Remove matricula (se existir)
        if ($aluno->matriculaModel) {
            $aluno->matriculaModel->delete();
        }

        // Desvincula responsaveis (pivot)
        if (method_exists($aluno, 'responsaveis')) {
            $aluno->responsaveis()->detach();
        }

        // Remove usuario APENAS se existir
        if ($aluno->user) {
            $aluno->user->delete();
        }

        // Remove o aluno
        $aluno->delete();

        return redirect()
            ->route('admin.alunos.index')
            ->with('success', 'Aluno removido com sucesso!');
    }

}
