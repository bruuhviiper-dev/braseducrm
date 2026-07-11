@props(['label' => 'Salvar'])
{{-- FAB "✓ Salvar" azul no canto inferior direito (padrão EDUQ Clean UI) --}}
<button type="submit" class="fixed bottom-6 right-6 z-40 px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-xl shadow-cyan-500/30 flex items-center gap-2 transition-transform active:scale-95">
    <i class="fa-solid fa-check"></i> {{ $label }}
</button>
