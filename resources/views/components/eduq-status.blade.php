@props(['ativo' => true, 'labelOn' => 'Ativo', 'labelOff' => 'Inativo'])
{{-- Badge de status estilo EDUQ Clean UI: pill azul claro para ativo, cinza para inativo --}}
@if($ativo)
<span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-cyan-50 text-cyan-600">{{ $labelOn }}</span>
@else
<span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">{{ $labelOff }}</span>
@endif
