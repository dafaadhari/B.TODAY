<x-admin-layout title="Tambah Berita Baru - B.Today CMS">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Tambah Berita Baru</h1>
            <p class="text-xs text-gray-400">Tulis dan ajukan draft berita baru ke dalam alur review redaksi.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="py-2.5 px-4 bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold text-xs rounded-xl transition-all">
            ← Kembali Ke Daftar Artikel
        </a>
    </div>

    <div class="bg-gray-900 border border-gray-800 p-6 md:p-8 rounded-2xl shadow-xl">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Judul Artikel *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none" placeholder="Masukkan judul artikel berita yang menarik">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Kategori Berita *</label>
                    <select name="category_id" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Gambar Sampul / Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Status Publikasi *</label>
                    <select name="status" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none">
                        <option value="draft">Draft Redaksi</option>
                        <option value="pending_review">Ajukan Ke Antrean Review</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Isi Konten Artikel *</label>
                <textarea name="content" id="editor" rows="12" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl p-4 text-sm text-gray-100 outline-none">{{ old('content') }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-800 flex justify-end">
                <button type="submit" class="py-3 px-8 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl">
                    💾 Simpan Artikel Baru
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
