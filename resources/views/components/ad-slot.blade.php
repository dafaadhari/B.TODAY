@php($ratio = $spec['ratio'] ?? 'auto')

<aside {{ $attributes->class('w-full') }} aria-label="Iklan">
    @if ($ad)
        <span class="mb-1.5 block text-xs font-bold tracking-widest text-fg-subtle uppercase">Iklan</span>
        <a href="{{ $ad->redirect_url }}" target="_blank" rel="sponsored noopener"
            class="block overflow-hidden rounded-xl border border-line bg-raised transition-colors hover:border-brand/50"
            style="aspect-ratio: {{ $ratio }}">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="size-full object-cover" loading="lazy" decoding="async">
        </a>
    @else
        <span class="mb-1.5 block text-xs font-bold tracking-widest text-fg-subtle uppercase">Ruang Iklan</span>
        <a href="{{ route('pasang-iklan') }}"
            class="group flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-line-strong bg-surface p-6 text-center transition-colors hover:border-brand"
            style="aspect-ratio: {{ $ratio }}">
            <span class="text-xs font-bold tracking-widest text-fg-subtle uppercase">{{ $spec['size'] ?? '' }}</span>
            <span class="text-base font-extrabold text-fg-strong group-hover:text-accent">Pasang iklan di sini</span>
            <span class="text-sm text-fg-muted">Jangkau pembaca Banten &amp; nasional</span>
        </a>
    @endif
</aside>
