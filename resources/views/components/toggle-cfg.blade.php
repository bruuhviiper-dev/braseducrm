@props(['nome', 'ativo' => false, 'rotulo', 'dica' => null])
<label class="flex items-start gap-2 cursor-pointer">
    <input type="hidden" name="{{ $nome }}" value="0">
    <input type="checkbox" name="{{ $nome }}" value="1" @checked($ativo) class="sr-only peer">
    <span class="mt-0.5 w-10 h-5 shrink-0 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
    <span class="text-sm text-gray-700">{{ $rotulo }}@if($dica)<span class="block text-[11px] text-gray-400">{{ $dica }}</span>@endif</span>
</label>
