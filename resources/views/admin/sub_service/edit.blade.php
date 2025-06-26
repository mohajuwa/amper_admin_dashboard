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
                        <li class="breadcrumb-item"><a href="{{ route('admin.sub_service.list') }}">الخدمات الفرعية</a></li>
                        <li class="breadcrumb-item active">تعديل الخدمة الفرعية</li>
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
                                تعديل بيانات الخدمة الفرعية
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.sub_service.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="editSubServiceForm"
                            action="{{ route('admin.sub_service.update', $getRecord->sub_service_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <!-- Parent Service -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="service_id" class="required">
                                                <i class="fas fa-cogs mr-1"></i>
                                                الخدمة الرئيسية
                                            </label>
                                            <select class="form-control @error('service_id') is-invalid @enderror"
                                                id="service_id" name="service_id">
                                                <option value="">اختر الخدمة الرئيسية</option>
                                                @foreach ($getServices as $service)
                                                    <option value="{{ $service->service_id }}"
                                                        {{ old('service_id', $getRecord->service_id) == $service->service_id ? 'selected' : '' }}>
                                                        {{ is_array($service->service_name) ? $service->service_name['ar'].' - '.$service->service_name['en'] ?? $service->service_name['ar'] : $service->service_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('service_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Names Row -->
                                <div class="row">
                                    <!-- Arabic Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_ar" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الخدمة الفرعية (عربي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('name_ar') is-invalid @enderror" id="name_ar"
                                                name="name_ar"
                                                value="{{ old('name_ar', $getRecord->getNameForLang('ar')) }}"
                                                placeholder="أدخل اسم الخدمة الفرعية باللغة العربية">
                                            @error('name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- English Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_en" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الخدمة الفرعية (إنجليزي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('name_en') is-invalid @enderror" id="name_en"
                                                name="name_en"
                                                value="{{ old('name_en', $getRecord->getNameForLang('en')) }}"
                                                placeholder="Enter sub-service name in English">
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Price and Status Row -->
                                <div class="row">
                                    <!-- Price -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price" class="required">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                السعر
                                            </label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('price') is-invalid @enderror"
                                                    id="price" name="price"
                                                    value="{{ old('price', $getRecord->price) }}" placeholder="0.00"
                                                    min="0" step="0.01">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">ريال</span>
                                                </div>
                                            </div>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة الخدمة الفرعية
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

                                <!-- Service Information Card -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-info">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">معلومات الخدمة الفرعية</span>
                                                <span class="info-box-number">
                                                    تاريخ الإنشاء:
                                                    {{ $getRecord->created_at ? $getRecord->created_at->format('Y-m-d H:i') : 'غير محدد' }}
                                                </span>
                                                @if ($getRecord->updated_at && $getRecord->updated_at != $getRecord->created_at)
                                                    <div class="progress-description">
                                                        <small>آخر تحديث:
                                                            {{ $getRecord->updated_at->format('Y-m-d H:i') }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                            <i class="fas fa-save mr-2"></i>
                                            <span class="btn-text">تحديث الخدمة الفرعية</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.sub_service.list') }}"
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
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.sub_service.list') }}");
            }

            // Clear validation styles
            $('body').on('input change', '#name_ar, #name_en, #price, #status, #service_id', function() {
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

            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#addSubServiceForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.sub_service.list') }}";
                }
            });

            // Price formatting
            $('body').on('blur', '#price', function() {
                let value = $(this).val();
                if (value && !isNaN(value)) {
                    $(this).val(parseFloat(value).toFixed(2));
                }
            });
        });
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#addSubServiceForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');

            $submitBtn.prop('disabled', true);
            $btnText.html('جاري الإضافة...');
            $submitBtn.find('i').removeClass('fa-plus').addClass('fa-spinner fa-spin');

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
                    let message = 'حدث خطأ أثناء إضافة الخدمة الفرعية';
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
            $text.html('إضافة الخدمة الفرعية');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-plus');
        }

        function displayErrors(errors) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            for (const field in errors) {
                const $field = $(`#${field}`);
                if ($field.length) {
                    $field.addClass('is-invalid');
                    $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        }

        // Debug version of displayErrors
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
