<nav class="settings-nav">

    <div class="settings-sidebar-title">
        Configurações
    </div>

    <ul class="settings-menu">

        {{-- =============================== --}}
        {{-- GERAL --}}
        {{-- =============================== --}}
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec' => 'geral']) }}"
               class="settings-link {{ request('sec', 'geral') === 'geral' ? 'active' : '' }}">
                ⚙️ Gerais
            </a>
        </li>

        {{-- =============================== --}}
        {{-- ACADÊMICO (COM SUBMENU) --}}
        {{-- =============================== --}}
        @php
            $academicoOpen = request('sec') === 'academico';
        @endphp

        <li class="settings-item settings-group {{ $academicoOpen ? 'open' : '' }}">

            <button type="button"
                    class="settings-link has-submenu"
                    data-toggle="submenu"
                    aria-expanded="{{ $academicoOpen ? 'true' : 'false' }}">
                📚 Acadêmicas
                <span class="chevron">▾</span>
            </button>

            <ul class="settings-submenu">

                @foreach([
                    'ano-letivo'  => 'Ano letivo & Avaliações',
                    'calendario'  => 'Calendário escolar',
                    'feriados'    => 'Feriados & dias não letivos',
                    'turnos'      => 'Turnos da escola',
                    'modulos'     => 'Estrutura modular',
                    'carga-curso' => 'Carga horária / curso',
                    'fechamento'  => 'Fechamento de notas',
                    'promocao'    => 'Promoção & globais',
                ] as $key => $label)

                    <li>
                        <a href="{{ route('admin.settings.edit', ['sec' => 'academico', 'sub' => $key]) }}"
                           class="settings-sublink {{ request('sub') === $key ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    </li>

                @endforeach

            </ul>
        </li>

        {{-- =============================== --}}
        {{-- OUTRAS SEÇÕES --}}
        {{-- =============================== --}}
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'documentos']) }}"
               class="settings-link {{ request('sec') === 'documentos' ? 'active' : '' }}">
                📄 Documentos & PDFs
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'usuarios']) }}"
               class="settings-link {{ request('sec') === 'usuarios' ? 'active' : '' }}">
                👤 Usuários & Acesso
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'notificacoes']) }}"
               class="settings-link {{ request('sec') === 'notificacoes' ? 'active' : '' }}">
                🔔 Notificações
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'financeiro']) }}"
               class="settings-link {{ request('sec') === 'financeiro' ? 'active' : '' }}">
                💰 Financeiro
            </a>
        </li>

    </ul>

</nav>
