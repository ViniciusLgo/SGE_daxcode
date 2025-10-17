<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Exibe a tela de edição das configurações gerais.
     */
    public function edit()
    {
        // Busca a primeira configuração existente (ou cria uma nova em branco)
        $setting = Setting::first() ?? new Setting();

        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Atualiza as configurações gerais do sistema.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'nome_instituicao' => 'nullable|string|max:255',
            'email'            => 'nullable|email|max:255',
            'telefone'         => 'nullable|string|max:50',
            'endereco'         => 'nullable|string|max:255',
            'versao_sistema'   => 'nullable|string|max:20',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        // Recupera a configuração existente ou cria uma nova
        $setting = Setting::first() ?? new Setting();

        // Se houver upload de nova logo, apaga a antiga e salva a nova
        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Atualiza os dados
        $setting->fill($data);
        $setting->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
}
