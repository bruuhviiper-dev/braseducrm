@props(['route' => null, 'label' => 'Novo'])

@if($route)
<a href="{{ $route }}" class="group fixed bottom-6 right-6 z-40 flex items-center" title="{{ $label }}">
    <span class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-medium px-2.5 py-1 rounded shadow-lg whitespace-nowrap">{{ $label }}</span>
    <span class="w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-xl shadow-primary-600/30 flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
        <i class="fa-solid fa-plus text-2xl"></i>
    </span>
</a>
@endif
