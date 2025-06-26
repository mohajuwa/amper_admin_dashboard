{{-- Pagination Widget --}}
@props([
    'records',
    'showInfo' => true,
    'showLinks' => true,
    'wrapperClass' => 'row mt-3',
    'infoClass' => 'col-md-6 d-flex align-items-center',
    'linksClass' => 'col-md-6 d-flex justify-content-end'
])

@if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
    <div class="{{ $wrapperClass }}">
        @if($showInfo)
            <div class="{{ $infoClass }}">
                <div class="pagination-info">
                    <small class="text-muted">
                        عرض {{ $records->firstItem() }} إلى {{ $records->lastItem() }} من أصل {{ $records->total() }} نتيجة
                    </small>
                </div>
            </div>
        @endif
        
        @if($showLinks)
            <div class="{{ $linksClass }}">
                {{ $records->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endif