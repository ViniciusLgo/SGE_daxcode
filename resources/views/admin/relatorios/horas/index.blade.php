@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">Relatório Mensal de Horas</h1>
                <p class="text-gray-500">Horas registradas por professor</p>
            </div>
        </div>

        {{-- FILTRO --}}
        <form method="GET" class="flex gap-4 mb-6">
            <select name="mes" class="border rounded px-3 py-2">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($m == $mes)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="ano" class="border rounded px-3 py-2">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" @selected($y == $ano)>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Filtrar
            </button>
        </form>

        {{-- TABELA --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Professor</th>
                    <th class="px-4 py-2 text-center">Registros</th>
                    <th class="px-4 py-2 text-center">Blocos</th>
                    <th class="px-4 py-2 text-center">Minutos</th>
                    <th class="px-4 py-2 text-center">Horas</th>
                </tr>
                </thead>
                <tbody>
                @forelse($aulas as $linha)
                    @php
                        $horas = round($linha->total_minutos / 60, 2);
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2">
                            {{ $linha->professor->nome ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $linha->total_registros }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $linha->total_blocos }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $linha->total_minutos }}
                        </td>
                        <td class="px-4 py-2 text-center font-semibold">
                            {{ $horas }} h
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
