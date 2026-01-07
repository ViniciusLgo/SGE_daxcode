@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Meu Perfil
                </h1>
                <p class="text-sm text-slate-500">
                    Atualize seus dados, senha e configuracoes de conta.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                            bg-white dark:bg-dax-dark/60 p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                            bg-white dark:bg-dax-dark/60 p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-red-200 dark:border-red-900
                        bg-white dark:bg-dax-dark/60 p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
