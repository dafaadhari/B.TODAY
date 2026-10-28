@php
    // $noun dioper dari ->links('vendor.pagination.admin', ['noun' => 'artikel']).
    $noun = $noun ?? 'data';
    $perPageOptions = \App\Http\Controllers\Controller::ADMIN_PER_PAGE_OPTIONS;
    $keptQuery = collect(request()->except(['per_halaman', 'page']))->filter(fn ($value) => is_string($value) && $value !== '');
    $arrow = 'relative inline-flex w-11 h-11 sm:w-9 sm:h-9 items-center justify-center rounded-lg border transition-colors';
    $arrowEnabled = 'border-gray-700 text-gray-300 hover:border-amber-500 hover:text-amber-400';
    $arrowDisabled = 'border-gray-800 text-gray-700 cursor-not-allowed';
@endphp

@if ($paginator->total() > 0)
    <nav role="navigation" aria-label="Navigasi halaman {{ $noun }}" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-gray-400 sm:justify-start">
            <p>
                Menampilkan <span class="font-bold text-gray-200">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                dari <span class="font-bold text-gray-200">{{ number_format($paginator->total(), 0, ',', '.') }}</span> {{ $noun }}
            </p>

            <form method="GET" x-data class="flex items-center gap-2">
                @foreach ($keptQuery as $name => $value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endforeach
                <label for="per-halaman-{{ Str::slug($noun) }}">Tampilkan</label>
                <select id="per-halaman-{{ Str::slug($noun) }}" name="per_halaman" @change="$el.form.submit()"
                    class="h-9 rounded-lg border border-gray-700 bg-gray-950 px-2 pr-7 text-xs text-gray-200 focus:border-amber-500 focus:outline-none">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected($paginator->perPage() === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="text-amber-400">Terapkan</button></noscript>
            </form>
        </div>

        @if ($paginator->hasPages())
            {{-- HP: ringkas "‹ Hal 1 dari 6 ›" dengan tombol 44px. --}}
            <div class="flex items-center justify-center gap-3 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="{{ $arrow }} {{ $arrowDisabled }}" aria-disabled="true">
                        <x-heroicon-o-chevron-left class="w-5 h-5" aria-hidden="true" /><span class="sr-only">Halaman sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $arrow }} {{ $arrowEnabled }}">
                        <x-heroicon-o-chevron-left class="w-5 h-5" aria-hidden="true" /><span class="sr-only">Halaman sebelumnya</span>
                    </a>
                @endif

                <span class="min-w-28 text-center text-sm font-bold text-gray-200">
                    Hal {{ $paginator->currentPage() }} <span class="font-normal text-gray-500">dari {{ $paginator->lastPage() }}</span>
                </span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $arrow }} {{ $arrowEnabled }}">
                        <x-heroicon-o-chevron-right class="w-5 h-5" aria-hidden="true" /><span class="sr-only">Halaman berikutnya</span>
                    </a>
                @else
                    <span class="{{ $arrow }} {{ $arrowDisabled }}" aria-disabled="true">
                        <x-heroicon-o-chevron-right class="w-5 h-5" aria-hidden="true" /><span class="sr-only">Halaman berikutnya</span>
                    </span>
                @endif
            </div>

            {{-- Tablet & desktop: nomor halaman. --}}
            <ul class="hidden items-center gap-1.5 sm:flex">
                <li>
                    @if ($paginator->onFirstPage())
                        <span class="{{ $arrow }} {{ $arrowDisabled }}" aria-disabled="true">
                            <x-heroicon-o-chevron-left class="w-4 h-4" aria-hidden="true" /><span class="sr-only">Halaman sebelumnya</span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $arrow }} {{ $arrowEnabled }}">
                            <x-heroicon-o-chevron-left class="w-4 h-4" aria-hidden="true" /><span class="sr-only">Halaman sebelumnya</span>
                        </a>
                    @endif
                </li>

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="px-1 text-xs text-gray-500" aria-hidden="true">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li>
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex w-9 h-9 items-center justify-center rounded-lg bg-amber-500 text-xs font-black text-gray-950">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex w-9 h-9 items-center justify-center rounded-lg border border-gray-700 text-xs font-bold text-gray-300 transition-colors hover:border-amber-500 hover:text-amber-400">
                                        <span class="sr-only">Halaman</span> {{ $page }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach

                <li>
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $arrow }} {{ $arrowEnabled }}">
                            <x-heroicon-o-chevron-right class="w-4 h-4" aria-hidden="true" /><span class="sr-only">Halaman berikutnya</span>
                        </a>
                    @else
                        <span class="{{ $arrow }} {{ $arrowDisabled }}" aria-disabled="true">
                            <x-heroicon-o-chevron-right class="w-4 h-4" aria-hidden="true" /><span class="sr-only">Halaman berikutnya</span>
                        </span>
                    @endif
                </li>
            </ul>
        @endif
    </nav>
@endif
