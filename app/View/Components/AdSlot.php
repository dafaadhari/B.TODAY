<?php

namespace App\View\Components;

use App\Models\Advertisement;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Slot iklan publik (PRD 4C.3 & 8.7): mengambil sendiri iklan aktif per
 * placement dan mencatat impression hanya saat slot benar-benar dirender.
 * Bila beberapa iklan aktif di placement yang sama, iklan dipilih acak tiap
 * render sehingga tayangannya bergiliran.
 */
class AdSlot extends Component
{
    public ?Advertisement $ad;

    /**
     * @var array{label?: string, size?: string, ratio?: string, position?: string, description?: string, max_file?: string}
     */
    public array $spec;

    public function __construct(public string $placement)
    {
        $this->spec = config("portal.ad_slots.{$placement}", []);

        $this->ad = Advertisement::query()
            ->where('placement', $placement)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();
    }

    /**
     * Slot kosong tidak dirender, kecuali sidebar yang menampilkan promo "Pasang iklan di sini".
     */
    public function shouldRender(): bool
    {
        return $this->ad !== null || $this->placement === 'sidebar';
    }

    public function render(): View
    {
        $this->ad?->increment('impressions_count');

        return view('components.ad-slot');
    }
}
