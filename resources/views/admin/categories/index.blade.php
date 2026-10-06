<x-admin-layout title="Manajemen Kategori - B.Today CMS">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-white">Manajemen Kategori Berita</h1>
        <p class="text-xs text-gray-400">Atur kategori utama dan sub-kategori wilayah Banten Today.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form -->
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl h-fit">
            <h3 class="text-sm font-bold text-amber-400 mb-4">+ Tambah Kategori Baru</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-300 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Otomotif / Serang Today" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-gray-100 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-300 mb-1">Induk Kategori (Optional)</label>
                    <select name="parent_id" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-gray-100 outline-none">
                        <option value="">-- Kategori Flat (Tanpa Induk) --</option>
                        @foreach($categories->whereNull('parent_id') as $pCat)
                            <option value="{{ $pCat->id }}">{{ $pCat->name }} (Induk)</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg">
                    Tambah Kategori
                </button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-4 border-b border-gray-800">
                <h3 class="text-sm font-bold text-white">Daftar Kategori Terdaftar</h3>
            </div>
            <div class="divide-y divide-gray-800">
                @foreach($categories->whereNull('parent_id') as $parentCat)
                    <div class="p-4 bg-gray-950/40 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-sm text-amber-400">{{ $parentCat->name }}</span>
                            <span class="text-[10px] text-gray-500 block">Slug: {{ $parentCat->slug }}</span>
                        </div>
                        <form action="{{ route('admin.categories.destroy', $parentCat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-400 hover:underline">Hapus</button>
                        </form>
                    </div>
                    @foreach($parentCat->children as $childCat)
                        <div class="p-3 pl-8 bg-gray-900/60 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-gray-300">└ {{ $childCat->name }}</span>
                                <span class="text-[10px] text-gray-500 ml-2">(Sub-kategori {{ $parentCat->name }})</span>
                            </div>
                            <form action="{{ route('admin.categories.destroy', $childCat->id) }}" method="POST" onsubmit="return confirm('Hapus sub-kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-400 hover:underline">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>
