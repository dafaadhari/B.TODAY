<x-app-layout :trending="$trendingArticles">
    <h1 class="sr-only">{{ config('portal.name') }} — Berita Terkini Banten &amp; Nasional</h1>

    <div class="mx-auto max-w-7xl px-4">
        <x-ad-slot placement="leaderboard" class="mt-6" />
    </div>

    <div class="mx-auto mt-10 max-w-7xl px-4">
        @if ($heroArticle)
            <x-hero-article :article="$heroArticle" class="mb-14" />
        @endif

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="min-w-0 lg:col-span-2">
                <section aria-label="Berita terbaru">
                    <x-section-heading title="Berita Terbaru" :href="route('search')" />

                    @if ($latestArticles->isEmpty())
                        <x-empty-state title="Belum ada berita">
                            Berita terbaru akan tampil di sini begitu redaksi menerbitkannya.
                        </x-empty-state>
                    @else
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @foreach ($latestArticles as $article)
                                @if ($loop->index < 2)
                                    <x-article-card :article="$article" :eager="$loop->first" />
                                @else
                                    {{-- Di HP kartu ke-3 dst. ringkas agar tidak perlu scroll panjang. --}}
                                    <div class="hidden md:block">
                                        <x-article-card :article="$article" />
                                    </div>
                                    <div class="md:hidden">
                                        <x-article-card :article="$article" variant="horizontal" />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </section>

                @if ($popularArticles->isNotEmpty())
                    <section aria-label="Terpopuler" class="mt-12 lg:hidden">
                        <x-section-heading title="Terpopuler" size="sm" />
                        <x-popular-list :articles="$popularArticles" />
                    </section>
                @endif

                @if ($bantenArticles->isNotEmpty())
                    <section aria-label="Banten Today" class="mt-12 border-t border-line pt-10">
                        <x-section-heading title="Banten Today" :href="route('categories.show', 'banten')" link-label="Semua Banten" />

                        @if ($bantenCategory?->children->isNotEmpty())
                            <ul class="hide-scrollbar -mx-4 mb-6 flex gap-2 overflow-x-auto px-4 md:mx-0 md:flex-wrap md:px-0" aria-label="Wilayah Banten">
                                @foreach ($bantenCategory->children as $region)
                                    <li class="shrink-0">
                                        <a href="{{ route('categories.show', $region->slug) }}"
                                            class="inline-flex min-h-10 items-center rounded-lg border border-line-strong bg-raised px-4 text-xs font-bold whitespace-nowrap text-fg transition-colors hover:border-brand hover:text-accent">
                                            {{ $region->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @foreach ($bantenArticles as $article)
                                <x-article-card :article="$article" variant="horizontal" />
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-10" aria-label="Sidebar">
                @if ($popularArticles->isNotEmpty())
                    <section aria-label="Terpopuler" class="hidden lg:block">
                        <x-section-heading title="Terpopuler" size="sm" />
                        <x-popular-list :articles="$popularArticles" />
                    </section>
                @endif

                <x-ad-slot placement="sidebar" />

                <section aria-label="Kirim tulisan" class="rounded-xl border border-line bg-surface p-6">
                    <p class="mb-2 text-xs font-bold tracking-widest text-accent uppercase">Kontribusi Pembaca</p>
                    <h2 class="mb-2 text-lg leading-snug font-extrabold text-fg-strong">Punya gagasan atau kabar dari daerah Anda?</h2>
                    <p class="mb-5 text-sm leading-relaxed text-fg-muted">
                        Kirim tulisan tanpa perlu membuat akun. Setiap naskah dibaca redaksi sebelum tayang.
                    </p>
                    <x-button :href="route('kirim-tulisan.create')" class="w-full">Kirim Tulisan</x-button>
                </section>

                @if ($featuredTags->isNotEmpty())
                    <section aria-label="Topik pilihan">
                        <x-section-heading title="Topik Pilihan" size="sm" />
                        <ul class="flex flex-wrap gap-2">
                            @foreach ($featuredTags as $tag)
                                <li>
                                    <a href="{{ route('search', ['q' => $tag->name]) }}"
                                        class="inline-flex min-h-10 items-center rounded-lg border border-line-strong bg-raised px-4 text-xs font-bold text-fg transition-colors hover:border-brand hover:text-accent">
                                        #{{ $tag->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </aside>
        </div>
    </div>
</x-app-layout>
