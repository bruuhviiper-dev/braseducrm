@props(['name' => 'ativo', 'label' => 'Ativo', 'checked' => true])
{{-- Toggle "Ativo" no topo do formulário (padrão EDUQ Clean UI): card com borda azul, check e switch à direita --}}
<label class="flex items-center justify-between w-full border-2 border-cyan-400 rounded-xl px-4 py-3 cursor-pointer mb-4">
    <span class="flex items-center gap-2 text-sm font-medium text-gray-700">
        <i class="fa-solid fa-circle-check text-cyan-500"></i> {{ $label }}
    </span>
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($checked) class="sr-only peer">
    <span class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-cyan-500 relative transition after:content-[''] after:absolute after:w-5 after:h-5 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition after:shadow"></span>
</label>
