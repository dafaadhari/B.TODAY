@props(['articles', 'headingLevel' => 'h3'])

<ol {{ $attributes }}>
    @foreach ($articles as $article)
        <li @class(['group relative -mx-2 flex items-start gap-4 rounded px-2 py-4 transition-colors hover:bg-raised/40', 'border-b border-line' => ! $loop->last])>
            {{-- Angka urut amber 50% (3,5:1, teks besar) — demo memakai 30% yang terlalu pudar. --}}
            <span class="mt-0.5 w-9 shrink-0 text-3xl leading-none font-extrabold text-brand/50 transition-colors group-hover:text-brand" aria-hidden="true">
                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
            </span>
            <div class="min-w-0">
                <{{ $headingLevel }} class="text-sm leading-snug font-bold text-fg transition-colors group-hover:text-accent">
                    <a href="{{ route('articles.show', $article->slug) }}" class="after:absolute after:inset-0">{{ $article->title }}</a>
                </{{ $headingLevel }}>
                <p class="mt-1 text-xs text-fg-subtle">
                    {{ $article->views_label }} dibaca &bull; <x-time :date="$article->published_at" format="day" />
                </p>
            </div>
        </li>
    @endforeach
</ol>
