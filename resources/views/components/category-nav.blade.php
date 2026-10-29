@props(['categories'])

@php
    $currentSlug = request()->routeIs('categories.show') ? request()->route('slug') : null;
    $isActiveCategory = fn ($category): bool => $currentSlug !== null
        && in_array($currentSlug, [$category->slug, ...$category->children->pluck('slug')->all()], true);

    // Kategori di luar batas masuk "Lainnya" agar header tidak meluap (config portal.nav_limit).
    $navLimit = (int) config('portal.nav_limit', 8);
    $visibleCategories = $categories->take($navLimit);
    $moreCategories = $categories->slice($navLimit)->values();
    $isMoreActive = $moreCategories->contains($isActiveCategory);
    $dropdownPanel = 'absolute top-full z-50 w-56 rounded-xl border border-line bg-overlay py-2 shadow-2xl backdrop-blur-lg';
@endphp

<nav aria-label="Kategori" {{ $attributes }}>
    <ul class="flex items-center gap-x-4 text-xs font-bold tracking-widest uppercase 2xl:gap-x-5">
        @foreach ($visibleCategories as $category)
            @php
                $isActive = $isActiveCategory($category);
            @endphp
            <li>
                @if ($category->children->isNotEmpty())
                    <div class="relative" x-data="disclosure(true)" @mouseenter="hoverOpen()" @mouseleave="hoverClose()"
                        @click.outside="close()" @keydown.escape.prevent.stop="close(true)"
                        @focusout="if (! $el.contains($event.relatedTarget)) close()">
                        <button type="button" x-ref="trigger" @click="toggle()" :aria-expanded="open.toString()"
                            aria-controls="menu-{{ $category->slug }}"
                            @class([
                                'flex min-h-11 items-center gap-1 tracking-widest uppercase transition-colors',
                                'text-accent' => $isActive,
                                'text-fg-muted hover:text-accent' => ! $isActive,
                            ])>
                            {{ $category->navLabel() }}
                            <svg class="size-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="menu-{{ $category->slug }}" x-show="open" x-cloak
                            x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-2 opacity-0"
                            x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="opacity-0"
                            class="{{ $dropdownPanel }} left-0">
                            <a href="{{ route('categories.show', $category->slug) }}"
                                @if ($currentSlug === $category->slug) aria-current="page" @endif
                                class="block border-b border-line px-4 py-2.5 text-accent transition-colors hover:bg-raised">
                                Semua {{ $category->name }}
                            </a>
                            @foreach ($category->children as $child)
                                <a href="{{ route('categories.show', $child->slug) }}"
                                    @if ($currentSlug === $child->slug) aria-current="page" @endif
                                    @class([
                                        'block px-4 py-2.5 transition-colors hover:bg-raised hover:text-accent',
                                        'text-accent' => $currentSlug === $child->slug,
                                        'text-fg-muted' => $currentSlug !== $child->slug,
                                    ])>
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('categories.show', $category->slug) }}"
                        @if ($isActive) aria-current="page" @endif
                        @class([
                            'flex min-h-11 items-center transition-colors',
                            'text-accent' => $isActive,
                            'text-fg-muted hover:text-accent' => ! $isActive,
                        ])>
                        {{ $category->navLabel() }}
                    </a>
                @endif
            </li>
        @endforeach

        @if ($moreCategories->isNotEmpty())
            <li>
                <div class="relative" x-data="disclosure(true)" @mouseenter="hoverOpen()" @mouseleave="hoverClose()"
                    @click.outside="close()" @keydown.escape.prevent.stop="close(true)"
                    @focusout="if (! $el.contains($event.relatedTarget)) close()">
                    <button type="button" x-ref="trigger" @click="toggle()" :aria-expanded="open.toString()" aria-controls="menu-lainnya"
                        @class([
                            'flex min-h-11 items-center gap-1 tracking-widest uppercase transition-colors',
                            'text-accent' => $isMoreActive,
                            'text-fg-muted hover:text-accent' => ! $isMoreActive,
                        ])>
                        Lainnya
                        <svg class="size-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Rata kanan: "Lainnya" selalu di ujung menu, jadi panel tidak keluar layar. --}}
                    <div id="menu-lainnya" x-show="open" x-cloak
                        x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-2 opacity-0"
                        x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="opacity-0"
                        class="{{ $dropdownPanel }} right-0">
                        @foreach ($moreCategories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}"
                                @if ($currentSlug === $category->slug) aria-current="page" @endif
                                @class([
                                    'block px-4 py-2.5 transition-colors hover:bg-raised hover:text-accent',
                                    'text-accent' => $currentSlug === $category->slug,
                                    'text-fg-muted' => $currentSlug !== $category->slug,
                                ])>
                                {{ $category->name }}
                            </a>
                            @foreach ($category->children as $child)
                                <a href="{{ route('categories.show', $child->slug) }}"
                                    @if ($currentSlug === $child->slug) aria-current="page" @endif
                                    @class([
                                        'block py-2.5 pr-4 pl-8 transition-colors hover:bg-raised hover:text-accent',
                                        'text-accent' => $currentSlug === $child->slug,
                                        'text-fg-muted' => $currentSlug !== $child->slug,
                                    ])>
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </li>
        @endif
    </ul>
</nav>
