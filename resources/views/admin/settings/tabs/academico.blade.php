@if(request('sec') === 'academico')

    @php
        $sub = request('sub');
    @endphp

    <div class="settings-stack">
        @include('admin.settings.academico.ano-letivo', ['open' => $sub === 'ano-letivo'])
        @include('admin.settings.academico.calendario', ['open' => $sub === 'calendario'])
        @include('admin.settings.academico.feriados', ['open' => $sub === 'feriados'])
        @include('admin.settings.academico.turnos', ['open' => $sub === 'turnos'])
        @include('admin.settings.academico.modulos', ['open' => $sub === 'modulos'])
        @include('admin.settings.academico.carga-curso', ['open' => $sub === 'carga-curso'])
        @include('admin.settings.academico.fechamento', ['open' => $sub === 'fechamento'])
        @include('admin.settings.academico.promocao', ['open' => $sub === 'promocao'])
    </div>

@endif
