<x-app-layout title="Formulir Kiriman Tulisan Publik - B.Today">
    <div class="max-w-3xl mx-auto">
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-2xl p-6 md:p-10 shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-black dark:text-white text-slate-900">Kirim Tulisan Publik</h1>
                <p class="text-xs dark:text-gray-400 text-slate-500 mt-2 max-w-lg mx-auto leading-relaxed">
                    Bagikan artikel, opini, atau liputan Anda kepada jutaan pembaca B.Today. Tulisan Anda akan ditinjau oleh tim Redaksi sebelum diterbitkan.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-semibold leading-relaxed">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs space-y-1">
                    <span class="font-bold block">Mohon perbaiki kesalahan berikut:</span>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kirim-tulisan.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors" placeholder="Masukkan nama lengkap Anda">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Instansi / Komunitas (Opsional)</label>
                        <input type="text" name="instansi" value="{{ old('instansi') }}" class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors" placeholder="Contoh: Pegiat UMKM / Mahasiswa">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors" placeholder="email@domain.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Kategori Berita *</label>
                        <select name="category_id" required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Judul Tulisan *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors" placeholder="Tuliskan judul berita/opini yang menarik">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider dark:text-gray-300 text-slate-700 mb-2">Naskah / Isi Tulisan *</label>
                    <textarea name="content" rows="10" required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl p-4 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors" placeholder="Tuliskan isi artikel Anda secara jelas minimal 50 karakter...">{{ old('content') }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-sm uppercase tracking-wider rounded-xl transition-all shadow-xl">
                        🚀 Kirim Naskah Ke Redaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

