{{-- resources/views/admin/partials/pagination.blade.php --}}
<div class="card-footer">
    <div class="d-flex justify-content-center">
        {!! $records->appends(request()->except('page'))->links() !!}
    </div>
</div>
