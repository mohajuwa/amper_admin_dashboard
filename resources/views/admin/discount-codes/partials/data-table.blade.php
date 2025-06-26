{{-- resources/views/admin/discount-codes/partials/data-table.blade.php --}}
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">قائمة أكواد الخصم (المجموع: {{ $records->total() }})</h3>
    </div>
    <div id="table-container">
        <div class="card-body p-0">
            {{-- Desktop Table --}}
            @include('admin.discount-codes.partials.desktop-table', ['records' => $records])

            {{-- Mobile Cards --}}
            @include('admin.discount-codes.partials.mobile-cards', ['records' => $records])

            {{-- Pagination --}}
            @include('admin.partials.pagination', ['records' => $records])
        </div>
    </div>
</div>
