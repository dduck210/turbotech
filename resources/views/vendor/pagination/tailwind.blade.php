{{--
    Overrides Laravel's built-in `pagination::tailwind` view (this exact
    path is auto-registered as a higher-priority override — see
    ServiceProvider::loadViewsFrom) so every `{{ $x->links() }}` call in
    the app picks this up with no further wiring. Restyled with the
    ink-*/brand-* boutique tokens instead of Laravel's default gray/blue.
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Điều hướng phân trang" class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-ink-500">
            Hiển thị
            @if ($paginator->firstItem())
                <span class="font-medium text-ink-700">{{ $paginator->firstItem() }}</span>
                –
                <span class="font-medium text-ink-700">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            trong tổng số <span class="font-medium text-ink-700">{{ $paginator->total() }}</span> kết quả
        </p>

        <span class="inline-flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Trang trước">
                    <span class="inline-flex items-center rounded-md border border-ink-200 px-3 py-2 text-ink-300" aria-hidden="true">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước" class="inline-flex items-center rounded-md border border-ink-300 px-3 py-2 text-ink-600 transition-colors hover:border-brand-500 hover:text-brand-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </a>
            @endif

            <span class="hidden items-center gap-1 sm:inline-flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="px-2 text-sm text-ink-400">{{ $element }}</span>
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex min-w-9 items-center justify-center rounded-md bg-brand-600 px-3 py-2 text-sm font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Đến trang {{ $page }}" class="inline-flex min-w-9 items-center justify-center rounded-md border border-ink-300 px-3 py-2 text-sm text-ink-600 transition-colors hover:border-brand-500 hover:text-brand-600">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </span>

            <span class="px-2 text-sm text-ink-500 sm:hidden">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau" class="inline-flex items-center rounded-md border border-ink-300 px-3 py-2 text-ink-600 transition-colors hover:border-brand-500 hover:text-brand-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="Trang sau">
                    <span class="inline-flex items-center rounded-md border border-ink-200 px-3 py-2 text-ink-300" aria-hidden="true">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                </span>
            @endif
        </span>
    </nav>
@endif
