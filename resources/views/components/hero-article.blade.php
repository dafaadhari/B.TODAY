@props(['article'])

<article {{ $attributes->class('group relative flex flex-col gap-6 lg:flex-row lg:gap-8 has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-4 has-[a:focus-visible]:outline-focus rounded-2xl') }}>
    <div class="relative overflow-hidden rounded-2xl bg-raised lg:w-2/3">
        <img src="{{ $article->thumbnail_path ?: asset('images/placeholder.svg') }}" data-fallback="{{ asset('images/placeholder.svg') }}" alt="" width="1280" height="720" fetchpriority="high" decoding="async"
            class="aspect-video w-full object-cover transition-transform duration-700 group-hover:scale-105 md:aspect-auto md:h-[420px]">
        <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/20 to-transparent" aria-hidden="true"></div>
        @if ($article->category)
            <span class="absolute bottom-5 left-5 rounded bg-brand px-3 py-1.5 text-xs font-extrabold tracking-widest text-on-brand uppercase shadow-lg">
                {{ $article->category->name }}
            </span>
        @endif
    </div>

    <div class="flex flex-col justify-center lg:w-1/3">
        <h2 class="mb-4 text-2xl leading-[1.2] font-extrabold text-balance text-fg-strong transition-colors group-hover:text-accent md:text-4xl lg:text-3xl xl:text-4xl">
            <a href="{{ route('articles.show', $article->slug) }}" class="after:absolute after:inset-0 focus-visible:outline-none">{{ $article->title }}</a>
        </h2>
        <p class="mb-6 line-clamp-3 text-sm leading-relaxed text-fg-muted">{{ $article->excerpt }}</p>
        <div class="flex items-center gap-3 border-t border-line pt-4">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-amber-400 to-amber-600 text-xs font-bold text-black" aria-hidden="true">
                {{ $article->byline_initials }}
            </span>
            <div>
                <p class="text-xs font-bold text-fg-strong">{{ $article->byline_name }}</p>
                <p class="text-xs text-fg-subtle">
                    <x-time :date="$article->published_at" format="relative" /> &bull; {{ $article->read_minutes }} menit baca
                </p>
            </div>
        </div>
    </div>
</article>
