@props(['title', 'value', 'icon' => '📄', 'color' => 'amber'])

@php
    $borderColor = match($color) {
        'emerald' => 'hover:border-emerald-500/50',
        'amber' => 'hover:border-amber-500/50',
        'blue' => 'hover:border-blue-500/50',
        'rose' => 'hover:border-rose-500/50',
        default => 'hover:border-amber-500/50',
    };
@endphp

<div class="bg-gray-900/90 border border-gray-800 rounded-xl p-5 shadow-lg transition-all {{ $borderColor }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ $title }}</p>
            <h4 class="text-2xl font-extrabold text-gray-100">{{ number_format($value) }}</h4>
        </div>
        <div class="w-12 h-12 rounded-lg bg-gray-800/80 border border-gray-700/60 flex items-center justify-center text-xl shadow">
            {{ $icon }}
        </div>
    </div>
</div>
