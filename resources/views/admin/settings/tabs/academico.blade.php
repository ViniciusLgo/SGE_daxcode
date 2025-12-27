@if(request('sec') === 'academico')

    <div class="settings-card">

        <div class="settings-card-header">
            <h5>📚 Configurações Acadêmicas</h5>
            <p class="settings-card-subtitle">
                Defina regras acadêmicas, calendário e avaliações.
            </p>
        </div>

        <div class="settings-card-body">

            @switch(request('sub'))

                @case('ano-letivo')
                    @include('admin.settings.academico.ano-letivo')
                    @break

                @case('calendario')
                    @include('admin.settings.academico.calendario')
                    @break

                @case('feriados')
                    @include('admin.settings.academico.feriados')
                    @break

                @case('turnos')
                    @include('admin.settings.academico.turnos')
                    @break

                @case('modulos')
                    @include('admin.settings.academico.modulos')
                    @break

                @case('carga-curso')
                    @include('admin.settings.academico.carga-curso')
                    @break

                @case('fechamento')
                    @include('admin.settings.academico.fechamento')
                    @break

                @case('promocao')
                    @include('admin.settings.academico.promocao')
                    @break

                @default
                    <p class="text-muted">
                        Selecione uma opção no menu acadêmico.
                    </p>

            @endswitch

        </div>
    </div>

@endif
