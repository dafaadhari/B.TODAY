<x-error-page code="429" title="Terlalu Banyak Percobaan">
    Anda mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu sekitar satu menit, lalu coba lagi.
    Isian formulir Kirim Tulisan tersimpan otomatis di perangkat ini.
    <x-slot:actions>
        <a href="{{ url()->previous() }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-line-strong bg-raised px-6 py-3 text-sm font-bold tracking-widest text-fg-strong uppercase hover:border-brand">Kembali</a>
    </x-slot:actions>
</x-error-page>
