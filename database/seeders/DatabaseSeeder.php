<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed 5 Super Admin Accounts
        $admins = [
            [
                'name' => 'Moch Dapa Adhari',
                'email' => 'dapa@btoday.com',
            ],
            [
                'name' => 'Redaksi Utama B.Today',
                'email' => 'redaksi@btoday.com',
            ],
            [
                'name' => 'Editor Banten Today',
                'email' => 'banten@btoday.com',
            ],
            [
                'name' => 'Editor Nasional',
                'email' => 'nasional@btoday.com',
            ],
            [
                'name' => 'Super Admin 5',
                'email' => 'admin5@btoday.com',
            ],
        ];

        $adminUsers = [];
        foreach ($admins as $index => $adminData) {
            $adminUsers[] = User::updateOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'super_admin',
                    'two_factor_enabled' => false,
                ]
            );
        }

        // 2. Seed Categories
        $flatCategories = [
            'Nasional' => 'nasional',
            'Jabodetabek' => 'jabodetabek',
            'Budaya' => 'budaya',
            'Travel & Kuliner' => 'travel-kuliner',
            'Hukum & Kriminal' => 'hukum-kriminal',
            'Olahraga' => 'olahraga',
            'Teknologi' => 'teknologi',
            'Otomotif' => 'otomotif',
        ];

        $catModels = [];
        foreach ($flatCategories as $name => $slug) {
            $catModels[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'parent_id' => null]
            );
        }

        // Kategori Induk Banten & Sub-kategori
        $bantenParent = Category::updateOrCreate(
            ['slug' => 'banten'],
            ['name' => 'Banten', 'parent_id' => null]
        );
        $catModels['banten'] = $bantenParent;

        $bantenChildren = [
            'Lebak Today' => 'lebak-today',
            'Cilegon Today' => 'cilegon-today',
            'Pandeglang Today' => 'pandeglang-today',
            'Serang Today' => 'serang-today',
            'Tangerang Today' => 'tangerang-today',
        ];

        foreach ($bantenChildren as $name => $slug) {
            $catModels[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'parent_id' => $bantenParent->id]
            );
        }

        // 3. Seed Tags
        $tagsData = ['Politik', 'Sepakbola', 'AI & Gadget', 'Wisata Banten', 'Infrastruktur', 'Pilkada 2026', 'Kuliner Nusantara'];
        $tagModels = [];
        foreach ($tagsData as $tagName) {
            $tagModels[] = Tag::updateOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
        }

        // 4. Seed Advertisements
        Advertisement::updateOrCreate(
            ['placement' => 'leaderboard'],
            [
                'title' => 'Banner Promo Utama B.Today 728x90',
                'image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=728&h=90&fit=crop',
                'redirect_url' => 'https://btoday.com/pasang-iklan',
                'is_active' => true,
                'impressions_count' => 1500,
                'clicks_count' => 120,
            ]
        );

        Advertisement::updateOrCreate(
            ['placement' => 'sidebar'],
            [
                'title' => 'Banner Partner Sponsorship 300x250',
                'image_url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=300&h=250&fit=crop',
                'redirect_url' => 'https://btoday.com/pasang-iklan',
                'is_active' => true,
                'impressions_count' => 850,
                'clicks_count' => 45,
            ]
        );

        Advertisement::updateOrCreate(
            ['placement' => 'in_article'],
            [
                'title' => 'Native Ad Inside Article',
                'image_url' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=600&h=120&fit=crop',
                'redirect_url' => 'https://btoday.com/pasang-iklan',
                'is_active' => true,
                'impressions_count' => 2100,
                'clicks_count' => 310,
            ]
        );

        // 5. Seed Sample Articles
        $sampleArticles = [
            [
                'title' => 'Tol Serang-Panimbang Seksi 2 Resmi Beroperasi, Dorong Pariwisata Banten Selatan',
                'slug' => 'tol-serang-panimbang-seksi-2-resmi-beroperasi',
                'category' => $catModels['serang-today'],
                'author' => $adminUsers[0],
                'reviewer' => $adminUsers[1],
                'views' => 1420,
                'thumbnail' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&h=450&fit=crop',
                'content' => '<p>Pembangunan infrastruktur di wilayah Banten terus menunjukkan progres signifikan. Seksi 2 Tol Serang-Panimbang kini resmi mulai dilintasi kendaraan, membuka akses lebih cepat menuju destinasi wisata unggulan di Lebak dan Pandeglang.</p><blockquote>"Aksesibilitas ini akan memangkas waktu tempuh dari Jakarta menuju Tanjung Lesung hingga 50 persen," ujar Kepala Dinas Perhubungan Banten.</blockquote><p>Para pelaku UMKM dan industri pariwisata menyambut baik pembukaan jalur ini karena diyakini akan meningkatkan kunjungan wisatawan pada akhir pekan.</p>',
            ],
            [
                'title' => 'Pelabuhan Merak Cilegon Catat Rekor Logistik Pertengahan Tahun 2026',
                'slug' => 'pelabuhan-merak-cilegon-catat-rekor-logistik-2026',
                'category' => $catModels['cilegon-today'],
                'author' => $adminUsers[2],
                'reviewer' => $adminUsers[0],
                'views' => 980,
                'thumbnail' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&h=450&fit=crop',
                'content' => '<p>Arus distribusi barang antar Pulau Jawa dan Sumatra via Pelabuhan Merak Cilegon mengalami peningkatan volume sebesar 18% dibandingkan tahun sebelumnya. Digitalisasi sistem ticketing dan penataan dermaga baru menjadi kunci kelancaran operasional.</p>',
            ],
            [
                'title' => 'Transformasi Digital UMKM Serang: Batik Banten Tembus Pasar Ekspor Eropa',
                'slug' => 'transformasi-digital-umkm-serang-batik-banten-ekspor',
                'category' => $catModels['serang-today'],
                'author' => null,
                'guest_name' => 'Ahmad Fauzi',
                'guest_instansi' => 'Pengrajin Batik Serang',
                'reviewer' => $adminUsers[1],
                'views' => 1850,
                'thumbnail' => 'https://images.unsplash.com/photo-1606744888344-493238951221?w=800&h=450&fit=crop',
                'content' => '<p>Batik Banten dengan motif khas Surosowan dan Datulaya makin memikat konsumen internasional. Lewat pendampingan pemasaran digital dan platform e-commerce, karya perajin Serang kini rutin dipasok ke pameran busana di Perancis dan Jerman.</p>',
            ],
            [
                'title' => 'Timnas Indonesia Siap Hadapi Laga Kualifikasi World Cup 2026',
                'slug' => 'timnas-indonesia-siap-hadapi-kualifikasi-world-cup-2026',
                'category' => $catModels['olahraga'],
                'author' => $adminUsers[3],
                'reviewer' => $adminUsers[0],
                'views' => 3200,
                'thumbnail' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=800&h=450&fit=crop',
                'content' => '<p>Skuad Garuda menggelar latihan perdana di Jakarta untuk persiapan laga krusial minggu ini. Pelatih optimis koordinasi lini belakang dan ketajaman penyerang muda akan memberikan hasil maksimal di hadapan puluhan ribu suporter.</p>',
            ],
            [
                'title' => 'Teknologi AI Generatif Terbaru Resmi Diluncurkan untuk Sektor Media',
                'slug' => 'teknologi-ai-generatif-terbaru-resmi-diluncurkan',
                'category' => $catModels['teknologi'],
                'author' => $adminUsers[0],
                'reviewer' => $adminUsers[1],
                'views' => 2100,
                'thumbnail' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&h=450&fit=crop',
                'content' => '<p>Inovasi kecerdasan buatan terus berkembang pesat. Platform AI terbaru kini mampu membantu jurnalis melakukan pemrosesan data cepat dan verifikasi fakta awal secara transparan.</p>',
            ],
        ];

        foreach ($sampleArticles as $art) {
            $article = Article::updateOrCreate(
                ['slug' => $art['slug']],
                [
                    'title' => $art['title'],
                    'category_id' => $art['category']->id,
                    'author_id' => isset($art['author']) ? $art['author']?->id : null,
                    'guest_author_name' => $art['guest_name'] ?? null,
                    'guest_author_instansi' => $art['guest_instansi'] ?? null,
                    'reviewed_by' => $art['reviewer']->id,
                    'reviewed_at' => now(),
                    'content' => $art['content'],
                    'thumbnail_path' => $art['thumbnail'],
                    'views_count' => $art['views'],
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );

            // Attach random tags
            $article->tags()->sync([$tagModels[0]->id, $tagModels[2]->id]);
        }

        // Seed comprehensive articles across all categories
        $this->call(DummyCategoryArticlesSeeder::class);
    }
}
