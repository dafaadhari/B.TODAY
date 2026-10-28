@use('App\Http\Controllers\SearchController')

@php
    $activeFilters = collect([
        'category_id' => $selectedCategory ? ($selectedCategory->children->isNotEmpty() ? 'Semua '.$selectedCategory->name : $selectedCategory->name) : null,
        'rentang' => $dateRange !== '' ? SearchController::DATE_RANGES[$dateRange][0] : null,
        'urut' => $sort !== '' ? SearchController::SORTS[$sort] : null,
    ])->filter();

    $pageTitle = filled($query) ? 'Hasil "'.$query.'" - '.config('portal.name') : 'Cari Berita - '.config('portal.name');
    $selectClass = 'block min-h-11 w-full rounded-lg border border-control bg-raised px-3 text-base text-fg-strong sm:text-sm';
@endphp

<x-app-layout :title="$pageTitle" robots="noindex, follow">
    <div class="mx-auto max-w-4xl px-4 py-10">
        <form action="{{ route('search') }}" method="GET" x-data="{ filtersOpen: @js($activeFilters->isNotEmpty()) }">
            <div class="mb-10 text-center">
                <h1 class="mb-6 text-3xl font-extrabold text-fg-strong md:text-4xl">Cari Berita</h1>
                <div class="relative mx-auto max-w-2xl">
                    <label for="q" class="sr-only">Kata kunci berita</label>
                    <input type="search" id="q" name="q" value="{{ $query }}" enterkeyhint="search"
                        placeholder="Ketik kata kunci berita..."
                        class="w-full rounded-xl border border-control bg-surface py-4 pr-16 pl-6 text-base text-fg-strong placeholder:text-fg-subtle focus:border-brand">
                    <button type="submit" class="absolute top-1/2 right-2 inline-flex size-11 -translate-y-1/2 items-center justify-center rounded-lg bg-brand text-on-brand transition-colors hover:bg-brand-hover">
                        <span class="sr-only">Cari</span>
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-8 border-b border-line pb-6">
                <button type="button" @click="filtersOpen = ! filtersOpen" :aria-expanded="filtersOpen.toString()" aria-controls="filter-pencarian"
                    class="inline-flex min-h-11 items-center gap-2 text-xs font-bold tracking-widest text-fg-muted uppercase sm:hidden">
                    Filter{{ $activeFilters->isNotEmpty() ? ' ('.$activeFilters->count().')' : '' }}
                    <svg class="size-4 transition-transform" :class="filtersOpen && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                    </svg>
                </button>

                <div id="filter-pencarian" class="mt-3 hidden grid-cols-1 gap-4 sm:mt-0 sm:grid sm:grid-cols-3" :class="filtersOpen && 'grid!'">
                    <div>
                        <label for="filter-kategori" class="mb-1.5 block text-xs font-bold tracking-widest text-fg-muted uppercase">Kategori</label>
                        <select id="filter-kategori" name="category_id" class="{{ $selectClass }}" @change="$el.form.requestSubmit()">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $parentCategory)
                                @if ($parentCategory->children->isNotEmpty())
                                    <optgroup label="{{ $parentCategory->name }}">
                                        <option value="{{ $parentCategory->id }}" @selected($categoryId == $parentCategory->id)>Semua {{ $parentCategory->name }}</option>
                                        @foreach ($parentCategory->children as $child)
                                            <option value="{{ $child->id }}" @selected($categoryId == $child->id)>{{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $parentCategory->id }}" @selected($categoryId == $parentCategory->id)>{{ $parentCategory->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter-rentang" class="mb-1.5 block text-xs font-bold tracking-widest text-fg-muted uppercase">Rentang Waktu</label>
                        <select id="filter-rentang" name="rentang" class="{{ $selectClass }}" @change="$el.form.requestSubmit()">
                            @foreach (SearchController::DATE_RANGES as $value => [$label])
                                <option value="{{ $value }}" @selected($dateRange === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter-urut" class="mb-1.5 block text-xs font-bold tracking-widest text-fg-muted uppercase">Urutkan</label>
                        <select id="filter-urut" name="urut" class="{{ $selectClass }}" @change="$el.form.requestSubmit()">
                            @foreach (SearchController::SORTS as $value => $label)
                                <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <noscript>
                    <button type="submit" class="mt-4 text-sm font-bold text-accent">Terapkan filter</button>
                </noscript>
            </div>
        </form>

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-fg-muted" role="status">
                @if (filled($query))
                    Menampilkan <strong class="text-fg-strong">{{ number_format($articles->total(), 0, ',', '.') }} hasil</strong>
                    untuk "<strong class="text-accent">{{ $query }}</strong>"
                @else
                    <strong class="text-fg-strong">Berita terbaru</strong> &middot; {{ number_format($articles->total(), 0, ',', '.') }} artikel
                @endif
            </p>

            @if ($activeFilters->isNotEmpty())
                <ul class="flex flex-wrap gap-2" aria-label="Filter aktif">
                    @foreach ($activeFilters as $parameter => $label)
                        <li>
                            <a href="{{ request()->fullUrlWithoutQuery([$parameter, 'page']) }}"
                                class="inline-flex min-h-9 items-center gap-1.5 rounded-full border border-line-strong bg-raised px-3 text-xs font-semibold text-fg transition-colors hover:border-brand">
                                {{ $label }}
                                <span class="sr-only">— hapus filter</span>
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        @if ($articles->isEmpty())
            <x-empty-state title="Tidak ada berita yang cocok">
                Coba kata kunci lain yang lebih umum, periksa ejaan, atau hapus filter kategori dan rentang waktu.
                @if ($activeFilters->isNotEmpty())
                    <x-slot:actions>
                        <x-button :href="route('search', array_filter(['q' => $query]))" variant="secondary">Hapus semua filter</x-button>
                    </x-slot:actions>
                @endif
            </x-empty-state>
        @else
            <div class="space-y-4 sm:space-y-6">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" variant="list" :highlight="$query" heading-level="h2" :eager="$loop->index < 3" />
                @endforeach
            </div>

            {{ $articles->links('vendor.pagination.btoday') }}
        @endif
    </div>
</x-app-layout>
