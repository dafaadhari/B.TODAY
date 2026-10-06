<x-app-layout title="Halaman Tidak Ditemukan (404) - B.Today">
    <div class="max-w-2xl mx-auto py-16 text-center space-y-6">
        <span class="text-7xl font-black text-amber-500 block animate-bounce">404</span>
        <h1 class="text-3xl font-extrabold text-white">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-gray-400 max-w-md mx-auto leading-relaxed">
            Maaf, halaman atau berita yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda tuju salah.
        </p>
        <div class="pt-4">
            <a href="{{ route('home') }}" class="inline-block py-3 px-8 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl">
                ⬅️ Kembali Ke Beranda Utama
            </a>
        </div>
    </div>
</x-app-layout>
