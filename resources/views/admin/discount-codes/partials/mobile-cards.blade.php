{{-- resources/views/admin/discount-codes/partials/mobile-cards.blade.php --}}
<div class="d-lg-none">
    @forelse ($records as $record)
        @php
            $expiryData = app('App\Services\CouponExpiryService')->getExpiryData(
                $record->coupon_expiredate,
                $record->coupon_status,
            );
            $discountData = app('App\Services\DiscountBadgeService')->getBadgeData($record->coupon_discount);
        @endphp
        <div class="card mb-3 mx-3">
            <div class="card-body">
                @include('admin.discount-codes.partials.mobile-card-header', [
                    'record' => $record,
                    'expiryData' => $expiryData,
                ])

                @include('admin.discount-codes.partials.mobile-card-body', [
                    'discountData' => $discountData,
                    'count' => $record->coupon_count,
                ])

                @include('admin.discount-codes.partials.action-buttons', ['record' => $record])
            </div>
        </div>
    @empty
        @include('admin.partials.no-data', [
            'icon' => 'fas fa-ticket-alt',
            'message' => 'لا توجد أكواد خصم متاحة حالياً.',
            'wrapperClass' => 'text-center py-5',
        ])
    @endforelse
</div>
