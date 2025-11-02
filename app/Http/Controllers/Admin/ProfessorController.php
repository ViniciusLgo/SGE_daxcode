<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            ->withCount('disciplinas')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.professores.index', compact('professores', 'search'));
    }

    public function create()
    {
        return view('admin.professores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'telefone'        => 'nullable|string|max:20',
            'especializacao'  => 'nullable|string|max:255',
            'foto_perfil'     => 'nullable|image|max:5120',
        ]);

        // Cria usuário vinculado
        $user = User::create([
            'name'     => $validated['nome'],
            'email'    => $validated['email'],
            'password' => Hash::make('123456789'),
            'tipo'     => 'professor',
        ]);

        // Upload da foto
        $fotoPath = null;
        if ($request->hasFile('foto_perfil')) {
            $fotoPath = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }

        // Cria professor
        Professor::create([
            'user_id'        => $user->id,
            'telefone'       => $validated['telefone'] ?? null,
            'especializacao' => $validated['especializacao'] ?? null,
            'foto_perfil'    => $fotoPath,
        ]);

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor e usuário criados com sucesso!');
    }

    public function show($id)
    {
        $professor = Professor::with('user')->findOrFail($id);
        $disciplinas = $professor->disciplinas()->paginate(10);

        return view('admin.professores.show', compact('professor', 'disciplinas'));
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
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $professor->user->id,
            'telefone'        => 'nullable|string|max:20',
            'especializacao'  => 'nullable|string|max:255',
            'foto_perfil'     => 'nullable|image|max:5120',
        ]);

        // Atualiza User
        $professor->user->update([
            'name'  => $validated['nome'],
            'email' => $validated['email'],
        ]);

        // Atualiza foto
        if ($request->hasFile('foto_perfil')) {
            if ($professor->foto_perfil) {
                Storage::disk('public')->delete($professor->foto_perfil);
            }
            $professor->foto_perfil = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }

        $professor->update([
            'telefone'       => $validated['telefone'] ?? null,
            'especializacao' => $validated['especializacao'] ?? null,
            'foto_perfil'    => $professor->foto_perfil ?? null,
        ]);

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor e usuário atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $professor = Professor::with('user')->findOrFail($id);

        if ($professor->foto_perfil) {
            Storage::disk('public')->delete($professor->foto_perfil);
        }

        $professor->user?->delete();
        $professor->delete();

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor e usuário removidos com sucesso!');
    }
}
