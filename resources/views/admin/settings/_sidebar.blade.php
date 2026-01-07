<nav class="settings-nav">

    <div class="settings-sidebar-title">
        Configuracoes
    </div>

    <ul class="settings-menu">

        {{-- =============================== --}}
        {{-- GERAL --}}
        {{-- =============================== --}}
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec' => 'geral']) }}"
               class="settings-link {{ request('sec', 'geral') === 'geral' ? 'active' : '' }}">
                 Gerais
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
                 Academicas
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
        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'documentos']) }}"
               class="settings-link {{ request('sec') === 'documentos' ? 'active' : '' }}">
                 Documentos & PDFs
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'usuarios']) }}"
               class="settings-link {{ request('sec') === 'usuarios' ? 'active' : '' }}">
                 Usuarios & Acesso
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'notificacoes']) }}"
               class="settings-link {{ request('sec') === 'notificacoes' ? 'active' : '' }}">
                 Notificacoes
            </a>
        </li>

        <li class="settings-item">
            <a href="{{ route('admin.settings.edit', ['sec'=>'financeiro']) }}"
               class="settings-link {{ request('sec') === 'financeiro' ? 'active' : '' }}">
                 Financeiro
            </a>
        </li>

    </ul>

</nav>
