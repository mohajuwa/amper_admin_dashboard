@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">{{ $header_title }}</h1>
                <a href="{{ route('admin.product_by_car.create') }}" class="btn btn-sm btn-primary">إضافة منتج جديد</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- Search PBCF  --}}
                    <form action="" method="get">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">البحث في المنتجات</h3>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>معرف المنتج</label>
                                            <input type="text" name="product_id" value="{{ request('product_id') }}"
                                                class="form-control" placeholder="معرف المنتج">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الخدمة الرئيسية</label>
                                            <select name="service_id" class="select2 form-control" id="service_select">
                                                <option value="">جميع الخدمات</option>
                                                @php
                                                    $services = \App\Models\Service::getAllServices();
                                                @endphp
                                                @foreach ($services as $service)
                                                    @php
                                                        $serviceName = is_array($service->service_name)
                                                            ? $service->service_name['ar'] ??
                                                                $service->service_name['en']
                                                            : $service->service_name;

                                                        $serviceNameEn = is_array($service->service_name)
                                                            ? $service->service_name['en'] ??
                                                                $service->service_name['ar']
                                                            : $service->service_name;
                                                    @endphp
                                                    <option value="{{ $service->service_id }}"
                                                        {{ request('service_id') == $service->service_id ? 'selected' : '' }}>
                                                        {{ $serviceName }} - {{ $serviceNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الخدمة الفرعية</label>
                                            <select name="sub_service_id" class="select2 form-control"
                                                id="sub_service_select" disabled>
                                                <option value="">اختر الخدمة الرئيسية أولاً</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>ماركة السيارة</label>
                                            <select name="make_id" class="select2 form-control" id="car_make_select">
                                                <option value="">جميع الماركات</option>
                                                @php
                                                    $carMakes = \App\Models\CarMakesModel::getRecord();
                                                @endphp
                                                @foreach ($carMakes as $make)
                                                    @php
                                                        $makeNameAr = is_string($make->name)
                                                            ? json_decode($make->name, true)['ar'] ??
                                                                (json_decode($make->name, true)['en'] ?? $make->name)
                                                            : $make->name['ar'] ?? ($make->name['en'] ?? 'غير محدد');

                                                        $makeNameEn = is_string($make->name)
                                                            ? json_decode($make->name, true)['en'] ??
                                                                (json_decode($make->name, true)['ar'] ?? $make->name)
                                                            : $make->name['en'] ??
                                                                ($make->name['ar'] ?? 'Not specified');
                                                    @endphp
                                                    <option value="{{ $make->make_id }}"
                                                        {{ request('make_id') == $make->make_id ? 'selected' : '' }}>
                                                        {{ $makeNameAr }} - {{ $makeNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>موديل السيارة</label>
                                            <select name="model_id" class="select2 form-control" id="car_model_select"
                                                disabled>
                                                <option value="">اختر الماركة أولاً</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>السنة</label>
                                            <select name="year" class=" select2 form-control">
                                                <option value="">جميع السنوات</option>
                                                @foreach (\App\Models\ProductByCar::getYearsRange() as $year)
                                                    <option value="{{ $year }}"
                                                        {{ request('year') == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الحالة</label>
                                            <select name="status" class="form-control">
                                                <option value="">جميع الحالات</option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>نشط
                                                </option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>غير
                                                    نشط</option>
                                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                                    محذوف</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>من تاريخ</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>إلى تاريخ</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-2 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.product_by_car.list') }}"
                                                class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- Product by Car Table --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">قائمة المنتجات حسب السيارة (المجموع: {{ $getRecord->total() }})</h3>
                        </div>
                        <div id="table-container">
                            <div class="card-body p-0">
                                {{-- Desktop Table --}}
                                <div class="d-none d-lg-block">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead class="text-center bg-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>موديل السيارة</th>
                                                    <th>العلامة التجارية</th>
                                                    <th>الخدمة الرئيسية</th>
                                                    <th>الخدمة الفرعية</th>
                                                    <th>السنة</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    <tr>
                                                        <td>{{ $value->product_id }}</td>
                                                        <td>
                                                            @if ($value->name)
                                                                @php
                                                                    $modelName = is_string($value->name)
                                                                        ? json_decode($value->name, true)['ar'] ??
                                                                            (json_decode($value->name, true)['en'] ??
                                                                                $value->name)
                                                                        : $value->name['ar'] ??
                                                                            ($value->name['en'] ?? 'غير محدد');
                                                                @endphp
                                                                <span class="badge badge-info">{{ $modelName }}</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($value->car_make_name)
                                                                @php
                                                                    $makeName = is_string($value->car_make_name)
                                                                        ? json_decode($value->car_make_name, true)[
                                                                                'ar'
                                                                            ] ??
                                                                            (json_decode($value->car_make_name, true)[
                                                                                'en'
                                                                            ] ??
                                                                                $value->car_make_name)
                                                                        : $value->car_make_name['ar'] ??
                                                                            ($value->car_make_name['en'] ?? 'غير محدد');
                                                                @endphp


                                                                @if (!empty(url('https://modwir.com/haytham_store/upload/cars/' . $value->car_make_logo)))
                                                                    <img src="{{ url('https://modwir.com/haytham_store/upload/cars/' . $value->car_make_logo) }}"
                                                                        style="height: 30px;" />

                                                                    <div class="small mt-1">
                                                                        {{ $makeName }}
                                                                    </div>
                                                                @else
                                                                    {{ $makeName ?? 'غير محدد' }}
                                                                @endif
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if ($value->service_name)
                                                                @php
                                                                    $serviceName = is_string($value->service_name)
                                                                        ? json_decode($value->service_name, true)[
                                                                                'ar'
                                                                            ] ??
                                                                            (json_decode($value->service_name, true)[
                                                                                'en'
                                                                            ] ??
                                                                                $value->service_name)
                                                                        : $value->service_name['ar'] ??
                                                                            ($value->service_name['en'] ?? 'غير محدد');
                                                                @endphp
                                                                <span
                                                                    class="badge badge-primary">{{ $serviceName }}</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($value->sub_service_name)
                                                                @php
                                                                    $subServiceName = is_string(
                                                                        $value->sub_service_name,
                                                                    )
                                                                        ? json_decode($value->sub_service_name, true)[
                                                                                'ar'
                                                                            ] ??
                                                                            (json_decode(
                                                                                $value->sub_service_name,
                                                                                true,
                                                                            )['en'] ??
                                                                                $value->sub_service_name)
                                                                        : $value->sub_service_name['ar'] ??
                                                                            ($value->sub_service_name['en'] ??
                                                                                'غير محدد');
                                                                @endphp
                                                                <span
                                                                    class="badge badge-success">{{ $subServiceName }}</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($value->year)
                                                                <span class="badge badge-dark">{{ $value->year }}
                                                                    م</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($value->status == 0)
                                                                <span class="badge badge-success px-3 py-2">
                                                                    <i class="fas fa-check-circle mr-1"></i>نشط
                                                                </span>
                                                            @elseif ($value->status == 1)
                                                                <span class="badge badge-secondary px-3 py-2">
                                                                    <i class="fas fa-pause-circle mr-1"></i>غير نشط
                                                                </span>
                                                            @elseif ($value->status == 2)
                                                                <span class="badge badge-danger px-3 py-2">
                                                                    <i class="fas fa-times-circle mr-1"></i>محذوف
                                                                </span>
                                                            @else
                                                                <span class="badge badge-warning px-3 py-2">
                                                                    <i class="fas fa-question-circle mr-1"></i>غير معروف
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="text-sm">
                                                                <div class="font-weight-bold">
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->locale('ar')->translatedFormat('j F Y') }}
                                                                </div>
                                                                <div class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->format('g:i A') }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('admin.product_by_car.edit', $value->product_id) }}"
                                                                    class="btn-action btn-edit" title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn-action btn-delete btnDelete"
                                                                    data-id="{{ $value->product_id }}" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center py-4">
                                                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">لا توجد منتجات متاحة حالياً.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Mobile Cards --}}
                                <div class="d-lg-none">
                                    @forelse ($getRecord as $value)
                                        @php
                                            // Process service name
                                            $serviceDisplayName = 'غير محدد';
                                            if ($value->service_name) {
                                                $serviceDisplayName = is_string($value->service_name)
                                                    ? json_decode($value->service_name, true)['ar'] ??
                                                        (json_decode($value->service_name, true)['en'] ??
                                                            $value->service_name)
                                                    : $value->service_name['ar'] ??
                                                        ($value->service_name['en'] ?? 'غير محدد');
                                            }

                                            // Process sub service name
                                            $subServiceDisplayName = 'غير محدد';
                                            if ($value->sub_service_name) {
                                                $subServiceDisplayName = is_string($value->sub_service_name)
                                                    ? json_decode($value->sub_service_name, true)['ar'] ??
                                                        (json_decode($value->sub_service_name, true)['en'] ??
                                                            $value->sub_service_name)
                                                    : $value->sub_service_name['ar'] ??
                                                        ($value->sub_service_name['en'] ?? 'غير محدد');
                                            }

                                            // Process model name
                                            $modelDisplayName = 'غير محدد';
                                            if ($value->name) {
                                                $modelDisplayName = is_string($value->name)
                                                    ? json_decode($value->name, true)['ar'] ??
                                                        (json_decode($value->name, true)['en'] ?? $value->name)
                                                    : $value->name['ar'] ?? ($value->name['en'] ?? 'غير محدد');
                                            }

                                            // Process make name
                                            $makeDisplayName = 'غير محدد';
                                            if ($value->car_make_name) {
                                                $makeDisplayName = is_string($value->car_make_name)
                                                    ? json_decode($value->car_make_name, true)['ar'] ??
                                                        (json_decode($value->car_make_name, true)['en'] ??
                                                            $value->car_make_name)
                                                    : $value->car_make_name['ar'] ??
                                                        ($value->car_make_name['en'] ?? 'غير محدد');
                                            }
                                        @endphp

                                        <div class="card mb-3 mx-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        <div class="bg-white text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            @if (!empty(url('https://modwir.com/haytham_store/upload/cars/' . $value->car_make_logo)))
                                                                <img src="{{ url('https://modwir.com/haytham_store/upload/cars/' . $value->car_make_logo) }}"
                                                                    alt="logo"
                                                                    style="max-width: 100%; max-height: 100%;">
                                                            @else
                                                                <i class="fas fa-car text-secondary"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 class="card-title mb-1">{{ $modelDisplayName }}</h6>
                                                        <p class="text-muted small mb-1">{{ $makeDisplayName }}</p>
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-hashtag"></i> {{ $value->product_id }}
                                                        </p>
                                                    </div>
                                                    <div class="col-3 text-right">
                                                        @if ($value->status == 0)
                                                            <span class="badge badge-success d-block mb-1">
                                                                <i class="fas fa-check-circle"></i> نشط
                                                            </span>
                                                        @elseif ($value->status == 1)
                                                            <span class="badge badge-secondary d-block mb-1">
                                                                <i class="fas fa-pause-circle"></i> غير نشط
                                                            </span>
                                                        @elseif ($value->status == 2)
                                                            <span class="badge badge-danger d-block mb-1">
                                                                <i class="fas fa-times-circle"></i> محذوف
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning d-block mb-1">
                                                                <i class="fas fa-question-circle"></i> غير معروف
                                                            </span>
                                                        @endif

                                                        @if ($value->year)
                                                            <small
                                                                class="text-dark font-weight-bold d-block">{{ $value->year }}
                                                                م</small>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <small class="text-primary">
                                                            <i class="fas fa-cogs"></i> {{ $serviceDisplayName }}
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-success">
                                                            <i class="fas fa-tools"></i> {{ $subServiceDisplayName }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <div class="col-12">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($value->created_at)->locale('ar')->translatedFormat('j F Y') }}
                                                            {{ \Carbon\Carbon::parse($value->created_at)->format('g:i A') }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12 text-center">
                                                        <a href="{{ route('admin.product_by_car.edit', $value->product_id) }}"
                                                            class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm mx-1 btnDelete"
                                                            data-id="{{ $value->product_id }}" style="min-width: 70px;">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد منتجات متاحة حالياً.</p>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Pagination --}}
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        {!! $getRecord->appends(request()->except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $('body').delegate('.btnDelete', 'click', function() {
            let button = $(this);
            const confirmMessage = 'هل أنت متأكد من الحذف ؟';

            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(confirmMessage, function() {
                    productByCarYearDelete(button);
                }, 'تأكيد العملية');
            } else {
                if (confirm(confirmMessage)) {
                    productByCarYearDelete(button);
                }
            }
        });

        function productByCarYearDelete(button) {
            const productByCarId = button.data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('admin/product-by-car/delete') }}/" + productByCarId,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(data) {
                    if (data.success) {
                        $('#table-container').load(location.href + ' #table-container > *');
                        if (typeof showSuccess === 'function') {
                            showSuccess('تم الحذف  بنجاح');
                        } else {
                            alert('تم الحذف  بنجاح');
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء الحذف ');
                        if (typeof showError === 'function') {
                            showError(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    const errorMsg = 'حدث خطأ أثناء الاتصال بالخادم: ' + error;
                    if (typeof showError === 'function') {
                        showError(errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }

        // Update table count after deletion
        function updateTableCount() {
            const currentCount = parseInt($('.card-title').text().match(/\d+/)?.[0] || 0);
            if (currentCount > 0) {
                $('.card-title').text($('.card-title').text().replace(/\d+/, currentCount - 1));
            }
        }
        $('#car_make_select').on('change', function() {
            var makeId = $(this).val();
            var modelSelect = $('#car_model_select');

            if (makeId) {
                // Enable the model select
                modelSelect.prop('disabled', false);

                // Clear current options
                modelSelect.html('<option value="">جاري التحميل...</option>');

                // Make AJAX request to get models for selected make
                $.ajax({
                    url: '{{ route('admin.ajax.car_models') }}', // This matches your routes file
                    type: 'GET',
                    data: {
                        make_id: makeId
                    },
                    success: function(response) {
                        modelSelect.html('<option value="">جميع الموديلات</option>');

                        if (response.models && response.models.length > 0) {
                            $.each(response.models, function(index, model) {
                                var modelNameAr = 'غير محدد';
                                var modelNameEn = 'Not specified';

                                if (typeof model.name === 'string') {
                                    try {
                                        var parsedName = JSON.parse(model.name);
                                        modelNameAr = parsedName.ar || parsedName.en || model
                                            .name;
                                        modelNameEn = parsedName.en || parsedName.ar || model
                                            .name;
                                    } catch (e) {
                                        modelNameAr = model.name;
                                        modelNameEn = model.name;
                                    }
                                } else if (typeof model.name === 'object') {
                                    modelNameAr = model.name.ar || model.name.en || 'غير محدد';
                                    modelNameEn = model.name.en || model.name.ar ||
                                        'Not specified';
                                }

                                var selected = '{{ request('model_id') }}' == model.model_id ?
                                    'selected' : '';
                                modelSelect.append('<option value="' + model.model_id + '" ' +
                                    selected + '>' +
                                    modelNameAr + ' - ' + modelNameEn + '</option>');
                            });
                        }
                    },
                    error: function() {
                        modelSelect.html('<option value="">خطأ في تحميل الموديلات</option>');
                    }
                });
            } else {
                // Disable and reset model select
                modelSelect.prop('disabled', true);
                modelSelect.html('<option value="">اختر الماركة أولاً</option>');
            }
        });

        // Trigger change event on page load if make is already selected
        if ($('#car_make_select').val()) {
            $('#car_make_select').trigger('change');
        }
        $('#service_select').on('change', function() {
            var serviceId = $(this).val();
            var subServiceSelect = $('#sub_service_select');

            if (serviceId) {
                // Enable the sub-service select
                subServiceSelect.prop('disabled', false);

                // Clear current options
                subServiceSelect.html('<option value="">جاري التحميل...</option>');

                // Make AJAX request to get sub-services for selected service
                $.ajax({
                    url: '{{ route('admin.ajax.sub_services') }}',
                    type: 'GET',
                    data: {
                        service_id: serviceId
                    },
                    success: function(response) {
                        console.log('Full response:', response); // Debug line

                        subServiceSelect.html('<option value="">جميع الخدمات الفرعية</option>');

                        if (response.sub_services && response.sub_services.length > 0) {
                            // Replace your current parsing logic with this simpler version:
                            $.each(response.sub_services, function(index, subService) {
                                var nameAr = 'غير محدد';
                                var nameEn = 'Not specified';

                                if (subService.name) {
                                    if (typeof subService.name === 'object') {
                                        // Already parsed as object
                                        nameAr = subService.name.ar || 'غير محدد';
                                        nameEn = subService.name.en || 'Not specified';
                                    } else if (typeof subService.name === 'string') {
                                        // Try to parse the JSON string
                                        try {
                                            var parsed = JSON.parse(subService.name);
                                            nameAr = parsed.ar || 'غير محدد';
                                            nameEn = parsed.en || 'Not specified';
                                        } catch (e) {
                                            // If parsing fails, use the string as Arabic
                                            nameAr = subService.name;
                                            nameEn = 'Not specified';
                                        }
                                    }
                                }

                                var optionText = nameAr + ' - ' + nameEn;
                                var selected = '{{ request('sub_service_id') }}' == subService
                                    .sub_service_id ? 'selected' : '';

                                subServiceSelect.append('<option value="' + subService
                                    .sub_service_id + '" ' + selected + '>' + optionText +
                                    '</option>');
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', xhr.responseText); // Debug line
                        subServiceSelect.html('<option value="">خطأ في تحميل الخدمات الفرعية</option>');
                    }
                });
            } else {
                // Disable and reset sub-service select
                subServiceSelect.prop('disabled', true);
                subServiceSelect.html('<option value="">اختر الخدمة الرئيسية أولاً</option>');
            }
        });

        // Trigger change event on page load if service is already selected
        if ($('#service_select').val()) {
            $('#service_select').trigger('change');
        }
        // Form validation enhancement
        $('form').on('submit', function(e) {
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');

            // Disable submit button to prevent double submission
            submitBtn.prop('disabled', true);

            // Re-enable after 3 seconds as fallback
            setTimeout(function() {
                submitBtn.prop('disabled', false);
            }, 3000);
        });
    </script>
@endsection
