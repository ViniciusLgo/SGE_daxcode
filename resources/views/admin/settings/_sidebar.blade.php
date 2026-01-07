<nav class="settings-nav">

    <div class="settings-sidebar-title">
        Configuracoes
    </div>

    <ul class="settings-menu">

        {{-- =============================== --}}
        {{-- GERAL --}}
        {{-- =============================== --}}
        <li class="settings-menu-title">Base</li>
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec' => 'geral']) }}"
               class="settings-link {{ request('sec', 'geral') === 'geral' ? 'active' : '' }}">
                <span>Gerais</span>
                <i class="bi bi-gear"></i>
            </a>
        </li>

        {{-- =============================== --}}
        {{-- ACADEMICO (COM SUBMENU) --}}
        {{-- =============================== --}}
        @php
            $academicoOpen = request('sec') === 'academico';
        @endphp

        <li class="settings-item settings-group {{ $academicoOpen ? 'open' : '' }}">

            <button type="button"
                    class="settings-link has-submenu"
                    data-toggle="submenu"
                    aria-expanded="{{ $academicoOpen ? 'true' : 'false' }}">
                <span>Academicas</span>
                <span class="chevron"></span>
            </button>

            <ul class="settings-submenu">

                @foreach([
                    'ano-letivo'  => 'Ano letivo & Avaliacoes',
                    'calendario'  => 'Calendario escolar',
                    'feriados'    => 'Feriados & dias nao letivos',
                    'turnos'      => 'Turnos da escola',
                    'modulos'     => 'Estrutura modular',
                    'carga-curso' => 'Carga horaria / curso',
                    'fechamento'  => 'Fechamento de notas',
                    'promocao'    => 'Promocao & globais',
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
        {{-- OUTRAS SECOES --}}
        {{-- =============================== --}}
        <li class="settings-menu-title">Gestao</li>
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'documentos']) }}"
               class="settings-link {{ request('sec') === 'documentos' ? 'active' : '' }}">
                <span>Documentos & PDFs</span>
                <i class="bi bi-file-earmark-text"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'usuarios']) }}"
               class="settings-link {{ request('sec') === 'usuarios' ? 'active' : '' }}">
                <span>Usuarios & Acesso</span>
                <i class="bi bi-people"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'notificacoes']) }}"
               class="settings-link {{ request('sec') === 'notificacoes' ? 'active' : '' }}">
                <span>Notificacoes</span>
                <i class="bi bi-bell"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'financeiro']) }}"
               class="settings-link {{ request('sec') === 'financeiro' ? 'active' : '' }}">
                <span>Financeiro</span>
                <i class="bi bi-cash-coin"></i>
            </a>
        </li>

        <li class="settings-menu-title">Sistema</li>
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'comunicacao']) }}"
               class="settings-link {{ request('sec') === 'comunicacao' ? 'active' : '' }}">
                <span>Comunicacao</span>
                <i class="bi bi-broadcast"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'logs']) }}"
               class="settings-link {{ request('sec') === 'logs' ? 'active' : '' }}">
                <span>Logs & Auditoria</span>
                <i class="bi bi-journal-text"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'backup']) }}"
               class="settings-link {{ request('sec') === 'backup' ? 'active' : '' }}">
                <span>Backup</span>
                <i class="bi bi-hdd"></i>
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'avancado']) }}"
               class="settings-link {{ request('sec') === 'avancado' ? 'active' : '' }}">
                <span>Avancado</span>
                <i class="bi bi-sliders"></i>
            </a>
        </li>

    </ul>

</nav>
