{{-- resources/views/admin/discount-codes/partials/discount-badge.blade.php --}}
<div class="discount-wrapper">
    <span class="badge {{ $discountData['badgeClass'] }} discount-badge">
        <div class="discount-content">
            <span class="discount-icon">{{ $discountData['icon'] }}</span>
            <span class="discount-value">{{ $discountData['discount'] }}%</span>
        </div>
    </span>
</div>








