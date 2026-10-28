<x-admin-layout title="Antrean Kiriman Publik - B.Today CMS">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-white">Antrean Kiriman Tulisan Publik</h1>
        <p class="text-xs text-gray-400">Tinjau naskah artikel yang dikirim oleh publik tanpa akun. Setujui untuk penayangan otomatis dengan byline penulis.</p>
    </div>

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-stat-card title="Total Kiriman" :value="$stats['total']" icon="inbox-arrow-down" color="amber" />
        <x-stat-card title="Menunggu Review" :value="$stats['pending']" icon="clock" color="amber" />
        <x-stat-card title="Disetujui" :value="$stats['approved']" icon="check-circle" color="emerald" />
        <x-stat-card title="Ditolak" :value="$stats['rejected']" icon="x-circle" color="rose" />
    </div>

    <!-- Submissions Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="relative overflow-x-auto">
            <table class="w-full min-w-[48rem] text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-950 text-gray-400 border-b border-gray-800">
                        <th class="p-4 font-bold uppercase tracking-wider">Penulis &amp; Instansi</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Judul &amp; Naskah</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Kategori</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Status</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Dikirim</th>
                        <th class="p-4 font-bold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-gray-300">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-gray-800/40 transition-colors" x-data="{ showDetail: false, showReject: false }">
                            <td class="p-4">
                                <span class="font-bold text-gray-100 block text-xs">{{ $sub->name }}</span>
                                <span class="text-[10px] text-amber-400 block">{{ $sub->instansi ?: 'Independen' }}</span>
                                <span class="text-[10px] text-gray-500 block">{{ $sub->email }}</span>
                            </td>
                            <td class="p-4 max-w-xs">
                                <span class="font-bold text-gray-100 block text-xs line-clamp-1 mb-1">{{ $sub->title }}</span>
                                <button @click="showDetail = !showDetail" type="button" class="text-[10px] text-amber-400 hover:underline">
                                    [Pratinjau Naskah]
                                </button>
                                <div x-show="showDetail" class="mt-2 p-3 bg-gray-950 border border-gray-800 rounded-lg text-[11px] leading-relaxed max-h-48 overflow-y-auto">
                                    {!! nl2br(e($sub->content)) !!}
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-800 text-gray-300 px-2 py-1 rounded text-[10px] font-semibold">
                                    {{ $sub->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <x-status-badge :status="$sub->status" />
                                @if($sub->rejection_reason)
                                    <span class="block text-[10px] text-rose-400 mt-1">Alasan: {{ $sub->rejection_reason }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-[11px] text-gray-400">
                                {{ $sub->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4 text-right space-x-2 shrink-0">
                                @if($sub->status === 'pending')
                                    <form action="{{ route('admin.submissions.approve', $sub->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui kiriman ini? Artikel publik akan otomatis dibuat dan ditayangkan.')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-gray-950 font-bold rounded-lg text-[11px] shadow">
                                            Setujui &amp; Terbitkan
                                        </button>
                                    </form>

                                    <button @click="showReject = !showReject" type="button" class="px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 border border-rose-500/40 font-bold rounded-lg text-[11px]">
                                        Tolak
                                    </button>

                                    <!-- Reject Form Modal inline -->
                                    <div x-show="showReject" class="mt-2 text-left p-3 bg-gray-950 border border-rose-500/30 rounded-xl space-y-2">
                                        <form action="{{ route('admin.submissions.reject', $sub->id) }}" method="POST">
                                            @csrf
                                            <input type="text" name="rejection_reason" required placeholder="Tuliskan alasan penolakan..." class="w-full bg-gray-900 border border-gray-800 rounded px-2 py-1 text-xs text-gray-100 mb-2">
                                            <button type="submit" class="py-1 px-3 bg-rose-600 text-white font-bold rounded text-xs">Konfirmasi Penolakan</button>
                                        </form>
                                    </div>
                                @elseif($sub->status === 'approved' && $sub->article_id)
                                    <a href="{{ route('articles.show', $sub->article->slug ?? '') }}" target="_blank" class="text-[11px] text-amber-400 underline">
                                        Lihat Artikel Tayang $\rightarrow$
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 text-xs">
                                Belum ada kiriman tulisan publik dalam antrean.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($submissions->total() > 0)
            <div class="p-4 border-t border-gray-800">
                {{ $submissions->links('vendor.pagination.admin', ['noun' => 'kiriman']) }}
            </div>
        @endif
    </div>
</x-admin-layout>
