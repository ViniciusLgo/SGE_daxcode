@extends('layouts.app')

@section('content')

    {{-- ALERTAS (erros / sucesso / mensagens do sistema) --}}
    @include('admin.settings._alerts')

    {{-- ESTILOS ESPECÍFICOS DO MÓDULO SETTINGS --}}
    @include('admin.settings._styles')

    {{-- WRAPPER GERAL DO MÓDULO --}}
    <div class="settings-wrapper">

        {{-- HERO / CABEÇALHO DO MÓDULO --}}
        @include('admin.settings._hero')

        {{-- CONTAINER PRINCIPAL
             - Sidebar
             - Conteúdo central
             - Controle por sec/sub
        --}}
        @include('admin.settings._container')

    </div>

    {{-- SCRIPTS ESPECÍFICOS DO MÓDULO SETTINGS --}}
    @include('admin.settings._scripts')

@endsection
