 {{-- `resources/views/admin/partials/components/tables/car-make-image-cell.blade.php` --}}

@if ($record->getCarMakeLogo())
    <img src="{{ $record->getCarMakeLogo() }}"
        style="width: 60px; height: 60px; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: 5px;"
        alt="صورة الماركة">
@else
    <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-image text-muted"></i>
    </div>
@endif