<x-admin-layout title="Manajemen Slot Iklan - B.Today CMS">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Manajemen Slot Iklan Dinamis</h1>
            <p class="text-xs text-gray-400">Atur penayangan banner iklan Leaderboard, Sidebar, dan In-Article Native Ads.</p>
        </div>
    </div>

    <!-- Add Ad Form Card -->
    <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl mb-8 shadow-xl">
        <h3 class="text-sm font-bold text-amber-400 mb-4">+ Tambah Banner Iklan Baru</h3>
        <form action="{{ route('admin.ads.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-300 mb-1">Judul / Pengiklan</label>
                <input type="text" name="title" required placeholder="Contoh: Sponsor Promo A" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-3 py-2 text-xs text-gray-100 outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-300 mb-1">URL Banner Gambar</label>
                <input type="url" name="image_url" required placeholder="https://..." class="w-full bg-gray-950 border border-gray-800 rounded-xl px-3 py-2 text-xs text-gray-100 outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-300 mb-1">URL Tujuan Redirect</label>
                <input type="url" name="redirect_url" required placeholder="https://..." class="w-full bg-gray-950 border border-gray-800 rounded-xl px-3 py-2 text-xs text-gray-100 outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-gray-300 mb-1">Posisi Placement</label>
                <select name="placement" required class="w-full bg-gray-950 border border-gray-800 rounded-xl px-3 py-2 text-xs text-gray-100 outline-none">
                    <option value="leaderboard">Leaderboard (728x90 Top)</option>
                    <option value="sidebar">Sidebar (300x250 Right)</option>
                    <option value="in_article">In-Article Native Ad</option>
                </select>
            </div>
            <div class="md:col-span-2 lg:col-span-4 flex items-center justify-between pt-2">
                <label class="flex items-center space-x-2 text-xs text-gray-300 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded bg-gray-950 border-gray-800 text-amber-500">
                    <span>Langsung Aktifkan Penayangan</span>
                </label>
                <button type="submit" class="py-2.5 px-6 bg-amber-500 hover:bg-amber-600 text-gray-950 font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg">
                    Simpan Slot Iklan
                </button>
            </div>
        </form>
    </div>

    <!-- Ads Inventory Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-white">Daftar Slot Iklan Terpasang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-950 text-gray-400 border-b border-gray-800">
                        <th class="p-4 font-bold uppercase">Banner</th>
                        <th class="p-4 font-bold uppercase">Judul &amp; Placement</th>
                        <th class="p-4 font-bold uppercase">URL Tujuan</th>
                        <th class="p-4 font-bold uppercase">Status</th>
                        <th class="p-4 font-bold uppercase">Impressions / Clicks</th>
                        <th class="p-4 font-bold uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse($ads as $ad)
                        <tr class="hover:bg-gray-800/40">
                            <td class="p-4">
                                <img src="{{ $ad->image_url }}" class="h-10 max-w-[120px] object-cover rounded border border-gray-800">
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-100 block text-xs">{{ $ad->title }}</span>
                                <span class="text-[10px] text-amber-400 uppercase font-mono">{{ $ad->placement }}</span>
                            </td>
                            <td class="p-4">
                                <a href="{{ $ad->redirect_url }}" target="_blank" class="text-xs text-amber-400 hover:underline truncate max-w-xs block">
                                    {{ $ad->redirect_url }}
                                </a>
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.ads.toggle', $ad->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-bold border {{ $ad->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30' }}">
                                        {{ $ad->is_active ? '● AKTIF' : '○ NONAKTIF' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-xs font-mono">
                                👁️ {{ number_format($ad->impressions_count) }} | 🖱️ {{ number_format($ad->clicks_count) }}
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Hapus slot iklan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-500/20 text-rose-400 border border-rose-500/40 font-bold rounded text-[11px]">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 text-xs">
                                Belum ada slot iklan terpasang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
