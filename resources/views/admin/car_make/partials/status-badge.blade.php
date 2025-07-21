{{-- resources/views/admin/car_make/partials/status-badge.blade.php --}}
@php
    $mobile = $mobile ?? false;
    $blockClass = $mobile ? 'd-block mb-1' : '';
@endphp

@if ($status == 1)
    <span class="badge badge-success px-3 py-2 {{ $blockClass }}">
        <i class="fas fa-check-circle {{ $mobile ? '' : 'mr-1' }}"></i>نشط
    </span>
@elseif ($status == 0)
    <span class="badge badge-secondary px-3 py-2 {{ $blockClass }}">
        <i class="fas fa-pause-circle {{ $mobile ? '' : 'mr-1' }}"></i>غير نشط
    </span>
@elseif ($status == 2)
    <span class="badge badge-danger px-3 py-2 {{ $blockClass }}">
        <i class="fas fa-times-circle {{ $mobile ? '' : 'mr-1' }}"></i>محذوف
    </span>
@else
    <span class="badge badge-warning px-3 py-2 {{ $blockClass }}">
        <i class="fas fa-question-circle {{ $mobile ? '' : 'mr-1' }}"></i>غير معروف
    </span>
@endif