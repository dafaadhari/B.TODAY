<x-error-page code="419" title="Sesi Kedaluwarsa">
    Halaman terlalu lama dibuka sehingga sesi keamanannya habis. Kembali ke halaman sebelumnya dan kirim ulang.
    Jika Anda sedang mengisi Kirim Tulisan, draf Anda tersimpan otomatis di perangkat ini dan bisa dipulihkan.
    <x-slot:actions>
        <a href="{{ url()->previous() }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-line-strong bg-raised px-6 py-3 text-sm font-bold tracking-widest text-fg-strong uppercase hover:border-brand">Kembali</a>
    </x-slot:actions>
</x-error-page>
