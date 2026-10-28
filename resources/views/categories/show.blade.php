@php
    $breadcrumb = [['label' => 'Beranda', 'url' => route('home')]];
    if ($category->parent) {
        $breadcrumb[] = ['label' => $category->parent->name, 'url' => route('categories.show', $category->parent->slug)];
    }
    $breadcrumb[] = ['label' => $category->name];

    // Chip wilayah: halaman induk menampilkan anaknya, halaman anak menampilkan saudaranya.
    $regionParent = $category->children->isNotEmpty() ? $category : $category->parent;
    $regions = $regionParent?->children ?? collect();

    $description = $category->children->isNotEmpty()
        ? 'Berita terbaru dari seluruh wilayah '.$category->name.': '.$category->children->pluck('name')->implode(', ').'.'
        : 'Kumpulan berita dan liputan terkini seputar '.$category->name.' dari redaksi '.config('portal.name').'.';

    $pageTitle = 'Berita '.$category->name
        .($articles->currentPage() > 1 ? ' - Halaman '.$articles->currentPage() : '')
        .' - '.config('portal.name');

    $adPosition = min(5, $articles->count());
@endphp

<x-app-layout :title="$pageTitle" :description="$description">
    <div class="mx-auto max-w-7xl px-4 py-10">
        <header class="mb-10">
            <x-breadcrumb :items="$breadcrumb" class="mb-4" />
            <h1 class="text-4xl font-extrabold tracking-tight text-fg-strong uppercase md:text-5xl">{{ $category->name }}</h1>
            <p class="mt-4 line-clamp-3 max-w-2xl leading-relaxed text-fg-muted md:line-clamp-none">{{ $description }}</p>
            <p class="mt-5 flex flex-wrap gap-x-6 gap-y-1 text-xs font-bold tracking-widest text-fg-subtle uppercase">
                <span>{{ number_format($articles->total(), 0, ',', '.') }} artikel</span>
                @if ($lastPublishedAt)
                    <span>Diperbarui <x-time :date="\Illuminate\Support\Carbon::parse($lastPublishedAt)" format="relative" class="normal-case" /></span>
                @endif
            </p>
        </header>

        @if ($regions->isNotEmpty())
            <ul class="hide-scrollbar -mx-4 mb-8 flex gap-2 overflow-x-auto px-4 md:mx-0 md:flex-wrap md:px-0" aria-label="Wilayah {{ $regionParent->name }}">
                <li class="shrink-0">
                    <a href="{{ route('categories.show', $regionParent->slug) }}"
                        @if ($category->is($regionParent)) aria-current="page" @endif
                        @class([
                            'inline-flex min-h-10 items-center rounded-lg border px-4 text-xs font-bold whitespace-nowrap transition-colors',
                            'border-brand bg-brand text-on-brand' => $category->is($regionParent),
                            'border-line-strong bg-raised text-fg hover:border-brand hover:text-accent' => ! $category->is($regionParent),
                        ])>
                        Semua {{ $regionParent->name }}
                    </a>
                </li>
                @foreach ($regions as $region)
                    <li class="shrink-0">
                        <a href="{{ route('categories.show', $region->slug) }}"
                            @if ($category->is($region)) aria-current="page" @endif
                            @class([
                                'inline-flex min-h-10 items-center rounded-lg border px-4 text-xs font-bold whitespace-nowrap transition-colors',
                                'border-brand bg-brand text-on-brand' => $category->is($region),
                                'border-line-strong bg-raised text-fg hover:border-brand hover:text-accent' => ! $category->is($region),
                            ])>
                            {{ $region->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <x-ad-slot placement="leaderboard" class="mb-8" />

        @if ($articles->isEmpty())
            <x-empty-state title="Belum ada berita di kategori ini">
                Redaksi belum menerbitkan berita {{ $category->name }}. Punya kabar dari sini? Anda bisa mengirimkannya.
                <x-slot:actions>
                    <x-button :href="route('kirim-tulisan.create')">Kirim Tulisan</x-button>
                    <x-button :href="route('home')" variant="secondary">Kembali ke Beranda</x-button>
                </x-slot:actions>
            </x-empty-state>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" :show-category="$category->children->isNotEmpty()" :eager="$loop->index < 3" />

                    @if ($loop->iteration === $adPosition)
                        <x-ad-slot placement="sidebar" class="self-center" />
                    @endif
                @endforeach
            </div>

            {{ $articles->links('vendor.pagination.btoday') }}
        @endif
    </div>
</x-app-layout>
