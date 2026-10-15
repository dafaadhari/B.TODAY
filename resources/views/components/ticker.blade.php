@props(['articles'])

<section aria-label="Berita trending" {{ $attributes->class('border-b border-line bg-linear-to-r from-raised via-canvas to-raised') }}>
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4">
        <span class="flex shrink-0 items-center gap-1.5 py-2.5 text-xs font-extrabold tracking-widest text-accent uppercase">
            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M13 2 4.09 12.11a.5.5 0 0 0 .38.83H11l-1 8.06 8.91-10.11a.5.5 0 0 0-.38-.83H13l1-8.06Z" />
            </svg>
            Trending
        </span>
        <div class="hide-scrollbar min-w-0 flex-1 overflow-x-auto">
            {{-- Bergerak hanya bila pembaca tidak memilih reduced-motion; berhenti saat hover/fokus (PRD 4A.2). --}}
            <div class="flex w-max hover:[animation-play-state:paused] focus-within:[animation-play-state:paused] motion-safe:animate-ticker">
                @foreach ([false, true] as $isDuplicate)
                    <ul @class(['flex shrink-0 items-center', 'motion-reduce:hidden' => $isDuplicate])
                        @if ($isDuplicate) aria-hidden="true" @endif>
                        @foreach ($articles as $article)
                            <li class="flex items-center">
                                <a href="{{ route('articles.show', $article->slug) }}"
                                    @if ($isDuplicate) tabindex="-1" @endif
                                    class="py-2.5 text-xs font-semibold whitespace-nowrap text-fg-muted transition-colors hover:text-fg-strong">
                                    {{ $article->title }}
                                </a>
                                <span class="px-4 text-line-strong" aria-hidden="true">|</span>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
</section>
