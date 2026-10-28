{{-- Satu baris tabel kategori; $isChild = true untuk sub-kategori (menjorok di bawah induknya). --}}
@php
    $blocker = $category->deletionBlocker();
    $deleteHint = match (true) {
        $category->articles_count > 0 && $category->children_count > 0 => 'Pindahkan artikelnya ke kategori lain lewat Manajemen Artikel dan ubah induk sub-kategorinya, lalu coba lagi.',
        $category->articles_count > 0 => 'Pindahkan artikelnya ke kategori lain lewat Manajemen Artikel, lalu coba lagi.',
        default => 'Ubah induk sub-kategorinya atau hapus sub-kategorinya dulu, lalu coba lagi.',
    };
    $hasChildren = ! $isChild && $category->children_count > 0;
    $totalArticles = $category->articles_count + ($hasChildren ? $category->children->sum('articles_count') : 0);
    $publicUrl = route('categories.show', $category->slug);
    $iconButton = 'relative inline-flex w-9 h-9 sm:w-8 sm:h-8 items-center justify-center rounded-lg border transition-colors';
@endphp

<tr class="{{ $isChild ? 'bg-gray-950/30' : '' }} hover:bg-gray-800/40 transition-colors">
    <td class="{{ $isChild ? 'py-3 pl-4 pr-3 sm:pl-10 sm:pr-4' : 'py-3.5 px-3 sm:p-4' }}">
        <div class="flex {{ $isChild ? 'items-start' : 'items-center' }} gap-2.5 sm:gap-3">
            @if ($isChild)
                <span class="mt-0.5 shrink-0 text-gray-600" aria-hidden="true"><x-heroicon-o-arrow-turn-down-right class="w-4 h-4" /></span>
            @else
                <span class="hidden sm:flex w-9 h-9 shrink-0 items-center justify-center rounded-lg border border-amber-500/20 bg-amber-500/10 text-amber-400" aria-hidden="true">
                    <x-heroicon-o-folder class="w-4 h-4" />
                </span>
            @endif

            <div class="min-w-0">
                <p class="{{ $isChild ? 'text-xs font-semibold text-gray-200' : 'text-sm font-bold text-gray-100' }}">{{ $category->name }}</p>
                <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="mt-0.5 block text-[11px] text-gray-500 hover:text-amber-400 [overflow-wrap:anywhere] sm:hidden">/kategori/{{ $category->slug }}</a>
                @if ($hasChildren)
                    <p class="mt-0.5 text-[11px] text-gray-400">{{ $category->children_count }} sub-kategori &middot; total {{ $totalArticles }} artikel</p>
                @else
                    <p class="mt-0.5 text-[11px] text-gray-400 sm:hidden">{{ $category->articles_count }} artikel</p>
                @endif
            </div>
        </div>
    </td>
    <td class="hidden p-4 sm:table-cell">
        <span class="font-mono text-[11px] text-gray-400">/kategori/{{ $category->slug }}</span>
    </td>
    <td class="hidden p-4 text-center text-sm font-bold text-gray-200 sm:table-cell">{{ $category->articles_count }}</td>
    <td class="py-3 px-3 sm:p-4">
        <div class="flex items-center justify-end gap-1.5 sm:gap-2">
            <a href="{{ $publicUrl }}" target="_blank" rel="noopener" title="Lihat di situs"
                class="{{ $iconButton }} !hidden sm:!inline-flex border-gray-700 text-gray-300 hover:border-amber-500 hover:text-amber-400">
                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" aria-hidden="true" />
                <span class="sr-only">Lihat kategori {{ $category->name }} di situs</span>
            </a>
            <button type="button" @click="openForm(records[{{ $category->id }}])" title="Edit kategori"
                class="{{ $iconButton }} bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 border-amber-500/40">
                <x-heroicon-o-pencil-square class="w-4 h-4" aria-hidden="true" />
                <span class="sr-only">Edit kategori {{ $category->name }}</span>
            </button>
            <button type="button" @click="confirmDelete(@js(['name' => $category->name, 'url' => route('admin.categories.destroy', $category), 'blocker' => $blocker, 'hint' => $deleteHint]))"
                title="{{ $blocker ? 'Belum bisa dihapus. '.$blocker : 'Hapus kategori' }}"
                class="{{ $iconButton }} {{ $blocker ? 'border-gray-700 bg-gray-800/40 text-gray-500 hover:text-gray-300' : 'bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 border-rose-500/40' }}">
                <x-heroicon-o-trash class="w-4 h-4" aria-hidden="true" />
                <span class="sr-only">Hapus kategori {{ $category->name }}{{ $blocker ? ' (belum bisa dihapus)' : '' }}</span>
            </button>
        </div>
    </td>
</tr>
