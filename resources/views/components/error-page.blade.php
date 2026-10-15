{{-- Layout error mandiri tanpa query database, supaya tetap tampil saat aplikasi/DB bermasalah. --}}
@props(['code', 'title'])
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.meta', ['title' => $title.' - '.config('portal.name'), 'robots' => 'noindex'])
</head>
<body class="flex min-h-screen flex-col font-sans">
    <header class="border-b border-line bg-glass">
        <div class="mx-auto flex h-16 max-w-7xl items-center px-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded bg-brand text-xl font-extrabold text-on-brand" aria-hidden="true">B</span>
                <span class="text-2xl font-extrabold tracking-tight text-fg-strong">B<span class="text-accent">.</span>TODAY</span>
            </a>
        </div>
    </header>

    <main id="konten" class="mx-auto flex w-full max-w-2xl flex-1 flex-col items-center justify-center px-4 py-16 text-center">
        <p class="mb-4 text-7xl leading-none font-extrabold text-brand md:text-8xl" aria-hidden="true">{{ $code }}</p>
        <h1 class="mb-4 text-2xl font-extrabold text-fg-strong md:text-3xl">{{ $title }}</h1>
        <div class="mb-8 max-w-md leading-relaxed text-fg-muted">{{ $slot }}</div>
        <div class="flex flex-col gap-3 sm:flex-row">
            {{ $actions ?? '' }}
            <a href="{{ url('/') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-brand px-6 py-3 text-sm font-bold tracking-widest text-on-brand uppercase hover:bg-brand-hover">Kembali ke Beranda</a>
        </div>
    </main>
</body>
</html>
