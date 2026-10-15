@props(['title', 'href' => null, 'linkLabel' => 'Lihat Semua', 'as' => 'h2', 'size' => 'md'])

<div {{ $attributes->class('mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-1') }}>
    <{{ $as }} @class([
        'border-l-4 border-brand font-extrabold tracking-widest text-fg-strong uppercase',
        'pl-3 text-lg' => $size === 'sm',
        'pl-3 text-lg sm:text-xl' => $size === 'md',
        'pl-4 text-xl sm:text-2xl' => $size === 'lg',
    ])>{{ $title }}</{{ $as }}>

    @if ($href)
        <a href="{{ $href }}" class="inline-flex min-h-8 shrink-0 items-center gap-1 text-xs font-bold tracking-widest text-fg-muted uppercase transition-colors hover:text-fg-strong">
            {{ $linkLabel }} <span aria-hidden="true">&rarr;</span>
        </a>
    @endif
</div>
