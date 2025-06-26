{{-- resources/views/admin/partials/form-fields/table-action-buttons.blade.php --}}


@php
    $id = $record->{$idField ?? 'id'} ?? null;
    $status = $record->{$statusField ?? 'status'} ?? null;
    $isDeleted = (int) $status === 2;
@endphp

{{-- أزرار سطح المكتب --}}
<div class="action-buttons d-none d-md-flex align-items-center">
    {{-- زر التعديل --}}
    @isset($editRoute)
        <a href="{{ $editRoute }}" class="btn-action btn-edit" title="تعديل">
            <i class="fas fa-edit"></i>
        </a>
    @endisset

    {{-- زر الحذف أو الاستعادة حسب الحالة --}}
    <a href="javascript:void(0)" 
       class="btn-action btnDelete {{ $isDeleted ? 'btn-primary' : 'btn-delete' }}"
       data-id="{{ $id }}" 
       data-status="{{ $status }}" 
       title="{{ $isDeleted ? 'استعادة' : 'حذف' }}">
        <i class="fas {{ $isDeleted ? 'fa-undo' : 'fa-trash' }}"></i>
    </a>
</div>

{{-- أزرار الموبايل --}}
<div class="row mt-3 d-md-none text-center">
    <div class="col-12">
        @isset($editRoute)
            <a href="{{ $editRoute }}" class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                <i class="fas fa-edit"></i> تعديل
            </a>
        @endisset

        <a href="javascript:void(0)" class="btn btn-danger btn-sm mx-1 btnDelete" data-id="{{ $id }}"
           data-status="{{ $status }}" style="min-width: 70px;">
            <i class="fas {{ $isDeleted ? 'fa-undo' : 'fa-trash' }}"></i> {{ $isDeleted ? 'استعادة' : 'حذف' }}
        </a>
    </div>
</div>
