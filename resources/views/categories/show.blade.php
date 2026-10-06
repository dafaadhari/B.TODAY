<x-app-layout :title="'Berita Kategori ' . $category->name . ' - B.Today'">
    <div class="space-y-8">
        <!-- Category Header -->
        <div class="bg-gradient-to-r dark:from-gray-900 dark:via-gray-900 dark:to-amber-950/40 from-white via-white to-amber-50 p-8 rounded-2xl border dark:border-gray-800 border-gray-200 shadow-xl">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs dark:text-gray-400 text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-amber-500">Beranda</a>
                <span>/</span>
                @if($category->parent)
                    <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-amber-500">{{ $category->parent->name }}</a>
                    <span>/</span>
                @endif
                <span class="text-amber-500 font-semibold">{{ $category->name }}</span>
            </nav>

            <h1 class="text-3xl font-black dark:text-white text-slate-900 mb-2">
                Indeks Berita: <span class="text-amber-500">{{ $category->name }}</span>
            </h1>
            <p class="text-sm dark:text-gray-300 text-slate-600">
                Kumpulan berita terbaru dan liputan terkini seputar isu {{ $category->name }}.
            </p>

            <!-- Sub-categories pills if Banten -->
            @if($category->children->count())
                <div class="mt-6 pt-4 border-t dark:border-gray-800 border-gray-200 flex flex-wrap gap-2">
                    <span class="text-xs font-bold dark:text-gray-400 text-slate-500 self-center mr-2">Wilayah Banten Today:</span>
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}" class="text-xs dark:bg-gray-800 bg-slate-100 hover:bg-amber-500 hover:text-gray-950 dark:text-gray-200 text-slate-700 px-3 py-1.5 rounded-lg font-semibold transition-colors">
                            📍 {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Article Grid & Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @if($articles->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($articles as $article)
                            <x-article-card :article="$article" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="p-12 text-center dark:bg-gray-900/40 bg-white border dark:border-gray-800 border-gray-200 rounded-2xl shadow-md">
                        <span class="text-4xl block mb-3">📰</span>
                        <h3 class="text-base font-bold dark:text-gray-300 text-slate-700">Belum ada berita pada kategori ini</h3>
                        <p class="text-xs dark:text-gray-500 text-slate-400 mt-1">Silakan cek kembali secara berkala untuk pembaruan terkini.</p>
                    </div>
                @endif
            </div>

            <aside>
                <x-ad-slot :ad="$sidebarAd" placement="sidebar" />
            </aside>
        </div>
    </div>
</x-app-layout>

