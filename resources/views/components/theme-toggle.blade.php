<button type="button" x-data @click="$store.theme.toggle()"
    :aria-pressed="$store.theme.dark.toString()"
    :title="$store.theme.dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'"
    {{ $attributes->class('inline-flex size-10 items-center justify-center rounded-lg text-fg-muted transition-colors hover:bg-raised hover:text-fg-strong min-[360px]:size-11') }}>
    <span class="sr-only">Mode gelap</span>
    {{-- Matahari tampil saat gelap (klik untuk terang), bulan tampil saat terang. --}}
    <svg class="hidden size-5 dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
    </svg>
    <svg class="size-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 0 1 8.646 3.646 9.003 9.003 0 0 0 12 21a9.003 9.003 0 0 0 8.354-5.646Z" />
    </svg>
</button>
