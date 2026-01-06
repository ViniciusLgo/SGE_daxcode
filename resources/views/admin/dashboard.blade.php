
@extends('layouts.app')

@section('content')

    @php
        $totalAlunos      = $alunosCount ?? 0;
        $totalProfessores = $professoresCount ?? 0;
        $totalDisciplinas = $disciplinasCount ?? 0;
        $totalTurmas      = $turmasCount ?? 0;

        $recentUsuarios   = $recentUsuarios ?? collect();
        $recentAlunos     = $recentAlunos ?? collect();
        $despesasRecentes = $despesasRecentes ?? collect();

        $totalDespesasMes = $totalDespesasMes ?? 0;
        $totalDespesasAno = $totalDespesasAno ?? 0;

        $version = optional($settings)->version ?? '1.0.0';
    @endphp

    {{-- HEADER --}}
    <x-admin.header
        title="Painel Administrativo"
        :subtitle="'Bem-vindo, ' . (auth()->user()->name ?? 'Administrador')"
        :version="$version"
    />

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <x-admin.kpi-card title="Alunos" :value="$totalAlunos" icon="bi-mortarboard-fill" variant="yellow" />
        <x-admin.kpi-card title="Professores" :value="$totalProfessores" icon="bi-person-video3" variant="green" />
        <x-admin.kpi-card title="Disciplinas" :value="$totalDisciplinas" icon="bi-book" variant="dark" />
        <x-admin.kpi-card title="Turmas" :value="$totalTurmas" icon="bi-building" variant="slate" />
    </div>

    {{-- GRID DE BLOCOS --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Usuarios Recentes --}}
        <div class="xl:col-span-2 bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-dax-dark dark:text-dax-light">
                    Usuarios Recentes
                </h3>

                <a href="{{ route('admin.usuarios.index') }}"
                   class="text-sm font-semibold text-dax-green hover:underline">
                    Ver todos
                </a>
            </div>

            <table class="w-full text-sm">
                <thead>
                <tr class="text-left text-slate-500">
                    <th class="pb-2">Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($recentUsuarios as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                        <td class="py-2 font-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="capitalize">{{ $user->tipo }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-slate-500">
                            Nenhum usuario recente
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Atalhos --}}
        <div class="bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">

            <h3 class="font-black text-dax-dark dark:text-dax-light mb-4">
                Atalhos Rapidos
            </h3>

            <div class="space-y-3">
                <x-admin.tile :href="route('admin.usuarios.create')" icon="bi-person-plus-fill">
                    Novo Usuario
                </x-admin.tile>

                <x-admin.tile :href="route('admin.alunos.index')" icon="bi-mortarboard-fill">
                    Gerenciar Alunos
                </x-admin.tile>

                <x-admin.tile :href="route('admin.turmas.index')" icon="bi-building">
                    Gerenciar Turmas
                </x-admin.tile>


            </div>
        </div>
    </div>

    {{-- BLOCO INFERIOR --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 mt-6">

        {{-- Alunos Recentes --}}
        <div class="bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">
            <h3 class="font-black text-dax-dark dark:text-dax-light mb-4">
                Alunos Recentes
            </h3>

            <ul class="space-y-3 text-sm">
                @forelse($recentAlunos as $aluno)
                    <li class="flex items-center justify-between">
                        <span class="font-semibold">{{ $aluno->user->name ?? '-' }}</span>
                        <span class="text-slate-500">{{ $aluno->turma->nome ?? '-' }}</span>
                    </li>
                @empty
                    <li class="text-slate-500">Nenhum aluno recente</li>
                @endforelse
            </ul>
        </div>

        {{-- Alertas --}}
        <div class="bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">
            <h3 class="font-black text-dax-dark dark:text-dax-light mb-4">
                Alertas
            </h3>
            <ul class="space-y-2 text-sm">
                <li>Turmas sem professor: <strong>{{ $turmasSemProfessor }}</strong></li>
                <li>Alunos sem documentos: <strong>{{ $alunosSemDocumentos }}</strong></li>
                <li>Aniversariantes hoje: <strong>{{ $aniversariantesHoje }}</strong></li>
                <li>Ocupacao media: <strong>{{ $ocupacaoMedia }}%</strong></li>
            </ul>
        </div>

        {{-- Proximos aniversarios --}}
        <div class="bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">
            <h3 class="font-black text-dax-dark dark:text-dax-light mb-4">
                Proximos Aniversarios
            </h3>

            <ul class="space-y-2 text-sm">
                @forelse(($proximosAniversarios ?? collect()) as $aluno)
                    @php
                        $nasc = $aluno->data_nascimento ? \Carbon\Carbon::parse($aluno->data_nascimento) : null;
                        $proximo = $nasc ? $nasc->copy()->year(now()->year) : null;
                        if ($proximo && $proximo->isPast()) {
                            $proximo = $proximo->addYear();
                        }
                    @endphp
                    <li class="flex items-center justify-between">
                        <span class="font-semibold">{{ $aluno->user->name ?? '-' }}</span>
                        <span class="text-slate-500">
                            {{ $proximo ? $proximo->format('d/m') : '-' }}
                        </span>
                    </li>
                @empty
                    <li class="text-slate-500">Nenhum aniversario cadastrado</li>
                @endforelse
            </ul>
        </div>

        {{-- Financeiro --}}
        <div class="bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-dax-dark dark:text-dax-light">
                    Financeiro
                </h3>
                <a href="{{ route('admin.financeiro.dashboard') }}"
                   class="text-sm font-semibold text-dax-green hover:underline">
                    Ver
                </a>
            </div>

            <div class="text-sm space-y-2 mb-4">
                <div>Total mes: <strong>R$ {{ number_format($totalDespesasMes, 2, ',', '.') }}</strong></div>
                <div>Total ano: <strong>R$ {{ number_format($totalDespesasAno, 2, ',', '.') }}</strong></div>
            </div>

            <div class="text-xs text-slate-500 mb-2">Despesas recentes</div>
            <ul class="space-y-2 text-sm">
                @forelse($despesasRecentes as $despesa)
                    <li class="flex items-center justify-between">
                        <span>{{ $despesa->categoria?->nome ?? '-' }}</span>
                        <span>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="text-slate-500">Nenhuma despesa recente</li>
                @endforelse
            </ul>
        </div>
    </div>

@endsection
