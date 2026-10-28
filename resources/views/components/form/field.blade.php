@props(['name', 'label', 'required' => false, 'hint' => null])

{{--
    Pembungkus field: label terhubung ke input (id = $name), petunjuk & error per field.
    Input di dalam slot memasang aria-describedby="{name}-hint {name}-error" dan aria-invalid (PRD 9.4).
--}}
<div {{ $attributes }}>
    <label for="{{ $name }}" class="mb-2 block text-xs font-bold tracking-widest text-fg-muted uppercase">
        {{ $label }}
        @if ($required)
            <span class="text-accent" aria-hidden="true">*</span><span class="sr-only">(wajib)</span>
        @else
            <span class="font-medium tracking-normal normal-case">(opsional)</span>
        @endif
    </label>

    {{ $slot }}

    @if ($hint)
        <p id="{{ $name }}-hint" class="mt-1.5 text-sm text-fg-subtle">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="mt-1.5 text-sm font-semibold text-danger">{{ $message }}</p>
    @enderror
</div>
