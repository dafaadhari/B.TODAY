<x-admin-layout title="Edit Artikel - B.Today CMS">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Sunting Artikel Berita</h1>
            <p class="text-xs text-gray-400">Ubah judul, naskah, atau status artikel berita.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-1.5 py-2.5 px-4 bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold text-xs rounded-xl transition-all">
            <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            Kembali ke Daftar Artikel
        </a>
    </div>

    <div class="bg-gray-900 border border-gray-800 p-6 md:p-8 rounded-2xl shadow-xl">
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Judul Artikel *</label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Kategori Berita *</label>
                    <select name="category_id" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Ganti Gambar Sampul</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2.5 text-xs text-gray-300">
                    @if($article->thumbnail_path)
                        <img src="{{ $article->thumbnail_path }}" class="mt-2 h-16 rounded border border-gray-800">
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Status Publikasi *</label>
                    <select name="status" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none">
                        <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Draft Redaksi</option>
                        <option value="pending_review" {{ $article->status == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>Terbit (Published)</option>
                        <option value="rejected" {{ $article->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Isi Konten Artikel *</label>
                <textarea name="content" rows="12" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl p-4 text-sm text-gray-100 outline-none">{{ old('content', $article->content) }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-800 flex justify-end">
                <button type="submit" class="py-3 px-8 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl">
                    Perbarui Artikel
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
