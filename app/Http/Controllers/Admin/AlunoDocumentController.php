<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoDocumentController extends Controller
{
    private const TIPOS_DOCUMENTOS = [
        'Certidao de Nascimento',
        'RG',
        'CPF',
        'Comprovante de Residencia',
        'Historico Escolar',
        'Transferencia Escolar',
        'Foto 3x4',
        'Carteira de Vacinacao',
        'Declaracao de Vacinas',
        'Laudo Medico',
        'Termo de Responsabilidade',
        'Contrato de Matricula',
        'Documento do Responsavel',
    ];

    /**
     * Lista documentos do aluno.
     */
    public function index(Aluno $aluno)
    {
        $documentos = $aluno->documentos()->orderByDesc('created_at')->get();
        return view('admin.aluno_documentos.index', compact('aluno', 'documentos'));
    }

    /**
     * Lista todos os documentos (visao geral).
     */
    public function indexAll()
    {
        $documentos = UserDocument::with('aluno.user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.aluno_documentos.all', compact('documentos'));
    }

    /**
     * Formulario de novo documento (visao geral).
     */
    public function createAll()
    {
        $tipos = self::TIPOS_DOCUMENTOS;
        $alunos = Aluno::with('user')
            ->orderBy('id')
            ->get();

        return view('admin.aluno_documentos.create_all', compact('tipos', 'alunos'));
    }

    /**
     * Salva documento (visao geral).
     */
    public function storeAll(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => ['required', 'exists:alunos,id'],
            'tipo' => ['required', 'string', 'max:100'],
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'observacoes' => ['nullable', 'string', 'max:255'],
            'data_envio' => ['nullable', 'date'],
        ]);

        $path = $request->file('arquivo')->store('alunos/documentos', 'public');

        UserDocument::create([
            'aluno_id' => $validated['aluno_id'],
            'tipo' => $validated['tipo'],
            'arquivo' => $path,
            'observacoes' => $validated['observacoes'] ?? null,
            'data_envio' => $validated['data_envio'] ?? null,
        ]);

        return redirect()
            ->route('admin.documentos.index')
            ->with('status', 'Documento enviado com sucesso!');
    }

    /**
     * Formulario de novo documento.
     */
    public function create(Aluno $aluno)
    {
        $tipos = self::TIPOS_DOCUMENTOS;
        return view('admin.aluno_documentos.create', compact('aluno', 'tipos'));
    }

    /**
     * Armazena novo documento do aluno.
     */
    public function store(Request $request, Aluno $aluno)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:100'],
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'observacoes' => ['nullable', 'string', 'max:255'],
            'data_envio' => ['nullable', 'date'],
        ]);

        $path = $request->file('arquivo')->store('alunos/documentos', 'public');

        $aluno->documentos()->create([
            'tipo' => $validated['tipo'],
            'arquivo' => $path,
            'observacoes' => $validated['observacoes'] ?? null,
            'data_envio' => $validated['data_envio'] ?? null,
        ]);

        return redirect()
            ->route('admin.alunos.documentos.index', $aluno)
            ->with('status', 'Documento enviado com sucesso!');
    }

    /**
     * Exibe documento.
     */
    public function show(UserDocument $documento)
    {
        $documento->load('aluno.user');
        return view('admin.aluno_documentos.show', compact('documento'));
    }

    /**
     * Formulario de edicao do documento.
     */
    public function edit(UserDocument $documento)
    {
        $documento->load('aluno.user');
        $tipos = self::TIPOS_DOCUMENTOS;
        return view('admin.aluno_documentos.edit', compact('documento', 'tipos'));
    }

    /**
     * Atualiza documento do aluno.
     */
    public function update(Request $request, UserDocument $documento)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:100'],
            'arquivo' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'observacoes' => ['nullable', 'string', 'max:255'],
            'data_envio' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('arquivo')) {
            if ($documento->arquivo && Storage::disk('public')->exists($documento->arquivo)) {
                Storage::disk('public')->delete($documento->arquivo);
            }
            $documento->arquivo = $request->file('arquivo')->store('alunos/documentos', 'public');
        }

        $documento->tipo = $validated['tipo'];
        $documento->observacoes = $validated['observacoes'] ?? null;
        $documento->data_envio = $validated['data_envio'] ?? $documento->data_envio;
        $documento->save();

        return redirect()
            ->route('admin.documentos.show', $documento)
            ->with('status', 'Documento atualizado com sucesso!');
    }

    /**
     * Exclui um documento do aluno.
     */
    public function destroy(UserDocument $documento)
    {
        $alunoId = $documento->aluno_id;

        if (Storage::disk('public')->exists($documento->arquivo)) {
            Storage::disk('public')->delete($documento->arquivo);
        }

        $documento->delete();

        return redirect()
            ->route('admin.alunos.documentos.index', $alunoId)
            ->with('status', 'Documento excluido com sucesso.');
    }
}
