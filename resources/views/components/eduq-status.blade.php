@props(['ativo' => true, 'labelOn' => 'Ativo', 'labelOff' => 'Inativo'])
{{-- Status estilo EDUQ Clean UI: texto puro verde (ativo) / vermelho (inativo), sem pill --}}
@if($ativo)
<span class="text-sm font-medium text-green-600">{{ $labelOn }}</span>
@else
<span class="text-sm font-medium text-red-500">{{ $labelOff }}</span>
@endif
