@php
    // Pengunjung dari tautan artikel lama: isi kotak cari dari slug URL.
    $suggestedQuery = request()->is('berita/*') ? str_replace('-', ' ', (string) request()->segment(2)) : '';
@endphp

<x-app-layout :title="'Halaman Tidak Ditemukan - '.config('portal.name')" robots="noindex, follow">
    <div class="mx-auto max-w-4xl px-4 py-10 md:py-20">
        <div class="mb-16 text-center">
            <p class="mb-6 text-[96px] leading-none font-extrabold text-brand select-none motion-safe:animate-float md:text-[160px]" aria-hidden="true">404</p>
            <h1 class="mb-4 text-2xl font-extrabold text-fg-strong md:text-4xl">Halaman Tidak Ditemukan</h1>
            <p class="mx-auto mb-8 max-w-md leading-relaxed text-fg-muted">
                Maaf, halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamatnya salah ketik.
            </p>

            <form action="{{ route('search') }}" method="GET" class="relative mx-auto mb-6 max-w-lg">
                <label for="cari-404" class="sr-only">Cari berita</label>
                <input type="search" id="cari-404" name="q" value="{{ $suggestedQuery }}" placeholder="Cari berita..."
                    class="w-full rounded-xl border border-control bg-surface py-3.5 pr-16 pl-5 text-base text-fg-strong placeholder:text-fg-subtle focus:border-brand">
                <button type="submit" class="absolute top-1/2 right-2 inline-flex size-11 -translate-y-1/2 items-center justify-center rounded-lg bg-brand text-on-brand hover:bg-brand-hover">
                    <span class="sr-only">Cari</span>
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" /></svg>
                </button>
            </form>

            <x-button :href="route('home')"><span aria-hidden="true">&larr;</span> Kembali ke Beranda</x-button>
        </div>

        @if (($popularArticles ?? collect())->isNotEmpty())
            <section aria-label="Mungkin Anda tertarik">
                <x-section-heading title="Mungkin Anda Tertarik" />
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($popularArticles as $article)
                        <x-article-card :article="$article" variant="compact" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
