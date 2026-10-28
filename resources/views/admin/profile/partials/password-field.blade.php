{{-- Input password dengan tombol tampilkan/sembunyikan; pesan diambil dari error bag $bag. --}}
@php
    $hasError = $errors->{$bag}->has($name);
@endphp

<div x-data="{ visible: false }">
    <label for="{{ $id }}" class="mb-1.5 block text-xs font-bold text-gray-300">{{ $label }}</label>
    <div class="relative">
        <input id="{{ $id }}" name="{{ $name }}" type="password" :type="visible ? 'text' : 'password'" autocomplete="{{ $autocomplete }}"
            @if ($required ?? true) required @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="w-full rounded-xl border {{ $hasError ? 'border-rose-500/60' : 'border-gray-800' }} bg-gray-950 py-2.5 pl-4 pr-12 text-base text-gray-100 outline-none transition-colors focus:border-amber-500 sm:text-sm">
        <button type="button" @click="visible = ! visible" :aria-pressed="visible.toString()"
            class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-gray-500 transition-colors hover:text-gray-200">
            <span x-show="! visible"><x-heroicon-o-eye class="w-5 h-5" aria-hidden="true" /></span>
            <span x-show="visible" x-cloak><x-heroicon-o-eye-slash class="w-5 h-5" aria-hidden="true" /></span>
            <span class="sr-only">Tampilkan password</span>
        </button>
    </div>
    @if (! empty($hint))
        <p class="mt-1.5 text-[11px] text-gray-500">{{ $hint }}</p>
    @endif
    @if ($hasError)
        <p id="{{ $id }}-error" class="mt-1.5 text-[11px] font-semibold text-rose-400">{{ $errors->{$bag}->first($name) }}</p>
    @endif
</div>
