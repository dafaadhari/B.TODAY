@props(['eyebrow' => null, 'lead' => null, 'align' => 'left'])

<header {{ $attributes->class(['mb-12', 'text-center' => $align === 'center']) }}>
    @if ($eyebrow)
        <p class="mb-3 text-xs font-bold tracking-widest text-accent uppercase">{{ $eyebrow }}</p>
    @endif

    <h1 class="text-4xl leading-tight font-extrabold text-balance text-fg-strong md:text-5xl">{{ $slot }}</h1>

    @if ($lead)
        <p @class(['mt-4 max-w-2xl leading-relaxed text-fg-muted', 'mx-auto' => $align === 'center'])>{{ $lead }}</p>
    @endif
</header>
