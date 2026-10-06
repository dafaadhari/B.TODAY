<x-app-layout title="Pencarian Berita - B.Today">
    <div class="space-y-8">
        <!-- Search Header Bar -->
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-2xl p-6 md:p-8 shadow-xl">
            <h1 class="text-xl font-black dark:text-white text-slate-900 mb-4">🔍 Pencarian Artikel &amp; Berita</h1>
            <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Ketik kata kunci pencarian..." class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors">
                </div>
                <div>
                    <select name="category_id" class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold text-sm rounded-xl transition-all shadow-lg">
                        Cari Berita
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results Grid -->
        <div>
            @if($query)
                <p class="text-sm dark:text-gray-400 text-slate-500 mb-6">
                    Menampilkan hasil pencarian untuk: <span class="text-amber-500 font-bold">"{{ $query }}"</span> ({{ $articles->total() }} berita ditemukan)
                </p>
            @endif

            @if($articles->count())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="p-12 text-center dark:bg-gray-900/40 bg-white border dark:border-gray-800 border-gray-200 rounded-2xl shadow-md">
                    <span class="text-4xl block mb-3">🔍</span>
                    <h3 class="text-base font-bold dark:text-gray-300 text-slate-700">Tidak ada artikel yang cocok</h3>
                    <p class="text-xs dark:text-gray-500 text-slate-400 mt-1">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
