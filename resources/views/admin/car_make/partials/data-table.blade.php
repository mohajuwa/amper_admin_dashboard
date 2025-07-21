{{-- resources/views/admin/car_make/partials/data-table.blade.php --}}
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">قائمة ماركات السيارات (المجموع: {{ $records->total() }})</h3>
    </div>
    <div id="table-container">
        <div class="card-body p-0">
            {{-- Desktop Table --}}
            @include('admin.car_make.partials.desktop-table', ['records' => $records])

            {{-- Mobile Cards --}}
            @include('admin.car_make.partials.mobile-cards', ['records' => $records])

            {{-- Pagination --}}
            @include('admin.partials.pagination', ['records' => $records])
        </div>
    </div>
</div>