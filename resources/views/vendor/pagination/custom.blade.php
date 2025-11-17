@if ($paginator->hasPages())
    <nav style="display:flex;justify-content:center;align-items:center;gap:8px;margin-top:32px">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="padding:10px 16px;background:#f3f4f6;color:#9ca3af;border-radius:8px;cursor:not-allowed;pointer-events:none">
                ← Trước
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" 
               style="padding:10px 16px;background:#fff;color:#333;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;transition:all 0.2s;font-weight:500"
               onmouseover="this.style.background='#f9fafb';this.style.borderColor='#a435f0'"
               onmouseout="this.style.background='#fff';this.style.borderColor='#e5e7eb'">
                ← Trước
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div style="display:flex;gap:4px;align-items:center">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding:10px;color:#9ca3af">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding:10px 16px;background:#a435f0;color:white;border-radius:8px;font-weight:600;min-width:40px;text-align:center">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               style="padding:10px 16px;background:#fff;color:#333;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;transition:all 0.2s;min-width:40px;text-align:center;display:inline-block"
                               onmouseover="this.style.background='#f9fafb';this.style.borderColor='#a435f0'"
                               onmouseout="this.style.background='#fff';this.style.borderColor='#e5e7eb'">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               style="padding:10px 16px;background:#fff;color:#333;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;transition:all 0.2s;font-weight:500"
               onmouseover="this.style.background='#f9fafb';this.style.borderColor='#a435f0'"
               onmouseout="this.style.background='#fff';this.style.borderColor='#e5e7eb'">
                Sau →
            </a>
        @else
            <span style="padding:10px 16px;background:#f3f4f6;color:#9ca3af;border-radius:8px;cursor:not-allowed;pointer-events:none">
                Sau →
            </span>
        @endif
    </nav>
@endif

