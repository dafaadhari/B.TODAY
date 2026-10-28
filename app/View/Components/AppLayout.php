<?php

namespace App\View\Components;

use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * @param  Collection<int, Article>|null  $trending  Artikel untuk ticker trending (hanya beranda).
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public string $type = 'website',
        public ?string $robots = null,
        public ?string $canonical = null,
        public ?Collection $trending = null,
    ) {}

    public function render(): View
    {
        return view('layouts.app');
    }
}
