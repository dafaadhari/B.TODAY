<?php

/*
|--------------------------------------------------------------------------
| Konten & Identitas Portal B.Today
|--------------------------------------------------------------------------
|
| Satu sumber data untuk header, footer, halaman Redaksi, dan Pasang Iklan
| (PRD 9.5), supaya alamat, kontak, dan jabatan tidak lagi saling bertentangan.
|
| DATA SEMENTARA: nama dewan redaksi, alamat, email, dan nomor WhatsApp di
| bawah masih placeholder sampai disediakan pemilik (PRD 10.4). Nomor
| WhatsApp sengaja kosong secara default agar tombol WA tidak mengarah ke
| nomor orang lain; isi lewat .env (PORTAL_WHATSAPP, format 62xxxxxxxxxx).
|
*/

return [

    'name' => 'B.Today',

    'tagline' => 'Portal berita independen untuk Banten dan Indonesia. Cepat, terverifikasi, dan berimbang.',

    'office' => [
        'legal_name' => env('PORTAL_LEGAL_NAME', 'Redaksi B.Today'),
        'address' => env('PORTAL_ADDRESS', 'Jl. Raya Pandeglang-Serang No. 88, Banten'),
        'email_redaksi' => env('PORTAL_EMAIL_REDAKSI', 'redaksi@btoday.com'),
        'email_iklan' => env('PORTAL_EMAIL_IKLAN', 'iklan@btoday.com'),
        'phone' => env('PORTAL_PHONE'),
        'whatsapp' => env('PORTAL_WHATSAPP'),
    ],

    /*
     * Susunan dewan redaksi untuk halaman Redaksi. `is_lead` menandai
     * penanggung jawab (avatar amber, sesuai demo).
     */
    'redaksi' => [
        ['name' => 'Moch Dapa Adhari', 'position' => 'Pemimpin Redaksi / Penanggung Jawab', 'is_lead' => true],
        ['name' => 'Tim Redaksi B.Today', 'position' => 'Redaktur Pelaksana', 'is_lead' => false],
        ['name' => 'Editor Banten Today', 'position' => 'Editor Wilayah Banten', 'is_lead' => false],
        ['name' => 'Editor Nasional B.Today', 'position' => 'Editor Nasional & Teknologi', 'is_lead' => false],
    ],

    /*
     * Urutan & label pendek navigasi kategori induk (slug => label).
     * Kategori yang tidak tercantum tetap tampil di akhir dengan nama aslinya.
     */
    'nav' => [
        'nasional' => 'Nasional',
        'jabodetabek' => 'Jabodetabek',
        'banten' => 'Banten',
        'budaya' => 'Budaya',
        'travel-kuliner' => 'Travel',
        'hukum-kriminal' => 'Hukum',
        'olahraga' => 'Olahraga',
        'teknologi' => 'Teknologi',
        'otomotif' => 'Otomotif',
    ],

    /*
     * Perkiraan lama review kiriman tulisan publik, ditampilkan ke penulis tamu.
     */
    'review_days' => '3–5 hari kerja',

    /*
     * Spesifikasi slot iklan (PRD 4C). `ratio` dipakai untuk memesan ruang
     * agar halaman tidak bergeser saat materi iklan dimuat.
     */
    'ad_slots' => [
        'leaderboard' => [
            'label' => 'Leaderboard Banner',
            'size' => '728 × 90 px',
            'ratio' => '728/90',
            'position' => 'Atas halaman beranda & kategori',
            'description' => 'Posisi paling atas, terlihat pertama kali saat halaman dibuka.',
            'max_file' => '150 KB',
        ],
        'sidebar' => [
            'label' => 'Sidebar Banner',
            'size' => '300 × 250 px',
            'ratio' => '300/250',
            'position' => 'Kolom samping beranda, artikel & kategori',
            'description' => 'Tampil di samping artikel selama pembaca membaca.',
            'max_file' => '150 KB',
        ],
        'in_article' => [
            'label' => 'In-Article Native Ad',
            'size' => '600 × 120 px (lebar penuh)',
            'ratio' => '5/1',
            'position' => 'Di dalam isi artikel, setelah paragraf ke-3',
            'description' => 'Menyatu dengan alur baca, tetap berlabel "Iklan".',
            'max_file' => '200 KB',
        ],
    ],

];
