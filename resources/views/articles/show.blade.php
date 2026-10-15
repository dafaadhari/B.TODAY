@php
    $articleUrl = route('articles.show', $article->slug);
    $category = $article->category;

    $breadcrumb = [['label' => 'Beranda', 'url' => route('home')]];
    if ($category?->parent) {
        $breadcrumb[] = ['label' => $category->parent->name, 'url' => route('categories.show', $category->parent->slug)];
    }
    if ($category) {
        $breadcrumb[] = ['label' => $category->name, 'url' => route('categories.show', $category->slug)];
    }
    $breadcrumb[] = ['label' => $article->title, 'class' => 'hidden md:flex'];

    $imageUrl = $article->thumbnail_path ? url($article->thumbnail_path) : null;
@endphp

<x-app-layout :title="$article->title.' - '.config('portal.name')" :description="$article->excerpt" :image="$imageUrl" type="article" :canonical="$articleUrl">
    @push('head')
        @if ($article->published_at)
            <meta property="article:published_time" content="{{ $article->published_at->toIso8601String() }}">
        @endif
        @if ($category)
            <meta property="article:section" content="{{ $category->name }}">
        @endif
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'NewsArticle',
                'headline' => $article->title,
                'description' => $article->excerpt,
                'image' => $imageUrl ? [$imageUrl] : [asset('images/og-default.png')],
                'datePublished' => $article->published_at?->toIso8601String(),
                'dateModified' => $article->published_at?->toIso8601String(),
                'author' => [['@type' => 'Person', 'name' => $article->byline_name]],
                'publisher' => ['@type' => 'NewsMediaOrganization', 'name' => config('portal.name'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('images/og-default.png')]],
                'mainEntityOfPage' => $articleUrl,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($breadcrumb)->filter(fn ($item) => ! empty($item['url']))->values()->map(fn ($item, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['label'],
                    'item' => $item['url'],
                ])->all(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
        </script>
    @endpush

    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-8 lg:grid-cols-3">
        <article class="min-w-0 lg:col-span-2">
            <x-breadcrumb :items="$breadcrumb" />

            <h1 class="mb-6 text-[1.75rem] leading-[1.15] font-extrabold text-balance text-fg-strong sm:text-4xl lg:text-5xl">
                {{ $article->title }}
            </h1>

            <div class="mb-8 flex flex-col justify-between gap-4 border-y border-line py-4 sm:flex-row sm:items-center">
                <x-byline :article="$article" />
                <x-share-buttons :url="$articleUrl" :title="$article->title" class="shrink-0" />
            </div>

            @if ($article->thumbnail_path)
                <figure class="mb-8">
                    <img src="{{ $article->thumbnail_path }}" alt="{{ $article->title }}" width="1280" height="720" fetchpriority="high" decoding="async"
                        class="aspect-video w-full rounded-2xl bg-raised object-cover">
                </figure>
            @endif

            {{-- Iklan native disisipkan setelah paragraf ke-3; artikel pendek mendapat iklan setelah isi (PRD 4C.3). --}}
            <div class="article-body">{!! $contentBeforeAd !!}</div>
            <x-ad-slot placement="in_article" class="my-8 max-w-[70ch]" />
            @if ($contentAfterAd !== '')
                <div class="article-body">{!! $contentAfterAd !!}</div>
            @endif

            @if ($article->is_guest_contribution)
                <aside class="mt-10 max-w-[70ch] rounded-xl border border-line bg-raised p-5 text-sm leading-relaxed text-fg-muted" aria-label="Catatan tulisan pembaca">
                    <p>
                        <span class="font-bold text-fg-strong">Tulisan kiriman pembaca.</span>
                        Isi tulisan ini menjadi tanggung jawab penulis dan telah melalui peninjauan redaksi {{ config('portal.name') }}.
                    </p>
                    <a href="{{ route('kirim-tulisan.create') }}" class="mt-2 inline-flex min-h-11 items-center font-bold text-accent hover:underline">
                        Ingin menulis juga? Kirim tulisan Anda <span class="ml-1" aria-hidden="true">&rarr;</span>
                    </a>
                </aside>
            @endif

            @if ($article->tags->isNotEmpty())
                <div class="mt-10 flex flex-wrap items-center gap-2 border-t border-line pt-6">
                    <span class="mr-1 text-xs font-bold tracking-widest text-fg-muted uppercase">Tags:</span>
                    @foreach ($article->tags as $tag)
                        <a href="{{ route('search', ['q' => $tag->name]) }}"
                            class="inline-flex min-h-10 items-center rounded-lg border border-line-strong bg-raised px-3 text-xs font-bold text-fg transition-colors hover:border-brand hover:text-accent">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <x-share-buttons :url="$articleUrl" :title="$article->title" label="Bagikan artikel ini"
                :class="Arr::toCssClasses(['mt-6', 'border-t border-line pt-6' => $article->tags->isEmpty()])" />

            @if ($relatedArticles->isNotEmpty())
                <section aria-label="Artikel terkait" class="mt-12">
                    <x-section-heading title="Artikel Terkait" />
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        @foreach ($relatedArticles as $related)
                            <div @class(['hidden sm:block' => $loop->index >= 2])>
                                <x-article-card :article="$related" variant="compact" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        <aside class="space-y-10 lg:sticky lg:top-24 lg:self-start" aria-label="Sidebar">
            <x-ad-slot placement="sidebar" />

            @if ($popularArticles->isNotEmpty())
                <section aria-label="Baca juga">
                    <x-section-heading title="Baca Juga" size="sm" />
                    <ul class="space-y-1">
                        @foreach ($popularArticles as $popular)
                            <li class="group relative rounded-lg p-3 transition-colors hover:bg-raised/50">
                                <h3 class="text-sm leading-snug font-bold text-fg transition-colors group-hover:text-accent">
                                    <a href="{{ route('articles.show', $popular->slug) }}" class="after:absolute after:inset-0">{{ $popular->title }}</a>
                                </h3>
                                <p class="mt-1 text-xs text-fg-subtle">
                                    <x-time :date="$popular->published_at" format="relative" /> &bull; {{ $popular->views_label }} dibaca
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($article->tags->isNotEmpty())
                <section aria-label="Topik terkait" class="hidden lg:block">
                    <x-section-heading title="Topik Terkait" size="sm" />
                    <ul class="flex flex-wrap gap-2">
                        @foreach ($article->tags as $tag)
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
</x-app-layout>
