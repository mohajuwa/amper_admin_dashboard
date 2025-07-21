@extends('admin.layouts.app')

@section('style')
@include('admin.order.partials.style')
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-file-invoice"></i> تفاصيل الطلب #{{ $getRecord->order_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.order.list') }}">الطلبات</a></li>
                        <li class="breadcrumb-item active">تفاصيل الطلب</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle"></i> المعلومات الأساسية</h5>
                        <div class="card-content">
                            <div class="info-row">
                                <span class="info-label">رقم الطلب:</span>
                                <span class="info-value">{{ $getRecord->order_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">العميل:</span>
                                <span class="info-value">{{ $getRecord->user_name ?? 'غير متاح' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">رقم المستخدم:</span>
                                <span class="info-value">{{ $getRecord->user_id ?? 'غير متاح' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">تاريخ الطلب:</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($getRecord->order_date)->format('Y-m-d H:i:s') ?? 'غير متاح' }}
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">الحالة الحالية:</span>
                                <span class="info-value">
                                    @php
                                        $statuses = [
                                            0 => ['name' => 'في الانتظار', 'class' => 'badge-warning'],
                                            1 => ['name' => 'معتمد', 'class' => 'badge-success'],
                                            2 => ['name' => 'في الطريق', 'class' => 'badge-primary'],
                                            3 => ['name' => 'تم التسليم', 'class' => 'badge-info'],
                                            4 => ['name' => 'مؤرشف', 'class' => 'badge-secondary'],
                                            5 => ['name' => 'ملغي', 'class' => 'badge-danger'],
                                            6 => ['name' => 'مجدول', 'class' => 'badge-dark'],
                                        ];
                                        $currentStatus = $statuses[$getRecord->order_status] ?? [
                                            'name' => 'غير معروف',
                                            'class' => 'badge-secondary',
                                        ];
                                    @endphp
                                    <span class="badge {{ $currentStatus['class'] }} status-badge">
                                        {{ $currentStatus['name'] }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">نوع الطلب:</span>
                                <span class="info-value">{{ $getRecord->order_type ?? 'غير متاح' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">ملاحظات:</span>
                                <span class="info-value">{{ $getRecord->notes ?? 'لا توجد ملاحظات' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-car"></i> معلومات المركبة والخدمة</h5>
                        <div class="card-content">
                            <div class="bilingual-field">
                                <div class="info-label">المورد:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('vendor_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('vendor_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">الماركة:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('make_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('make_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">الموديل:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('model_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('model_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <span class="info-label">السنة:</span>
                                <span class="info-value">{{ $getRecord->year ?? 'غير متاح' }}</span>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">رقم لوحة الترخيص:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('license_plate_number', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('license_plate_number', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">الخدمات:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('service_names', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('service_names', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">نوع العطل:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('fault_type_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('fault_type_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="collapse-section">
                        <div class="collapse-header" id="paymentHeader" role="button" aria-expanded="false" aria-controls="paymentContent">
                            <i class="fas fa-credit-card"></i> معلومات الدفع
                            <i class="fas fa-chevron-down float-right"></i>
                        </div>
                        <div id="paymentContent" class="collapse-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">طريقة الدفع:</span>
                                        <span
                                            class="info-value">{{ $getRecord->orders_paymentmethod ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">سعر الخدمات:</span>
                                        <span
                                            class="info-value text-primary">ر.س{{ number_format($getRecord->services_total_price ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">سعر التوصيل:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->orders_pricedelivery ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">المبلغ الإجمالي:</span>
                                        <span
                                            class="info-value text-success"><strong>ر.س{{ number_format($getRecord->total_amount ?? 0, 2) }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">مبلغ المورد:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->workshop_amount ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">عمولة التطبيق:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->app_commission ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">حالة الدفع:</span>
                                        <span
                                            class="badge badge-{{ $getRecord->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ $getRecord->payment_status == 'paid' ? 'مدفوع' : 'في الانتظار' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="collapse-section">
                        <div class="collapse-header" id="locationHeader" role="button" aria-expanded="false" aria-controls="locationContent">
                            <i class="fas fa-map-marker-alt"></i> تفاصيل الموقع
                            <i class="fas fa-chevron-down float-right"></i>
                        </div>
                        <div id="locationContent" class="collapse-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">اسم العنوان:</span>
                                        <span class="info-value">{{ $getRecord->address_name ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">الشارع:</span>
                                        <span class="info-value">{{ $getRecord->address_street ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">المدينة:</span>
                                        <span class="info-value">{{ $getRecord->address_city ?? 'غير متاح' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">خط الطول (Longitude):</span>
                                        <span class="info-value">{{ $getRecord->address_longitude ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">خط العرض (Latitude):</span>
                                        <span class="info-value">{{ $getRecord->address_latitude ?? 'غير متاح' }}</span>
                                    </div>
                                    @if ($getRecord->address_latitude && $getRecord->address_longitude)
                                    <div class="info-row">
                                        <span class="info-label">عرض على الخريطة:</span>
                                        <span class="info-value">
                                            <a href="http://maps.google.com/maps?q={{ $getRecord->address_latitude }},{{ $getRecord->address_longitude }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-map"></i> فتح الخريطة
                                            </a>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="action-section">
                        <h5><i class="fas fa-cog"></i> إدارة الطلب</h5>

                        <form id="orderUpdateForm">
                            <input type="hidden" name="orderId" value="{{ $getRecord->order_id }}">
                            <input type="hidden" name="userId" value="{{ $getRecord->user_id ?? '' }}">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="orderStatus">تغيير الحالة:</label>
                                        <select name="orderStatus" id="orderStatus" class="form-control" required>
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
                                    <div class="form-group">
                                        <label for="totalAmountInput">المبلغ الإجمالي:</label>
                                        <div class="input-group">
                                            <input type="number" name="totalAmount" id="totalAmountInput"
                                                class="form-control price-input" step="0.01"
                                                min="0" placeholder="أدخل المبلغ الإجمالي"
                                                value="{{ number_format($getRecord->total_amount ?? 0, 2, '.', '') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">اتركه فارغاً إذا لم ترد تغيير المبلغ الإجمالي.</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> </label><br>
                                        <button type="button" id="updateOrderBtn" class="btn btn-success btn-custom">
                                            <i class="fas fa-save"></i> حفظ التغييرات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="updateResult" style="margin-top: 20px;"></div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-12">
                                <h5><i class="fas fa-paper-plane"></i> إرسال عرض سعر جديد للمورد</h5>
                                <p class="text-muted">أرسل عرض سعر محدد ومستقل للمورد لهذا الطلب. سيتم إرسال إشعار إلى
                                    المورد فقط.</p>

                                <div class="form-group">
                                    <label>الخدمة الأساسية للطلب:</label>
                                    <p>
                                        <span class="info-label">EN:</span>
                                        <span
                                            class="info-value">{{ $getRecord->getLocalizedValue('service_names', 'en') ?: 'غير متاح' }}</span>
                                    </p>
                                    <p>
                                        <span class="info-label">AR:</span>
                                        <span
                                            class="info-value">{{ $getRecord->getLocalizedValue('service_names', 'ar') ?: 'غير متاح' }}</span>
                                    </p>
                                    <input type="hidden" id="offerSubServiceId"
                                        value="{{ $getRecord->sub_service_ids ?? '' }}">
                                    <input type="hidden" id="offerSubServiceName"
                                        @php
                                            $subServiceNameValue = '';
                                            if (is_array($getRecord->sub_service_names)) {
                                                $subServiceNameValue = $getRecord->sub_service_names['en'] ?? ($getRecord->sub_service_names['ar'] ?? '');
                                            } elseif (is_string($getRecord->sub_service_names) && !empty($getRecord->sub_service_names)) {
                                                $decoded = json_decode($getRecord->sub_service_names, true);
                                                if (is_array($decoded)) {
                                                    $subServiceNameValue = $decoded['en'] ?? ($decoded['ar'] ?? '');
                                                }
                                            } @endphp
                                        value="{{ $subServiceNameValue }}">
                                    <input type="hidden" id="offerAddressStreet"
                                        value="{{ $getRecord->address_street ?? '' }}">
                                    <input type="hidden" id="offerAddressCity"
                                        value="{{ $getRecord->address_city ?? '' }}">
                                </div>

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerVendorSelect">اختر المورد لإرسال العرض إليه:</label>
                                    <select id="offerVendorSelect" class="form-control" required>
                                        <option value="">-- اختر مورد --</option>
                                        @foreach ($vendor as $v)
                                            @php
                                                $vendorNameData = json_decode($v->vendor_name ?? 'null', true);
                                                $vendorArName =
                                                    is_array($vendorNameData) && isset($vendorNameData['ar'])
                                                        ? $vendorNameData['ar']
                                                        : $v->vendor_name;
                                            @endphp
                                            <option value="{{ $v->vendor_id }}"
                                                {{ ($getRecord->vendor_id ?? '') == $v->vendor_id ? 'selected' : '' }}>
                                                {{ $vendorArName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerCustomPrice">سعر العرض المحدد (مبلغ المورد):</label>
                                    <div class="input-group">
                                        <input type="number" id="offerCustomPrice" class="form-control price-input"
                                            step="0.01" min="0" placeholder="أدخل مبلغ المورد"
                                            value="{{ number_format($getRecord->workshop_amount ?? 0, 2, '.', '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">هذا هو المبلغ الذي سيتلقاه المورد مباشرة.</small>
                                </div>

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerCustomAppCommission">عمولة التطبيق المخصصة:</label>
                                    <div class="input-group">
                                        <input type="number" id="offerCustomAppCommission"
                                            class="form-control price-input" step="0.01" min="0"
                                            placeholder="أدخل عمولة التطبيق المخصصة"
                                            value="{{ number_format($getRecord->app_commission ?? 0, 2, '.', '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">اتركه فارغاً للحساب التلقائي (10% من السعر الكلي).</small>
                                </div>

                                <button type="button" id="sendOfferToVendorBtn" class="btn btn-info btn-custom mt-3">
                                    <i class="fas fa-paper-plane"></i> إرسال عرض السعر للمورد
                                </button>
                                <div id="updateResultOffer" style="margin-top: 15px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@include('admin.order.partials.script')
@endsection

=====================










@extends('admin.layouts.app')

@section('style')
    @include('admin.order.partials.style')
    {{-- Custom styles for the improved activity timeline --}}

@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-file-invoice"></i> تفاصيل الطلب #{{ $getRecord->order_number }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.order.list') }}">الطلبات</a></li>
                        <li class="breadcrumb-item active">تفاصيل الطلب</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- Basic Info & Vehicle Info --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle"></i> المعلومات الأساسية</h5>
                        <div class="card-content">
                            <div class="info-row">
                                <span class="info-label">رقم الطلب:</span>
                                <span class="info-value">{{ $getRecord->order_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">العميل:</span>
                                <span class="info-value">{{ $getRecord->user_name ?? 'غير متاح' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">رقم المستخدم:</span>
                                <span class="info-value">{{ $getRecord->user_id ?? 'غير متاح' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">تاريخ الطلب:</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($getRecord->order_date)->format('Y-m-d H:i:s') ?? 'غير متاح' }}
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">الحالة الحالية:</span>
                                <span class="info-value">
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
                                    <span class="badge {{ $currentStatus['class'] }} status-badge">
                                        {{ $currentStatus['name'] }}
                                    </span>
                                </span>
                            </div>
                            @if (!empty($getScheduling->scheduling))
                                @php
                                    $schedule = $getScheduling->scheduling;
                                    $scheduledTime = $schedule->scheduled_datetime;
                                @endphp

                                @if ($schedule->is_completed == 1)
                                    <div class="alert alert-light alert-permanent text-muted p-2 mt-2" role="alert"
                                        style="border: 1px solid #ddd;">
                                        <i class="fas fa-archive mr-1"></i>
                                        <strong>مكتمل:</strong> تم أرشفة هذا الطلب المجدول.
                                    </div>
                                @elseif ($scheduledTime->isPast())
                                    <div class="alert alert-danger alert-permanent p-2 mt-2" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        <strong>متأخر:</strong> {{ $scheduledTime->diffForHumans() }}
                                    </div>
                                @elseif ($scheduledTime->diffInHours(now()) <= 12)
                                    <div class="alert alert-warning alert-permanent p-2 mt-2" role="alert">
                                        <i class="fas fa-bell mr-1"></i>
                                        <strong>قريبًا:</strong> {{ $scheduledTime->diffForHumans() }}
                                    </div>
                                @else
                                    <div class="info-row">
                                        <span class="info-label">مجدول الى :</span>
                                        <span class="info-value" style="font-weight: bold; color: #17a2b8;">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $scheduledTime->diffForHumans() }}
                                        </span>
                                    </div>
                                @endif
                            @endif
                            <div class="info-row">
                                <span class="info-label">نوع الطلب:</span>
                                <span class="info-value">
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
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">ملاحظات:</span>
                                <span class="info-value">{{ $getRecord->notes ?? 'لا توجد ملاحظات' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-car"></i> معلومات المركبة والخدمة</h5>
                        <div class="card-content">
                            <div class="bilingual-field">
                                <div class="info-label">المورد:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('vendor_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('vendor_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">الماركة:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('make_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('make_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="bilingual-field">
                                <div class="info-label">الموديل:</div>
                                <div>
                                    <span class="language-label">EN:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('model_name', 'en') ?: 'غير متاح' }}</span>
                                </div>
                                <div>
                                    <span class="language-label">AR:</span>
                                    <span
                                        class="info-value">{{ $getRecord->getLocalizedValue('model_name', 'ar') ?: 'غير متاح' }}</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <span class="info-label">السنة:</span>
                                <span class="info-value">{{ $getRecord->year ?? 'غير متاح' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RESTORED: Payment & Location Details --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="collapse-section">
                        <div class="collapse-header" id="paymentHeader" role="button" aria-expanded="false"
                            aria-controls="paymentContent">
                            <i class="fas fa-credit-card"></i> معلومات الدفع
                            <i class="fas fa-chevron-down float-right"></i>
                        </div>
                        <div id="paymentContent" class="collapse-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">طريقة الدفع:</span>
                                        <span class="info-value">
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
                                        </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">سعر الخدمات:</span>
                                        <span
                                            class="info-value text-primary">ر.س{{ number_format($getRecord->services_total_price ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">سعر التوصيل:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->orders_pricedelivery ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">المبلغ الإجمالي:</span>
                                        <span
                                            class="info-value text-success"><strong>ر.س{{ number_format($getRecord->total_amount ?? 0, 2) }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-row">
                                        <span class="info-label">مبلغ المورد:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->workshop_amount ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">عمولة التطبيق:</span>
                                        <span
                                            class="info-value">ر.س{{ number_format($getRecord->app_commission ?? 0, 2) }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">حالة الدفع:</span>
                                        <span
                                            class="badge badge-{{ $getRecord->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ $getRecord->payment_status == 'paid' ? 'مدفوع' : 'في الانتظار' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="collapse-section">
                        <div class="collapse-header" id="locationHeader" role="button" aria-expanded="false"
                            aria-controls="locationContent">
                            <i class="fas fa-map-marker-alt"></i> تفاصيل الموقع
                            <i class="fas fa-chevron-down float-right"></i>
                        </div>
                        <div id="locationContent" class="collapse-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">اسم العنوان:</span>
                                        <span class="info-value">{{ $getRecord->address_name ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">الشارع:</span>
                                        <span class="info-value">{{ $getRecord->address_street ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">المدينة:</span>
                                        <span class="info-value">{{ $getRecord->address_city ?? 'غير متاح' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">خط الطول (Longitude):</span>
                                        <span class="info-value">{{ $getRecord->address_longitude ?? 'غير متاح' }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">خط العرض (Latitude):</span>
                                        <span class="info-value">{{ $getRecord->address_latitude ?? 'غير متاح' }}</span>
                                    </div>
                                    @if ($getRecord->address_latitude && $getRecord->address_longitude)
                                        <div class="info-row">
                                            <span class="info-label">عرض على الخريطة:</span>
                                            <span class="info-value">
                                                <a href="http://maps.google.com/maps?q={{ $getRecord->address_latitude }},{{ $getRecord->address_longitude }}"
                                                    target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-map"></i> فتح الخريطة
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================== --}}
            {{--       REDESIGNED ORDER ACTIVITY & NEGOTIATION HISTORY      --}}
            {{-- ========================================================== --}}
            @php
                // Helper to translate activity types into user-friendly Arabic text
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
            <div class="row py-4">
                <div class="col-md-12">
                    <div class="info-card">
                        <h5><i class="fas fa-history"></i> سجل نشاط وتفاوض الطلب</h5>
                        <div class="activity-timeline">
                            @forelse ($getRecord->activityLog as $activity)
                                @php
                                    $metadata = json_decode($activity->metadata, true);
                                    $actorName = ucfirst($activity->actor_type);
                                    $icon = 'fa-info-circle';
                                    $color = 'bg-secondary';
                                    if ($activity->actor_type == 'admin') {
                                        $icon = 'fa-user-shield';
                                        $color = 'bg-primary';
                                        $actorName = 'الإدارة';
                                    } elseif ($activity->actor_type == 'vendor') {
                                        $icon = 'fa-user-tie';
                                        $color = 'bg-info';
                                        $actorName = 'المورد';
                                    } elseif ($activity->actor_type == 'customer') {
                                        $icon = 'fa-user';
                                        $color = 'bg-success';
                                        $actorName = 'العميل';
                                    } elseif ($activity->actor_type == 'system') {
                                        $icon = 'fa-cogs';
                                        $color = 'bg-dark';
                                        $actorName = 'النظام';
                                    }
                                @endphp
                                <div class="timeline-entry">
                                    <div class="timeline-icon {{ $color }}">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <span
                                                class="timeline-title">{{ $activityTranslations[$activity->activity_type] ?? $activity->activity_type }}</span>
                                            <span class="timeline-time">
                                                <i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="timeline-body">
                                            <p><strong>بواسطة:</strong> {{ $actorName }} (ID:
                                                {{ $activity->actor_id }})</p>

                                            @if ($metadata && is_array($metadata))
                                                <ul class="timeline-details">
                                                    @if (isset($metadata['vendor_name']))
                                                        <li>
                                                            <span class="detail-label">إلى المورد:</span>
                                                            <span
                                                                class="detail-value vendor">{{ $metadata['vendor_name'] }}</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($metadata['total_amount']))
                                                        <li>
                                                            <span class="detail-label">المبلغ الإجمالي للعرض:</span>
                                                            <span
                                                                class="detail-value">{{ number_format($metadata['total_amount'], 2) }}
                                                                ر.س</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($metadata['workshop_amount']))
                                                        <li>
                                                            <span class="detail-label">مبلغ المورد:</span>
                                                            <span
                                                                class="detail-value">{{ number_format($metadata['workshop_amount'], 2) }}
                                                                ر.س</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($metadata['app_commission']))
                                                        <li>
                                                            <span class="detail-label">عمولة التطبيق:</span>
                                                            <span
                                                                class="detail-value">{{ number_format($metadata['app_commission'], 2) }}
                                                                ر.س</span>
                                                        </li>
                                                    @endif
                                                    @if (isset($metadata['rejection_reason']))
                                                        <li>
                                                            <span class="detail-label">سبب الرفض:</span>
                                                            <span
                                                                class="detail-value">{{ $metadata['rejection_reason'] }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-entry">
                                    <div class="timeline-icon bg-gray"><i class="fas fa-info-circle"></i></div>
                                    <div class="timeline-content">
                                        <p class="mb-0">لا يوجد سجل نشاط لهذا الطلب حتى الآن.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            {{-- Manage Order & Send Offer --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="action-section">
                        <h5><i class="fas fa-cog"></i> إدارة الطلب</h5>

                        <form id="orderUpdateForm">
                            <input type="hidden" name="orderId" value="{{ $getRecord->order_id }}">
                            <input type="hidden" name="userId" value="{{ $getRecord->user_id ?? '' }}">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="orderStatus">تغيير الحالة:</label>
                                        <select name="orderStatus" id="orderStatus" class="form-control" required>
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
                                    <div class="form-group">
                                        <label for="totalAmountInput">المبلغ الإجمالي:</label>
                                        <div class="input-group">
                                            <input type="number" name="totalAmount" id="totalAmountInput"
                                                class="form-control price-input" step="0.01" min="0"
                                                placeholder="أدخل المبلغ الإجمالي"
                                                value="{{ number_format($getRecord->total_amount ?? 0, 2, '.', '') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">ر.س</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">اتركه فارغاً إذا لم ترد تغيير المبلغ الإجمالي.</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> </label><br>
                                        <button type="button" id="updateOrderBtn" class="btn btn-success btn-custom">
                                            <i class="fas fa-save"></i> حفظ التغييرات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="updateResult" style="margin-top: 20px;"></div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-12">
                                <h5><i class="fas fa-paper-plane"></i> إرسال عرض سعر جديد للمورد</h5>
                                <p class="text-muted">أرسل عرض سعر محدد ومستقل للمورد لهذا الطلب. سيتم إرسال إشعار إلى
                                    المورد فقط.</p>

                                <input type="hidden" id="offerSubServiceId"
                                    value="{{ $getRecord->sub_service_ids ?? '' }}">
                                <input type="hidden" id="offerSubServiceName"
                                    value="{{ $getRecord->getLocalizedValue('service_names', 'en') ?: '' }}">
                                <input type="hidden" id="offerAddressStreet"
                                    value="{{ $getRecord->address_street ?? '' }}">
                                <input type="hidden" id="offerAddressCity"
                                    value="{{ $getRecord->address_city ?? '' }}">
                                <input type="hidden" id="orderType" value="{{ $getRecord->order_type ?? '0' }}">

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerVendorSelect">اختر المورد لإرسال العرض إليه:</label>
                                    <select id="offerVendorSelect" class="form-control" required>
                                        <option value="">-- اختر مورد --</option>
                                        @foreach ($vendor as $v)
                                            @php
                                                $vendorNameData = json_decode($v->vendor_name ?? 'null', true);
                                                $vendorArName =
                                                    is_array($vendorNameData) && isset($vendorNameData['ar'])
                                                        ? $vendorNameData['ar']
                                                        : $v->vendor_name;
                                                $vendorEnName =
                                                    is_array($vendorNameData) && isset($vendorNameData['en'])
                                                        ? $vendorNameData['en']
                                                        : $v->vendor_name;
                                            @endphp
                                            <option value="{{ $v->vendor_id }}"
                                                {{ ($getRecord->vendor_id ?? '') == $v->vendor_id ? 'selected' : '' }}>
                                                {{ $vendorArName }} - {{ $vendorEnName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerCustomPrice">سعر العرض المحدد (مبلغ المورد):</label>
                                    <div class="input-group">
                                        <input type="number" id="offerCustomPrice" class="form-control price-input"
                                            step="0.01" min="0" placeholder="أدخل مبلغ المورد"
                                            value="{{ number_format($getRecord->workshop_amount ?? 0, 2, '.', '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">هذا هو المبلغ الذي سيتلقاه المورد مباشرة.</small>
                                </div>

                                <div class="form-group col-md-6 px-0">
                                    <label for="offerCustomAppCommission">عمولة التطبيق المخصصة:</label>
                                    <div class="input-group">
                                        <input type="number" id="offerCustomAppCommission"
                                            class="form-control price-input" step="0.01" min="0"
                                            placeholder="أدخل عمولة التطبيق المخصصة"
                                            value="{{ number_format($getRecord->app_commission ?? 0, 2, '.', '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">اتركه فارغاً للحساب التلقائي (10% من السعر الكلي).</small>
                                </div>

                                <button type="button" id="sendOfferToVendorBtn" class="btn btn-info btn-custom mt-3">
                                    <i class="fas fa-paper-plane"></i> إرسال عرض السعر للمورد
                                </button>
                                <div id="updateResultOffer" style="margin-top: 15px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.order.partials.script')
@endsection
