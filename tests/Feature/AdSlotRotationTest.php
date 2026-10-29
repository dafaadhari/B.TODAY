<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdSlotRotationTest extends TestCase
{
    use RefreshDatabase;

    private function createAd(string $title, string $placement = 'leaderboard', bool $isActive = true): Advertisement
    {
        return Advertisement::create([
            'title' => $title,
            'image_url' => 'https://example.com/'.str($title)->slug().'.jpg',
            'redirect_url' => 'https://example.com',
            'placement' => $placement,
            'is_active' => $isActive,
        ]);
    }

    public function test_every_active_ad_in_the_same_slot_gets_its_turn(): void
    {
        $first = $this->createAd('Banner Lama');
        $second = $this->createAd('Banner Baru');
        $this->createAd('Banner Nonaktif', isActive: false);

        $shownTitles = collect(range(1, 40))
            ->map(fn (): string => (string) $this->blade('<x-ad-slot placement="leaderboard" />'))
            ->map(fn (string $html): string => str_contains($html, 'Banner Lama') ? 'Banner Lama' : (str_contains($html, 'Banner Baru') ? 'Banner Baru' : 'lain'))
            ->unique()
            ->sort()
            ->values()
            ->all();

        $this->assertSame(['Banner Baru', 'Banner Lama'], $shownTitles);
        $this->assertSame(40, $first->fresh()->impressions_count + $second->fresh()->impressions_count);
    }

    public function test_admin_sees_which_ads_share_a_slot(): void
    {
        $this->createAd('Banner Lama');
        $this->createAd('Banner Baru');
        $this->createAd('Banner Samping', placement: 'sidebar');
        $admin = User::create(['name' => 'Admin Satu', 'email' => 'admin1@btoday.com', 'password' => bcrypt('password')]);

        $html = $this->actingAs($admin)->get(route('admin.ads.index'))->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'Bergiliran dengan 1 iklan lain'));
    }
}
