@extends('layouts.app')
@section('title', $forum->titulo)

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">306</span>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">{{ $forum->titulo }}</h1>
                    <p class="text-xs text-gray-500">{{ $forum->cursoEad?->nome ?? 'Sem curso vinculado' }}</p>
                </div>
            </div>
            <a href="{{ route('ead.foruns.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-arrow-left mr-1"></i> Voltar</a>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5 space-y-3">
        <h2 class="text-sm font-semibold text-gray-700">Mensagens</h2>
        @forelse($forum->mensagens as $m)
        <div class="border rounded-lg p-3 {{ $m->do_tutor ? 'bg-primary-50 border-primary-200' : 'bg-gray-50' }}">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-semibold {{ $m->do_tutor ? 'text-primary-700' : 'text-gray-700' }}">
                    {{ $m->do_tutor ? 'Tutor' : ($m->pessoa?->nome ?? 'Aluno') }}
                    @if($m->do_tutor)<i class="fa-solid fa-chalkboard-user ml-1"></i>@endif
                </span>
                <span class="text-xs text-gray-400">{{ $m->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $m->mensagem }}</p>
        </div>
        @empty
        <p class="text-sm text-gray-400 py-2">Nenhuma mensagem ainda. Seja o primeiro a responder.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Nova mensagem</h2>
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-3">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <form action="{{ route('ead.foruns.mensagem', $forum) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <textarea name="mensagem" rows="3" required class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Escreva sua mensagem...">{{ old('mensagem') }}</textarea>
            </div>
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs text-gray-500 mb-1">Autor (aluno)</label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">— (anônimo / tutor)</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}">{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-700 pb-2">
                    <input type="checkbox" name="do_tutor" value="1" class="rounded border-gray-300"> Publicar como tutor
                </label>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-paper-plane mr-1"></i> Publicar</button>
            </div>
        </form>
    </div>
</div>
@endsection
