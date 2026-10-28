@props(['categories'])

@php
    $currentSlug = request()->routeIs('categories.show') ? request()->route('slug') : null;
    $linkClass = 'flex min-h-11 items-center text-sm font-bold tracking-widest uppercase transition-colors';
    $idleClass = 'text-fg-muted hover:text-fg-strong';
@endphp

<nav id="menu-utama" aria-label="Menu utama" x-show="menuOpen" x-cloak
    x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="-translate-y-2 opacity-0"
    x-transition:leave="transition duration-150 ease-in" x-transition:leave-end="opacity-0"
    @click="if ($event.target.closest('a')) menuOpen = false"
    {{ $attributes->class('max-h-[70vh] overflow-y-auto border-t border-line bg-overlay backdrop-blur-lg xl:hidden') }}>
    <ul class="mx-auto max-w-7xl px-4 py-3">
        <li>
            <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif
                @class([$linkClass, 'text-accent' => request()->routeIs('home'), $idleClass => ! request()->routeIs('home')])>
                Beranda
            </a>
        </li>
        @foreach ($categories as $category)
            @php
                $isActive = $currentSlug && in_array($currentSlug, [$category->slug, ...$category->children->pluck('slug')->all()], true);
            @endphp
            <li>
                @if ($category->children->isNotEmpty())
                    <div x-data="{ expanded: @js($isActive) }">
                        <button type="button" @click="expanded = ! expanded" :aria-expanded="expanded.toString()"
                            aria-controls="menu-hp-{{ $category->slug }}"
                            @class([$linkClass, 'w-full justify-between', 'text-accent' => $isActive, $idleClass => ! $isActive])>
                            {{ $category->name }}
                            <svg class="size-5 transition-transform" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <ul id="menu-hp-{{ $category->slug }}" x-show="expanded" x-cloak class="mb-2 ml-1 border-l-2 border-line pl-4">
                            <li>
                                <a href="{{ route('categories.show', $category->slug) }}"
                                    @if ($currentSlug === $category->slug) aria-current="page" @endif
                                    class="flex min-h-11 items-center text-sm font-bold text-accent">
                                    Semua {{ $category->name }}
                                </a>
                            </li>
                            @foreach ($category->children as $child)
                                <li>
                                    <a href="{{ route('categories.show', $child->slug) }}"
                                        @if ($currentSlug === $child->slug) aria-current="page" @endif
                                        @class([
                                            'flex min-h-11 items-center text-sm font-semibold transition-colors',
                                            'text-accent' => $currentSlug === $child->slug,
                                            $idleClass => $currentSlug !== $child->slug,
                                        ])>
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <a href="{{ route('categories.show', $category->slug) }}" @if ($isActive) aria-current="page" @endif
                        @class([$linkClass, 'text-accent' => $isActive, $idleClass => ! $isActive])>
                        {{ $category->name }}
                    </a>
                @endif
            </li>
        @endforeach
        <li class="my-2 border-t border-line" aria-hidden="true"></li>
        <li><a href="{{ route('search') }}" class="{{ $linkClass }} {{ $idleClass }}">Cari Berita</a></li>
        <li><a href="{{ route('kirim-tulisan.create') }}" class="{{ $linkClass }} {{ $idleClass }}">Kirim Tulisan</a></li>
        <li><a href="{{ route('pasang-iklan') }}" class="{{ $linkClass }} {{ $idleClass }}">Pasang Iklan</a></li>
        <li><a href="{{ route('redaksi') }}" class="{{ $linkClass }} {{ $idleClass }}">Redaksi</a></li>
    </ul>
</nav>
