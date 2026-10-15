@props(['article'])

<div {{ $attributes->class('flex items-center gap-3') }}>
    <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-amber-400 to-amber-600 text-lg font-bold text-black" aria-hidden="true">
        {{ $article->byline_initials }}
    </span>
    <div class="min-w-0">
        <p class="text-sm font-bold text-fg-strong">
            {{ $article->byline_name }}
            @if ($article->is_guest_contribution)
                <span class="ml-1 font-semibold text-accent">{{ $article->byline_label }}</span>
            @endif
        </p>
        <p class="text-xs leading-relaxed font-medium text-fg-subtle">
            @if ($article->reviewer)
                Editor: {{ $article->reviewer->name }} &bull;
            @endif
            <x-time :date="$article->published_at" format="long" />
            &bull; {{ $article->read_minutes }} menit baca
        </p>
    </div>
</div>
