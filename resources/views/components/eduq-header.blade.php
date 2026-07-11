@props(['title', 'breadcrumb' => null, 'back' => null])
{{-- Cabeçalho de formulário (padrão EDUQ): seta voltar + título + breadcrumb --}}
<div class="flex items-start gap-3 mb-5">
    @if($back)
    <a href="{{ $back }}" class="text-gray-400 hover:text-gray-700 mt-1"><i class="fa-solid fa-arrow-left"></i></a>
    @endif
    <div>
        <h1 class="text-xl font-bold text-gray-800">{{ $title }}</h1>
        @if($breadcrumb)<p class="text-xs text-gray-400">{{ $breadcrumb }}</p>@endif
    </div>
</div>
