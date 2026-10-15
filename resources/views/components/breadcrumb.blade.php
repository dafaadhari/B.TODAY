@props(['items'])

{{-- $items: list<array{label: string, url?: string|null, class?: string}>; item terakhir dianggap halaman saat ini. --}}
<nav aria-label="Breadcrumb" {{ $attributes->class('mb-6') }}>
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-bold tracking-widest uppercase">
        @foreach ($items as $item)
            <li class="{{ $item['class'] ?? 'flex' }} min-w-0 items-center gap-2">
                @unless ($loop->first)
                    <span class="text-line-strong" aria-hidden="true">/</span>
                @endunless

                @if (! empty($item['url']) && ! $loop->last)
                    <a href="{{ $item['url'] }}" class="inline-flex min-h-8 items-center text-accent transition-colors hover:text-fg-strong">{{ $item['label'] }}</a>
                @else
                    <span @if ($loop->last) aria-current="page" @endif @class(["text-fg-muted", "block max-w-[40ch] truncate" => $loop->last])>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
