<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyCategoryArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $author = $users->first();
        // Comply with four-eyes principle: reviewer must be different from author
        $reviewer = $users->count() > 1 ? $users->skip(1)->first() : null;

        $tags = Tag::all();
        if ($tags->isEmpty()) {
            $defaultTags = ['Terkini', 'Ekonomi', 'Infrastruktur', 'Gaya Hidup', 'Kearifan Lokal'];
            foreach ($defaultTags as $tagName) {
                $tags->push(Tag::create(['name' => $tagName, 'slug' => Str::slug($tagName)]));
            }
        }

        $dummyArticles = [
            // 1. OTOMOTIF
            [
                'category_slug' => 'otomotif',
                'title' => 'Revolusi Kendaraan Listrik: Ekosistem Baterai dan Jaringan SPKLU di Jalur Banten Siap Beroperasi',
                'slug' => 'revolusi-kendaraan-listrik-ekosistem-spklu-banten',
                'thumbnail' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=800&h=450&fit=crop',
                'views' => 2450,
                'published_at' => now()->subHours(6),
                'content' => '<p>Perkembangan industri otomotif ramah lingkungan di Indonesia mencatatkan tonggak sejarah baru. Pembangunan jaringan stasiun pengisian kendaraan listrik umum (SPKLU) ultra-fast charging di sepanjang koridor tol Tangerang-Merak kini telah rampung dan siap melayani lonjakan pemudik serta pengguna harian mobil listrik.</p><blockquote>"Ketersediaan infrastruktur pengisian daya cepat setiap 30 kilometer memberikan rasa tenang bagi pemilik kendaraan listrik," ungkap Kepala Analis Transportasi Nasional.</blockquote><p>Selain infrastruktur, tren penurunan harga baterai generasi terbaru juga diproyeksikan akan membuat harga mobil listrik ramah lingkungan semakin terjangkau bagi kalangan keluarga muda.</p>',
            ],
            [
                'category_slug' => 'otomotif',
                'title' => 'Panduan Perawatan Mesin Turbo dan Sistem Pengereman Kendaraan Saat Menghadapi Cuaca Ekstrem',
                'slug' => 'panduan-perawatan-mesin-turbo-dan-pengereman-cuaca-ekstrem',
                'thumbnail' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=800&h=450&fit=crop',
                'views' => 1780,
                'published_at' => now()->subDays(2),
                'content' => '<p>Memasuki periode pancaroba dengan curah hujan tinggi, pengendara kendaraan roda empat maupun roda dua diimbau untuk meningkatkan kewaspadaan teknis. Komponen vital seperti sistem pengereman ABS, kelaikan ban, serta sirkulasi oli pada mesin berturbo memerlukan pengecekan berkala guna mencegah insiden di jalan basah.</p><p>Para mekanik berpengalaman merekomendasikan pergantian minyak rem setiap 20.000 km dan memastikan tekanan angin ban selalu berada pada standar pabrikan untuk daya cengkeram optimal.</p>',
            ],

            // 2. TRAVEL & KULINER
            [
                'category_slug' => 'travel-kuliner',
                'title' => 'Menelusuri Kelezatan Rabeg Khas Serang: Kuliner Daging Rempah Warisan Kesultanan Banten',
                'slug' => 'menelusuri-kelezatan-rabeg-khas-serang-warisan-kesultanan',
                'thumbnail' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=450&fit=crop',
                'views' => 3120,
                'published_at' => now()->subHours(14),
                'content' => '<p>Bagi penikmat kuliner tradisional Nusantara, hidangan Rabeg merupakan ikon tak tergantikan dari tanah Banten. Olahan daging kambing berpadu bumbu rempah aromatik seperti lada, biji pala, dan kayu manis menghadirkan sensasi gurih pedas manis yang khas.</p><blockquote>"Rabeg dulunya merupakan menu santapan para bangsawan Kesultanan Banten usai pulang dari tanah suci, kini dapat dinikmati semua kalangan di penjuru kota," tutur pengelola kedai legendaris di Serang.</blockquote><p>Ditemani nasi hangat dan emping melinjo renyah, kuliner ini senantiasa diburu wisatawan luar daerah setiap akhir pekan.</p>',
            ],
            [
                'category_slug' => 'travel-kuliner',
                'title' => 'Eksplorasi Pesona Pantai Sawarna: Surga Bahari Tersembunyi di Pesisir Selatan Banten',
                'slug' => 'eksplorasi-pesona-pantai-sawarna-surga-bahari-pesisir-selatan',
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=450&fit=crop',
                'views' => 2890,
                'published_at' => now()->subDays(3),
                'content' => '<p>Pantai Sawarna di Kabupaten Lebak kembali menjadi magnet utama pelancong lokal dan mancanegara. Gugusan tebing karang Tanjung Layar yang megah dipadu bentangan pasir putih luas menjadikannya destinasi wajib untuk berburu lanskap matahari terbenam.</p><p>Kini dengan akses jalan lintas selatan yang kian mulus, waktu tempuh dari Serang menuju Sawarna telah berkurang drastis, membangkitkan ekosistem homestay dan ekonomi masyarakat pesisir.</p>',
            ],

            // 3. NASIONAL
            [
                'category_slug' => 'nasional',
                'title' => 'Pemerintah Terbitkan Kebijakan Insentif Pajak Hijau untuk Memacu Investasi Energi Terbarukan',
                'slug' => 'pemerintah-terbitkan-insentif-pajak-hijau-energi-terbarukan',
                'thumbnail' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?w=800&h=450&fit=crop',
                'views' => 1950,
                'published_at' => now()->subHours(8),
                'content' => '<p>Kementerian Keuangan bersama Kementerian ESDM resmi mengumumkan paket regulasi insentif fiskal bagi korporasi yang mengalihkan sumber energinya ke pembangkit tenaga surya dan bayu. Langkah ini ditargetkan mampu memangkas emisi karbon nasional secara terukur.</p><blockquote>"Transformasi hijau bukan sekadar komitmen global, melainkan peluang penciptaan ratusan ribu lapangan kerja berkualitas di masa depan," tegas perwakilan kementerian.</blockquote><p>Para pelaku usaha menyambut positif kepastian regulasi ini dan bersiap meningkatkan alokasi belanja modal proyek ramah lingkungan.</p>',
            ],
            [
                'category_slug' => 'nasional',
                'title' => 'Proyek Jalur Kereta Logistik Antarmoda Siap Pangkas Biaya Distribusi Komoditas Nasional',
                'slug' => 'proyek-kereta-logistik-antarmoda-pangkas-biaya-distribusi',
                'thumbnail' => 'https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=800&h=450&fit=crop',
                'views' => 1430,
                'published_at' => now()->subDays(1),
                'content' => '<p>Pembangunan infrastruktur konektivitas logistik rel kereta api yang menghubungkan pelabuhan utama dengan kawasan industri strategis di Pulau Jawa memasuki tahap finalisasi. Jalur ganda ini diharapkan mampu mengurai kepadatan jalan raya nasional sekaligus mengurangi beban subsidi BBM logistik.</p>',
            ],

            // 4. JABODETABEK
            [
                'category_slug' => 'jabodetabek',
                'title' => 'Integrasi Tarif dan Rute Baru Transjabodetabek Perluas Mobilitas Jutaan Komuter Tiap Hari',
                'slug' => 'integrasi-tarif-dan-rute-baru-transjabodetabek-mobilitas-komuter',
                'thumbnail' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800&h=450&fit=crop',
                'views' => 2640,
                'published_at' => now()->subHours(18),
                'content' => '<p>Pemerintah daerah di kawasan aglomerasi Jabodetabek meluncurkan sistem pembayaran tiket terpadu berbasis kartu tunggal dan aplikasi seluler. Layanan terintegrasi ini mencakup armada bus raya terpadu (BRT), kereta rel listrik (KRL), dan moda raya terpadu (MRT).</p><p>Survei kepuasan publik menunjukkan penghematan waktu transit hingga 25 menit bagi warga pinggiran yang berkantor di pusat Jakarta setiap hari kerja.</p>',
            ],
            [
                'category_slug' => 'jabodetabek',
                'title' => 'Revitalisasi Ruang Terbuka Hijau dan Taman Kota Multifungsi Perkuat Resiliensi Iklim Perkotaan',
                'slug' => 'revitalisasi-ruang-terbuka-hijau-taman-kota-resiliensi-iklim',
                'thumbnail' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=800&h=450&fit=crop',
                'views' => 1290,
                'published_at' => now()->subDays(2),
                'content' => '<p>Sejumlah taman tematik ramah anak dan waduk resapan alami di Tangerang Selatan dan Jakarta Barat kini resmi dibuka untuk publik. Selain berfungsi sebagai paru-paru kota, ruang terbuka ini dirancang mampu menampung luapan air hujan lebat secara efektif.</p>',
            ],

            // 5. BUDAYA
            [
                'category_slug' => 'budaya',
                'title' => 'Menghayati Kearifan Ekologis Adat Baduy: Ajaran Leluhur Merawat Hutan Titipan',
                'slug' => 'menghayati-kearifan-ekologis-adat-baduy-merawat-hutan',
                'thumbnail' => 'https://images.unsplash.com/photo-1528728329032-2972f65dfb3f?w=800&h=450&fit=crop',
                'views' => 3890,
                'published_at' => now()->subHours(20),
                'content' => '<p>Di tengah modernisasi yang bergerak cepat, tatanan kehidupan masyarakat adat Kanekes (Baduy) di pedalaman Kabupaten Lebak tetap teguh memegang prinsip "Lojor teu meunang dipotong, pondok teu meunang disambung". Falsafah ini melarang perusakan bentang alam demi menjaga mata air dan hutan lindung.</p><blockquote>"Kearifan ini membuktikan bahwa perlindungan lingkungan hidup yang paling kokoh berakar pada nilai-nilai budaya dan spiritualitas," ungkap peneliti antropologi budaya.</blockquote><p>Hasil tenun Baduy dan madu hutan murni kini juga menjadi daya tarik budaya yang sangat diminati pengunjung edukatif.</p>',
            ],
            [
                'category_slug' => 'budaya',
                'title' => 'Regenerasi Seni Rampak Bedug Banten: Harmonisasi Ketukan Perkusi yang Memikat Dunia',
                'slug' => 'regenerasi-seni-rampak-bedug-banten-harmonisasi-perkusi',
                'thumbnail' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&h=450&fit=crop',
                'views' => 1650,
                'published_at' => now()->subDays(4),
                'content' => '<p>Kelompok kesenian muda di Banten terus menghidupkan tradisi Rampak Bedug dengan aransemen kolaboratif bersama instrumen kontemporer. Gerak tari teatrikal berpadu dinamika tabuhan serempak melahirkan pertunjukan megah yang kerap mewakili Indonesia di festival seni budaya mancanegara.</p>',
            ],

            // 6. HUKUM & KRIMINAL
            [
                'category_slug' => 'hukum-kriminal',
                'title' => 'Direktorat Siber Perketat Pengawasan Kejahatan Finansial Digital dan Modus Penipuan Online',
                'slug' => 'direktorat-siber-perketat-pengawasan-kejahatan-finansial-digital',
                'thumbnail' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800&h=450&fit=crop',
                'views' => 2200,
                'published_at' => now()->subHours(10),
                'content' => '<p>Aparat penegak hukum meningkatkan patroli siber berkala menyusul maraknya peredaran tautan phishing yang menyamar sebagai tagihan resmi dan undangan elektronik. Kolaborasi antara kepolisian, Otoritas Jasa Keuangan (OJK), dan operator seluler diperkuat guna memutus rekening penampung dana ilegal secara cepat.</p><p>Masyarakat diimbau agar tidak mudah membagikan kode OTP maupun mengunduh aplikasi tidak resmi yang disebarkan melalui pesan singkat.</p>',
            ],
            [
                'category_slug' => 'hukum-kriminal',
                'title' => 'Pendekatan Keadilan Restoratif Berhasil Tuntaskan Ratusan Perkara Ringan Secara Kekeluargaan',
                'slug' => 'pendekatan-keadilan-restoratif-tuntaskan-perkara-ringan',
                'thumbnail' => 'https://images.unsplash.com/photo-1453738773917-9c3eff1db985?w=800&h=450&fit=crop',
                'views' => 1340,
                'published_at' => now()->subDays(3),
                'content' => '<p>Kejaksaan dan kepolisian di wilayah hukum Banten mencatatkan peningkatan penyelesaian perkara hukum melalui mekanisme Restorative Justice. Pendekatan ini mengutamakan pemulihan keadilan bagi korban serta rekonsiliasi sosial tanpa harus berujung pada penahanan di lembaga pemasyarakatan.</p>',
            ],

            // 7. LEBAK TODAY (Subkategori Banten)
            [
                'category_slug' => 'lebak-today',
                'title' => 'Komoditas Kopi Robusta Pegunungan Kendeng Lebak Mulai Masuki Pasar Kafe Modern Ibu Kota',
                'slug' => 'kopi-robusta-pegunungan-kendeng-lebak-pasar-kafe-modern',
                'thumbnail' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&h=450&fit=crop',
                'views' => 2100,
                'published_at' => now()->subHours(5),
                'content' => '<p>Petani kopi di kawasan perbukitan Lebak selatan merasakan dampak positif dari teknik pascapanen modern seperti metode honey process dan natural anaerobic. Biji kopi lokal yang dulunya hanya dijual curah kini dikemas premium dan menjadi langganan jaringan kedai kopi di Jakarta.</p><blockquote>"Karakteristik acidity yang lembut dengan aroma cokelat karamel menjadikan kopi Lebak punya daya saing tinggi," ujar barista bersertifikat nasional.</blockquote>',
            ],
            [
                'category_slug' => 'lebak-today',
                'title' => 'Peningkatan Fasilitas Stasiun Rangkasbitung Pacu Kemudahan Akses Wisatawan ke Bumi Multatuli',
                'slug' => 'peningkatan-fasilitas-stasiun-rangkasbitung-wisata-multatuli',
                'thumbnail' => 'https://images.unsplash.com/photo-1519331379826-f10be5486c6f?w=800&h=450&fit=crop',
                'views' => 1490,
                'published_at' => now()->subDays(2),
                'content' => '<p>Penataan integrasi antarmoda di sekitar kawasan Museum Multatuli dan Stasiun Rangkasbitung menghadirkan wajah baru bagi pusat kota Kabupaten Lebak. Wisatawan kini dengan mudah melanjutkan perjalanan wisata sejarah menggunakan shuttle bus ramah difabel.</p>',
            ],

            // 8. PANDEGLANG TODAY (Subkategori Banten)
            [
                'category_slug' => 'pandeglang-today',
                'title' => 'Kabar Gembira Konservasi: Kelahiran Dua Anak Badak Jawa Terdeteksi Kamera Sensor Ujung Kulon',
                'slug' => 'kelahiran-dua-anak-badak-jawa-kamera-sensor-ujung-kulon',
                'thumbnail' => 'https://images.unsplash.com/photo-1534177616072-ef7dc120449d?w=800&h=450&fit=crop',
                'views' => 4210,
                'published_at' => now()->subHours(12),
                'content' => '<p>Balai Taman Nasional Ujung Kulon (TNUK) Pandeglang merilis rekaman video kamera jebak yang mengonfirmasi kelahiran dua anak Badak Jawa (Rhinoceros sondaicus) dalam kondisi sehat. Penemuan ini menjadi bukti nyata keberhasilan program perlindungan habitat dan patroli satwa terpadu di semenanjung terbarat Jawa.</p>',
            ],
            [
                'category_slug' => 'pandeglang-today',
                'title' => 'Panen Raya Padi Organik Petani Pandeglang Perkuat Ketahanan Pangan Lumbung Wilayah Banten',
                'slug' => 'panen-raya-padi-organik-petani-pandeglang-ketahanan-pangan',
                'thumbnail' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&h=450&fit=crop',
                'views' => 1820,
                'published_at' => now()->subDays(3),
                'content' => '<p>Hamparan sawah irigasi di wilayah Menes dan Cadasari mencatatkan hasil panen melimpah dengan produktivitas mencapai 7,2 ton gabah kering panen per hektare. Pupuk organik hayati mandiri yang dikembangkan kelompok tani terbukti menekan biaya operasional secara drastis.</p>',
            ],

            // 9. TANGERANG TODAY (Subkategori Banten)
            [
                'category_slug' => 'tangerang-today',
                'title' => 'Kawasan Pusat Data Ramah Lingkungan Berdaya Energi Terbarukan Tumbuh Pesat di Tangerang',
                'slug' => 'kawasan-pusat-data-ramah-lingkungan-energi-terbarukan-tangerang',
                'thumbnail' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=450&fit=crop',
                'views' => 2750,
                'published_at' => now()->subHours(4),
                'content' => '<p>Tangerang semakin memantapkan posisinya sebagai koridor teknologi dan infrastruktur digital terdepan di Indonesia. Tiga fasilitas hyperscale data center baru dengan sertifikasi efisiensi energi internasional kini resmi beroperasi untuk melayani kebutuhan cloud computing Asia Tenggara.</p>',
            ],
            [
                'category_slug' => 'tangerang-today',
                'title' => 'Digitalisasi Layanan Kesehatan RSUD Tangerang Pangkas Waktu Antre Pasien Hingga Setengah Jam',
                'slug' => 'digitalisasi-layanan-kesehatan-rsud-tangerang-pangkas-antrean',
                'thumbnail' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&h=450&fit=crop',
                'views' => 1630,
                'published_at' => now()->subDays(1),
                'content' => '<p>Sistem pendaftaran online terintegrasi rekam medis digital dan antrean apotek otomatis berhasil mengurai kepadatan di instalasi rawat jalan rumah sakit daerah. Pasien kini dapat menentukan jam konsultasi dokter spesialis langsung lewat ponsel pintar.</p>',
            ],

            // 10. OLAHRAGA (Tambahan)
            [
                'category_slug' => 'olahraga',
                'title' => 'Prestasi Membanggakan: Atlet Muda Panahan Banten Raih Gelar Juara Umum Kejurnas Junior 2026',
                'slug' => 'prestasi-atlet-muda-panahan-banten-juara-umum-kejurnas-2026',
                'thumbnail' => 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=800&h=450&fit=crop',
                'views' => 1980,
                'published_at' => now()->subHours(16),
                'content' => '<p>Kontingen panahan Provinsi Banten berhasil membawa pulang 5 medali emas dan 3 perak pada kejuaraan nasional pelajar di Jakarta. Ketangguhan mental bertanding di nomor recurve dan compound membuktikan keberhasilan pembinaan bibit atlet berjenjang.</p>',
            ],

            // 11. TEKNOLOGI (Tambahan)
            [
                'category_slug' => 'teknologi',
                'title' => 'Layanan Internet Satelit Orbit Rendah Berkecepatan Tinggi Mulai Jangkau Pulau-Pulau Terluar',
                'slug' => 'layanan-internet-satelit-orbit-rendah-jangkau-pulau-terluar',
                'thumbnail' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=450&fit=crop',
                'views' => 2340,
                'published_at' => now()->subDays(2),
                'content' => '<p>Kesenjangan akses informasi digital di kawasan pesisir dan kepulauan terpencil kini kian tertutup berkat teknologi koneksi satelit broadband LEO. Fasilitas puskesmas dan sekolah dasar di kepulauan kini dapat menikmati sambungan internet stabil hingga 150 Mbps.</p>',
            ],

            // 12. CILEGON TODAY (Tambahan)
            [
                'category_slug' => 'cilegon-today',
                'title' => 'Industri Manufaktur Cilegon Terapkan Teknologi Dekarbonisasi Pabrik Baja Hijau Modern',
                'slug' => 'industri-manufaktur-cilegon-dekarbonisasi-baja-hijau-modern',
                'thumbnail' => 'https://images.unsplash.com/photo-1504917599217-d4dc5ebe6122?w=800&h=450&fit=crop',
                'views' => 2050,
                'published_at' => now()->subHours(7),
                'content' => '<p>Kawasan industri strategis Kota Cilegon memelopori konversi teknologi peleburan baja berbasis hidrogen bersih menggantikan bahan bakar batubara. Inisiatif dekarbonisasi ini mendapat apresiasi luas dari konsorsium industri ramah lingkungan internasional.</p>',
            ],
        ];

        foreach ($dummyArticles as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();
            if (!$category) {
                continue;
            }

            $article = Article::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'author_id' => $author?->id,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'thumbnail_path' => $data['thumbnail'],
                    'reviewed_by' => $reviewer?->id,
                    'reviewed_at' => now(),
                    'views_count' => $data['views'],
                    'status' => 'published',
                    'published_at' => $data['published_at'],
                ]
            );

            // Sync tags
            if ($tags->count()) {
                $article->tags()->sync($tags->random(min(2, $tags->count()))->pluck('id'));
            }
        }
    }
}
