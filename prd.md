# Product Requirement Document (PRD)
## Proyek: Portal Berita Modern "B.Today" (sebelumnya nama kerja: Nexus News)

| Parameter | Detail Informasi |
| :--- | :--- |
| **Pengembang Utama** | Mochamad Dapa Adhari |
| **Nama Sistem** | B.Today Portal |
| **Domain** | B.Today.com |
| **Target Waktu** | 1 Minggu (Pengerjaan Efektif) |
| **Model Hosting** | Demo Statis (Vercel) → Production (Laravel + VPS/Shared Hosting) |
| **Referensi Desain** | Folder `demo/` — prototipe statis (nama kerja Nexus News) yang menjadi acuan tampilan & layout final (lihat bagian 9) |

---

## 1. Tujuan Proyek & Lingkup Kerja

Membangun aplikasi portal berita berbasis web yang *custom*, aman, cepat, dan responsif. Sistem dirancang dengan identitas visual **Modern Dark Mode** sebagai tampilan utama (mengacu pada karakter visual Narasi, Indozone, dan Kumparan), dengan dukungan **Light Mode** sebagai mode alternatif yang bisa dipilih pembaca, serta difokuskan pada kekuatan **SEO** dan **monetisasi iklan dinamis**.

### Scope In (Dikerjakan):
* Pengembangan rancangan UI/UX responsif (Desktop, Tablet, Mobile), dengan dukungan Dark Mode & Light Mode.
* Pembuatan skema basis data relasional untuk sistem berita & iklan.
* Pengembangan CMS Redaksi kustom berbasis Laravel (akses admin setara, lihat bagian RBAC).
* Modul Manajemen Iklan (*Ad Inventory System*).
* Formulir Kiriman Tulisan Publik (tanpa akun) + antrean review oleh admin.
* Integrasi SEO dasar (Meta Tag, OpenGraph, XML Sitemap).
* Setup infrastruktur (Domain .com / .id + Server/Hosting).
* Redesign tampilan Laravel agar tampil & tersusun seperti prototipe `demo/`, dengan perbaikan UX dari sudut pandang pengguna (lihat bagian 9).

### Scope Out (Tidak Dikerjakan di Tahap Ini):
* Aplikasi mobile native (Android/iOS APK).
* Sistem pembayaran *paywall* / konten berlangganan otomatis.

---

## 2. Spesifikasi Teknis & Infrastruktur

### Tech Stack
* **Frontend:** HTML5, Tailwind CSS **v4** yang di-compile lewat Vite (dark mode strategy: `class`, bukan `media`, supaya toggle manual berfungsi independen dari pengaturan OS — di v4 lewat `@custom-variant`, lihat 8.5), Alpine.js via npm, Plus Jakarta Sans (di-host sendiri), CSS Glassmorphism. Tailwind tidak lagi dimuat dari CDN di halaman publik, karena compiler di browser berat untuk HP kelas menengah ke bawah.
* **Backend:** Laravel Framework (PHP 8.x).
* **Database:** MySQL / PostgreSQL.
* **Deployment & Demo:** Vercel (Demo Prototype) → Server/VPS Hosting (Production).

### Breakdown Anggaran (Tahun Pertama)
* **Domain (.com / .id):** ~Rp 200.000 / tahun
* **Server / Hosting (Laravel Ready):** ~Rp 600.000 / tahun (Estimasi operasional ~Rp 50.000/bulan)
* **Jasa Pembuatan Sistem:** Rp 700.000 (Sekali bayar - Harga Teman)
* **Total Biaya Paket Terima Beres:** **Rp 1.500.000**
* *Catatan Perpanjangan Tahun ke-2:* ~Rp 800.000 / tahun.

---

## 3. Peta Halaman (Sitemap) & Antarmuka UI

Sistem terdiri dari **8 halaman utama** berbasis folder statis untuk tahap awal demo. File statis tersimpan di folder **`demo/`** (ditambah 14 varian `kategori-*.html` yang hanya berbeda nama kategori & breadcrumb). `kirim-tulisan.html` tidak dibuat di demo — desainnya diturunkan dari bahasa visual demo (lihat 9.6).

| Nama File | Fungsi & Komponen Utama |
| :--- | :--- |
| **`index.html`** | **Halaman Utama (Beranda):** Navbar *glassmorphism*, *trending ticker*, *hero article split layout*, grid berita terbaru, *sidebar* terpopuler, dan slot iklan *leaderboard*. (Tanpa *top bar* terpisah — demo tidak memakainya, header + ticker sudah cukup.) |
| **`detail.html`** | **Halaman Detail Artikel:** *Breadcrumbs*, judul utama, metadata penulis & tanggal publikasi, foto utama, *text body*, *blockquote*, *social share*, *tags*, dan artikel terkait. Byline menampilkan nama penulis staf ATAU nama+instansi pengirim publik (jika artikel berasal dari kiriman publik). |
| **`kategori.html`** | **Halaman Indeks Kategori:** Header kategori, deskripsi topik, *grid card* artikel 3 kolom, dan *pagination*. |
| **`kirim-tulisan.html`** | **Formulir Kiriman Tulisan Publik:** Form tanpa login — nama, instansi, email, no. HP (opsional), judul, isi tulisan, upload gambar pendukung (opsional). Dilengkapi CAPTCHA. Setelah submit, status "menunggu review" ditampilkan ke pengirim. |
| **`pasang-iklan.html`** | **Media Kit & Info Iklan:** Informasi posisi slot iklan, spesifikasi dimensi banner, dan formulir kontak pengiklan. (Tahap redesign: form mengirim lewat WhatsApp/email tanpa penyimpanan; penyimpanan + inbox admin menyusul, lihat bagian 10.) |
| **`search.html`** | **Hasil Pencarian:** Bilah pencarian kata kunci, filter tanggal/kategori, dan daftar hasil pencarian. |
| **`redaksi.html`** | **Struktur Redaksi & Pedoman:** Susunan dewan redaksi, alamat kantor, dan teks Pedoman Media Siber Dewan Pers (Syarat Google News/AdSense). |
| **`404.html`** | **Custom Error Page:** Penanganan tautan rusak dengan navigasi kembali ke beranda & rekomendasi berita populer. |

---

## 4. Kebutuhan Fungsional (Functional Requirements)

### A. Sisi Pembaca (Frontend)
1. **Navigasi Responsif:** *Header sticky* dengan menu hamburger yang dapat dibuka di perangkat seluler.
   * Dropdown **Banten** wajib bisa dibuka lewat klik/tap/keyboard (Enter/Spasi, tutup dengan Esc) — bukan hover saja, karena hover tidak bisa dipakai di HP, tablet, maupun keyboard.
   * Tombol **cari** tampil di semua lebar layar.
   * Tombol pill **"Pasang Iklan"** di header (sesuai demo). **"Kirim Tulisan"** ditempatkan di drawer HP, footer, kartu sidebar beranda, dan akhir artikel kiriman pembaca.
2. **Indikator Trending Topic:** Bilah *ticker* di bawah navigasi utama untuk topik-topik hangat.
   * Hanya tampil di beranda, **di bawah header dan tidak ikut sticky**, supaya header yang menempel tetap ±64px dan layar HP lebih lega untuk berita.
   * *Auto-scroll* hanya bila pembaca tidak memilih `prefers-reduced-motion`, berhenti saat di-hover/difokus, dan tetap bisa digeser manual.
3. **Pencatatan Populer Otomatis:** Sistem mencatat *views* setiap artikel secara *real-time* untuk menyusun daftar berita "Terpopuler".
4. **Optimasi Berbagi (Social Share):** Tombol bagi cepat ke WhatsApp dan Twitter dengan pratinjau gambar (*OpenGraph*) yang rapi.
5. **Theme Switcher (Light/Dark Mode):** Tombol toggle di header untuk berpindah antara Dark Mode (default/utama) dan Light Mode. Preferensi pembaca disimpan otomatis (localStorage) supaya tidak perlu diatur ulang di kunjungan berikutnya. Seluruh komponen (navbar, card artikel, slot iklan, blockquote, dsb.) wajib punya varian warna untuk kedua mode — bukan cuma invert warna otomatis, supaya kontras dan keterbacaan tetap terjaga di keduanya. Nilai warna kedua mode ditetapkan sebagai token di **9.3**.
6. **Kirim Tulisan Publik:** Pembaca bisa mengirim tulisan tanpa perlu membuat akun — cukup isi nama, instansi, email, judul, dan isi tulisan di `kirim-tulisan.html`. Tulisan masuk ke antrean review admin, TIDAK langsung tayang. Pengirim menerima konfirmasi (di layar, opsional via email) bahwa tulisannya "menunggu review".
   * **Dikerjakan bertahap:** tahap redesign mencakup tampilan, alur review yang dijelaskan sebelum menulis, pesan error per field, panel konfirmasi yang menggantikan form setelah sukses, dan draf otomatis (localStorage) supaya tulisan panjang tidak hilang. CAPTCHA, no. HP, dan upload gambar pendukung menyusul (bagian 10).

### B. Sisi Redaksi (Backend CMS)
1. **Akses Admin (5 Akun, Setara):** Tidak ada tingkatan role (bukan Editor/Author terpisah) — kelima akun Super Admin punya akses penuh yang identik ke seluruh sistem, termasuk manajemen user dan modul iklan. Jumlah akun bisa ditambah kapan saja tanpa perubahan sistem, selama aksesnya tetap setara (akses berjenjang/terbatas di luar scope saat ini).
2. **Alur Review Wajib:** Artikel tidak bisa langsung tayang. Setiap artikel wajib melalui status *draft* → *pending review* → *published*, dan harus disetujui oleh admin **lain** (bukan admin yang menulis artikel tersebut) sebelum tayang — mencegah satu akun approve tulisannya sendiri.
3. **WYSIWYG Rich-Text Editor:** Fasilitas format teks, penyisipan gambar dalam paragraf, dan kutipan (*blockquote*).
4. **Manajemen Kategori & Tag:** Pengelompokan isu secara fleksibel.
5. **Antrean Review Kiriman Publik:** Halaman admin terpisah yang menampilkan daftar kiriman tulisan publik berstatus *pending*, dengan aksi: **Setujui** (artikel otomatis dibuat dengan byline nama+instansi pengirim, lanjut ke alur *Alur Review Wajib* di atas) atau **Tolak** (wajib isi alasan penolakan, tersimpan untuk referensi). Pola tampilannya mengikuti **Pola Desain Dashboard Admin** di bawah — kartu ringkasan (Total Kiriman, Pending, Ditolak) + tabel dengan status badge.
6. **Pola Desain Dashboard Admin:** Mengikuti pola tampilan admin panel dark-mode dengan struktur berikut:
   * Header admin terpisah dari header publik — branding/logo di kiri, tombol **"Lihat Portal Publik"** (shortcut buka tab baru ke situs utama), info akun admin yang sedang login + tombol logout di kanan.
   * Banner notifikasi kontekstual (mis. "Selamat datang kembali, [nama]!") di bagian atas halaman.
   * **Kartu ringkasan statistik** di setiap halaman manajemen (mis. Total Berita, Diterbitkan, Draft) dengan ikon dan angka besar — bukan cuma tabel polos.
   * Search bar + dropdown filter status di atas tabel data.
   * Tabel data dengan kolom: thumbnail/sampul, judul & slug, **status berupa badge berwarna** (hijau = publikasi, kuning/amber = draft, dst.), tanggal, dan kolom aksi (ikon edit & hapus).
   * Tombol aksi utama (mis. "Tambah Berita Baru") menonjol di pojok kanan atas halaman, warna aksen kontras (oranye/amber) terhadap latar gelap.

### C. Sistem Manajemen Iklan (Ad Inventory)
1. **Slot Iklan Terstruktur:**
   * **Leaderboard Banner:** Posisi atas (ukuran 728x90 px).
   * **Sidebar Banner:** Posisi samping (ukuran 300x250 px).
   * **In-Article Native Ad:** Posisi di tengah paragraf artikel.
2. **Aktivasi Dinamis:** Admin dapat menyalakan/mematikan iklan, mengatur batas tanggal penayangan, dan memasukkan *redirect URL* tujuan.
3. **Aturan Tampil Iklan (demi pembaca & pengiklan):**
   * Setiap iklan diberi label **"Iklan"** di luar materi iklan (tidak menutupi logo/teks pengiklan), sesuai poin Iklan Pedoman Media Siber.
   * Ruang slot dipesan sesuai ukuran (rasio 728×90, 300×250), supaya halaman tidak bergeser saat gambar iklan dimuat.
   * Slot kosong: leaderboard & in-article **tidak dirender** (pembaca tidak melihat kotak placeholder); slot sidebar menampilkan promo kecil "Pasang iklan di sini" yang menuju halaman Pasang Iklan.
   * Tautan iklan memakai `rel="sponsored noopener"` dan dibuka di tab baru.
   * In-article native ad disisipkan **setelah paragraf ke-3** isi artikel; artikel pendek mendapat iklan setelah isi.
   * `impressions_count` hanya bertambah untuk slot yang benar-benar dirender (lihat `<x-ad-slot>` di 8.7).

### D. Keamanan Sistem (Security Requirements)

**Prioritas Tinggi (wajib ada sejak hari pertama development backend):**
1. **Sanitasi Konten WYSIWYG:** Semua output dari rich-text editor (kolom `content`) wajib disaring lewat library sanitasi HTML (mis. HTMLPurifier) sebelum disimpan ke database, untuk mencegah *stored XSS* dari akun admin yang disusupi.
2. **Validasi File Upload:** Upload gambar (`thumbnail_path` & gambar inline artikel) wajib divalidasi berdasarkan MIME type asli file (bukan hanya ekstensi), di-*re-encode* ulang sebagai gambar, disimpan dengan nama file acak (random hash) di luar direktori yang bisa dieksekusi PHP.
3. **Rate Limiting Login:** Middleware `throttle` Laravel wajib diaktifkan pada endpoint login CMS untuk mencegah *brute force* ke akun redaksi.
3a. **CAPTCHA + Rate Limiting Form Publik:** Endpoint `kirim-tulisan.html` WAJIB pakai CAPTCHA (reCAPTCHA/hCaptcha) dan rate limiting per IP terpisah dari rate limit login — ini form tanpa akun, jadi satu-satunya sinyal pembatas yang ada cuma alamat IP pengirim.
4. **Validasi `redirect_url` Iklan:** URL tujuan pada modul iklan wajib divalidasi format dan scheme-nya (hanya `http`/`https`), untuk mencegah penyalahgunaan sebagai *open redirect* ke situs phishing/malware.
5. **HTTPS/SSL Wajib:** Seluruh environment (demo & production) wajib berjalan di atas HTTPS (Let's Encrypt gratis untuk shared hosting/VPS).

**Prioritas Menengah:**
6. **Two-Factor Authentication (2FA):** Wajib aktif di kelima akun Super Admin — karena tidak ada lagi role dengan akses terbatas, satu akun bocor berarti seluruh sistem (termasuk modul iklan) langsung terekspos.
7. **Anti-Spam Views Counter:** Pencatatan *views* otomatis wajib dilakukan deduplikasi per sesi/IP per artikel (mis. maksimal 1 view/artikel/24 jam per pengunjung) untuk mencegah manipulasi daftar "Terpopuler".
8. **Content Security Policy (CSP):** Header CSP wajib disetel di level aplikasi, khususnya karena sistem me-render `image_url` iklan dari sumber eksternal — mencegah satu iklan yang disusupi menjadi vektor XSS ke seluruh halaman.
9. **Audit Log Editorial:** Sistem wajib mencatat siapa membuat/menyunting/menghapus/mem-publish artikel apa dan kapan, untuk akuntabilitas editorial dan forensik insiden.

**Prioritas Rendah (dicatat, dieksekusi menyesuaikan waktu):**
10. **Kepatuhan UU PDP:** Karena sistem menyimpan data pribadi (email user, data pengiklan dari formulir kontak), perlu privacy policy minimal dan kebijakan retensi data.

---

## 5. Skema Basis Data (Database Structure)

```text
+------------------------+   +------------------------+   +------------------------+
|         users          |   |        articles        |   |       categories       |
+------------------------+   +------------------------+   +------------------------+
| id (PK)                |   | id (PK)                |   | id (PK)                |
| name                   |   | author_id (FK, null)   |   | name                   |
| email                  |   | category_id (FK)       |   | slug                   |
| password               |   | title / slug / content |   +------------------------+
| role = 'super_admin'   |   | thumbnail_path         |                             
| two_factor_enabled     |   | guest_author_name      |                             
+------------------------+   | guest_author_instansi  |                             
                             | reviewed_by (FK)       |                             
                             | reviewed_at            |                             
                             | views_count            |                             
                             | status (enum)          |                             
                             | published_at           |                             
                             | deleted_at             |                             
                             +------------------------+                             

+------------------------+   +------------------------+   +------------------------+
|          tags          |   |      article_tag       |   |   guest_submissions    |
+------------------------+   +------------------------+   +------------------------+
| id (PK)                |   | article_id (FK)        |   | id (PK)                |
| name                   |   | tag_id (FK)            |   | category_id (FK,null)  |
| slug                   |   +------------------------+   | reviewed_by (FK)       |
+------------------------+                                | article_id (FK,null)   |
                                                          | name / instansi        |
                                                          | email                  |
                                                          | status (enum)          |
                                                          +------------------------+

+------------------------+   +------------------------+   +------------------------+
|     advertisements     |   |       audit_logs       |   |         media          |
+------------------------+   +------------------------+   +------------------------+
| id (PK)                |   | id (PK)                |   | id (PK)                |
| title                  |   | user_id (FK)           |   | article_id (FK)        |
| image_url              |   | action (enum)          |   | file_path              |
| redirect_url           |   | subject_type           |   | mime_type              |
| placement (enum)       |   | subject_id             |   | created_at             |
| is_active (bool)       |   | created_at             |   +------------------------+
| impressions_count      |   +------------------------+                             
| clicks_count           |                                                          
+------------------------+                                                          
```

### Penambahan Skema (Security & Operasional)

* **Tabel `guest_submissions`** — menampung kiriman tulisan publik (nama, instansi, email, isi) tanpa perlu akun. Setelah disetujui admin, isinya dipindahkan jadi baris baru di `articles` dengan `guest_author_name`/`guest_author_instansi` terisi (bukan `author_id`, karena pengirim tidak punya akun).
* **`articles.reviewed_by` & `reviewed_at`** — wajib terisi sebelum status bisa `published` (lihat *Alur Review Wajib* di bagian 4B); `reviewed_by` harus berbeda dari `author_id` supaya admin tidak bisa approve tulisannya sendiri.
* **`articles.author_id` sekarang nullable** — artikel dari kiriman publik tidak terikat ke akun manapun.
* **`articles.deleted_at`** — Kolom *soft delete* agar artikel yang tidak sengaja terhapus (sudah punya views/share) bisa dipulihkan, bukan hilang permanen.
* **Tabel `audit_logs`** — mencatat setiap aksi create/update/delete/publish per user, untuk akuntabilitas editorial (lihat requirement D.9 di atas).
* **`advertisements.impressions_count` & `clicks_count`** — dibutuhkan untuk pelaporan/billing ke klien pengiklan; tanpa ini tidak ada data untuk menjustifikasi tagihan iklan.
* **Tabel `media`** — media library terpisah agar file gambar yang di-upload tapi artikelnya terhapus (*orphan file*) tetap bisa dilacak dan dibersihkan.
* **Indexing wajib:** `slug` di tabel `articles`, `categories`, dan `tags` harus `unique` + `indexed`; `author_id` dan `category_id` di `articles` sebagai foreign key juga perlu index untuk performa query listing/kategori pada skala data besar.
* **`views_count` pada skala tinggi:** untuk artikel viral, pertimbangkan memindahkan counter ke cache (Redis) dengan sinkronisasi berkala ke DB, agar tidak terjadi *row lock contention* saat traffic tinggi.

---

## 6. Kebutuhan Non-Fungsional & Operasional Tambahan

1. **Mekanisme Search:** `search.html` memerlukan kejelasan implementasi backend. Query `LIKE '%keyword%'` di MySQL akan melambat seiring jumlah artikel bertambah — minimal gunakan *full-text index* MySQL, atau pertimbangkan Meilisearch/Typesense jika ingin performa pencarian yang serius.
2. **Sitemap XML Dinamis:** Sitemap wajib digenerate otomatis setiap ada artikel baru/terbit (bukan file statis), agar Google dapat mengindeks artikel baru — sitemap statis membuat investasi SEO tidak efektif.
3. **Backup Strategy:** Perlu backup database otomatis terjadwal (cron job), karena shared hosting kelas ~Rp50.000/bulan umumnya tidak menyediakan backup otomatis secara default. Konten redaksi adalah aset bisnis utama sistem ini.

---

## 7. Rencana Kerja & Jadwal Pelaksanaan (1 Minggu)

> **Catatan Kelayakan:** Menambahkan seluruh item keamanan (sanitasi upload, rate limiting, 2FA, audit log) ke dalam timeline 7 hari solo development sangat agresif. Disarankan memisahkan menjadi **MVP** (sanitasi WYSIWYG, validasi upload, rate limiting login, HTTPS, validasi `redirect_url` — wajib ada dari hari pertama) vs **Fase 2** (2FA, audit log, CSP, anti-spam views counter, kepatuhan UU PDP — bisa menyusul setelah go-live awal).

Hari 1: Finalisasi Wireframe & Penyusunan File Statis HTML/Tailwind (index, detail, kategori, kirim-tulisan, dll).

Hari 2: Deployment Demo Statis ke Vercel untuk Peninjauan Klien (Bang Andi).

Hari 3: Inisialisasi Proyek Laravel, Setup Migrations, dan Skema Database (termasuk kolom/tabel security: `deleted_at`, `audit_logs`, tracking iklan).

Hari 4: Pembangunan Dashboard Redaksi (CMS), Antrean Review Kiriman Publik, & Modul Manajemen Iklan — termasuk sanitasi WYSIWYG (HTMLPurifier), validasi file upload, dan CAPTCHA di form kiriman publik sejak awal, bukan ditambahkan belakangan.

Hari 5: Integrasi Frontend Blade Templating dengan API/Backend Laravel, plus rate limiting login dan validasi `redirect_url`.

Hari 6: Pengujian Responsif, Optimasi SEO (termasuk sitemap dinamis), Setup HTTPS/SSL, dan Setup Domain/Hosting Production.

Hari 7: Go Live (Penyerahan Web Siap Pakai & Serah Terima Akses Admin) — sertakan setup backup database otomatis sebelum serah terima.

### 7.1 Tahap Redesign Mengikuti Demo

Tampilan Laravel hasil Hari 3–5 berbeda jauh dari demo `demo/` yang disukai klien. Tahap ini menyamakan tampilan & layout halaman publik dengan demo (spesifikasi lengkap di bagian 9). Dikerjakan di branch `redesign-demo`, tiap fase dicek dulu sebelum lanjut:

| Fase | Isi | Perkiraan |
| :--- | :--- | :--- |
| P. PRD | Memasukkan keputusan redesign ke dokumen ini → ditinjau klien sebelum kode dikerjakan | ½ jam |
| 0. Fondasi | Vite + Tailwind v4 + Alpine via npm; token warna dua tema; font di-host sendiri; partial head publik baru (admin & login memakai head lama); `config/portal.php`; data navigasi lewat view composer; komponen `<x-ad-slot>` | ½–1 hari |
| 1. Shell & komponen dasar | Header (nav, dropdown Banten, pill Pasang Iklan, cari, toggle tema), drawer HP, ticker, footer, skip link; komponen di 8.7; view pagination | ½–1 hari |
| 2. Halaman baca | Beranda, detail artikel (share, sisipan iklan, sidebar, data terstruktur), kategori induk/anak | 1–1½ hari |
| 3. Halaman pendukung | Pencarian (filter), 404 + 419/429/500, Redaksi, Pasang Iklan, Kirim Tulisan (+ semua state & draf) | 1 hari |
| 4. QA | Aksesibilitas, kontras, performa, pengecekan visual berdampingan dengan demo | ½ hari |

Panel admin & login **tidak termasuk** tahap ini (dicatat di bagian 10).

---

## 8. Panduan Migrasi ke Laravel (Backend & Frontend, untuk Agent Development)

> Bagian ini ditulis sebagai instruksi kerja untuk AI coding agent yang akan mengeksekusi migrasi dari demo statis (Hari 1-2) ke aplikasi Laravel penuh (mulai Hari 3) — mencakup database, backend, DAN konversi seluruh halaman statis jadi Blade template dinamis. Rujukan schema lengkap (kolom, tipe data, constraint) ada di file terpisah `nexus-news-schema.sql` — bagian ini fokus ke urutan eksekusi, struktur kode, dan logika yang TIDAK terwakili oleh file HTML statis atau kolom SQL saja.

### 8.1 Urutan Migration (wajib sesuai urutan ini, karena foreign key)

1. `users`
2. `categories` — kolom `parent_id` self-referencing; deklarasikan `$table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();` DALAM migration yang sama (tidak perlu migration terpisah, MySQL/Laravel mendukung self-reference nullable dalam satu `Schema::create`).
3. `articles` — FK ke `users` (author_id, reviewed_by — keduanya nullable) dan `categories` (category_id).
4. `tags`
5. `article_tag` — pivot, FK ke `articles` dan `tags`.
6. `media` — FK ke `articles` (nullable) dan `users` (uploaded_by).
7. `advertisements` — tidak ada FK keluar.
8. `audit_logs` — FK ke `users` (nullable).
9. `guest_submissions` — FK ke `categories` (nullable), `users` (reviewed_by), `articles` (article_id, nullable).

### 8.2 Model Eloquent & Relasi

| Model | Relasi yang perlu didefinisikan |
| :--- | :--- |
| `User` | `articles()` → hasMany(Article, 'author_id'); `reviewedArticles()` → hasMany(Article, 'reviewed_by'); `auditLogs()` → hasMany(AuditLog); `reviewedSubmissions()` → hasMany(GuestSubmission, 'reviewed_by'); `uploadedMedia()` → hasMany(Media, 'uploaded_by') |
| `Category` | `parent()` → belongsTo(Category, 'parent_id'); `children()` → hasMany(Category, 'parent_id'); `articles()` → hasMany(Article) |
| `Article` | `author()` → belongsTo(User, 'author_id'); `reviewer()` → belongsTo(User, 'reviewed_by'); `category()` → belongsTo(Category); `tags()` → belongsToMany(Tag, 'article_tag'); `media()` → hasMany(Media); pakai trait `SoftDeletes` (kolom `deleted_at` sudah ada di schema) |
| `Tag` | `articles()` → belongsToMany(Article, 'article_tag') |
| `Media` | `article()` → belongsTo(Article); `uploader()` → belongsTo(User, 'uploaded_by') |
| `Advertisement` | tidak ada relasi keluar |
| `AuditLog` | `user()` → belongsTo(User) |
| `GuestSubmission` | `category()` → belongsTo(Category); `reviewer()` → belongsTo(User, 'reviewed_by'); `article()` → belongsTo(Article) |

### 8.3 Seeder

* `DatabaseSeeder` wajib membuat **5 akun Super Admin** (role tetap `super_admin` untuk semua), dengan password acak/sementara yang di-reset manual oleh masing-masing admin saat login pertama — JANGAN hardcode password yang sama untuk kelima akun di seeder produksi.
* Seeder kategori: 9 kategori flat (Nasional, Jabodetabek, Budaya, Travel & Kuliner, Hukum & Kriminal, Olahraga, Teknologi, Otomotif) + 1 kategori induk "Banten" dengan 5 anak (Lebak Today, Cilegon Today, Pandeglang Today, Serang Today, Tangerang Today).

### 8.4 Business Logic Wajib (di Controller/Form Request/Observer — BUKAN di migration)

Ini bagian paling penting karena tidak ter-enforce otomatis oleh skema SQL:

1. **Cegah self-review:** sebelum `articles.status` diubah ke `published`, validasi `reviewed_by !== author_id`. Tolak dengan error kalau sama.
2. **Status transition published:** `articles.status` hanya boleh pindah ke `published` jika `reviewed_by` sudah terisi (gunakan Form Request atau Model Observer `saving()` untuk enforce ini).
3. **Sanitasi HTML:** `articles.content` DAN `guest_submissions.content` wajib disaring HTMLPurifier (atau setara) sebelum disimpan — pasang di Form Request/Model mutator, bukan cuma di frontend.
4. **Guest submission → article:** saat admin approve `guest_submissions`, buat row baru di `articles` dengan `author_id = NULL`, `guest_author_name`/`guest_author_instansi` diisi dari submission, dan isi `guest_submissions.article_id` dengan id artikel yang baru dibuat (jangan hapus row submission-nya, biar ada jejak).
5. **Upload gambar:** validasi MIME type asli (bukan ekstensi), re-encode ulang pakai Intervention Image atau setara, random filename, simpan di luar public path yang bisa dieksekusi PHP.
6. **CAPTCHA + rate limit:** pasang di route `kirim-tulisan` (guest submission) dan route login — dua middleware/rule terpisah, jangan digabung.
7. **Audit log:** setiap create/update/delete/publish di `articles` dan `advertisements` wajib menulis row baru ke `audit_logs` (bisa pakai Model Observer global, jangan manual di tiap controller supaya tidak ada yang kelewat).
8. **2FA:** wajib aktif di kelima akun Super Admin sebelum akun tersebut bisa dipakai — bisa di-enforce lewat middleware yang redirect ke setup 2FA kalau belum aktif.

### 8.5 Checklist Konfigurasi Lain

* Dark mode berbasis class (bukan `'media'`) supaya toggle manual light/dark berfungsi independen dari OS. Karena Tailwind **v4** tidak lagi memakai `tailwind.config.js`, deklarasinya ada di `resources/css/app.css`: `@custom-variant dark (&:where(.dark, .dark *));`. Token warna kedua mode (9.3) juga didefinisikan di file ini sebagai CSS variable dan dipetakan lewat `@theme inline`.
* `.env` production: `APP_DEBUG=false` — WAJIB dicek sebelum go-live (lihat requirement keamanan D di atas).
* Queue worker: siapkan kalau `views_count` nanti dipindah ke Redis + sync berkala (lihat catatan skema di bagian 5).


### 8.6 Struktur Blade Template

Konversi 8 file HTML statis (Hari 1, kini di folder `demo/`) jadi view dinamis dengan layout terpisah untuk publik dan admin:

* **`layouts/app.blade.php`** — layout publik (navbar + footer + slot tema light/dark), dipakai semua halaman pembaca.
* **`layouts/admin.blade.php`** — layout khusus CMS (header admin terpisah + sidebar/menu navigasi), dipakai semua halaman redaksi.
* Pemetaan file statis → Blade view:

| File statis (Hari 1-2) | View Laravel | Catatan |
| :--- | :--- | :--- |
| `index.html` | `resources/views/home.blade.php` | Query artikel terbaru + terpopuler + trending |
| `detail.html` | `resources/views/articles/show.blade.php` | Route model binding by slug |
| `kategori.html` | `resources/views/categories/show.blade.php` | Dynamic by slug; breadcrumb berjenjang kalau `category->parent` ada isinya (kasus Banten) |
| `search.html` | `resources/views/search.blade.php` | Query via full-text index (lihat requirement 6.1) |
| `kirim-tulisan.html` | `resources/views/kirim-tulisan.blade.php` | Form submission ke `guest_submissions`, bukan `articles` |
| `pasang-iklan.html` | `resources/views/pasang-iklan.blade.php` | Mostly static + form kontak pengiklan |
| `redaksi.html` | `resources/views/redaksi.blade.php` | Static content |
| `404.html` | `resources/views/errors/404.blade.php` | Ikuti konvensi custom error view Laravel |
| *(tidak ada di demo)* | `resources/views/errors/419.blade.php`, `429.blade.php`, `500.blade.php` | Gaya sama dengan 404; 500 tanpa query database |

* **Head:** layout publik memakai `partials/meta.blade.php` (Vite, lihat 8.10). Admin & login sementara memakai `partials/admin-head.blade.php` (isi head lama berbasis CDN) sampai redesign admin (bagian 10), sehingga tampilannya tidak ikut berubah.
* Halaman admin (belum ada versi statisnya, baru dibangun Hari 4 langsung di Laravel): `admin/dashboard.blade.php`, `admin/articles/index.blade.php` + `create.blade.php` + `edit.blade.php`, `admin/submissions/index.blade.php` (antrean kiriman publik), `admin/ads/index.blade.php`, `admin/categories/index.blade.php`.

### 8.7 Blade Component yang Wajib Reusable

Jangan duplikasi markup — pecah jadi component dari awal supaya konsisten dan gampang di-maintain:

* `<x-article-card variant="grid|compact|list|horizontal">` — dipakai di home, kategori, search, 404, dan "artikel terkait". Seluruh kartu satu area klik (satu tab stop), tanggal dalam `<time>`, badge "Kontributor" untuk tulisan kiriman publik.
* `<x-ad-slot placement="leaderboard|sidebar|in_article">` — *class component* (`app/View/Components/AdSlot.php`) yang query iklan aktif by placement, sekaligus catat `impressions_count` tiap kali render. Controller tidak lagi meng-query iklan. Aturan tampil di 4C.3.
* `<x-theme-toggle>` — tombol switch light/dark (44px, `aria-pressed`), state dikelola Alpine.js + disimpan ke `localStorage`.
* `<x-category-nav>` — render navbar kategori; khusus item "Banten" render sebagai dropdown berisi `category->children` (klik/tap/keyboard), item lain tampil flat.
* Komponen tampilan publik tambahan (bagian 9):
  * `<x-section-heading>` — judul seksi dengan bar amber kiri + "Lihat Semua →".
  * `<x-hero-article>` — hero split 2/3 + 1/3.
  * `<x-popular-list>` — daftar Terpopuler bernomor 01–05.
  * `<x-byline>` — avatar inisial, nama staf atau "Kontributor · instansi", editor, waktu terbit/diperbarui, menit baca.
  * `<x-share-buttons>` — WhatsApp, X, Facebook, salin tautan, share bawaan HP.
  * `<x-breadcrumb>` — level berjenjang (Banten / wilayah).
  * `<x-page-header>`, `<x-card>`, `<x-button>`, `<x-form.input|select|textarea>` (label, petunjuk, pesan error per field).
  * `<x-mobile-nav>`, `<x-ticker>`, `<x-empty-state>`, `<x-flash>`.
  * View pagination `vendor/pagination/btoday.blade.php` (gaya demo, bahasa Indonesia).
* `<x-status-badge status="draft|pending_review|published|rejected">` — dipakai di admin/articles DAN admin/submissions supaya warna badge konsisten (hijau/kuning/merah).
* `<x-stat-card>` — kartu ringkasan angka di dashboard admin (Total/Published/Draft, dst — lihat requirement 4B.6).

### 8.8 Interaktivitas Frontend (JS)

* Pakai **Alpine.js** (ringan, langsung cocok dipasang di Blade tanpa build step kompleks, selaras dengan tech stack HTML+Tailwind yang sudah dipilih) untuk: toggle menu hamburger mobile, toggle theme light/dark, trending ticker (auto-scroll), validasi inline di form `kirim-tulisan`.
* Alpine dipasang via npm (bukan CDN) bersama plugin `@alpinejs/focus` (fokus keyboard di dropdown/drawer) dan `@alpinejs/persist` (draf otomatis form `kirim-tulisan`). Perilaku lain: dropdown Banten, salin tautan & share bawaan HP, auto-submit filter pencarian, susun pesan WhatsApp dari form Pasang Iklan.
* JANGAN campur dengan framework JS lain (Vue/React) untuk bagian ini — di luar scope dan bikin build process tidak perlu rumit untuk kebutuhan yang sebenarnya ringan.
* WYSIWYG editor di admin (requirement 4B.3): gunakan TinyMCE atau Quill via CDN/npm — tapi sanitasi HTML di server (HTMLPurifier, requirement 8.4 poin 3) TETAP WAJIB jalan meski sudah ada WYSIWYG di client, karena request API/form bisa saja tidak lewat UI editor sama sekali.

### 8.9 Asset Pipeline

* Pakai Vite bawaan Laravel untuk compile Tailwind CSS v4 (dark mode class, lihat 8.5) dan Alpine.js. Folder hasil build `public/build` ikut diunggah ke hosting.
* Font Plus Jakarta Sans (400–800): self-host lewat helper `bunny()` milik `laravel-vite-plugin` di `vite.config.js` (font diunduh saat build, `font-display: swap`, preload) dan directive `@fonts` di `<head>` — tidak render-blocking.
* Gambar artikel: wajib `loading="lazy"` di luar viewport awal, dan generate ukuran thumbnail berbeda (pakai Intervention Image saat upload) untuk card vs halaman detail — jangan serve gambar ukuran penuh di card kecil. *(Thumbnail multi-ukuran dicatat di bagian 10.)*
* Semua `<img>` wajib punya `width`/`height` atau rasio (`aspect-video`) agar layout tidak bergeser; gambar utama (hero/foto artikel) memakai `fetchpriority="high"` tanpa lazy.

### 8.10 SEO & Meta Tag (Blade Partial)

* Buat satu partial `partials/meta.blade.php` yang di-include di `<head>` tiap halaman publik, isinya: title, meta description, OpenGraph (og:title, og:image, og:description), Twitter Card — nilai-nilainya dioper dari masing-masing view/controller, bukan statis.
* Tambahan:
  * `og:image` berupa URL absolut (fallback `public/images/og-default.jpg`, 1200×630) supaya pratinjau WhatsApp tampil.
  * `og:type=article` + `article:published_time` di halaman detail.
  * `<link rel="canonical">`.
  * `noindex` untuk pencarian & 404.
  * Data terstruktur JSON-LD: `Organization` (layout), `NewsArticle` + `BreadcrumbList` (detail artikel).
* Sitemap XML dinamis (requirement 6.2): pakai package `spatie/laravel-sitemap`, generate ulang via scheduled command tiap ada artikel baru published, bukan di-generate manual.

---

## 9. Spesifikasi Redesign UI/UX (Mengikuti `demo/`)

> Rujukan visual: `demo/*.html`. Rujukan perilaku & data: bagian 4 dan 8. Bila demo berbeda dengan bagian ini, **bagian ini yang berlaku** — perbedaannya disengaja demi pengguna.

### 9.1 Tujuan, Cakupan & Prinsip

**Tujuan:** halaman publik Laravel tampil & tersusun seperti demo, semua fungsi dinamis tetap jalan, Light Mode dirancang tersendiri (demo hanya punya dark), dan masalah UX pengguna nyata diperbaiki tanpa mengubah identitas visual demo.

**Cakupan tahap ini:**
* **Termasuk:** shell publik, beranda, detail artikel, kategori induk/anak, pencarian, 404 + 419/429/500, redaksi, pasang iklan, kirim tulisan; sistem warna dua tema; komponen Blade (8.7). Perubahan controller hanya untuk menyiapkan data yang **sudah ada** bagi layout demo.
* **Tidak termasuk:** panel admin & login, tabel/kolom baru, perubahan alur bisnis (bagian 10).

**Prinsip:**
1. **Ambil 1:1 dari demo:**
   * Kanvas hitam netral dan kartu bergaris tipis (kedalaman dari border, bukan bayangan).
   * Satu aksen amber dan Plus Jakarta Sans.
   * Label uppercase `tracking-widest` dan judul seksi dengan bar amber kiri.
   * Layout 2/3 + sidebar 1/3, pola kartu, breadcrumb uppercase, pagination kotak amber.
   * Panel form, stat tile, dan pill "Pasang Iklan".
2. **Ubah dengan sengaja demi pengguna:**
   * Kontras teks abu dinaikkan; teks yang harus dibaca ≥12px; tap target ≥44px.
   * Dropdown Banten bisa diklik/keyboard; ticker tidak ikut sticky; focus ring terlihat.
   * Label "Iklan" di luar materi iklan.
   * Isi artikel 17–18px dengan lebar ≤70 karakter.
   * Kartu ringkas di HP; ikon SVG, bukan emoji; tombol share yang benar-benar berfungsi.
3. **Fungsi yang wajib tetap ada:**
   * Semua route name.
   * CSRF, `old()`, flash, `$errors`.
   * Pagination + query string; parameter pencarian `q`/`category_id`.
   * View counter per sesi dan impression iklan.
   * Byline staf vs tamu; status 404 asli; throttle.
   * Tes fitur yang ada harus lulus **tanpa diubah**.
4. **Satu sumber warna:** semua warna lewat token (9.3), sehingga setiap komponen otomatis punya varian gelap & terang.

### 9.2 Persona & Jaminan Desain

| Pengguna | Situasi | Yang wajib dijamin desain |
| :--- | :--- | :--- |
| **Pembaca HP** (mayoritas) | Datang dari tautan WhatsApp/Google **langsung ke artikel**. Android kelas menengah ke bawah, 4G putus-putus, kuota terbatas, satu tangan, sering di luar ruangan | CSS hasil build; font di-host sendiri; judul + awal gambar terlihat di layar pertama; isi 17px; tombol ≥44px; share WhatsApp di atas & setelah artikel; iklan tidak menggeser layout; pindah ke Light Mode satu ketukan saat terik |
| **Pembaca desktop/setia** | Membuka beranda beberapa kali sehari, memindai judul | Hirarki hero → terbaru → terpopuler; waktu relatif ("2 jam lalu"); artikel ≤70 karakter per baris dengan sidebar sticky; pencarian dengan filter & kata kunci yang disorot |
| **Pembaca lanjut usia / low vision / keyboard / sensitif gerak** | Porsi besar pembaca berita | Kontras teks ≥4,5:1 di kedua tema; teks ≥12px; fokus terlihat; dropdown & drawer bisa lewat keyboard; zoom 200% tanpa terpotong; animasi diam bila `prefers-reduced-motion` |
| **Pembaca daerah Banten** | Mencari berita wilayahnya | Banten bisa diklik/tap di nav; chip wilayah di beranda & kategori; breadcrumb "Banten / Cilegon Today" |
| **Penulis tamu** | Mahasiswa/dosen/aktivis/ASN; bukan orang teknis; cemas tulisannya sampai atau tidak; menulis di Word lalu menempel; mungkin dari HP | Alur review dijelaskan sebelum menulis; isian tidak hilang (error validasi, sesi habis 419, batas kirim 429); konfirmasi yang jelas; label penulis jelas saat tayang |
| **Pengiklan lokal** | UMKM/marketing di Banten, terbiasa WhatsApp | Slot dengan pratinjau ukuran; cara minta harga; tombol WhatsApp langsung; tidak ada pesan "terkirim" palsu |
| **Pembaca skeptis / Google News / Dewan Pers** | Menilai kredibilitas media | Merek konsisten B.Today; redaksi & alamat nyata; Pedoman Media Siber lengkap; label "Iklan" & "Kontributor"; waktu terbit & diperbarui |

**Perjalanan kunci untuk menguji desain:**
1. **Pembaca HP dari WhatsApp:**
   * Tap tautan, lalu halaman tampil tanpa kedipan gaya/tema.
   * Header 64px → breadcrumb → judul → byline + share → gambar.
   * Membaca isi; iklan native setelah paragraf ke-3 sudah dipesan ruangnya.
   * Selesai membaca: share kedua, lalu Artikel Terkait.
   * Pindah wilayah lewat breadcrumb atau drawer.
2. **Penulis tamu:**
   * Menemukan "Kirim Tulisan", lalu membaca alur review & ketentuan.
   * Menempel naskah dari Word.
   * Salah isi email: pesan muncul di field itu, isian lain tetap.
   * Koneksi putus: draf bisa dipulihkan.
   * Sukses: form diganti panel konfirmasi.
3. **Pengiklan:**
   * Melihat tombol WhatsApp di atas halaman.
   * Membaca kartu slot, lalu "Pilih slot ini".
   * Form terisi otomatis; kirim membuka WhatsApp/email dengan pesan tersusun.

### 9.3 Token Desain (Dark & Light)

Didefinisikan sekali di `resources/css/app.css` (`:root` = light, `.dark` = dark) dan dipakai sebagai class semantik (`bg-canvas`, `text-fg-muted`, `border-line`, dst.). Rasio kontras dihitung menurut WCAG 2.2.

| Token | Dark (demo, dikoreksi) | Light | Dipakai untuk |
| :--- | :--- | :--- | :--- |
| `canvas` | `#050505` | `#fafaf9` | Latar halaman |
| `surface` | `#0a0a0a` | `#ffffff` | Kartu, panel |
| `raised` | gray-900 `#101828` | gray-100 `#f3f4f6` | Input, chip, blockquote |
| `line` | gray-800 /60 | gray-200 | Garis tipis (dekoratif) |
| `line-strong` | gray-700 | gray-300 | Border chip/tag |
| `control` | gray-500 `#6a7282` (3,7:1) | gray-500 (4,8:1) | Border input/select (komponen UI ≥3:1) |
| `fg-strong` | `#ffffff` | `#0a0a0a` | Judul |
| `fg` | gray-300 `#d1d5dc` (13,5:1) | gray-800 `#1e2939` (14,7:1) | Isi artikel, teks utama |
| `fg-muted` | gray-400 `#99a1af` (7,6:1) | gray-600 `#4a5565` (7,2:1) | Excerpt, lead, meta |
| `fg-subtle` | `#7d8595` (5,3:1) | gray-500 `#6a7282` (4,6:1; jangan di atas `raised`) | Tanggal, keterangan kecil |
| `brand` | amber-400 `#ffb900` | amber-400 | Tombol primer, badge, halaman aktif |
| `brand-hover` | amber-300 | amber-500 | Hover tombol primer |
| `on-brand` | `#000000` (12,2:1) | `#000000` | Teks di atas amber |
| `accent` | amber-400 (11,5:1) | amber-700 `#bb4d00` (5,0:1) | Link, eyebrow, hover judul |
| `focus` | amber-400 | amber-700 | Ring fokus 2px (`focus-visible`) |
| `footer` | `#000000` | gray-100 (teks memakai `fg-muted`) | Footer |

**Catatan:**
* Demo memakai gray-500 (≈4,1:1) dan gray-600 (≈2,6:1) untuk excerpt & tanggal. Keduanya di bawah standar 4,5:1, sehingga diganti `fg-muted`/`fg-subtle`.
* Amber-400 sebagai **teks** di Light Mode hanya ≈1,7:1, jadi teks aksen di Light Mode memakai amber-700. Amber-400 tetap dipakai sebagai **isi tombol/badge** dengan teks hitam di kedua mode.
* Pill "Pasang Iklan": dark `putih + teks hitam` (demo), light `#0a0a0a + teks putih`; hover keduanya amber-400 + teks hitam.
* Angka 01–05 Terpopuler memakai amber-400 opasitas 50% (3,5:1, teks besar), bukan 30% seperti demo (1,9:1).
* Header kaca: dark `black/90`, light `white/90`, keduanya `backdrop-blur-md`.
* Chip kategori di atas foto tetap kaca hitam (`black/70` + teks putih) di kedua mode.
* Pesan sukses/error: dark `teks *-300 + latar *-500/10`, light `teks *-800 + latar *-50`.

### 9.4 Tipografi & Aksesibilitas

**Tipografi** (skala demo dengan batas bawah):
* Nav & label chrome: 12px uppercase bold.
* Meta: 12–13px.
* Judul kartu: 16–18px.
* H1 artikel: 28px (HP) → 36px → 48px (desktop).
* Isi artikel: 17px (HP) / 18px (desktop), line-height ±1,75, lebar ≤70 karakter.
* Uppercase hanya untuk label pendek.

**Aturan aksesibilitas wajib:**
1. **Navigasi & landmark:** skip link "Langsung ke konten"; landmark `header`/`nav`/`main`/`footer`/`article`; satu `h1` per halaman; urutan heading tidak melompat.
2. **Fokus & target sentuh:** ring fokus terlihat di semua elemen interaktif; tap target ≥44px untuk tombol header, drawer, share.
3. **Menu:** dropdown & drawer memakai `aria-expanded`/`aria-controls`, bisa dibuka dengan Enter/Spasi, ditutup dengan Esc.
4. **Gerak:** animasi (ticker, 404) hanya berjalan bila tidak `prefers-reduced-motion`.
5. **Tema:** toggle tema memakai `aria-pressed`. Skrip tema dijalankan sebelum render, sehingga tidak ada kedipan tema salah.
6. **Form:** label terhubung ke field; petunjuk & error memakai `aria-describedby` + `aria-invalid`; input 16px di HP agar iOS tidak zoom.
7. **Gambar & bahasa:** gambar punya `alt`; ikon dekoratif `aria-hidden`; `lang="id"`.
8. **Zoom:** 200% tanpa konten terpotong. Tinggi gambar memakai rasio, bukan tinggi tetap.

### 9.5 Shell Publik (`layouts/app.blade.php`)

1. **Skip link.**
2. **Header sticky** (±64px, kaca demo):
   * Logo mark kotak amber "B" + wordmark "B.TODAY" dengan titik amber; wordmark tetap tampil di HP.
   * Nav kategori (≥1280px): 12px uppercase; urutan & label pendek dari `config/portal.php`; item aktif ditandai.
   * Dropdown Banten ("Semua Banten" + 5 wilayah).
   * Tombol cari, toggle tema, pill "Pasang Iklan" (≥768px), hamburger (<1280px).
3. **Drawer HP** (panel dorong, maks. 70% tinggi layar, bisa di-scroll):
   * Semua kategori + akordeon Banten.
   * Cari, Kirim Tulisan, Pasang Iklan, Redaksi.
   * Baris ≥44px.
4. **Ticker trending:** beranda saja, di bawah header (lihat 4A.2).
5. **Footer 4 kolom:**
   * Merek + tagline.
   * Navigasi kategori.
   * Perusahaan: Redaksi, Pedoman Media Siber, Kirim Tulisan, Pasang Iklan.
   * Kontak: email redaksi & iklan, WhatsApp, alamat.

   Data kontak diambil dari `config/portal.php`, sumber yang sama dengan halaman Redaksi, sehingga tidak ada lagi data yang saling bertentangan. Tanpa link `#`.
6. **Data shell:** kategori navigasi lewat view composer (di-cache), bukan query di dalam layout.

### 9.6 Spesifikasi per Halaman

| Halaman | Susunan (mengikuti demo) | Perbaikan UX yang disengaja |
| :--- | :--- | :--- |
| **Beranda** | Leaderboard → hero split 2/3 + 1/3 → kolom utama: Berita Terbaru (6 kartu, 2 kolom) + **Banten Today** (chip wilayah + 4 kartu horizontal) → sidebar: Terpopuler 01–05, iklan 300×250, kartu Kirim Tulisan, Topik Pilihan | Di HP kartu 3–6 berbentuk horizontal ringkas dan Terpopuler muncul tepat setelah Berita Terbaru; artikel tidak dobel antar-seksi; H1 halaman = nama situs |
| **Detail artikel** | Grid 2/3 + sidebar: breadcrumb → judul → byline + share → foto → isi (iklan native setelah paragraf ke-3) → tag → share kedua → Artikel Terkait; sidebar sticky: iklan 300×250, Baca Juga, Topik Terkait | Byline tamu "Kontributor · instansi" + catatan "Tulisan kiriman pembaca"; "Editor: …"; waktu terbit WIB + "Diperbarui"; share WhatsApp/X/Facebook/salin tautan/share bawaan HP; tanpa leaderboard di atas agar judul langsung terlihat |
| **Kategori induk & anak** | Header tanpa kotak (breadcrumb berjenjang, H1 uppercase, deskripsi, "N artikel · diperbarui …") → chip wilayah → leaderboard → grid 1/2/3 kolom → pagination | Halaman Banten memuat artikel 5 wilayah; halaman wilayah menampilkan chip wilayah lain; 12 artikel per halaman agar grid selalu penuh; pagination ringkas di HP; state kosong dengan arahan |
| **Pencarian** | Judul tengah → kotak cari besar → filter Kategori / Rentang Waktu / Urutkan → info jumlah hasil → daftar hasil horizontal → pagination | Kata kunci disorot; filter aktif bisa dihapus; filter dilipat di HP; "Semua Banten" mencakup wilayah; tanpa kata kunci tampil "Berita terbaru"; `noindex` |
| **404 & error** | Angka 404 besar → judul → tombol Beranda & Cari → "Mungkin Anda Tertarik" (4 artikel populer) | Animasi diam bila reduced-motion; kotak cari terisi dari URL lama; halaman 419/429/500 bergaya sama |
| **Redaksi** | Header "Tentang Kami / Redaksi." → kartu Susunan Dewan Redaksi → Alamat Kantor → Pedoman Pemberitaan Media Siber (teks resmi lengkap, bernomor) | Data dari `config/portal.php`; email/telepon/WhatsApp bisa diklik; anchor `#pedoman-media-siber` |
| **Pasang Iklan** | Hero + tombol "Hubungi via WhatsApp" & "Isi Formulir" → angka nyata → kartu slot (pratinjau ukuran, spesifikasi, "Pilih slot ini") → "Mengapa beriklan" → form kontak | Form mengirim lewat WhatsApp/email dengan pesan tersusun (tanpa pesan "terkirim" palsu); janji waktu balas; cara meminta rate card |
| **Kirim Tulisan** (tanpa demo) | Header "Kontribusi Pembaca" → grid 2/3 form + 1/3 aside sticky (Alur Review 01–04, Ketentuan, Privasi); form dikelompokkan "Data Penulis" & "Naskah" | Error per field + ringkasan; kategori bisa memilih wilayah Banten; penghitung kata; tombol nonaktif saat mengirim; panel konfirmasi menggantikan form setelah sukses; draf otomatis + "Pulihkan draf" |

### 9.7 Yang Tidak Disalin dari Demo

* **Identitas fiktif:**
  * Merek & domain **Nexus News / nexusnews.id / PT Nexus Media Group**, termasuk dateline "JAKARTA, NEXUS".
  * Alamat Jakarta, bio & penghargaan fiktif tim redaksi.
* **Klaim yang tidak didukung data:** "2.5M+ pageviews", "850K pembaca", "dashboard real-time" di Pasang Iklan.
* **Elemen tidak berfungsi:**
  * Link `#` (Privacy Policy, Terms, pagination).
  * Tombol share tanpa fungsi.
  * Form pencarian & pengiklan yang tidak mengirim apa pun.
* **Teks/ikon placeholder:**
  * Placeholder `[GAMBAR]`, `[IKLAN 728×90]`.
  * Label "Sponsored", diganti "Iklan".
  * Emoji sebagai ikon (🔍 ⚡ 📰).

### 9.8 Kriteria Penerimaan

1. **Kesesuaian visual:** setiap halaman publik, dibandingkan berdampingan dengan file `demo/` padanannya, memiliki susunan, warna, dan tipografi yang sama, kecuali perbedaan yang disengaja di 9.1 poin 2.
2. **Tema:** kedua tema lolos kontras token 9.3; pilihan tema tersimpan setelah reload dan tidak ada kedipan tema salah.
3. **Keyboard:** penggunaan keyboard saja bisa menjangkau skip link, seluruh nav termasuk wilayah Banten, drawer, dan semua form.
4. **Performa & SEO:** Lighthouse mobile (Performance, Accessibility, SEO) ≥90; tidak ada pergeseran layout di halaman beriklan.
5. **Lebar layar:** tampilan benar di 360px, 768px, 1280px, 1440px.
6. **Tes:** `php artisan test` lulus tanpa mengubah tes yang ada, ditambah tes baru untuk filter pencarian (kategori induk mencakup anak, rentang waktu), label "Kontributor", dan 404 yang tetap berstatus 404.
7. **Admin & login:** tampilannya tidak berubah dibanding sebelum redesign.

---

## 10. Backlog Temuan (Setelah Tahap Redesign)

Dicatat dari analisis tahap redesign. Tidak dikerjakan di tahap 7.1, diurutkan kira-kira menurut dampaknya ke pengguna.

### 10.1 Bug Fungsional

| # | Masalah | Lokasi |
| :--- | :--- | :--- |
| 1 | Memilih status "Terbit" di form edit admin memicu error 500 | `admin/articles/edit.blade.php` → guard `Article::saving` |
| 2 | Tombol Approve muncul di artikel milik sendiri dan bisa dipakai dari status apa pun | `admin/articles/index.blade.php`, `Admin/ArticleController@approve` |
| 3 | Menyetujui kiriman publik langsung menayangkan artikel, padahal 4B.5 mewajibkan Alur Review | `Admin/GuestSubmissionController@approve` |
| 4 | Menghapus kategori ikut menghapus permanen semua artikelnya (melewati soft delete) | migration `articles.category_id` `cascadeOnDelete` |
| 5 | HTMLPurifier (konfigurasi default) membuang h2/h3/blockquote dari isi artikel | belum ada `config/purifier.php` |
| 6 | Locale & zona waktu aplikasi masih `en`/UTC; pesan validasi berbahasa Inggris | `.env`, `config/app.php` |
| 7 | Klik iklan tidak pernah dihitung (`clicks_count` selalu 0) | perlu route pengalih penghitung klik |
| 8 | Admin tidak menampilkan error validasi; form iklan/kategori tidak menyimpan isian lama | `layouts/admin.blade.php` |
| 9 | Kelima admin hasil seeder berbagi password yang sama (bertentangan dengan 8.3) | `DatabaseSeeder` |
| 10 | Panel pratinjau/tolak di antrean kiriman berkedip terbuka saat dimuat; teks `$\rightarrow$` mentah | `admin/submissions/index.blade.php` |

### 10.2 Redesign Admin & Login

Mengikuti bahasa visual demo, mode gelap saja, utamakan efisiensi:
* **Kerangka:** header kaca dengan logo mark, sidebar dengan ikon SVG dan bar amber untuk item aktif, badge "Perlu Review" & "Kiriman Publik".
* **Dashboard:** "Perlu tindakan Anda" berisi artikel menunggu review dari admin lain dan kiriman tertua beserta umurnya.
* **Alur review:**
  * Pratinjau artikel memakai template publik.
  * Tombol approve nonaktif + alasan untuk artikel sendiri.
  * Status hanya diubah lewat tombol aksi.
* **Kiriman publik:** modal tolak dengan alasan; kiriman disetujui menjadi `pending_review` lalu membuka editor.
* **Konfirmasi:** konfirmasi hapus yang menyebut item dan akibatnya.
* **Editor:** WYSIWYG (4B.3); slug terlihat & bisa diedit; pratinjau sampul.
* **HP:** tabel berubah menjadi kartu.
* **Login:** tampilkan sandi, pesan throttle dengan hitung mundur, `noindex`.

### 10.3 Fitur & Konten yang Menyusul

* **Kirim Tulisan:** CAPTCHA (4D.3a), no. HP, upload gambar pendukung dengan validasi & re-encode (4D.2).
* **Pasang Iklan:** form pengiklan disimpan ke database + inbox di admin (data pengiklan masuk cakupan UU PDP, 4D.10).
* **Iklan:** jadwal tayang (4C.2) dan materi banner versi HP (320×100).
* **Halaman Kebijakan Privasi** (4D.10), lalu ditautkan dari footer.
* **Data tampilan:** kolom deskripsi kategori & caption foto artikel.
* **Konten dinamis:** Terpopuler berbasis 7 hari terakhir; thumbnail multi-ukuran (8.9); pencarian full-text (6.1).

### 10.4 Konten yang Perlu Disediakan Pemilik

Nama & jabatan asli dewan redaksi, nama badan hukum, alamat kantor, email redaksi & iklan, nomor WhatsApp/telepon, kebijakan harga iklan (rate card), dan teks Kebijakan Privasi. Sampai tersedia, `config/portal.php` diisi data sementara yang ditandai jelas.
