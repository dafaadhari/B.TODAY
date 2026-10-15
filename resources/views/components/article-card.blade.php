@props([
    'article',
    'variant' => 'grid',
    'layout' => null,
    'showCategory' => true,
    'highlight' => null,
    'eager' => false,
    'headingLevel' => 'h3',
])

@php
    // Prop lama `layout="horizontal"` tetap diterima.
    $variant = $layout === 'horizontal' ? 'horizontal' : $variant;
    $url = route('articles.show', $article->slug);
    $imageUrl = $article->thumbnail_path ?: asset('images/placeholder.svg');
    $categoryName = $article->category?->name;

    $terms = collect(preg_split('/\s+/u', trim((string) $highlight)))
        ->filter(fn (string $term): bool => mb_strlen($term) >= 2)
        ->map(fn (string $term): string => preg_quote(e($term), '/'));

    $mark = function (string $text) use ($terms): string {
        $escaped = e($text);

        return $terms->isEmpty()
            ? $escaped
            : preg_replace('/('.$terms->implode('|').')/iu', '<mark>$1</mark>', $escaped);
    };

    $loading = $eager ? 'eager' : 'lazy';
    $titleLink = 'after:absolute after:inset-0 focus-visible:outline-none';
    $cardBase = 'group relative overflow-hidden rounded-xl border border-line bg-surface transition-colors hover:border-brand/50 has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-2 has-[a:focus-visible]:outline-focus';
@endphp

@if ($variant === 'list')
    <article {{ $attributes->class([$cardBase, 'flex gap-4 p-3 sm:gap-5 sm:p-4']) }}>
        <div class="w-24 shrink-0 overflow-hidden rounded-lg bg-raised sm:w-48">
            <img src="{{ $imageUrl }}" alt="" width="384" height="256" loading="{{ $loading }}" decoding="async"
                class="aspect-[3/2] size-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="flex min-w-0 flex-col justify-center">
            <p class="mb-1.5 flex flex-wrap items-center gap-x-2 text-xs">
                @if ($showCategory && $categoryName)
                    <span class="font-bold tracking-widest text-accent uppercase">{{ $categoryName }}</span>
                    <span class="text-line-strong" aria-hidden="true">&bull;</span>
                @endif
                <x-time :date="$article->published_at" class="text-fg-subtle" />
            </p>
            <{{ $headingLevel }} class="text-base leading-snug font-bold text-fg-strong transition-colors group-hover:text-accent">
                <a href="{{ $url }}" class="{{ $titleLink }}">{!! $mark($article->title) !!}</a>
            </{{ $headingLevel }}>
            <p class="mt-1.5 line-clamp-2 hidden text-sm text-fg-muted sm:block">{!! $mark($article->excerpt) !!}</p>
        </div>
    </article>
@elseif ($variant === 'horizontal')
    <article {{ $attributes->class([$cardBase, 'flex gap-4 p-3']) }}>
        <div class="w-28 shrink-0 overflow-hidden rounded-lg bg-raised">
            <img src="{{ $imageUrl }}" alt="" width="224" height="168" loading="{{ $loading }}" decoding="async"
                class="aspect-[4/3] size-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="flex min-w-0 flex-col justify-center">
            @if ($showCategory && $categoryName)
                <p class="mb-1 text-xs font-bold tracking-widest text-accent uppercase">{{ $categoryName }}</p>
            @endif
            <{{ $headingLevel }} class="line-clamp-3 text-sm leading-snug font-bold text-fg-strong transition-colors group-hover:text-accent">
                <a href="{{ $url }}" class="{{ $titleLink }}">{{ $article->title }}</a>
            </{{ $headingLevel }}>
            <x-time :date="$article->published_at" format="relative" class="mt-1.5 text-xs text-fg-subtle" />
        </div>
    </article>
@elseif ($variant === 'compact')
    <article {{ $attributes->class([$cardBase, 'flex h-full flex-col']) }}>
        <div class="overflow-hidden bg-raised">
            <img src="{{ $imageUrl }}" alt="" width="640" height="360" loading="{{ $loading }}" decoding="async"
                class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        <div class="flex grow flex-col p-4">
            @if ($showCategory && $categoryName)
                <p class="mb-2 text-xs font-bold tracking-widest text-accent uppercase">{{ $categoryName }}</p>
            @endif
            <{{ $headingLevel }} class="text-sm leading-snug font-bold text-fg-strong transition-colors group-hover:text-accent">
                <a href="{{ $url }}" class="{{ $titleLink }}">{{ $article->title }}</a>
            </{{ $headingLevel }}>
            <x-time :date="$article->published_at" format="relative" class="mt-auto pt-2 text-xs text-fg-subtle" />
        </div>
    </article>
@else
    <article {{ $attributes->class([$cardBase, 'flex h-full flex-col']) }}>
        <div class="relative overflow-hidden bg-raised">
            <img src="{{ $imageUrl }}" alt="" width="800" height="450" loading="{{ $loading }}" decoding="async"
                class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-105">
            @if ($showCategory && $categoryName)
                <span class="absolute top-3 right-3 z-10 rounded border border-white/15 bg-black/70 px-2 py-1 text-xs font-bold text-white backdrop-blur">{{ $categoryName }}</span>
            @endif
        </div>
        <div class="flex grow flex-col p-5">
            <x-time :date="$article->published_at" format="relative" class="mb-2 text-xs font-medium text-fg-subtle" />
            <{{ $headingLevel }} class="mb-2 text-base leading-snug font-bold text-fg-strong transition-colors group-hover:text-accent md:text-lg">
                <a href="{{ $url }}" class="{{ $titleLink }}">{{ $article->title }}</a>
            </{{ $headingLevel }}>
            <p class="line-clamp-3 text-sm text-fg-muted">{{ $article->excerpt }}</p>
            <p class="mt-auto flex items-center gap-2 pt-4 text-xs text-fg-subtle">
                <span class="truncate">{{ $article->byline_name }}</span>
                @if ($article->is_guest_contribution)
                    <span class="shrink-0 rounded border border-line-strong px-1.5 py-0.5 font-bold tracking-wider text-fg-muted uppercase">Kontributor</span>
                @endif
            </p>
        </div>
    </article>
@endif
