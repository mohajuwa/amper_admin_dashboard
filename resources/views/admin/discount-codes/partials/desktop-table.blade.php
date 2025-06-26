{{-- resources/views/admin/discount-codes/partials/desktop-table.blade.php --}}
<div class="d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
            <thead class="text-center bg-light">
                <tr>
                    <th>#</th>
                    <th>اسم الكود</th>
                    <th>نسبة الخصم</th>
                    <th>عدد مرات الاستخدام</th>
                    <th>تاريخ الانتهاء</th>
                    <th>الحالة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($records as $record)
                    @php
                        $expiryData = app('App\Services\CouponExpiryService')->getExpiryData($record->coupon_expiredate,$record->coupon_status);
                        $discountData = app('App\Services\DiscountBadgeService')->getBadgeData($record->coupon_discount);
                    @endphp
                    <tr>
                        <td>{{ $record->coupon_id }}</td>
                        <td>
                            <span class="font-weight-bold text-primary">{{ $record->coupon_name }}</span>
                        </td>
                        <td>
                            @include('admin.discount-codes.partials.discount-badge', ['discountData' => $discountData])
                        </td>
                        <td>
                            @include('admin.discount-codes.partials.usage-count', ['count' => $record->coupon_count])
                        </td>
                        <td>
                            @include('admin.discount-codes.partials.expiry-date', ['expiryData' => $expiryData])
                        </td>
                        <td>
                            @include('admin.discount-codes.partials.status-badge', ['expiryData' => $expiryData])
                        </td>
                        <td>
                            @include('admin.discount-codes.partials.action-buttons', ['record' => $record])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.no-data', [
                        'colspan' => 7,
                        'icon' => 'fas fa-ticket-alt',
                        'message' => 'لا توجد أكواد خصم متاحة حالياً.'
                    ])
                @endforelse
            </tbody>
        </table>
    </div>
</div>