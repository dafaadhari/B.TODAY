<x-app-layout title="Media Kit & Pasang Iklan - B.Today">
    <div class="max-w-4xl mx-auto space-y-12">
        <!-- Banner Header -->
        <div class="bg-gradient-to-r dark:from-amber-500/20 dark:via-gray-900 dark:to-gray-900 from-amber-50 via-white to-white p-8 md:p-12 rounded-3xl border border-amber-500/30 text-center shadow-2xl">
            <span class="text-xs font-extrabold uppercase tracking-widest text-amber-500 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 mb-4 inline-block">Media Kit B.Today</span>
            <h1 class="text-3xl md:text-5xl font-black dark:text-white text-slate-900 mb-4">Jangkau Pembaca Berdaya Beli Tinggi</h1>
            <p class="text-sm dark:text-gray-300 text-slate-600 max-w-xl mx-auto leading-relaxed">
                Promosikan produk, brand, atau kampanye Anda di portal berita terkemuka dengan penempatan iklan responsif &amp; transparan.
            </p>
        </div>

        <!-- Spec Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 p-6 rounded-2xl shadow-md hover:border-amber-500/50 transition-all">
                <span class="text-2xl mb-2 block">🖥️</span>
                <h3 class="text-base font-bold dark:text-white text-slate-900 mb-1">Leaderboard Banner</h3>
                <p class="text-xs text-amber-500 font-semibold mb-3">Ukuran: 728 x 90 px</p>
                <p class="text-xs dark:text-gray-400 text-slate-500 leading-relaxed">Tampil di posisi paling atas header halaman utama dan detail artikel, langsung terlihat oleh pengunjung.</p>
            </div>
            <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 p-6 rounded-2xl shadow-md hover:border-amber-500/50 transition-all">
                <span class="text-2xl mb-2 block">📱</span>
                <h3 class="text-base font-bold dark:text-white text-slate-900 mb-1">Sidebar Banner</h3>
                <p class="text-xs text-amber-500 font-semibold mb-3">Ukuran: 300 x 250 px</p>
                <p class="text-xs dark:text-gray-400 text-slate-500 leading-relaxed">Posisi strategis di kolom kanan artikel dan kategori berita, mengikat perhatian pembaca.</p>
            </div>
            <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 p-6 rounded-2xl shadow-md hover:border-amber-500/50 transition-all">
                <span class="text-2xl mb-2 block">📰</span>
                <h3 class="text-base font-bold dark:text-white text-slate-900 mb-1">In-Article Native Ad</h3>
                <p class="text-xs text-amber-500 font-semibold mb-3">Ukuran: Fluid Native</p>
                <p class="text-xs dark:text-gray-400 text-slate-500 leading-relaxed">Penyisipan iklan secara alami di tengah paragraf naskah berita dengan rasio konversi klik tinggi.</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 p-8 rounded-2xl shadow-lg">
            <h3 class="text-xl font-bold dark:text-white text-slate-900 mb-2">Formulir Kontak Pengiklan</h3>
            <p class="text-xs dark:text-gray-400 text-slate-500 mb-6">Hubungi tim periklanan kami untuk penawaran khusus dan statistik traffic terkini.</p>
            <form action="#" method="POST" onsubmit="alert('Terima kasih! Pesan pengiklan Anda telah terkirim.'); return false;" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" placeholder="Nama Perusahaan / Brand" required class="dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors">
                    <input type="email" placeholder="Email Kerja" required class="dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl px-4 py-3 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors">
                </div>
                <textarea rows="4" placeholder="Detail kebutuhan iklan / durasi kampanye..." required class="w-full dark:bg-gray-950 bg-slate-50 border dark:border-gray-800 border-gray-300 focus:border-amber-500 rounded-xl p-4 text-sm dark:text-gray-100 text-slate-900 outline-none transition-colors"></textarea>
                <button type="submit" class="py-3 px-8 bg-amber-500 hover:bg-amber-600 text-gray-950 font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg">
                    Kirim Permohonan Iklan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
