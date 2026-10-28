@props(['date', 'format' => 'short'])

@if ($date)
    @php
        $local = $date->copy()->locale('id')->timezone('Asia/Jakarta');
        $isRecent = $date->isPast() && $date->isAfter(now()->subDay());

        $label = match ($format) {
            'long' => $local->translatedFormat('l, j F Y').' · '.$local->format('H.i').' WIB',
            'relative' => $isRecent ? $local->diffForHumans() : $local->translatedFormat('j M Y'),
            'day' => $local->translatedFormat('j M'),
            default => $local->translatedFormat('j M Y'),
        };
    @endphp
    <time datetime="{{ $date->toIso8601String() }}" {{ $attributes }}>{{ $label }}</time>
@endif
