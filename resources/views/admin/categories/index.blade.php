<x-admin-layout title="Kategori Berita - B.Today CMS">
    @php
        $pageCategories = $categories->getCollection()->flatMap(fn ($category) => collect([$category])->merge($category->children));

        $managerConfig = [
            'storeUrl' => route('admin.categories.store'),
            'updateUrl' => route('admin.categories.update', '__ID__'),
            'records' => $pageCategories->mapWithKeys(fn ($category) => [$category->id => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'original_slug' => $category->slug,
                'parent_id' => (string) $category->parent_id,
                'has_children' => $category->children_count > 0,
            ]]),
            // Validasi gagal: buka lagi jendela form beserta isian sebelumnya.
            'reopenForm' => $errors->category->any() ? [
                'id' => old('editing_id') ? (int) old('editing_id') : null,
                'name' => (string) old('name', ''),
                'slug' => (string) old('slug', ''),
                'parent_id' => (string) old('parent_id', ''),
            ] : null,
        ];

        $fieldError = 'mt-1.5 text-[11px] font-semibold text-rose-400';
        $fieldInput = 'w-full rounded-xl border border-gray-800 bg-gray-950 px-4 py-2.5 text-base text-gray-100 outline-none placeholder:text-gray-600 focus:border-amber-500 sm:text-sm';
        $secondaryButton = 'rounded-xl border border-gray-700 px-5 py-2.5 text-xs font-bold text-gray-300 transition-colors hover:bg-gray-800';
        $dialogBox = 'm-auto w-[calc(100%-2rem)] max-h-[calc(100dvh-2rem)] overflow-y-auto rounded-2xl border border-gray-800 bg-gray-900 p-0 text-gray-100 shadow-2xl backdrop:bg-black/70';
    @endphp

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('categoryManager', (config) => ({
                records: config.records,
                form: {},
                showErrors: false,
                pendingDelete: { name: '', url: '', blocker: null, hint: '' },

                init() {
                    this.resetForm();

                    if (config.reopenForm) {
                        this.$nextTick(() => this.openForm({ ...(this.records[config.reopenForm.id] ?? {}), ...config.reopenForm }, true));
                    }
                },
                resetForm(record = {}) {
                    this.form = { id: null, name: '', slug: '', original_slug: '', parent_id: '', has_children: false, ...record };
                },
                openForm(record = {}, showErrors = false) {
                    this.resetForm(record);
                    this.showErrors = showErrors;
                    this.$refs.formDialog.showModal();
                },
                formAction() {
                    return this.form.id ? config.updateUrl.replace('__ID__', this.form.id) : config.storeUrl;
                },
                // Mendekati Str::slug() agar pratinjau sama dengan slug yang disimpan.
                slugify(text) {
                    return String(text ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '').trim().replace(/[\s-]+/g, '-');
                },
                slugWillChange() {
                    return Boolean(this.form.id) && this.slugify(this.form.slug || this.form.name) !== this.form.original_slug;
                },
                confirmDelete(record) {
                    this.pendingDelete = record;
                    this.$refs.deleteDialog.showModal();
                },
            }));
        });
    </script>

    <div x-data="categoryManager(@js($managerConfig))">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-white">Kategori Berita</h1>
                <p class="text-xs text-gray-400">Atur kategori utama dan sub-kategori yang tampil di menu situs.</p>
            </div>
            <button type="button" @click="openForm()" class="inline-flex items-center justify-center gap-1.5 py-3 px-6 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shrink-0">
                <x-heroicon-o-plus class="w-4 h-4" aria-hidden="true" />
                Tambah Kategori
            </button>
        </div>

        <!-- Summary Stats Cards -->
        <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
            <x-stat-card title="Total Kategori" :value="$stats['total']" icon="folder" color="amber" />
            <x-stat-card title="Kategori Utama" :value="$stats['main']" icon="rectangle-stack" color="blue" />
            <x-stat-card title="Sub-kategori" :value="$stats['sub']" icon="arrow-turn-down-right" color="emerald" />
            <x-stat-card title="Kategori Kosong" :value="$stats['empty']" icon="archive-box" color="rose" />
        </div>

        <!-- Categories Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="relative overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-950 text-gray-400 border-b border-gray-800">
                            <th class="py-3 px-3 sm:p-4 font-bold uppercase tracking-wider">Kategori</th>
                            <th class="hidden p-4 font-bold uppercase tracking-wider sm:table-cell">Alamat</th>
                            <th class="hidden p-4 font-bold uppercase tracking-wider text-center sm:table-cell">Artikel</th>
                            <th class="py-3 px-3 sm:p-4 font-bold uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    @forelse ($categories as $category)
                        <tbody class="border-t border-gray-800 divide-y divide-gray-800/50 text-gray-300">
                            @include('admin.categories.partials.row', ['category' => $category, 'isChild' => false])
                            @foreach ($category->children as $child)
                                @include('admin.categories.partials.row', ['category' => $child, 'isChild' => true])
                            @endforeach
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500 text-xs">
                                    Belum ada kategori.
                                    <button type="button" @click="openForm()" class="font-bold text-amber-400 hover:underline">Tambahkan kategori pertama</button>
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
                </table>
            </div>
            @if ($categories->total() > 0)
                <div class="p-4 border-t border-gray-800">
                    {{ $categories->links('vendor.pagination.admin', ['noun' => 'kategori utama']) }}
                </div>
            @endif
        </div>

        <!-- Form Tambah / Edit Kategori -->
        <dialog x-ref="formDialog" aria-labelledby="category-form-title" @click.self="$refs.formDialog.close()" class="{{ $dialogBox }} max-w-lg">
            <form method="POST" :action="formAction()">
                @csrf
                <input type="hidden" name="_method" value="PUT" :disabled="! form.id">
                <input type="hidden" name="editing_id" :value="form.id ?? ''">

                <div class="flex items-start justify-between gap-4 border-b border-gray-800 p-5">
                    <div>
                        <h2 id="category-form-title" class="text-base font-bold text-white" x-text="form.id ? 'Edit Kategori' : 'Tambah Kategori'">Tambah Kategori</h2>
                        <p class="mt-0.5 text-xs text-gray-400" x-text="form.id ? 'Perubahan langsung berlaku di situs.' : 'Isi nama, lalu pilih induk bila ini sub-kategori.'"></p>
                    </div>
                    <button type="button" @click="$refs.formDialog.close()" class="relative -m-1 inline-flex w-9 h-9 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-800 hover:text-white">
                        <x-heroicon-o-x-mark class="w-5 h-5" aria-hidden="true" />
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>

                <div class="space-y-5 p-5">
                    <div>
                        <label for="category-name" class="mb-1.5 block text-xs font-bold text-gray-300">Nama kategori <span class="text-rose-400" aria-hidden="true">*</span></label>
                        <input id="category-name" type="text" name="name" x-model="form.name" required maxlength="255" autocomplete="off" autofocus
                            placeholder="Contoh: Otomotif atau Serang Today" class="{{ $fieldInput }}">
                        @error('name', 'category')
                            <p x-show="showErrors" class="{{ $fieldError }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category-slug" class="mb-1.5 block text-xs font-bold text-gray-300">Slug (alamat halaman)</label>
                        <div class="flex rounded-xl border border-gray-800 bg-gray-950 transition-colors focus-within:border-amber-500">
                            <span class="flex items-center pl-4 text-base text-gray-500 select-none sm:text-sm" aria-hidden="true">/kategori/</span>
                            <input id="category-slug" type="text" name="slug" x-model="form.slug" maxlength="255" autocomplete="off"
                                :placeholder="slugify(form.name) || 'dibuat-otomatis'"
                                class="min-w-0 flex-1 bg-transparent py-2.5 pl-0.5 pr-4 text-base text-gray-100 outline-none placeholder:text-gray-600 sm:text-sm">
                        </div>
                        <p x-show="! slugWillChange()" class="mt-1.5 text-[11px] text-gray-500">Kosongkan agar dibuat otomatis dari nama.</p>
                        <p x-show="slugWillChange()" class="mt-1.5 flex gap-1.5 text-[11px] text-amber-400">
                            <span class="shrink-0" aria-hidden="true"><x-heroicon-o-exclamation-triangle class="w-4 h-4" /></span>
                            <span>Alamat lama <span class="font-mono" x-text="'/kategori/' + form.original_slug"></span> akan berhenti berfungsi (404). Tautan yang sudah dibagikan ikut rusak.</span>
                        </p>
                        {{-- Slug otomatis ikut bentrok saat nama bentrok; cukup tampilkan pesan nama. --}}
                        @if ($errors->category->has('slug') && ! $errors->category->has('name'))
                            <p x-show="showErrors" class="{{ $fieldError }}">{{ $errors->category->first('slug') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="category-parent" class="mb-1.5 block text-xs font-bold text-gray-300">Induk kategori</label>
                        <select id="category-parent" name="parent_id" x-model="form.parent_id" :disabled="form.has_children" class="{{ $fieldInput }} disabled:opacity-60">
                            <option value="">Tidak ada (kategori utama)</option>
                            @foreach ($mainCategories as $mainCategory)
                                <option value="{{ $mainCategory->id }}" :disabled="form.id === {{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-[11px] text-gray-500"
                            x-text="form.has_children ? 'Kategori ini punya sub-kategori, jadi tetap menjadi kategori utama.' : 'Pilih induk bila ini sub-kategori, misalnya wilayah di bawah Banten.'"></p>
                        @error('parent_id', 'category')
                            <p x-show="showErrors" class="{{ $fieldError }}">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-gray-800 p-5 sm:flex-row sm:justify-end">
                    <button type="button" @click="$refs.formDialog.close()" class="{{ $secondaryButton }}">Batal</button>
                    <button type="submit" class="rounded-xl bg-amber-500 px-6 py-2.5 text-xs font-black uppercase tracking-wider text-gray-950 transition-colors hover:bg-amber-600"
                        x-text="form.id ? 'Simpan Perubahan' : 'Simpan Kategori'">Simpan Kategori</button>
                </div>
            </form>
        </dialog>

        <!-- Konfirmasi Hapus -->
        <dialog x-ref="deleteDialog" aria-labelledby="category-delete-title" @click.self="$refs.deleteDialog.close()" class="{{ $dialogBox }} max-w-md">
            <div class="flex items-start gap-4 p-5 sm:p-6">
                <span class="flex w-10 h-10 shrink-0 items-center justify-center rounded-full border"
                    :class="pendingDelete.blocker ? 'border-amber-500/30 bg-amber-500/10 text-amber-400' : 'border-rose-500/30 bg-rose-500/10 text-rose-400'" aria-hidden="true">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                </span>
                <div class="min-w-0">
                    <h2 id="category-delete-title" class="text-base font-bold text-white" x-text="pendingDelete.blocker ? 'Kategori belum bisa dihapus' : 'Hapus kategori ini?'"></h2>
                    <p class="mt-1 text-sm font-semibold text-amber-400" x-text="pendingDelete.name"></p>
                    <p x-show="pendingDelete.blocker" class="mt-3 text-sm leading-relaxed text-gray-300">
                        <span x-text="pendingDelete.blocker"></span>
                        <span x-text="pendingDelete.hint"></span>
                    </p>
                    <p x-show="! pendingDelete.blocker" class="mt-3 text-sm leading-relaxed text-gray-300">
                        Kategori akan dihapus permanen dan hilang dari situs. Tindakan ini tidak bisa dibatalkan.
                    </p>
                </div>
            </div>
            <div class="flex flex-col-reverse gap-2 border-t border-gray-800 p-5 sm:flex-row sm:justify-end">
                <button type="button" @click="$refs.deleteDialog.close()" class="{{ $secondaryButton }}" x-text="pendingDelete.blocker ? 'Mengerti' : 'Batal'">Batal</button>
                <form method="POST" :action="pendingDelete.url" x-show="! pendingDelete.blocker">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl bg-rose-600 px-6 py-2.5 text-xs font-black uppercase tracking-wider text-white transition-colors hover:bg-rose-500">
                        Hapus Kategori
                    </button>
                </form>
            </div>
        </dialog>
    </div>
</x-admin-layout>
