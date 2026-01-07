@extends('layouts.app')

@section('content')

    {{-- ALERTAS (erros / sucesso / mensagens do sistema) --}}
    @include('admin.settings._alerts')

    {{-- ESTILOS ESPECIFICOS DO MODULO SETTINGS --}}
    @include('admin.settings._styles')

    {{-- WRAPPER GERAL DO MODULO --}}
    <div class="settings-wrapper">

        {{-- HERO / CABECALHO DO MODULO --}}
        @include('admin.settings._hero')

        {{-- CONTAINER PRINCIPAL
             - Sidebar
             - Conteudo central
             - Controle por sec/sub
        --}}
        @include('admin.settings._container')

    </div>

    {{-- SCRIPTS ESPECIFICOS DO MODULO SETTINGS --}}
    @include('admin.settings._scripts')

@endsection
