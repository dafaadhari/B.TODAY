<x-admin-layout title="Manajemen Artikel - B.Today CMS">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Manajemen Artikel Redaksi</h1>
            <p class="text-xs text-gray-400">Kelola, sunting, dan setujui penayangan artikel berita B.Today.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="py-3 px-6 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg text-center shrink-0">
            + Tambah Berita Baru
        </a>
    </div>

    <!-- Summary Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Berita" :value="$stats['total']" icon="📰" color="amber" />
        <x-stat-card title="Terbit" :value="$stats['published']" icon="✅" color="emerald" />
        <x-stat-card title="Menunggu Review" :value="$stats['pending']" icon="⏳" color="amber" />
        <x-stat-card title="Draf" :value="$stats['draft']" icon="📝" color="blue" />
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-gray-900 border border-gray-800 p-4 rounded-2xl mb-6">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul artikel..." class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-xs text-gray-100 outline-none">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" onchange="this.form.submit()" class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-xs text-gray-100 outline-none">
                    <option value="">-- Semua Status --</option>
                    <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Terbit</option>
                    <option value="pending_review" {{ $status == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draf</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            @if($search || $status)
                <a href="{{ route('admin.articles.index') }}" class="py-2.5 px-4 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded-xl text-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Articles Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-950 text-gray-400 border-b border-gray-800">
                        <th class="p-4 font-bold uppercase tracking-wider">Sampul</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Judul &amp; Author</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Kategori</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Status</th>
                        <th class="p-4 font-bold uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 font-bold uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60 text-gray-300">
                    @forelse($articles as $article)
                        <tr class="hover:bg-gray-800/40 transition-colors">
                            <td class="p-4">
                                <img src="{{ $article->thumbnail_path ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=100&h=100&fit=crop' }}" class="w-14 h-10 object-cover rounded-lg border border-gray-800">
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-gray-100 block text-xs line-clamp-1 mb-0.5">{{ $article->title }}</span>
                                <span class="text-[10px] text-gray-500">
                                    Penulis: {{ $article->guest_author_name ? $article->guest_author_name . ' (' . ($article->guest_author_instansi ?: 'Publik') . ')' : ($article->author->name ?? 'Redaksi') }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-800 text-gray-300 px-2 py-1 rounded text-[10px] font-semibold">
                                    {{ $article->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <x-status-badge :status="$article->status" />
                            </td>
                            <td class="p-4 text-[11px] text-gray-400">
                                {{ $article->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                @if($article->status !== 'published')
                                    <form action="{{ route('admin.articles.approve', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui dan terbitkan artikel ini?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/40 rounded-lg text-[11px] font-bold">
                                            ✔ Approve
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="px-2.5 py-1 bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 border border-amber-500/40 rounded-lg text-[11px] font-bold">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 border border-rose-500/40 rounded-lg text-[11px] font-bold">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 text-xs">
                                Tidak ada data artikel yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-800">
            {{ $articles->links() }}
        </div>
    </div>
</x-admin-layout>
