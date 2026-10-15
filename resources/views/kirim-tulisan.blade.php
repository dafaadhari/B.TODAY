@php
    $submitted = session()->has('success');
    $hasServerInput = filled(old('content')) || filled(old('title'));
    $fieldClass = 'block min-h-11 w-full rounded-lg border border-control bg-raised px-4 py-3 text-base text-fg-strong placeholder:text-fg-subtle focus:border-brand aria-invalid:border-danger';
@endphp

<x-app-layout :title="'Kirim Tulisan - '.config('portal.name')" description="Kirim opini, liputan, atau gagasan Anda ke redaksi B.Today tanpa membuat akun. Setiap tulisan ditinjau redaksi sebelum tayang.">
    <div class="mx-auto max-w-7xl px-4 py-10">
        <x-page-header eyebrow="Kontribusi Pembaca" lead="Tanpa akun. Setiap tulisan dibaca redaksi sebelum tayang, dan nama Anda dicantumkan sebagai kontributor.">
            Kirim Tulisan<span class="text-accent">.</span>
        </x-page-header>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($submitted)
                    {{-- Konfirmasi menggantikan form, bukan flash di atas form kosong. --}}
                    <section role="status" aria-live="polite" class="rounded-2xl border border-line bg-surface p-6 text-center sm:p-10"
                        x-data="guestDraft(false, true)">
                        <span class="mx-auto mb-5 flex size-14 items-center justify-center rounded-full bg-success-soft text-success" aria-hidden="true">
                            <svg class="size-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        </span>
                        <h2 class="mb-3 text-2xl font-extrabold text-fg-strong">Tulisan Anda sudah kami terima</h2>
                        <p class="mx-auto mb-2 max-w-md leading-relaxed text-fg-muted">
                            Status: <strong class="text-fg-strong">menunggu review redaksi</strong>. Peninjauan biasanya memakan waktu {{ config('portal.review_days') }}.
                        </p>
                        <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-fg-muted">
                            Bila disetujui, tulisan tayang dengan nama dan instansi Anda. Bila perlu perbaikan atau tidak dapat dimuat, redaksi akan menghubungi lewat email yang Anda cantumkan.
                        </p>
                        <div class="flex flex-col justify-center gap-3 sm:flex-row">
                            <x-button :href="route('home')">Kembali ke Beranda</x-button>
                            <x-button :href="route('kirim-tulisan.create')" variant="secondary">Kirim Tulisan Lain</x-button>
                        </div>
                    </section>
                @else
                    <form action="{{ route('kirim-tulisan.store') }}" method="POST" novalidate
                        x-data="guestDraft(@js($hasServerInput))" @input="save($event)" @submit="submitting = true"
                        class="space-y-8 rounded-2xl border border-line bg-surface p-5 sm:p-8">
                        @csrf

                        <div x-show="canRestore" x-cloak role="status" class="flex flex-col gap-3 rounded-xl border border-brand/40 bg-raised p-4 text-sm sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-fg">Ada draf tulisan yang belum terkirim di perangkat ini.</p>
                            <div class="flex shrink-0 gap-2">
                                <button type="button" @click="restore()" class="min-h-11 rounded-lg bg-brand px-4 text-xs font-bold tracking-widest text-on-brand uppercase hover:bg-brand-hover">Pulihkan draf</button>
                                <button type="button" @click="discard()" class="min-h-11 rounded-lg px-4 text-xs font-bold tracking-widest text-fg-muted uppercase hover:text-fg-strong">Abaikan</button>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div role="alert" class="rounded-xl border border-danger/30 bg-danger-soft p-4 text-sm text-danger">
                                <p class="mb-2 font-bold">Tulisan belum terkirim. Mohon periksa:</p>
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->keys() as $field)
                                        <li><a href="#{{ $field }}" class="underline underline-offset-2">{{ $errors->first($field) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <fieldset class="space-y-6">
                            <legend class="mb-6 border-l-4 border-brand pl-3 text-lg font-extrabold tracking-widest text-fg-strong uppercase">Data Penulis</legend>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <x-form.field name="name" label="Nama Lengkap" required>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                                        aria-describedby="name-error" @error('name') aria-invalid="true" @enderror class="{{ $fieldClass }}">
                                </x-form.field>
                                <x-form.field name="instansi" label="Instansi / Komunitas" hint="Tampil di byline, mis. Mahasiswa Untirta.">
                                    <input type="text" id="instansi" name="instansi" value="{{ old('instansi') }}" autocomplete="organization"
                                        aria-describedby="instansi-hint instansi-error" @error('instansi') aria-invalid="true" @enderror class="{{ $fieldClass }}">
                                </x-form.field>
                            </div>
                            <x-form.field name="email" label="Email" required hint="Tidak dipublikasikan. Dipakai redaksi untuk mengabari hasil review.">
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" inputmode="email"
                                    aria-describedby="email-hint email-error" @error('email') aria-invalid="true" @enderror class="{{ $fieldClass }}">
                            </x-form.field>
                        </fieldset>

                        <hr class="border-line">

                        <fieldset class="space-y-6">
                            <legend class="mb-6 border-l-4 border-brand pl-3 text-lg font-extrabold tracking-widest text-fg-strong uppercase">Naskah</legend>
                            <x-form.field name="category_id" label="Kategori" required>
                                <select id="category_id" name="category_id" required
                                    aria-describedby="category_id-error" @error('category_id') aria-invalid="true" @enderror class="{{ $fieldClass }}">
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        @if ($category->children->isNotEmpty())
                                            <optgroup label="{{ $category->name }}">
                                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }} (umum)</option>
                                                @foreach ($category->children as $child)
                                                    <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>{{ $child->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </x-form.field>
                            <x-form.field name="title" label="Judul Tulisan" required>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"
                                    aria-describedby="title-error" @error('title') aria-invalid="true" @enderror class="{{ $fieldClass }}">
                            </x-form.field>
                            <x-form.field name="content" label="Isi Tulisan" required hint="Boleh ditempel dari Word; pisahkan paragraf dengan baris kosong. Minimal 50 karakter.">
                                <textarea id="content" name="content" rows="14" required
                                    aria-describedby="content-hint content-error" @error('content') aria-invalid="true" @enderror
                                    class="{{ $fieldClass }} field-sizing-content min-h-72 leading-relaxed">{{ old('content') }}</textarea>
                                <p class="mt-1.5 text-right text-xs text-fg-subtle" aria-live="polite"><span x-text="wordCount">0</span> kata</p>
                            </x-form.field>
                        </fieldset>

                        <div class="border-t border-line pt-8">
                            <x-button type="submit" class="w-full" ::disabled="submitting">
                                <span x-show="! submitting">Kirim ke Redaksi</span>
                                <span x-show="submitting" x-cloak>Mengirim…</span>
                            </x-button>
                            <p class="mt-3 text-center text-sm text-fg-muted">Draf tersimpan otomatis di perangkat ini sampai tulisan terkirim.</p>
                        </div>
                    </form>
                @endif
            </div>

            <aside class="order-first space-y-8 lg:order-none lg:sticky lg:top-24 lg:self-start" aria-label="Panduan kirim tulisan">
                <section class="rounded-xl border border-line bg-surface p-6">
                    <h2 class="mb-5 border-l-4 border-brand pl-3 text-lg font-extrabold tracking-widest text-fg-strong uppercase">Alur Review</h2>
                    <ol class="space-y-5">
                        @foreach ([
                            ['Kirim', 'Isi data diri dan naskah, lalu kirim. Tidak perlu membuat akun.'],
                            ['Ditinjau redaksi', 'Redaksi membaca dan memverifikasi naskah, biasanya '.config('portal.review_days').'.'],
                            ['Disunting atau ditolak', 'Naskah dapat disunting seperlunya. Bila tidak dimuat, alasannya dikirim lewat email.'],
                            ['Tayang', 'Tulisan terbit dengan nama dan instansi Anda sebagai kontributor.'],
                        ] as [$step, $description])
                            <li class="flex gap-3">
                                <span class="shrink-0 font-extrabold text-accent" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}.</span>
                                <div>
                                    <p class="text-sm font-bold text-fg-strong">{{ $step }}</p>
                                    <p class="text-sm leading-relaxed text-fg-muted">{{ $description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="rounded-xl border border-line bg-surface p-6">
                    <h2 class="mb-4 border-l-4 border-brand pl-3 text-lg font-extrabold tracking-widest text-fg-strong uppercase">Ketentuan</h2>
                    <ul class="space-y-2.5 text-sm text-fg">
                        @foreach (['Tulisan asli karya sendiri, bukan salinan.', 'Belum pernah dimuat di media lain.', 'Tidak memuat fitnah, SARA, atau ujaran kebencian.', 'Redaksi berhak menyunting tanpa mengubah substansi.'] as $rule)
                            <li class="flex gap-2"><span class="text-accent" aria-hidden="true">&check;</span>{{ $rule }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-5 border-t border-line pt-4 text-sm leading-relaxed text-fg-muted">
                        <span class="font-bold text-fg-strong">Privasi:</span> email Anda tidak dipublikasikan dan hanya dipakai untuk komunikasi soal tulisan ini.
                        Lihat juga <a href="{{ route('redaksi') }}#pedoman-media-siber" class="font-semibold text-accent hover:underline">Pedoman Media Siber</a>.
                    </p>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
