@php
    $office = config('portal.office');
    $slots = config('portal.ad_slots');
    $labelClass = 'mb-2 block text-xs font-bold tracking-widest text-fg-muted uppercase';
    $fieldClass = 'block min-h-11 w-full rounded-lg border border-control bg-raised px-4 py-3 text-base text-fg-strong placeholder:text-fg-subtle focus:border-brand sm:text-sm';
@endphp

<x-app-layout :title="'Pasang Iklan & Media Kit - '.config('portal.name')" :description="'Pasang iklan di '.config('portal.name').': posisi slot, ukuran banner, dan kontak tim iklan untuk menjangkau pembaca Banten dan nasional.'">
    <div class="mx-auto max-w-7xl px-4 py-10" x-data="adInquiry(@js($office['whatsapp']), @js($office['email_iklan']))">
        <x-page-header eyebrow="Media Kit & Advertising" align="center"
            lead="Jangkau pembaca Banten dan nasional lewat slot iklan yang jelas berlabel, tidak mengganggu bacaan, dan bisa dipantau tayangannya.">
            Pasang Iklan di<br><span class="text-accent">{{ config('portal.name') }}</span>
        </x-page-header>

        <div class="-mt-6 mb-16 flex flex-col items-stretch justify-center gap-3 sm:flex-row sm:items-center">
            @if ($office['whatsapp'])
                <x-button href="https://wa.me/{{ $office['whatsapp'] }}?text={{ urlencode('Halo tim iklan '.config('portal.name').', saya ingin informasi pemasangan iklan.') }}" target="_blank" rel="noopener">
                    Hubungi via WhatsApp
                </x-button>
            @else
                <x-button href="mailto:{{ $office['email_iklan'] }}">Email Tim Iklan</x-button>
            @endif
            <x-button href="#form-iklan" variant="secondary">Isi Formulir</x-button>
        </div>

        <section aria-label="Jangkauan" class="mb-16 grid grid-cols-3 gap-2 sm:gap-4">
            @foreach ([
                [number_format($stats['articles'], 0, ',', '.'), 'Artikel terbit'],
                [$stats['channels'], 'Kanal berita'],
                [$stats['regions'], 'Wilayah Banten'],
            ] as [$value, $label])
                <div class="rounded-xl border border-line bg-surface px-2 py-4 text-center sm:p-6">
                    <p class="mb-1 text-2xl font-extrabold text-accent sm:text-3xl md:text-4xl">{{ $value }}</p>
                    <p class="text-xs font-bold tracking-wide text-fg-muted uppercase sm:tracking-widest">{{ $label }}</p>
                </div>
            @endforeach
        </section>

        <section class="mb-16" aria-labelledby="judul-slot">
            <x-section-heading title="Posisi & Spesifikasi Iklan" size="lg" id="judul-slot" />
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($slots as $placement => $slot)
                    <article class="group flex flex-col rounded-xl border border-line bg-surface p-6 transition-colors hover:border-brand/40">
                        <div class="mb-5 flex h-28 items-center justify-center">
                            <div @class([
                                'flex items-center justify-center rounded-lg border border-dashed border-line-strong bg-raised',
                                'h-12 w-full' => $placement === 'leaderboard',
                                'h-28 w-32' => $placement === 'sidebar',
                                'h-16 w-full' => $placement === 'in_article',
                            ])>
                                <span class="text-xs font-bold text-fg-muted transition-colors group-hover:text-accent">{{ $slot['size'] }}</span>
                            </div>
                        </div>
                        <h3 class="mb-2 text-lg font-extrabold text-fg-strong">{{ $slot['label'] }}</h3>
                        <p class="mb-4 text-sm leading-relaxed text-fg-muted">{{ $slot['description'] }}</p>
                        <dl class="mb-6 divide-y divide-line border-y border-line text-sm">
                            @foreach (['Posisi' => $slot['position'], 'Ukuran' => $slot['size'], 'Format' => 'JPG, PNG, atau WebP', 'Maks. file' => $slot['max_file']] as $label => $value)
                                <div class="flex gap-3 py-2">
                                    <dt class="w-20 shrink-0 text-xs font-bold tracking-wider text-fg-muted uppercase">{{ $label }}</dt>
                                    <dd class="text-fg">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <button type="button" @click="chooseSlot(@js($slot['label']))"
                            class="mt-auto inline-flex min-h-11 items-center justify-center rounded-lg border border-line-strong text-xs font-bold tracking-widest text-fg-strong uppercase transition-colors hover:border-brand hover:text-accent">
                            Pilih slot ini
                        </button>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mb-16" aria-labelledby="judul-alasan">
            <x-section-heading title="Mengapa Beriklan di {{ config('portal.name') }}?" size="lg" id="judul-alasan" />
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['Jangkauan lokal Banten', 'Pembaca dari Serang, Cilegon, Tangerang, Lebak, dan Pandeglang, ditambah kanal nasional.'],
                    ['Tetap nyaman dibaca', 'Iklan selalu berlabel "Iklan", tidak menutupi konten, dan tidak menggeser halaman saat dimuat.'],
                    ['Laporan tayangan', 'Jumlah tayangan iklan Anda dicatat dan dapat kami laporkan secara berkala.'],
                ] as [$heading, $body])
                    <div class="rounded-xl border border-line bg-surface p-6">
                        <h3 class="mb-2 text-base font-bold text-fg-strong">{{ $heading }}</h3>
                        <p class="text-sm leading-relaxed text-fg-muted">{{ $body }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="form-iklan" class="mx-auto max-w-2xl scroll-mt-24" aria-labelledby="judul-form-iklan">
            <x-section-heading title="Hubungi Tim Iklan" size="lg" id="judul-form-iklan" />
            <form @submit.prevent="send()" class="space-y-6 rounded-2xl border border-line bg-surface p-5 sm:p-8">
                <p class="text-sm leading-relaxed text-fg-muted">
                    Isi kebutuhan Anda, lalu pesan akan terbuka di {{ $office['whatsapp'] ? 'WhatsApp' : 'aplikasi email' }} untuk dikirim.
                    Kami membalas maksimal 1×24 jam kerja beserta rate card terbaru.
                </p>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="iklan-nama" class="{{ $labelClass }}">Nama Lengkap <span class="text-accent">*</span></label>
                        <input type="text" id="iklan-nama" x-model="name" required autocomplete="name" class="{{ $fieldClass }}">
                    </div>
                    <div>
                        <label for="iklan-perusahaan" class="{{ $labelClass }}">Perusahaan / Brand <span class="font-medium normal-case tracking-normal">(opsional)</span></label>
                        <input type="text" id="iklan-perusahaan" x-model="company" autocomplete="organization" class="{{ $fieldClass }}">
                    </div>
                </div>
                <div>
                    <label for="iklan-kontak" class="{{ $labelClass }}">Email atau No. WhatsApp <span class="text-accent">*</span></label>
                    <input type="text" id="iklan-kontak" x-model="contact" required class="{{ $fieldClass }}">
                </div>
                <div>
                    <label for="iklan-slot" class="{{ $labelClass }}">Slot Iklan yang Diminati</label>
                    <select id="iklan-slot" x-model="slot" class="{{ $fieldClass }}">
                        <option value="">Belum tahu / minta saran</option>
                        @foreach ($slots as $slot)
                            <option value="{{ $slot['label'] }}">{{ $slot['label'] }} ({{ $slot['size'] }})</option>
                        @endforeach
                        <option value="Paket kombinasi">Paket kombinasi</option>
                    </select>
                </div>
                <div>
                    <label for="iklan-pesan" class="{{ $labelClass }}">Pesan / Kebutuhan</label>
                    <textarea id="iklan-pesan" x-model="message" rows="4" placeholder="Mis. durasi kampanye, target wilayah, anggaran" class="{{ $fieldClass }}"></textarea>
                </div>
                <x-button type="submit" class="w-full">
                    {{ $office['whatsapp'] ? 'Kirim via WhatsApp' : 'Kirim via Email' }} <span aria-hidden="true">&rarr;</span>
                </x-button>
                <p class="text-center text-sm text-fg-muted">
                    Atau email langsung ke <a href="mailto:{{ $office['email_iklan'] }}" class="font-semibold text-accent hover:underline">{{ $office['email_iklan'] }}</a>
                </p>
            </form>
        </section>
    </div>
</x-app-layout>
