@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $classes = [
        'inline-flex min-h-11 items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-bold tracking-widest uppercase transition-colors disabled:cursor-not-allowed disabled:opacity-60',
        'bg-brand text-on-brand hover:bg-brand-hover' => $variant === 'primary',
        'border border-line-strong bg-raised text-fg-strong hover:border-brand' => $variant === 'secondary',
        'text-fg-muted hover:bg-raised hover:text-fg-strong' => $variant === 'ghost',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
