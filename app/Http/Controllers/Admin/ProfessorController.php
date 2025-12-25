<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfessorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $professores = Professor::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.professores.index', compact('professores', 'search'));
    }

    /**
     * CREATE — recebe user_id
     */
    public function create(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        return view('admin.professores.create', compact('user'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'telefone'       => 'nullable|string|max:20',
            'especializacao' => 'nullable|string|max:255',
            'foto_perfil'    => 'nullable|image|max:5120',
        ]);

        $foto = null;
        if ($request->hasFile('foto_perfil')) {
            $foto = $request->file('foto_perfil')->store('professores/fotos', 'public');
        }

        Professor::create([
            'user_id'        => $validated['user_id'],
            'telefone'       => $validated['telefone'] ?? null,
            'especializacao' => $validated['especializacao'] ?? null,
            'foto_perfil'    => $foto,
        ]);

        return redirect()
            ->route('admin.professores.index')
            ->with('success', 'Professor cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $professor = Professor::with('user')->findOrFail($id);
        return view('admin.professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        $professor = Professor::with('user')->findOrFail($id);

        $validated = $request->validate([
            'user.name'   => 'required|string|max:255',
            'user.email'  => 'required|email|unique:users,email,' . $professor->user->id,
            'telefone'    => 'nullable|string|max:20',
            'especializacao' => 'nullable|string|max:255',
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        $professor->user->update($validated['user']);

        if ($request->hasFile('foto_perfil')) {
            if ($professor->foto_perfil) {
                Storage::disk('public')->delete($professor->foto_perfil);
            }
            $professor->foto_perfil = $request->file('foto_perfil')->store('professores/fotos', 'public');
        }

        $professor->update([
            'telefone'       => $validated['telefone'] ?? null,
            'especializacao' => $validated['especializacao'] ?? null,
            'foto_perfil'    => $professor->foto_perfil,
        ]);

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $professor = Professor::with('user')->findOrFail($id);

        if ($professor->foto_perfil) {
            Storage::disk('public')->delete($professor->foto_perfil);
        }

        $professor->user->delete();
        $professor->delete();

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor removido com sucesso!');
    }
}
