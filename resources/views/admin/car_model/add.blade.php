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
                        <li class="breadcrumb-item"><a href="{{ route('admin.car_model.list') }}">موديلات السيارات</a></li>
                        <li class="breadcrumb-item active">إضافة موديل جديد</li>
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
                                <i class="fas fa-plus mr-2"></i>
                                إضافة موديل سيارة جديد
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.car_model.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="addCarModelForm" action="{{ route('admin.car_model.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <!-- Car Make -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="make_id" class="required">
                                                <i class="fas fa-car mr-1"></i>
                                                ماركة السيارة
                                            </label>
                                            <select class="form-control select2 @error('make_id') is-invalid @enderror"
                                                id="make_id" name="make_id">
                                                <option value="">اختر ماركة السيارة</option>
                                                @foreach ($carMakes as $car_model)
                                                    <option value="{{ $car_model->make_id }}"
                                                        {{ old('make_id', $car_model->make_id) == $car_model->make_id ? 'selected' : '' }}>
                                                        @php
                                                            $name = is_array($car_model->name)
                                                                ? $car_model->name
                                                                : ['ar' => $car_model->name, 'en' => $car_model->name];
                                                            $nameAr = $name['ar'] ?? $name['en'];
                                                            $nameEn = $name['en'] ?? $name['ar'];
                                                        @endphp
                                                        {{ $nameAr }} - {{ $nameEn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('make_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Arabic Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model_name_ar" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الموديل (عربي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('model_name_ar') is-invalid @enderror"
                                                id="model_name_ar" name="model_name_ar" value="{{ old('model_name_ar') }}"
                                                placeholder="أدخل اسم الموديل باللغة العربية">
                                            @error('model_name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- English Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model_name_en" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الموديل (إنجليزي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('model_name_en') is-invalid @enderror"
                                                id="model_name_en" name="model_name_en" value="{{ old('model_name_en') }}"
                                                placeholder="Enter model name in English">
                                            @error('model_name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة الموديل
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>نشط
                                                </option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير نشط
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Car Model Information Card -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-info">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">معلومات مهمة</span>
                                                <span class="info-box-number">
                                                    تأكد من اختيار الماركة المناسبة وإدخال اسم الموديل بشكل صحيح
                                                </span>
                                                <div class="progress">
                                                    <small>سيتم حفظ الموديل وربطه بالماركة المحددة</small>
                                                </div>
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
                                            <span class="btn-text">حفظ الموديل</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.car_model.list') }}"
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
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.car_model.list') }}");
            }

            // Clear validation styles
            $('body').on('input change', '#model_name_ar, #model_name_en, #status, #make_id', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-warning');
            }).trigger('change');

            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#addCarModelForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.car_model.list') }}";
                }
            });




        });
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#addCarModelForm', function(e) {
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

    </script>
@endsection
