{{-- resources/views/admin/discount-codes/partials/expiry-date.blade.php --}}
<div class="expiry-info">
    <div class="expiry-date">{{ $expiryData['formattedDate'] }}</div>
    @if(!$expiryData['isExpired'] && $expiryData['daysLeft'] > 0)
        <small class="text-muted">
            {{ $expiryData['daysLeft'] }}
            {{ $expiryData['daysLeft'] == 1 ? 'يوم' : 'أيام' }} متبقية
        </small>
    @endif
</div>