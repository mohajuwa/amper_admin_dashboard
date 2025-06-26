{{-- resources/views/admin/partials/form-fields/action-buttons.blade.php --}}
<div class="{{ $colClass ?? 'col-12' }} d-flex align-items-center justify-content-center">
    <div class="form-group w-100">
        <button class="btn btn-info btn-block mb-2">
            <i class="fas fa-search"></i>
        </button>
        @if (isset($resetRoute))
            <a href="{{ $resetRoute }}" class="btn btn-info btn-block">
                <i class="fas fa-redo"></i>
            </a>
        @endif
    </div>
</div>
