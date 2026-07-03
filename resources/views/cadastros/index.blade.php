@extends('layouts.app')
@section('title', $cfg['titulo'])

@php $temAtivo = in_array('ativo', (new $cfg['model'])->getFillable()); @endphp

@section('content')
<x-data-table :title="$cfg['titulo']" :codigo="$cfg['codigo']" :createRoute="route('cadastros.create', $tipo)">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $r->id }}</td>
                @foreach($cfg['fields'] as $f)
                <td class="px-4 py-3 {{ $loop->first ? 'font-medium text-gray-800' : 'text-gray-600' }}">
                    @if($f['type'] === 'number' && $f['name'] === 'valor')
                        R$ {{ number_format((float) $r->{$f['name']}, 2, ',', '.') }}
                    @elseif($f['type'] === 'boolean')
                        {{ $r->{$f['name']} ? 'Sim' : 'Não' }}
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
                    <x-kebab :edit="route('cadastros.edit', [$tipo, $r->id])" :delete="route('cadastros.destroy', [$tipo, $r->id])" />
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
