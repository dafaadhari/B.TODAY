@if ($paginator->hasPages())
    @php
        $arrowClass = 'inline-flex size-11 items-center justify-center rounded-lg transition-colors';
        $arrowEnabled = 'text-fg-muted hover:bg-raised hover:text-fg-strong';
        $arrowDisabled = 'cursor-not-allowed text-line-strong';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman" class="mt-16 flex items-center justify-center border-t border-line pt-8">
        {{-- HP: ringkas "‹ 2 / 12 ›" agar deretan angka tidak meluap. --}}
        <div class="flex items-center gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="{{ $arrowClass }} {{ $arrowDisabled }}" aria-disabled="true">
                    <span class="sr-only">Halaman sebelumnya</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $arrowClass }} {{ $arrowEnabled }}">
                    <span class="sr-only">Halaman sebelumnya</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" /></svg>
                </a>
            @endif

            <span class="px-3 text-sm font-bold text-fg">
                Halaman {{ $paginator->currentPage() }} <span class="text-fg-subtle">dari {{ $paginator->lastPage() }}</span>
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $arrowClass }} {{ $arrowEnabled }}">
                    <span class="sr-only">Halaman berikutnya</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" /></svg>
                </a>
            @else
                <span class="{{ $arrowClass }} {{ $arrowDisabled }}" aria-disabled="true">
                    <span class="sr-only">Halaman berikutnya</span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" /></svg>
                </span>
            @endif
        </div>

        {{-- Tablet & desktop: kotak angka seperti demo/kategori.html. --}}
        <ul class="hidden items-center gap-2 sm:flex">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $arrowClass }} {{ $arrowDisabled }}" aria-disabled="true">
                        <span class="sr-only">Halaman sebelumnya</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" /></svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $arrowClass }} {{ $arrowEnabled }}">
                        <span class="sr-only">Halaman sebelumnya</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" /></svg>
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="px-2 text-fg-subtle" aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex size-11 items-center justify-center rounded-lg bg-brand font-bold text-on-brand">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex size-11 items-center justify-center rounded-lg font-bold text-fg-muted transition-colors hover:bg-raised hover:text-fg-strong">
                                    <span class="sr-only">Halaman</span> {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $arrowClass }} {{ $arrowEnabled }}">
                        <span class="sr-only">Halaman berikutnya</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" /></svg>
                    </a>
                @else
                    <span class="{{ $arrowClass }} {{ $arrowDisabled }}" aria-disabled="true">
                        <span class="sr-only">Halaman berikutnya</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" /></svg>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
