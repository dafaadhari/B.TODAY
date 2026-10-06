# Product Requirement Document (PRD)
## Proyek: Portal Berita Modern "B.Today" (sebelumnya nama kerja: Nexus News)

| Parameter | Detail Informasi |
| :--- | :--- |
| **Pengembang Utama** | Mochamad Dapa Adhari |
| **Nama Sistem** | B.Today Portal |
| **Domain** | B.Today.com |
| **Target Waktu** | 1 Minggu (Pengerjaan Efektif) |
| **Model Hosting** | Demo Statis (Vercel) → Production (Laravel + VPS/Shared Hosting) |

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

### Scope Out (Tidak Dikerjakan di Tahap Ini):
* Aplikasi mobile native (Android/iOS APK).
* Sistem pembayaran *paywall* / konten berlangganan otomatis.

---

## 2. Spesifikasi Teknis & Infrastruktur

### Tech Stack
* **Frontend:** HTML5, Tailwind CSS (dark mode strategy: `class`, bukan `media`, supaya toggle manual berfungsi independen dari pengaturan OS), Plus Jakarta Sans, CSS Glassmorphism.
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

Sistem terdiri dari **8 halaman utama** berbasis folder statis untuk tahap awal demo:

| Nama File | Fungsi & Komponen Utama |
| :--- | :--- |
| **`index.html`** | **Halaman Utama (Beranda):** Top bar, navbar *glassmorphism*, *trending ticker*, *hero article split layout*, grid berita terbaru, *sidebar* terpopuler, dan slot iklan *leaderboard*. |
| **`detail.html`** | **Halaman Detail Artikel:** *Breadcrumbs*, judul utama, metadata penulis & tanggal publikasi, foto utama, *text body*, *blockquote*, *social share*, *tags*, dan artikel terkait. Byline menampilkan nama penulis staf ATAU nama+instansi pengirim publik (jika artikel berasal dari kiriman publik). |
| **`kategori.html`** | **Halaman Indeks Kategori:** Header kategori, deskripsi topik, *grid card* artikel 3 kolom, dan *pagination*. |
| **`kirim-tulisan.html`** | **Formulir Kiriman Tulisan Publik:** Form tanpa login — nama, instansi, email, no. HP (opsional), judul, isi tulisan, upload gambar pendukung (opsional). Dilengkapi CAPTCHA. Setelah submit, status "menunggu review" ditampilkan ke pengirim. |
| **`pasang-iklan.html`** | **Media Kit & Info Iklan:** Informasi posisi slot iklan, spesifikasi dimensi banner, dan formulir kontak pengiklan. |
| **`search.html`** | **Hasil Pencarian:** Bilah pencarian kata kunci, filter tanggal/kategori, dan daftar hasil pencarian. |
| **`redaksi.html`** | **Struktur Redaksi & Pedoman:** Susunan dewan redaksi, alamat kantor, dan teks Pedoman Media Siber Dewan Pers (Syarat Google News/AdSense). |
| **`404.html`** | **Custom Error Page:** Penanganan tautan rusak dengan navigasi kembali ke beranda & rekomendasi berita populer. |

---

## 4. Kebutuhan Fungsional (Functional Requirements)

### A. Sisi Pembaca (Frontend)
1. **Navigasi Responsif:** *Header sticky* dengan menu hamburger yang dapat dibuka di perangkat seluler.
2. **Indikator Trending Topic:** Bilah *ticker* di bawah navigasi utama untuk topik-topik hangat.
3. **Pencatatan Populer Otomatis:** Sistem mencatat *views* setiap artikel secara *real-time* untuk menyusun daftar berita "Terpopuler".
4. **Optimasi Berbagi (Social Share):** Tombol bagi cepat ke WhatsApp dan Twitter dengan pratinjau gambar (*OpenGraph*) yang rapi.
5. **Theme Switcher (Light/Dark Mode):** Tombol toggle di header untuk berpindah antara Dark Mode (default/utama) dan Light Mode. Preferensi pembaca disimpan otomatis (localStorage) supaya tidak perlu diatur ulang di kunjungan berikutnya. Seluruh komponen (navbar, card artikel, slot iklan, blockquote, dsb.) wajib punya varian warna untuk kedua mode — bukan cuma invert warna otomatis, supaya kontras dan keterbacaan tetap terjaga di keduanya.
6. **Kirim Tulisan Publik:** Pembaca bisa mengirim tulisan tanpa perlu membuat akun — cukup isi nama, instansi, email, judul, dan isi tulisan di `kirim-tulisan.html`. Tulisan masuk ke antrean review admin, TIDAK langsung tayang. Pengirim menerima konfirmasi (di layar, opsional via email) bahwa tulisannya "menunggu review".

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

* `tailwind.config.js`: `darkMode: 'class'` (bukan `'media'`) supaya toggle manual light/dark berfungsi independen dari OS.
* `.env` production: `APP_DEBUG=false` — WAJIB dicek sebelum go-live (lihat requirement keamanan D di atas).
* Queue worker: siapkan kalau `views_count` nanti dipindah ke Redis + sync berkala (lihat catatan skema di bagian 5).


### 8.6 Struktur Blade Template

Konversi 8 file HTML statis (Hari 1) jadi view dinamis dengan layout terpisah untuk publik dan admin:

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

* Halaman admin (belum ada versi statisnya, baru dibangun Hari 4 langsung di Laravel): `admin/dashboard.blade.php`, `admin/articles/index.blade.php` + `create.blade.php` + `edit.blade.php`, `admin/submissions/index.blade.php` (antrean kiriman publik), `admin/ads/index.blade.php`, `admin/categories/index.blade.php`.

### 8.7 Blade Component yang Wajib Reusable

Jangan duplikasi markup — pecah jadi component dari awal supaya konsisten dan gampang di-maintain:

* `<x-article-card>` — dipakai di home, kategori, search, dan "artikel terkait".
* `<x-ad-slot placement="leaderboard|sidebar|in_article">` — query iklan aktif by placement, sekaligus catat `impressions_count` tiap kali render.
* `<x-theme-toggle>` — tombol switch light/dark, state dikelola Alpine.js + disimpan ke `localStorage`.
* `<x-category-nav>` — render navbar kategori; khusus item "Banten" render sebagai dropdown berisi `category->children`, item lain tampil flat.
* `<x-status-badge status="draft|pending_review|published|rejected">` — dipakai di admin/articles DAN admin/submissions supaya warna badge konsisten (hijau/kuning/merah).
* `<x-stat-card>` — kartu ringkasan angka di dashboard admin (Total/Published/Draft, dst — lihat requirement 4B.6).

### 8.8 Interaktivitas Frontend (JS)

* Pakai **Alpine.js** (ringan, langsung cocok dipasang di Blade tanpa build step kompleks, selaras dengan tech stack HTML+Tailwind yang sudah dipilih) untuk: toggle menu hamburger mobile, toggle theme light/dark, trending ticker (auto-scroll), validasi inline di form `kirim-tulisan`.
* JANGAN campur dengan framework JS lain (Vue/React) untuk bagian ini — di luar scope dan bikin build process tidak perlu rumit untuk kebutuhan yang sebenarnya ringan.
* WYSIWYG editor di admin (requirement 4B.3): gunakan TinyMCE atau Quill via CDN/npm — tapi sanitasi HTML di server (HTMLPurifier, requirement 8.4 poin 3) TETAP WAJIB jalan meski sudah ada WYSIWYG di client, karena request API/form bisa saja tidak lewat UI editor sama sekali.

### 8.9 Asset Pipeline

* Pakai Vite bawaan Laravel untuk compile Tailwind CSS (dengan `darkMode: 'class'`, lihat 8.5) dan Alpine.js.
* Font Plus Jakarta Sans: self-host via `fonts.bunny.net` atau Google Fonts dengan `font-display: swap`, hindari render-blocking.
* Gambar artikel: wajib `loading="lazy"` di luar viewport awal, dan generate ukuran thumbnail berbeda (pakai Intervention Image saat upload) untuk card vs halaman detail — jangan serve gambar ukuran penuh di card kecil.

### 8.10 SEO & Meta Tag (Blade Partial)

* Buat satu partial `partials/meta.blade.php` yang di-include di `<head>` tiap halaman publik, isinya: title, meta description, OpenGraph (og:title, og:image, og:description), Twitter Card — nilai-nilainya dioper dari masing-masing view/controller, bukan statis.
* Sitemap XML dinamis (requirement 6.2): pakai package `spatie/laravel-sitemap`, generate ulang via scheduled command tiap ada artikel baru published, bukan di-generate manual.
