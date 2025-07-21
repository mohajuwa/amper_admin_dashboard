{{-- resources/views/admin/car_make/partials/logo-image.blade.php --}}
@php
    $size = $size ?? 'normal';
    $dimensions = $size === 'small' ? '50px' : '60px';
    $padding = $size === 'small' ? '3px' : '5px';
@endphp

@if ($record->getCarMakeLogo())
    <img src="{{ $record->getCarMakeLogo() }}"
         style="width: {{ $dimensions }}; height: {{ $dimensions }}; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: {{ $padding }};"
         alt="صورة الماركة">
@else
    <div style="width: {{ $dimensions }}; height: {{ $dimensions }}; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; {{ $size === 'small' ? 'margin: 0 auto;' : '' }}">
        <i class="fas fa-image text-muted"></i>
    </div>
@endif