@extends('admin.layouts.app')

@section('style')
    @include('admin.order.partials.style')
@endsection

@section('content')
    <div class="container">
        <div class="order-header">
            <h1><i class="fas fa-file-invoice"></i> تفاصيل الطلب #{{ $getRecord->order_number }}</h1>
            <div class="order-meta">
                <div class="order-meta-item">
                    <span class="label">تاريخ الطلب</span>
                    <span class="value">{{ \Carbon\Carbon::parse($getRecord->order_date)->format('Y-m-d H:i:s') }}</span>
                </div>
                <div class="order-meta-item">
                    <span class="label">العميل</span>
                    <span class="value">{{ $getRecord->user_name ?? 'غير متاح' }}</span>
                </div>
                <div class="order-meta-item">
                    <span class="label">المبلغ الإجمالي</span>
                    <span class="value price-display">{{ number_format($getRecord->total_amount ?? 0, 2) }} ر.س</span>
                </div>
                <div class="order-meta-item">
                    <span class="label">الحالة</span>
                    <span class="value">
                        @php
                            $statuses = [
                                0 => ['name' => 'في الانتظار', 'class' => 'badge-warning'],
                                1 => ['name' => 'معتمد', 'class' => 'badge-success'],
                                2 => ['name' => 'في الطريق', 'class' => 'badge-primary'],
                                3 => ['name' => 'تم التسليم', 'class' => 'badge-info'],
                                4 => ['name' => 'مكتمل', 'class' => 'badge-secondary'],
                                5 => ['name' => 'ملغي', 'class' => 'badge-danger'],
                                6 => ['name' => 'مجدول', 'class' => 'badge-dark'],
                            ];
                            $currentStatus = $statuses[$getRecord->order_status] ?? [
                                'name' => 'غير معروف',
                                'class' => 'badge-secondary',
                            ];
                        @endphp
                        <span class="status-badge {{ $currentStatus['class'] }}">{{ $currentStatus['name'] }}</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="modern-card">
                    <div class="card-header">
                        <div class="icon"><i class="fas fa-info-circle"></i></div>
                        <h5>المعلومات الأساسية</h5>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-hashtag"></i></div>
                                <div class="content">
                                    <div class="label">رقم الطلب</div>
                                    <div class="value">{{ $getRecord->order_number }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-user"></i></div>
                                <div class="content">
                                    <div class="label">العميل</div>
                                    <div class="value">{{ $getRecord->user_name ?? 'غير متاح' }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-id-card"></i></div>
                                <div class="content">
                                    <div class="label">رقم المستخدم</div>
                                    <div class="value">{{ $getRecord->user_id ?? 'غير متاح' }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="content">
                                    <div class="label">نوع الطلب</div>
                                    <div class="value">
                                        @if (isset($getRecord->order_type))
                                            @if ((string) $getRecord->order_type === '0')
                                                توصيل
                                            @elseif ((string) $getRecord->order_type === '1')
                                                استلام في المركز
                                            @else
                                                غير متاح
                                            @endif
                                        @else
                                            غير متاح
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-sticky-note"></i></div>
                                <div class="content">
                                    <div class="label">ملاحظات</div>
                                    <div class="value">{{ $getRecord->notes ?? 'لا توجد ملاحظات' }}</div>
                                </div>
                            </div>
                        </div>

                        @if (!empty($getScheduling->scheduling))
                            @php
                                $schedule = $getScheduling->scheduling;
                                $scheduledTime = $schedule->scheduled_datetime;
                            @endphp
                            <div style="margin-top: 1.5rem;">
                                @if ($schedule->is_completed == 1)
                                    <div class="alert-modern alert-modern-info">
                                        <i class="fas fa-archive"></i>
                                        <strong>مكتمل:</strong> تم أرشفة هذا الطلب المجدول.
                                    </div>
                                @elseif ($scheduledTime->isPast())
                                    <div class="alert-modern alert-modern-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>متأخر:</strong> {{ $scheduledTime->diffForHumans() }}
                                    </div>
                                @elseif ($scheduledTime->diffInHours(now()) <= 12)
                                    <div class="alert-modern alert-modern-warning">
                                        <i class="fas fa-bell"></i>
                                        <strong>قريبًا:</strong> {{ $scheduledTime->diffForHumans() }}
                                    </div>
                                @else
                                    <div class="info-item">
                                        <div class="icon"><i class="fas fa-clock"></i></div>
                                        <div class="content">
                                            <div class="label">مجدول إلى</div>
                                            <div class="value" style="color: #17a2b8;">
                                                {{ $scheduledTime->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="modern-card">
                    <div class="card-header">
                        <div class="icon"><i class="fas fa-car"></i></div>
                        <h5>معلومات المركبة والخدمة</h5>
                    </div>
                    <div class="card-content">
                        <div class="bilingual-item">
                            <div class="label">المورد</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('vendor_name', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('vendor_name', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>

                        <div class="bilingual-item">
                            <div class="label">الماركة</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('make_name', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('make_name', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>

                        <div class="bilingual-item">
                            <div class="label">الموديل</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('model_name', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('model_name', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="content">
                                <div class="label">السنة</div>
                                <div class="value">{{ $getRecord->year ?? 'غير متاح' }}</div>
                            </div>
                        </div>

                        <div class="bilingual-item">
                            <div class="label">رقم لوحة الترخيص</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('license_plate_number', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('license_plate_number', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>

                        <div class="bilingual-item">
                            <div class="label">الخدمات</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('service_names', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('service_names', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>

                        <div class="bilingual-item">
                            <div class="label">نوع العطل</div>
                            <div class="language-row">
                                <span class="language-tag">EN</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('fault_type_name', 'en') ?: 'غير متاح' }}</span>
                            </div>
                            <div class="language-row">
                                <span class="language-tag">AR</span>
                                <span
                                    class="language-value">{{ $getRecord->getLocalizedValue('fault_type_name', 'ar') ?: 'غير متاح' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapsible-section">
            <div class="collapsible-header" aria-expanded="false">
                <div class="title">
                    <i class="fas fa-credit-card"></i>
                    معلومات الدفع
                </div>
                <i class="fas fa-chevron-down icon"></i>
            </div>
            <div class="collapsible-content">
                <div class="content-inner">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-wallet"></i></div>
                            <div class="content">
                                <div class="label">طريقة الدفع</div>
                                <div class="value">
                                    @if (isset($getRecord->orders_paymentmethod))
                                        @if ((string) $getRecord->orders_paymentmethod === '0')
                                            الدفع عند الاستلام
                                        @elseif ((string) $getRecord->orders_paymentmethod === '1')
                                            بطاقة
                                        @else
                                            غير معروف
                                        @endif
                                    @else
                                        غير متاح
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-concierge-bell"></i></div>
                            <div class="content">
                                <div class="label">سعر الخدمات</div>
                                <div class="value">ر.س{{ number_format($getRecord->services_total_price ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-truck"></i></div>
                            <div class="content">
                                <div class="label">سعر التوصيل</div>
                                <div class="value">ر.س{{ number_format($getRecord->orders_pricedelivery ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-calculator"></i></div>
                            <div class="content">
                                <div class="label">المبلغ الإجمالي</div>
                                <div class="value price-display">ر.س{{ number_format($getRecord->total_amount ?? 0, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-tools"></i></div>
                            <div class="content">
                                <div class="label">مبلغ المورد</div>
                                <div class="value">ر.س{{ number_format($getRecord->workshop_amount ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-percentage"></i></div>
                            <div class="content">
                                <div class="label">عمولة التطبيق</div>
                                <div class="value">ر.س{{ number_format($getRecord->app_commission ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                            <div class="content">
                                <div class="label">حالة الدفع</div>
                                <div class="value">
                                    <span
                                        class="status-badge {{ $getRecord->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $getRecord->payment_status == 'paid' ? 'مدفوع' : 'في الانتظار' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapsible-section">
            <div class="collapsible-header" aria-expanded="false">
                <div class="title">
                    <i class="fas fa-map-marker-alt"></i>
                    تفاصيل الموقع
                </div>
                <i class="fas fa-chevron-down icon"></i>
            </div>
            <div class="collapsible-content">
                <div class="content-inner">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-tag"></i></div>
                            <div class="content">
                                <div class="label">اسم العنوان</div>
                                <div class="value">{{ $getRecord->address_name ?? 'غير متاح' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-road"></i></div>
                            <div class="content">
                                <div class="label">الشارع</div>
                                <div class="value">{{ $getRecord->address_street ?? 'غير متاح' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-city"></i></div>
                            <div class="content">
                                <div class="label">المدينة</div>
                                <div class="value">{{ $getRecord->address_city ?? 'غير متاح' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-globe"></i></div>
                            <div class="content">
                                <div class="label">خط الطول</div>
                                <div class="value">{{ $getRecord->address_longitude ?? 'غير متاح' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon"><i class="fas fa-compass"></i></div>
                            <div class="content">
                                <div class="label">خط العرض</div>
                                <div class="value">{{ $getRecord->address_latitude ?? 'غير متاح' }}</div>
                            </div>
                        </div>
                        @if ($getRecord->address_latitude && $getRecord->address_longitude)
                            <div class="info-item">
                                <div class="icon"><i class="fas fa-map"></i></div>
                                <div class="content">
                                    <div class="label">عرض على الخريطة</div>
                                    <div class="value">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $getRecord->address_latitude }},{{ $getRecord->address_longitude }}"
                                            target="_blank" class="map-link">
                                            <i class="fas fa-map"></i> فتح الخريطة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="card-header">
                <div class="icon"><i class="fas fa-history"></i></div>
                <h5>سجل نشاط وتفاوض الطلب</h5>
            </div>
            <div class="card-content">
                <div id="activityLogWrapper">
                    @php
                        $activityTranslations = [
                            'offer_sent' => 'تم إرسال عرض سعر جديد',
                            'offer_accepted' => 'تم قبول العرض',
                            'offer_rejected' => 'تم رفض العرض',
                            'counter_offer_made' => 'تم تقديم عرض مضاد',
                            'price_negotiated' => 'تم التفاوض على السعر',
                            'vendor_assigned' => 'تم تعيين مورد',
                            'order_confirmed' => 'تم تأكيد الطلب',
                            'order_cancelled' => 'تم إلغاء الطلب',
                            'payment_processed' => 'تمت معالجة الدفع',
                            'service_started' => 'بدأت الخدمة',
                            'service_completed' => 'اكتملت الخدمة',
                        ];
                    @endphp

                    @forelse ($getRecord->activityLog as $activity)
                        @php
                            $metadata = json_decode($activity->metadata, true);
                            $actorName = ucfirst($activity->actor_type);
                            $color = 'info';

                            if ($activity->actor_type == 'admin') {
                                $color = 'primary';
                                $actorName = 'الإدارة';
                            } elseif ($activity->actor_type == 'vendor') {
                                $color = 'info';
                                $actorName = 'المورد';
                            } elseif ($activity->actor_type == 'customer') {
                                $color = 'success';
                                $actorName = 'العميل';
                            } elseif ($activity->actor_type == 'system') {
                                $color = 'secondary';
                                $actorName = 'النظام';
                            }
                        @endphp

                        <div class="info-item activity-log-item">
                            <div class="icon"><i class="fas fa-clock"></i></div>
                            <div class="content">
                                <div class="label">
                                    {{ $activityTranslations[$activity->activity_type] ?? $activity->activity_type }}</div>
                                <div class="value">
                                    <div style="margin-bottom: 0.5rem;">
                                        <span class="status-badge badge-{{ $color }}">{{ $actorName }}</span>
                                        <span
                                            style="margin-right: 1rem; color: #6c757d;">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                                    </div>
                                    @if ($metadata && is_array($metadata))
                                        @if (isset($metadata['vendor_name']))
                                            <div><strong>إلى المورد:</strong> {{ $metadata['vendor_name'] }}</div>
                                        @endif
                                        @if (isset($metadata['total_amount']))
                                            <div><strong>المبلغ الإجمالي:</strong>
                                                {{ number_format($metadata['total_amount'], 2) }} ر.س</div>
                                        @endif
                                        @if (isset($metadata['workshop_amount']))
                                            <div><strong>مبلغ المورد:</strong>
                                                {{ number_format($metadata['workshop_amount'], 2) }} ر.س</div>
                                        @endif
                                        @if (isset($metadata['app_commission']))
                                            <div><strong>عمولة التطبيق:</strong>
                                                {{ number_format($metadata['app_commission'], 2) }} ر.س</div>
                                        @endif
                                        @if (isset($metadata['rejection_reason']))
                                            <div><strong>سبب الرفض:</strong> {{ $metadata['rejection_reason'] }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert-modern alert-modern-info">
                            <i class="fas fa-info-circle"></i>
                            لا يوجد سجل نشاط لهذا الطلب حتى الآن.
                        </div>
                    @endforelse
                </div>

                @if ($getRecord->activityLog->count() > 2)
                    <div class="activity-pagination">
                        <button id="activityPrevBtn" class="pagination-btn"><i class="fas fa-chevron-left"></i>
                            السابق</button>
                        <span id="activityPageInfo" class="pagination-info"></span>
                        <button id="activityNextBtn" class="pagination-btn">التالي <i
                                class="fas fa-chevron-right"></i></button>
                    </div>
                @endif
            </div>
        </div>

        <div class="action-section">
            <h5>إدارة الطلب</h5>

            <form id="orderUpdateForm">
                <input type="hidden" name="orderId" value="{{ $getRecord->order_id }}">
                <input type="hidden" name="userId" value="{{ $getRecord->user_id ?? '' }}">

                <div class="row">
                    <div class="col-md-4">
                        <div class="modern-form-group">
                            <label for="orderStatus">تغيير الحالة:</label>
                            <select name="orderStatus" id="orderStatus" class="modern-form-control" required>
                                @foreach ($statuses as $key => $status)
                                    <option value="{{ $key }}"
                                        @if ($getRecord->order_status == $key) selected @endif>
                                        {{ $status['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="modern-form-group">
                            <label for="totalAmountInput">المبلغ الإجمالي:</label>
                            <input type="number" name="totalAmount" id="totalAmountInput" class="modern-form-control"
                                step="0.01" min="0" placeholder="أدخل المبلغ الإجمالي"
                                value="{{ number_format($getRecord->total_amount ?? 0, 2, '.', '') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="modern-form-group">
                            <label> </label>
                            <button type="button" id="updateOrderBtn" class="modern-btn modern-btn-success">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="updateResult" style="margin-top: 20px;"></div>

            <hr style="margin: 2rem 0; border: 1px solid var(--border-color);">

            <h5>إرسال عرض سعر جديد للمورد</h5>
            <p style="color: #6c757d; margin-bottom: 1.5rem;">أرسل عرض سعر محدد ومستقل للمورد لهذا الطلب. سيتم إرسال إشعار
                إلى المورد فقط.</p>

            <input type="hidden" id="offerSubServiceId" value="{{ $getRecord->sub_service_ids ?? '' }}">
            <input type="hidden" id="offerSubServiceName"
                value="{{ $getRecord->getLocalizedValue('service_names', 'en') ?: '' }}">
            <input type="hidden" id="offerAddressStreet" value="{{ $getRecord->address_street ?? '' }}">
            <input type="hidden" id="offerAddressCity" value="{{ $getRecord->address_city ?? '' }}">
            <input type="hidden" id="orderType" value="{{ $getRecord->order_type ?? '0' }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-form-group">
                        <label for="offerVendorSelect">اختر المورد لإرسال العرض إليه:</label>
                        <select id="offerVendorSelect" class="modern-form-control" required>
                            <option value="">-- اختر مورد --</option>
                            @foreach ($vendor as $v)
                                @php
                                    $isSelected = false;
                                    $firstOffer = $getRecord->offers->first();

                                    if ($firstOffer) {
                                        // This part is already correct from our last fix
                                        $response = $firstOffer->vendorResponses->first();
                                        if ($response && $response->vendor_id == $v->vendor_id) {
                                            $isSelected = true;
                                        }
                                    }
                                @endphp

                                <option value="{{ $v->vendor_id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $v->vendor_name_ar }} - {{ $v->vendor_name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="modern-form-group">
                        <label for="offerCustomPrice">سعر العرض المحدد (مبلغ المورد):</label>
                        <input type="number" id="offerCustomPrice" class="modern-form-control" step="0.01"
                            min="0" placeholder="أدخل مبلغ المورد" {{-- CORRECTED LINE: Added `->first()` and nullsafe operators `?->` for safety --}}
                            value="{{ number_format($getRecord->offers->first()?->vendorResponses->first()?->offered_price ?? 0, 2, '.', '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-form-group">
                        <label for="offerCustomAppCommission">عمولة التطبيق المخصصة:</label>
                        <input type="number" id="offerCustomAppCommission" class="modern-form-control" step="0.01"
                            min="0" placeholder="أدخل عمولة التطبيق المخصصة"
                            value="{{ number_format($getRecord->app_commission ?? 0, 2, '.', '') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="modern-form-group">
                        <label> </label>
                        <button type="button" id="sendOfferToVendorBtn" class="modern-btn modern-btn-info">
                            <i class="fas fa-paper-plane"></i> إرسال عرض السعر للمورد
                        </button>
                    </div>
                </div>
            </div>

            <div id="updateResultOffer" style="margin-top: 15px;"></div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.order.partials.script')
@endsection
