@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-people text-dax-yellow"></i>
                Completar Cadastro do Responsável
            </h1>
            <p class="text-slate-500">
                Usuário criado:
                <strong>{{ $user->name }}</strong> ({{ $user->email }})
            </p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.responsaveis.store') }}">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                @include('admin.responsaveis._form')
            </form>

        </div>
    </div>
@endsection
