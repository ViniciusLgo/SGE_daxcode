<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoDocumentController extends Controller
{
    /**
     * Armazena novo documento do aluno.
     */
    public function store(Request $request, Aluno $aluno)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', 'max:100'],
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'observacoes' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('arquivo')->store('alunos/documentos', 'public');

        $aluno->documentos()->create([
            'tipo' => $validated['tipo'],
            'arquivo' => $path,
            'observacoes' => $validated['observacoes'] ?? null,
        ]);

        return back()->with('status', '📎 Documento enviado com sucesso!');
    }

    /**
     * Exclui um documento do aluno.
     */
    public function destroy(UserDocument $documento)
    {
        if (Storage::disk('public')->exists($documento->arquivo)) {
            Storage::disk('public')->delete($documento->arquivo);
        }

        $documento->delete();

        return back()->with('status', '🗑️ Documento excluído com sucesso.');
    }
}
