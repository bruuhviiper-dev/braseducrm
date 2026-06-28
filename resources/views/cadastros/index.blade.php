@extends('layouts.app')
@section('title', $cfg['titulo'])

@php $temAtivo = in_array('ativo', (new $cfg['model'])->getFillable()); @endphp

@section('content')
<x-data-table :title="$cfg['titulo']" :codigo="$cfg['codigo']" :createRoute="route('cadastros.create', $tipo)">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                @foreach($cfg['fields'] as $f)
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">{{ $f['label'] }}</th>
                @endforeach
                @if($temAtivo)<th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>@endif
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $r->id }}</td>
                @foreach($cfg['fields'] as $f)
                <td class="px-4 py-3 {{ $loop->first ? 'font-medium text-gray-800' : 'text-gray-600' }}">
                    @if($f['type'] === 'number' && $f['name'] === 'valor')
                        R$ {{ number_format((float) $r->{$f['name']}, 2, ',', '.') }}
                    @else
                        {{ \Illuminate\Support\Str::limit($r->{$f['name']}, 60) ?: '—' }}
                    @endif
                </td>
                @endforeach
                @if($temAtivo)
                <td class="px-4 py-3">
                    @if($r->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                @endif
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('cadastros.edit', [$tipo, $r->id]) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('cadastros.destroy', [$tipo, $r->id]) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="{{ count($cfg['fields']) + ($temAtivo ? 3 : 2) }}" class="px-4 py-8 text-center text-gray-400">Nenhum registro cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
