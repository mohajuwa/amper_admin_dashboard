@extends('admin.layouts.app')

@section('content')
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $header_title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product_by_car.list') }}">المنتجات حسب
                                السيارة</a></li>
                        <li class="breadcrumb-item active">تعديل المنتج</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-2"></i>
                                تعديل بيانات المنتج حسب السيارة
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.product_by_car.list') }}" class="btn btn-tool"
                                    title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="editProductByCarForm"
                            action="{{ route('admin.product_by_car.update', $getRecord->product_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <!-- Service Selection Row -->
                                <div class="row">
                                    <!-- Main Service -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_id" class="required">
                                                <i class="fas fa-cogs mr-1"></i>
                                                الخدمة الرئيسية
                                            </label>
                                            <select class="form-control select2 @error('service_id') is-invalid @enderror"
                                                id="service_id" name="service_id">
                                                <option value="">اختر الخدمة الرئيسية</option>
                                                @foreach ($getServices as $service)
                                                    <option value="{{ $service->service_id }}"
                                                        {{ old('service_id', $getRecord->service_id) == $service->service_id ? 'selected' : '' }}>
                                                        @php
                                                            $serviceName = is_array($service->service_name)
                                                                ? $service->service_name['ar'] ??
                                                                    $service->service_name['en']
                                                                : (is_string($service->service_name)
                                                                    ? json_decode($service->service_name, true)['ar'] ??
                                                                        (json_decode($service->service_name, true)[
                                                                            'en'
                                                                        ] ??
                                                                            $service->service_name)
                                                                    : $service->service_name);

                                                            $serviceNameEn = is_array($service->service_name)
                                                                ? $service->service_name['en'] ??
                                                                    $service->service_name['ar']
                                                                : (is_string($service->service_name)
                                                                    ? json_decode($service->service_name, true)['en'] ??
                                                                        (json_decode($service->service_name, true)[
                                                                            'ar'
                                                                        ] ??
                                                                            $service->service_name)
                                                                    : $service->service_name);
                                                        @endphp
                                                        {{ $serviceName }} - {{ $serviceNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('service_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Sub Service -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sub_service_id" class="required">
                                                <i class="fas fa-tools mr-1"></i>
                                                الخدمة الفرعية
                                            </label>
                                            <select
                                                class="form-control select2 @error('sub_service_id') is-invalid @enderror"
                                                id="sub_service_id" name="sub_service_id">
                                                <option value="">اختر الخدمة الفرعية</option>
                                                @foreach ($getSubServices as $subService)
                                                    <option value="{{ $subService->sub_service_id }}"
                                                        {{ old('sub_service_id', $getRecord->sub_service_id) == $subService->sub_service_id ? 'selected' : '' }}>
                                                        @php
                                                            $subServiceName = is_array($subService->name)
                                                                ? $subService->name['ar'] ?? $subService->name['en']
                                                                : (is_string($subService->name)
                                                                    ? json_decode($subService->name, true)['ar'] ??
                                                                        (json_decode($subService->name, true)['en'] ??
                                                                            $subService->name)
                                                                    : $subService->name);

                                                            $subServiceNameEn = is_array($subService->name)
                                                                ? $subService->name['en'] ?? $subService->name['ar']
                                                                : (is_string($subService->name)
                                                                    ? json_decode($subService->name, true)['en'] ??
                                                                        (json_decode($subService->name, true)['ar'] ??
                                                                            $subService->name)
                                                                    : $subService->name);
                                                        @endphp
                                                        {{ $subServiceName }} - {{ $subServiceNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sub_service_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Car Information Row -->
                                <div class="row">
                                    <!-- Car Make -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="make_id" class="required">
                                                <i class="fas fa-car mr-1"></i>
                                                ماركة السيارة
                                            </label>
                                            <select class="form-control select2 @error('make_id') is-invalid @enderror"
                                                id="make_id" name="make_id">
                                                <option value="">اختر ماركة السيارة</option>
                                                @foreach ($getCarMakes as $make)
                                                    <option value="{{ $make->make_id }}"
                                                        {{ (string) old('make_id', $getRecord->carModel->make_id ?? '') === (string) $make->make_id ? 'selected' : '' }}>
                                                        @php
                                                            // Simplified name extraction
                                                            $makeName = 'غير محدد';
                                                            $makeNameEn = 'Not specified';

                                                            if (isset($make->name)) {
                                                                if (is_array($make->name)) {
                                                                    $makeName =
                                                                        $make->name['ar'] ??
                                                                        ($make->name['en'] ?? 'غير محدد');
                                                                    $makeNameEn =
                                                                        $make->name['en'] ??
                                                                        ($make->name['ar'] ?? 'Not specified');
                                                                } elseif (is_string($make->name)) {
                                                                    $decoded = json_decode($make->name, true);
                                                                    if (is_array($decoded)) {
                                                                        $makeName =
                                                                            $decoded['ar'] ??
                                                                            ($decoded['en'] ?? $make->name);
                                                                        $makeNameEn =
                                                                            $decoded['en'] ??
                                                                            ($decoded['ar'] ?? $make->name);
                                                                    } else {
                                                                        $makeName = $make->name;
                                                                        $makeNameEn = $make->name;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $makeName }} - {{ $makeNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('make_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Car Model -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model_id" class="required">
                                                <i class="fas fa-car-side mr-1"></i>
                                                موديل السيارة
                                            </label>
                                            <select class="form-control select2 @error('model_id') is-invalid @enderror"
                                                id="model_id" name="model_id">
                                                <option value="">اختر موديل السيارة</option>
                                                @foreach ($getCarModels as $model)
                                                    <option value="{{ $model->model_id }}"
                                                        {{ (string) old('model_id', $getRecord->model_id ?? '') === (string) $model->model_id ? 'selected' : '' }}>
                                                        @php
                                                            $modelName = 'غير محدد';
                                                            $modelNameEn = 'Not specified';

                                                            if (isset($model->name)) {
                                                                if (is_array($model->name)) {
                                                                    $modelName =
                                                                        $model->name['ar'] ??
                                                                        ($model->name['en'] ?? 'غير محدد');
                                                                    $modelNameEn =
                                                                        $model->name['en'] ??
                                                                        ($model->name['ar'] ?? 'Not specified');
                                                                } elseif (is_string($model->name)) {
                                                                    $decoded = json_decode($model->name, true);
                                                                    if (is_array($decoded)) {
                                                                        $modelName =
                                                                            $decoded['ar'] ??
                                                                            ($decoded['en'] ?? $model->name);
                                                                        $modelNameEn =
                                                                            $decoded['en'] ??
                                                                            ($decoded['ar'] ?? $model->name);
                                                                    } else {
                                                                        $modelName = $model->name;
                                                                        $modelNameEn = $model->name;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $modelName }} - {{ $modelNameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('model_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Year and Status Row -->
                                <div class="row">
                                    <!-- Year -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year" class="required">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                السنة
                                            </label>
                                            <select class="form-control select2 @error('year') is-invalid @enderror"
                                                id="year" name="year">
                                                <option value="">اختر السنة</option>
                                                @foreach (\App\Models\ProductByCar::getYearsRange() as $year)
                                                    <option value="{{ $year }}"
                                                        {{ old('year', $getRecord->year) == $year ? 'selected' : '' }}>
                                                        {{ $year }} م
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة المنتج
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="0"
                                                    {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}>
                                                    نشط</option>
                                                <option value="1"
                                                    {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}>
                                                    غير نشط</option>
                                                <option value="2"
                                                    {{ old('status', $getRecord->status) == '2' ? 'selected' : '' }}>
                                                    محذوف</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information Card -->
                                <div class="card card-secondary mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            معلومات إضافية
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        <i class="fas fa-hashtag mr-1"></i>
                                                        معرف المنتج
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $getRecord->product_id }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        تاريخ الإنشاء
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        value="{{ \Carbon\Carbon::parse($getRecord->created_at)->locale('ar')->translatedFormat('j F Y - g:i A') }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($getRecord->updated_at)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>
                                                            <i class="fas fa-edit mr-1"></i>
                                                            آخر تحديث
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            value="{{ \Carbon\Carbon::parse($getRecord->updated_at)->locale('ar')->translatedFormat('j F Y - g:i A') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                            <i class="fas fa-save mr-2"></i>
                                            <span class="btn-text">تحديث المنتج</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.product_by_car.list') }}"
                                            class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            العودة للقائمة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            function redirectToList(message, type) {
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.product_by_car.list') }}");
            }

            // Clear validation styles
            $('body').on('input change', '#service_id, #sub_service_id, #make_id, #model_id, #year, #status',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '0') $(this).addClass('border-success');
                else if (this.value === '1') $(this).addClass('border-warning');
                else if (this.value === '2') $(this).addClass('border-danger');
            });
            $('#status').trigger('change');

            // Prevent form submission on Enter key
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#editProductByCarForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.product_by_car.list') }}";
                }
            });

            // Dynamic Sub-Service Loading
            $('#service_id').on('change', function() {
                var serviceId = $(this).val();
                var subServiceSelect = $('#sub_service_id');

                if (serviceId) {
                    subServiceSelect.prop('disabled', false);
                    subServiceSelect.html('<option value="">جاري التحميل...</option>');

                    $.ajax({
                        url: '{{ route('admin.ajax.sub_services') }}',
                        type: 'GET',
                        data: {
                            service_id: serviceId
                        },
                        success: function(response) {
                            subServiceSelect.html(
                                '<option value="">اختر الخدمة الفرعية</option>');

                            if (response.sub_services && response.sub_services.length > 0) {
                                $.each(response.sub_services, function(index, subService) {
                                    var nameAr = 'غير محدد';
                                    var nameEn = 'Not specified';

                                    if (subService.name) {
                                        if (typeof subService.name === 'object') {
                                            nameAr = subService.name.ar || 'غير محدد';
                                            nameEn = subService.name.en ||
                                                'Not specified';
                                        } else if (typeof subService.name ===
                                            'string') {
                                            try {
                                                var parsed = JSON.parse(subService
                                                    .name);
                                                nameAr = parsed.ar || 'غير محدد';
                                                nameEn = parsed.en || 'Not specified';
                                            } catch (e) {
                                                nameAr = subService.name;
                                                nameEn = 'Not specified';
                                            }
                                        }
                                    }

                                    var optionText = nameAr + ' - ' + nameEn;
                                    var selected =
                                        '{{ old('sub_service_id', $getRecord->sub_service_id) }}' ==
                                        subService.sub_service_id ? 'selected' : '';

                                    subServiceSelect.append('<option value="' +
                                        subService.sub_service_id + '" ' +
                                        selected + '>' + optionText + '</option>');
                                });
                            }
                        },
                        error: function() {
                            subServiceSelect.html(
                                '<option value="">خطأ في تحميل الخدمات الفرعية</option>');
                        }
                    });
                } else {
                    subServiceSelect.prop('disabled', true);
                    subServiceSelect.html('<option value="">اختر الخدمة الرئيسية أولاً</option>');
                }
            });

            // Dynamic Car Model Loading
            $('#make_id').on('change', function() {
                var makeId = $(this).val();
                var modelSelect = $('#model_id');

                if (makeId) {
                    modelSelect.prop('disabled', false);
                    modelSelect.html('<option value="">جاري التحميل...</option>');

                    $.ajax({
                        url: '{{ route('admin.ajax.car_models') }}',
                        type: 'GET',
                        data: {
                            make_id: makeId
                        },
                        success: function(response) {
                            modelSelect.html('<option value="">اختر موديل السيارة</option>');

                            if (response.models && response.models.length > 0) {
                                $.each(response.models, function(index, model) {
                                    var modelNameAr = 'غير محدد';
                                    var modelNameEn = 'Not specified';

                                    if (typeof model.name === 'string') {
                                        try {
                                            var parsedName = JSON.parse(model.name);
                                            modelNameAr = parsedName.ar || parsedName
                                                .en || model.name;
                                            modelNameEn = parsedName.en || parsedName
                                                .ar || model.name;
                                        } catch (e) {
                                            modelNameAr = model.name;
                                            modelNameEn = model.name;
                                        }
                                    } else if (typeof model.name === 'object') {
                                        modelNameAr = model.name.ar || model.name.en ||
                                            'غير محدد';
                                        modelNameEn = model.name.en || model.name.ar ||
                                            'Not specified';
                                    }

                                    var selected =
                                        '{{ old('model_id', $getRecord->model_id) }}' ==
                                        model.model_id ? 'selected' : '';
                                    modelSelect.append('<option value="' + model
                                        .model_id + '" ' + selected + '>' +
                                        modelNameAr + ' - ' + modelNameEn +
                                        '</option>');
                                });
                            }
                        },
                        error: function() {
                            modelSelect.html(
                                '<option value="">خطأ في تحميل الموديلات</option>');
                        }
                    });
                } else {
                    modelSelect.prop('disabled', true);
                    modelSelect.html('<option value="">اختر ماركة السيارة أولاً</option>');
                }
            });

            // Trigger change events on page load if values are already selected
            if ($('#service_id').val()) {
                $('#service_id').trigger('change');
            }
            if ($('#make_id').val()) {
                $('#make_id').trigger('change');
            }
        });
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#editProductByCarForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');

            $submitBtn.prop('disabled', true);
            $btnText.html('جاري التحديث...');
            $submitBtn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        redirectToList(response.message, 'success');
                    } else {
                        window.showNotification('error', response.message);
                        resetSubmitButton($submitBtn, $btnText);
                    }
                },
                error: function(xhr) {
                    let message = 'حدث خطأ أثناء تعديل المنتج';
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        displayErrors(xhr.responseJSON.errors);
                        message = 'يرجى تصحيح الأخطاء المذكورة';
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    window.showNotification('error', message);
                    resetSubmitButton($submitBtn, $btnText);
                }
            });
        });

        function resetSubmitButton($btn, $text) {
            $btn.prop('disabled', false);
            $text.html('تحديث المنتج');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');
        }

        function displayErrors(errors) {
            console.log('displayErrors called with:', errors);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            for (const field in errors) {
                console.log(`Processing field: ${field}, errors:`, errors[field]);
                const $field = $(`#${field}`);
                console.log(`Field element found:`, $field.length > 0, $field);

                if ($field.length) {
                    $field.addClass('is-invalid');
                    $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    console.log(`Added error for ${field}:`, errors[field][0]);
                } else {
                    console.warn(`Field #${field} not found in DOM`);
                }
            }
        }
    </script>
@endsection
