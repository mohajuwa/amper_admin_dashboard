{{-- resources/views/admin/partials/no-data.blade.php --}}
@if(isset($colspan))
    <tr>
        <td colspan="{{ $colspan }}" class="{{ $wrapperClass ?? 'text-center py-4' }}">
            <i class="{{ $icon ?? 'fas fa-info-circle' }} fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ $message ?? 'لا توجد بيانات متاحة حالياً.' }}</p>
        </td>
    </tr>
@else
    <div class="{{ $wrapperClass ?? 'text-center py-5' }}">
        <i class="{{ $icon ?? 'fas fa-info-circle' }} fa-3x text-muted mb-3"></i>
        <p class="text-muted">{{ $message ?? 'لا توجد بيانات متاحة حالياً.' }}</p>
    </div>
@endif
