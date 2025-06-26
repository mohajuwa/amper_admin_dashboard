
{{-- resources/views/admin/discount-codes/partials/mobile-card-header.blade.php --}}
<div class="row">
    <div class="col-8">
        <h6 class="card-title mb-1 text-primary font-weight-bold">{{ $record->coupon_name }}</h6>
        <p class="text-muted small mb-1">
            <i class="fas fa-hashtag"></i> {{ $record->coupon_id }}
        </p>
        <p class="text-muted small mb-0">
            <i class="fas fa-calendar"></i> {{ $expiryData['formattedDate'] }}
        </p>
    </div>
    <div class="col-4 text-right">
        <span class="badge {{ $expiryData['statusClass'] }} d-block mb-1">
            <i class="{{ $expiryData['statusIcon'] }}"></i> {{ $expiryData['statusText'] }}
        </span>
    </div>
</div>