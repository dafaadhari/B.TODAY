@props(['title', 'value', 'icon' => 'document-text', 'color' => 'amber'])

{{--
    $icon: nama ikon Heroicons outline (mis. "newspaper"), dirender lewat blade-heroicons.
    HP: hanya ikon di atas angka (grid 3–4 kolom); label tetap dibaca pembaca layar.
    ≥640px: label + angka di kiri, ikon di kanan.
--}}
@php
    [$borderColor, $iconColor] = match($color) {
        'emerald' => ['hover:border-emerald-500/50', 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20'],
        'blue' => ['hover:border-blue-500/50', 'text-blue-400 bg-blue-500/10 border-blue-500/20'],
        'rose' => ['hover:border-rose-500/50', 'text-rose-400 bg-rose-500/10 border-rose-500/20'],
        default => ['hover:border-amber-500/50', 'text-amber-400 bg-amber-500/10 border-amber-500/20'],
    };
@endphp

<div class="relative bg-gray-900/90 border border-gray-800 rounded-xl px-2 py-3 sm:p-5 shadow-lg transition-all {{ $borderColor }}">
    <div class="flex flex-col-reverse items-center gap-2 sm:flex-row sm:justify-between sm:gap-3">
        <div class="min-w-0 text-center sm:text-left">
            <p class="sr-only sm:not-sr-only sm:mb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">{{ $title }}</p>
            <p class="text-lg sm:text-2xl font-extrabold leading-tight tabular-nums text-gray-100">{{ number_format($value) }}</p>
        </div>
        <div class="w-8 h-8 sm:w-12 sm:h-12 shrink-0 rounded-lg border flex items-center justify-center {{ $iconColor }}">
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="w-4 h-4 sm:w-6 sm:h-6" aria-hidden="true" />
        </div>
    </div>
</div>
