@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                    <i class="bi bi-pencil-square text-dax-yellow"></i>
                    Editar Professor
                </h1>
                <p class="text-slate-500">
                    Atualize as informações deste professor.
                </p>
            </div>

            <a href="{{ route('admin.professores.index') }}"
               class="px-4 py-2 rounded-xl border
                  border-slate-200 dark:border-slate-800">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            {{-- Mensagens --}}
            @if (session('success'))
                <div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('admin.professores.update', $professor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('admin.professores._form', ['professor' => $professor])
                </div>
            </form>
        </div>

    </div>
@endsection
