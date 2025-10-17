@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">
            <i class="bi bi-gear me-2 text-warning"></i> Configurações Gerais
        </h4>

        {{-- Mensagem de sucesso --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        {{-- Exibição de erros de validação --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Ops!</strong> Corrija os erros abaixo antes de continuar.
                <ul class="mt-2 mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card principal --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome da Instituição</label>
                            <input type="text" name="nome_instituicao" class="form-control"
                                   value="{{ old('nome_instituicao', $setting->nome_instituicao) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $setting->email) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input type="text" name="telefone" class="form-control"
                                   value="{{ old('telefone', $setting->telefone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Endereço</label>
                            <input type="text" name="endereco" class="form-control"
                                   value="{{ old('endereco', $setting->endereco) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Logo (PNG ou JPG)</label>
                            <input type="file" name="logo" class="form-control">
                            @if($setting->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo atual" height="60" class="border rounded shadow-sm p-1 bg-white">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Versão do Sistema</label>
                            <input type="text" name="versao_sistema" class="form-control"
                                   value="{{ old('versao_sistema', $setting->versao_sistema ?? '1.0.0') }}">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-dark px-4">
                            <i class="bi bi-save me-2"></i>Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
