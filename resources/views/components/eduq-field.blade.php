@props(['name', 'label', 'value' => '', 'type' => 'text', 'required' => false, 'options' => null, 'selected' => null, 'placeholder' => null])
{{-- Campo com floating label (padrão EDUQ): input/select outlined, label flutuante, asterisco em required --}}
<div class="relative eduq-field mb-3">
    @if($type === 'select')
        <select name="{{ $name }}" @required($required)
                class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-1.5 text-sm bg-white focus:border-cyan-400 outline-none appearance-none">
            <option value="">{{ $placeholder ?? 'Selecione...' }}</option>
            @foreach(($options ?? []) as $optKey => $optLabel)
            @php $optVal = is_int($optKey) ? $optLabel : $optKey; @endphp
            <option value="{{ $optVal }}" @selected((string) $selected === (string) $optVal)>{{ $optLabel }}</option>
            @endforeach
        </select>
        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" @required($required) rows="3"
                  class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-1.5 text-sm focus:border-cyan-400 outline-none">{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" @required($required) placeholder=" "
               class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-1.5 text-sm focus:border-cyan-400 outline-none">
    @endif
    <label class="absolute left-3 top-1.5 text-[11px] text-gray-400 pointer-events-none">{{ $label }}@if($required)<span class="text-red-500">*</span>@endif</label>
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
