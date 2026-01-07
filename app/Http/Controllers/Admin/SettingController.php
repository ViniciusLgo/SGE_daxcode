<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Exibe a tela de edicao de configuracoes globais do sistema.
     */
    public function edit()
    {
        // Busca o primeiro (e unico) registro de settings.
        // Se nao existir, cria um com valores padrao.
        $settings = Setting::first();

        if (! $settings) {
            $settings = Setting::create([
                'school_name'        => 'Minha Escola',
                'email'              => 'admin@admin.com',
                'phone'              => '',
                'address'            => '',
                'version'            => '1.0',
                'logo'               => null,
                'academic_settings'  => [
                    'ano_letivo'  => date('Y'),
                    'modelo_ano'  => 'bimestre',
                    'dias_letivos'=> null,
                ],
            ]);
        }

        // Garante que academic_settings seja sempre um array
        $settings->academic_settings = $settings->academic_settings ?? [];

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Atualiza as configuracoes globais.
     */
    public function update(Request $request)
    {
        $settings = Setting::firstOrFail();

        // -----------------------------
        // 1) VALIDACOES SIMPLES
        // -----------------------------
        $request->validate([
            'school_name' => ['nullable', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:255'],
            'version'     => ['nullable', 'string', 'max:50'],
            'logo'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
        ]);

        // -----------------------------
        // 2) CAMPOS BASICOS DA INSTITUICAO
        // -----------------------------
        $settings->school_name = $request->input('school_name');
        $settings->email       = $request->input('email');
        $settings->phone       = $request->input('phone');
        $settings->address     = $request->input('address');
        $settings->version     = $request->input('version');

        // -----------------------------
        // 3) UPLOAD DO LOGO (OPCIONAL)
        // -----------------------------
        if ($request->hasFile('logo')) {
            // Apaga o logo anterior se existir
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }

            // Salva o novo logo em storage/app/public/settings
            $path = $request->file('logo')->store('settings', 'public');
            $settings->logo = $path;
        }

        // -----------------------------
        // 4) BLOCO ACADEMICO (JSON)
        // -----------------------------
        // Pega tudo que veio em academic_settings[...]
        $academic = $request->input('academic_settings', []);

        // Garante que sempre seja array
        if (!is_array($academic)) {
            $academic = [];
        }

        // ---- LIMPEZA SIMPLES DE ARRAYS DINAMICOS ----
        // Remove linhas totalmente vazias de feriados
        if (!empty($academic['feriados']) && is_array($academic['feriados'])) {
            $academic['feriados'] = collect($academic['feriados'])
                ->filter(function ($item) {
                    // Mantem se tiver pelo menos data ou nome
                    return !empty($item['data']) || !empty($item['nome']);
                })
                ->values()
                ->all();
        }

        // Remove modulos que nao tenham nome nem carga horaria
        if (!empty($academic['modulos']) && is_array($academic['modulos'])) {
            $academic['modulos'] = collect($academic['modulos'])
                ->filter(function ($item) {
                    return !empty($item['nome']) || !empty($item['carga_horaria']);
                })
                ->values()
                ->all();
        }

        // Remove cursos sem nome
        if (!empty($academic['cursos']) && is_array($academic['cursos'])) {
            $academic['cursos'] = collect($academic['cursos'])
                ->filter(function ($item) {
                    return !empty($item['nome']);
                })
                ->values()
                ->all();
        }

        // Marca checkboxes que nao voltam no request como false
        // (para evitar undefined index depois)
        $promocao = $academic['promocao'] ?? [];
        $promocao['reprovar_por_nota']       = !empty($promocao['reprovar_por_nota']);
        $promocao['reprovar_por_frequencia'] = !empty($promocao['reprovar_por_frequencia']);
        $academic['promocao'] = $promocao;

        $fech = $academic['fechamento_notas'] ?? [];
        $fech['bloquear_apos_prazo'] = !empty($fech['bloquear_apos_prazo']);
        $fech['arredondar_media']    = !empty($fech['arredondar_media']);
        $academic['fechamento_notas'] = $fech;

        $hor = $academic['horarios'] ?? [];
        $hor['sabado_letivo_ativo'] = !empty($hor['sabado_letivo_ativo']);
        $academic['horarios'] = $hor;

        // Salva tudo no JSON academic_settings
        $settings->academic_settings = $academic;

        // -----------------------------
        // 5) SALVAR NO BANCO
        // -----------------------------
        $settings->save();

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Configuracoes atualizadas com sucesso!');
    }
}
