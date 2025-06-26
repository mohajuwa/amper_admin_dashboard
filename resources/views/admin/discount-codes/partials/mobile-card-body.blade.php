
{{-- resources/views/admin/discount-codes/partials/mobile-card-body.blade.php --}}
<div class="row mt-2">
    <div class="col-6">
        <span class="badge {{ $discountData['badgeClass'] }} discount-badge-mobile">
            <span class="discount-icon">{{ $discountData['icon'] }}</span>
            <span class="discount-value">{{ $discountData['discount'] }}%</span>
        </span>
    </div>
    <div class="col-6 text-right">
        <span class="badge badge-info">
            <i class="fas fa-repeat mr-1"></i>{{ $count }}
        </span>
    </div>
</div>