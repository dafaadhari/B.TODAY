@php
    $office = config('portal.office');
    $guidelines = [
        ['Ruang Lingkup', 'Media siber adalah segala bentuk media yang menggunakan wahana internet dan melaksanakan kegiatan jurnalistik, serta memenuhi persyaratan Undang-Undang Pers dan Standar Perusahaan Pers yang ditetapkan Dewan Pers. Isi Buatan Pengguna (User Generated Content) adalah segala isi yang dibuat dan/atau dipublikasikan oleh pengguna media siber, antara lain artikel, gambar, komentar, suara, video, dan berbagai bentuk unggahan yang melekat pada media siber.'],
        ['Verifikasi dan Keberimbangan Berita', 'Pada prinsipnya setiap berita harus melalui verifikasi. Berita yang dapat merugikan pihak lain memerlukan verifikasi pada berita yang sama untuk memenuhi prinsip akurasi dan keberimbangan. Ketentuan ini dikecualikan dengan syarat tertentu: berita mengandung kepentingan publik yang bersifat mendesak, sumber pertama jelas dan kredibel, subjek berita tidak diketahui keberadaannya atau tidak dapat diwawancarai, serta media memberikan penjelasan bahwa berita masih memerlukan verifikasi lebih lanjut yang diupayakan dalam waktu secepatnya.'],
        ['Isi Buatan Pengguna (User Generated Content)', 'Media siber wajib mencantumkan syarat dan ketentuan mengenai isi buatan pengguna yang tidak bertentangan dengan Undang-Undang Pers dan Kode Etik Jurnalistik. Isi buatan pengguna tidak boleh memuat kebohongan, fitnah, sadis, cabul, prasangka dan kebencian terkait SARA, serta anjuran kekerasan. Kiriman tulisan publik di '.config('portal.name').' selalu melalui peninjauan redaksi sebelum tayang.'],
        ['Ralat, Koreksi, dan Hak Jawab', 'Ralat, koreksi, dan hak jawab mengacu pada Undang-Undang Pers, Kode Etik Jurnalistik, dan Pedoman Hak Jawab yang ditetapkan Dewan Pers. Ralat, koreksi, dan/atau hak jawab wajib ditautkan pada berita yang diralat, dikoreksi, atau yang diberi hak jawab, serta dicantumkan waktu pemuatannya.'],
        ['Pencabutan Berita', 'Berita yang sudah dipublikasikan tidak dapat dicabut karena alasan penyensoran dari pihak luar redaksi, kecuali terkait masalah SARA, kesusilaan, masa depan anak, pengalaman traumatik korban, atau pertimbangan khusus lain yang ditetapkan Dewan Pers. Pencabutan berita wajib disertai alasan pencabutan dan diumumkan kepada publik.'],
        ['Iklan', 'Media siber wajib membedakan dengan tegas antara produk berita dan iklan. Setiap berita, artikel, atau isi yang merupakan iklan dan/atau isi berbayar wajib mencantumkan keterangan "Iklan", "Advertorial", atau keterangan lain yang menjelaskan bahwa isi tersebut adalah iklan.'],
        ['Hak Cipta', 'Media siber wajib menghormati hak cipta sebagaimana diatur dalam peraturan perundang-undangan yang berlaku.'],
        ['Pencantuman Pedoman', 'Media siber wajib mencantumkan Pedoman Pemberitaan Media Siber ini di medianya secara terang dan jelas.'],
        ['Sengketa', 'Penilaian akhir atas sengketa mengenai pelaksanaan Pedoman Pemberitaan Media Siber ini diselesaikan oleh Dewan Pers.'],
    ];
@endphp

<x-app-layout :title="'Redaksi & Pedoman Media Siber - '.config('portal.name')" :description="'Susunan dewan redaksi, alamat kantor, dan Pedoman Pemberitaan Media Siber '.config('portal.name').'.'">
    <div class="mx-auto max-w-4xl px-4 py-10">
        <x-page-header eyebrow="Tentang Kami" :lead="config('portal.name').' berkomitmen pada jurnalisme independen, akurat, dan bertanggung jawab. Berikut susunan dewan redaksi dan pedoman pemberitaan kami.'">
            Redaksi<span class="text-accent">.</span>
        </x-page-header>

        <section id="dewan-redaksi" class="mb-16 scroll-mt-24" aria-labelledby="judul-dewan-redaksi">
            <x-section-heading title="Susunan Dewan Redaksi" size="lg" id="judul-dewan-redaksi" />
            <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                @foreach (config('portal.redaksi') as $member)
                    <li class="flex items-center gap-4 rounded-xl border border-line bg-surface p-6">
                        <span @class([
                            'flex size-14 shrink-0 items-center justify-center rounded-full text-xl font-extrabold',
                            'bg-linear-to-br from-amber-400 to-amber-600 text-black' => $member['is_lead'],
                            'bg-linear-to-br from-gray-600 to-gray-800 text-white' => ! $member['is_lead'],
                        ]) aria-hidden="true">{{ Str::upper(Str::substr($member['name'], 0, 1)) }}</span>
                        <div>
                            <p class="text-base font-bold text-fg-strong">{{ $member['name'] }}</p>
                            <p @class(['text-xs font-bold tracking-widest uppercase', 'text-accent' => $member['is_lead'], 'text-fg-muted' => ! $member['is_lead']])>{{ $member['position'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        <section id="alamat-kantor" class="mb-16 scroll-mt-24" aria-labelledby="judul-alamat">
            <x-section-heading title="Alamat Kantor Redaksi" size="lg" id="judul-alamat" />
            <div class="grid grid-cols-1 gap-8 rounded-xl border border-line bg-surface p-6 sm:p-8 md:grid-cols-2">
                <div>
                    <h3 class="mb-3 text-base font-bold text-fg-strong">{{ $office['legal_name'] }}</h3>
                    <address class="text-sm leading-relaxed text-fg-muted not-italic">{{ $office['address'] }}</address>
                </div>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-bold tracking-widest text-fg-muted uppercase">Email Redaksi</dt>
                        <dd class="mt-1"><a href="mailto:{{ $office['email_redaksi'] }}" class="inline-flex min-h-8 items-center font-semibold break-all text-accent hover:underline">{{ $office['email_redaksi'] }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold tracking-widest text-fg-muted uppercase">Email Iklan</dt>
                        <dd class="mt-1"><a href="mailto:{{ $office['email_iklan'] }}" class="inline-flex min-h-8 items-center font-semibold break-all text-accent hover:underline">{{ $office['email_iklan'] }}</a></dd>
                    </div>
                    @if ($office['phone'])
                        <div>
                            <dt class="text-xs font-bold tracking-widest text-fg-muted uppercase">Telepon</dt>
                            <dd class="mt-1"><a href="tel:{{ $office['phone'] }}" class="inline-flex min-h-8 items-center font-semibold text-accent hover:underline">{{ $office['phone'] }}</a></dd>
                        </div>
                    @endif
                    @if ($office['whatsapp'])
                        <div>
                            <dt class="text-xs font-bold tracking-widest text-fg-muted uppercase">WhatsApp</dt>
                            <dd class="mt-1"><a href="https://wa.me/{{ $office['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex min-h-8 items-center font-semibold text-accent hover:underline">+{{ $office['whatsapp'] }}</a></dd>
                        </div>
                    @endif
                </dl>
            </div>
        </section>

        <section id="pedoman-media-siber" class="scroll-mt-24" aria-labelledby="judul-pedoman">
            <x-section-heading title="Pedoman Pemberitaan Media Siber" size="lg" id="judul-pedoman" />
            <div class="rounded-2xl border border-line bg-surface p-5 sm:p-8">
                <p class="mb-8 text-sm leading-relaxed text-fg-muted italic">
                    Kemerdekaan berpendapat, kemerdekaan berekspresi, dan kemerdekaan pers adalah hak asasi manusia yang dilindungi Pancasila, Undang-Undang Dasar 1945, dan Deklarasi Universal Hak Asasi Manusia PBB. {{ config('portal.name') }} menjalankan pedoman berikut sebagaimana ditetapkan Dewan Pers.
                </p>
                <ol class="space-y-8 text-base leading-relaxed text-fg">
                    @foreach ($guidelines as [$heading, $body])
                        <li>
                            <h3 class="mb-2 flex items-start gap-3 text-base font-bold text-fg-strong">
                                <span class="shrink-0 font-extrabold text-accent" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}.</span>
                                {{ $heading }}
                            </h3>
                            <p class="sm:ml-9">{{ $body }}</p>
                        </li>
                    @endforeach
                </ol>
                <p class="mt-8 border-t border-line pt-6 text-xs leading-relaxed text-fg-muted italic">
                    Disusun berdasarkan Peraturan Dewan Pers Nomor 1/Peraturan-DP/III/2012 tentang Pedoman Pemberitaan Media Siber.
                </p>
            </div>
        </section>
    </div>
</x-app-layout>
