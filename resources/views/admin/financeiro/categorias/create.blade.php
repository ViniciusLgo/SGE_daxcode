@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Nova Categoria de Despesa</h4>
        <a href="{{ route('admin.financeiro.categorias.index') }}" class="btn btn-outline-secondary">
            Voltar
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Verifique os campos abaixo.<br><br>
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.financeiro.categorias.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nome da categoria <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição (opcional)</label>
                    <textarea name="descricao" class="form-control" rows="3">{{ old('descricao') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Salvar
                </button>
            </form>
        </div>
    </div>
@endsection
