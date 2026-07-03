@extends('layouts.app')
@section('title', 'Central de Notificação do Aluno')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Total de notificações</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Não lidas</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['nao_lidas'] }}</p>
        </div>
    </div>

    <x-data-table title="Central de Notificação do Aluno" codigo="260" :createRoute="route('comunicacao.notificacoes.create')">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($notificacoes as $n)
                <tr class="hover:bg-gray-50">
                <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $n->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $n->titulo }}<span class="block text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($n->mensagem, 50) }}</span></td>
                    <td class="px-4 py-3 text-gray-600">{{ $n->para_todos ? 'Todos' : ($n->aluno?->pessoa?->nome ?? '—') }}</td>
                    <td class="px-4 py-3">
                        @php $cor = ['info'=>'bg-blue-100 text-blue-700','aviso'=>'bg-amber-100 text-amber-700','sucesso'=>'bg-green-100 text-green-700','urgente'=>'bg-red-100 text-red-700'][$n->tipo] ?? 'bg-gray-100 text-gray-700'; @endphp
                        <span class="px-2 py-0.5 rounded text-xs {{ $cor }}">{{ \App\Models\NotificacaoAluno::TIPOS[$n->tipo] ?? $n->tipo }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $n->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs {{ $n->lida ? 'bg-gray-100 text-gray-600' : 'bg-primary-100 text-primary-700' }}">{{ $n->lida ? 'Lida' : 'Não lida' }}</span></td>
                    <td class="px-4 py-3">
                        <x-kebab :delete="route('comunicacao.notificacoes.destroy', $n)"><form method="POST" action="{{ route('comunicacao.notificacoes.lida', $n) }}">
                                @csrf
                                <button class="p-1.5 text-gray-600 hover:bg-gray-100 rounded" title="Marcar lida/não lida"><i class="fa-solid fa-{{ $n->lida ? 'envelope-open' : 'envelope' }}"></i></button>
                            </form></x-kebab>
                        </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma notificação enviada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $notificacoes->links() }}</div>
    </x-data-table>
</div>
@endsection
