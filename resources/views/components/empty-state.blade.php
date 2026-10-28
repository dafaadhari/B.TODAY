@props(['title'])

<div {{ $attributes->class('rounded-2xl border border-line bg-surface px-6 py-14 text-center') }}>
    <svg class="mx-auto mb-4 size-10 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
    </svg>
    <h2 class="text-lg font-extrabold text-fg-strong">{{ $title }}</h2>
    @if ($slot->isNotEmpty())
        <div class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-fg-muted">{{ $slot }}</div>
    @endif
    @isset($actions)
        <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">{{ $actions }}</div>
    @endisset
</div>
