@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-pencil-square text-dax-yellow"></i>
                Editar Responsável
            </h1>
            <p class="text-slate-500">
                Atualize os dados cadastrados e os vínculos do responsável.
            </p>
        </div>

        {{-- Alertas --}}
        @include('partials.alerts')

        {{-- Form --}}
        <form action="{{ route('admin.responsaveis.update', $responsavel->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.responsaveis._form', ['responsavel' => $responsavel])
        </form>

    </div>
@endsection
