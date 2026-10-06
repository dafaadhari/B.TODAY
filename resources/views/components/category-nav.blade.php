@props(['categories'])

@php
    $primarySlugs = ['banten', 'nasional', 'jabodetabek', 'olahraga', 'teknologi'];
    $primaryCategories = collect($primarySlugs)->map(fn($slug) => $categories->firstWhere('slug', $slug))->filter();
    $moreCategories = $categories->reject(fn($c) => in_array($c->slug, $primarySlugs));
@endphp

<nav class="flex items-center space-x-1 xl:space-x-1.5 py-2 text-xs xl:text-sm font-medium whitespace-nowrap overflow-visible">
    <a href="{{ route('home') }}" class="px-2.5 py-1.5 rounded-md transition-colors {{ request()->routeIs('home') ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}">
        Beranda
    </a>

    @foreach($primaryCategories as $category)
        @if($category->slug === 'banten' && $category->children->count())
            <div x-data="{ open: false }" 
                 class="relative inline-flex items-center" 
                 @mouseenter="open = true" 
                 @mouseleave="open = false" 
                 @click.outside="open = false">
                <a href="{{ route('categories.show', 'banten') }}" 
                   class="px-2.5 py-1.5 rounded-md transition-colors {{ request()->is('kategori/banten*') ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}">
                    Banten
                </a>

                <!-- Dropdown Menu Wilayah Banten (Tanpa icon, bersih dan rapi) -->
                <div x-cloak 
                     x-show="open" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                     class="absolute left-0 top-full mt-1 w-48 dark:bg-slate-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-xl py-1.5 z-50">
                    <a href="{{ route('categories.show', 'banten') }}" class="block px-4 py-2 text-xs font-bold text-amber-500 hover:bg-amber-500/10 transition-colors {{ request()->fullUrlIs(route('categories.show', 'banten')) ? 'bg-amber-500/10' : '' }}">
                        Semua Banten Today
                    </a>
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}" class="block px-4 py-2 text-xs transition-colors {{ request()->is('kategori/'.$child->slug) ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}">
                            {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <a href="{{ route('categories.show', $category->slug) }}" class="px-2.5 py-1.5 rounded-md transition-colors {{ request()->is('kategori/'.$category->slug) ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}">
                {{ $category->name }}
            </a>
        @endif
    @endforeach

    <!-- Dropdown Kategori Lainnya (Budaya, Kuliner, Hukum, Otomotif) -->
    @if($moreCategories->count())
        <div x-data="{ openMore: false }" 
             class="relative inline-flex items-center" 
             @mouseenter="openMore = true" 
             @mouseleave="openMore = false" 
             @click.outside="openMore = false">
            <button @click="openMore = !openMore" 
                    type="button" 
                    class="inline-flex items-center px-2.5 py-1.5 rounded-md transition-colors {{ $moreCategories->contains(fn($c) => request()->is('kategori/'.$c->slug)) ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}"
                    title="Kategori Lainnya"
                    aria-label="Kategori Lainnya">
                <span>Lainnya</span>
                <svg class="w-3.5 h-3.5 ml-1 transform transition-transform duration-200" :class="{ 'rotate-180': openMore }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Menu Dropdown Lainnya -->
            <div x-cloak 
                 x-show="openMore" 
                 x-transition:enter="transition ease-out duration-150" 
                 x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave="transition ease-in duration-100" 
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                 class="absolute right-0 top-full mt-1 w-48 dark:bg-slate-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-xl py-1.5 z-50">
                @foreach($moreCategories as $moreCat)
                    <a href="{{ route('categories.show', $moreCat->slug) }}" class="block px-4 py-2 text-xs transition-colors {{ request()->is('kategori/'.$moreCat->slug) ? 'text-amber-500 font-bold bg-amber-500/10' : 'dark:text-gray-300 text-slate-700 hover:text-amber-500 hover:bg-amber-500/10' }}">
                        {{ $moreCat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</nav>
