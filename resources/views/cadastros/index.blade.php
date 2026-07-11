@extends('layouts.app')
@section('title', $cfg['titulo'])

@php $temAtivo = in_array('ativo', (new $cfg['model'])->getFillable()); @endphp

@section('content')
{{-- Lista genérica no padrão EDUQ Clean UI: sem coluna ID, kebab após a 1ª coluna, pill azul de status --}}
<x-data-table :title="$cfg['titulo']" :codigo="$cfg['codigo']" :breadcrumb="$cfg['breadcrumb'] ?? null" :createRoute="empty($cfg['sem_criar']) ? route('cadastros.create', $tipo) : null">
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3 px-3 w-8"></th>
                @foreach($cfg['fields'] as $f)
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">{{ $f['label'] }}</th>
                @if($loop->first)<th class="px-4 py-3 w-10"></th>@endif
                @endforeach
                @if($temAtivo)<th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>@endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-cyan-500 border-gray-300"></td>
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
                @if($loop->first)
                <td class="px-4 py-3">
                    <x-kebab :edit="route('cadastros.edit', [$tipo, $r->id])" :delete="empty($cfg['sem_criar']) ? route('cadastros.destroy', [$tipo, $r->id]) : null" dir="left" />
                </td>
                @endif
                @endforeach
                @if($temAtivo)
                <td class="px-4 py-3"><x-eduq-status :ativo="$r->ativo" /></td>
                @endif
            </tr>
            @empty
            <tr><td colspan="{{ count($cfg['fields']) + ($temAtivo ? 3 : 2) }}" class="px-4 py-8 text-center text-gray-400">Nenhum registro cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
