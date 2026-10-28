@props(['url', 'title', 'label' => null])

@php
    $encodedUrl = urlencode($url);
    $buttonClass = 'inline-flex size-11 items-center justify-center rounded-full transition-opacity hover:opacity-85';
@endphp

<div x-data="share(@js($url), @js($title))" {{ $attributes->class('flex flex-wrap items-center gap-2') }}>
    @if ($label)
        <span class="mr-1 w-full text-xs font-bold tracking-widest text-fg-muted uppercase sm:w-auto">{{ $label }}</span>
    @endif

    <a href="https://api.whatsapp.com/send?text={{ urlencode($title.' '.$url) }}" target="_blank" rel="noopener"
        class="{{ $buttonClass }} bg-[#128C7E] text-white">
        <span class="sr-only">Bagikan ke WhatsApp</span>
        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.21-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35ZM12.04 21.5h-.01a9.45 9.45 0 0 1-4.82-1.32l-.35-.21-3.58.94.96-3.49-.23-.36a9.43 9.43 0 0 1-1.45-5.03c0-5.22 4.25-9.47 9.48-9.47a9.4 9.4 0 0 1 6.7 2.78 9.4 9.4 0 0 1 2.77 6.7c0 5.22-4.25 9.46-9.47 9.46Zm8.06-17.53A11.33 11.33 0 0 0 12.04.63C5.76.63.65 5.74.65 12.02c0 2 .52 3.96 1.52 5.69L.55 23.63l6.06-1.59a11.38 11.38 0 0 0 5.43 1.38h.01c6.28 0 11.39-5.11 11.39-11.39 0-3.04-1.19-5.9-3.34-8.06Z" />
        </svg>
    </a>

    <a href="https://twitter.com/intent/tweet?text={{ urlencode($title) }}&url={{ $encodedUrl }}" target="_blank" rel="noopener"
        class="{{ $buttonClass }} border border-line-strong bg-black text-white">
        <span class="sr-only">Bagikan ke X</span>
        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.66l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25H8.08l4.71 6.23 5.45-6.23Zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64Z" />
        </svg>
    </a>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener"
        class="{{ $buttonClass }} bg-[#1877F2] text-white">
        <span class="sr-only">Bagikan ke Facebook</span>
        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.04V9.41c0-3.02 1.8-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.5c-1.5 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.62 23.1 24 18.1 24 12.07Z" />
        </svg>
    </a>

    <button type="button" @click="copy()" class="{{ $buttonClass }} border border-line-strong bg-raised text-fg-strong">
        <span class="sr-only">Salin tautan</span>
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.69a4.5 4.5 0 0 1 1.24 7.24l-4.5 4.5a4.5 4.5 0 0 1-6.36-6.36l1.76-1.76m11.34-1.42 1.76-1.76a4.5 4.5 0 0 0-6.36-6.36l-4.5 4.5a4.5 4.5 0 0 0 1.24 7.24" />
        </svg>
    </button>

    <button type="button" x-show="canNativeShare" x-cloak @click="nativeShare()" class="{{ $buttonClass }} border border-line-strong bg-raised text-fg-strong">
        <span class="sr-only">Bagikan lewat aplikasi lain</span>
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.22 10.91a2.25 2.25 0 1 0 0 2.18m0-2.18c.18.32.28.7.28 1.09s-.1.77-.28 1.09m0-2.18 9.56-5.31m-9.56 7.49 9.56 5.31m0 0a2.25 2.25 0 1 0 3.94 2.19 2.25 2.25 0 0 0-3.94-2.19Zm0-12.8a2.25 2.25 0 1 0 3.93-2.19 2.25 2.25 0 0 0-3.93 2.19Z" />
        </svg>
    </button>

    <span x-show="copied" x-cloak role="status" class="text-xs font-semibold text-accent">Tautan disalin</span>
</div>
