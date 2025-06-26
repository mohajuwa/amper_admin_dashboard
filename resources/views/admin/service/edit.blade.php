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
                        <li class="breadcrumb-item"><a href="{{ route('admin.service.list') }}">الخدمات</a></li>
                        <li class="breadcrumb-item active">تعديل الخدمة</li>
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
                                تعديل بيانات الخدمة
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.service.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="editServiceForm" action="{{ route('admin.service.update', $getRecord->service_id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">
                                    <!-- Arabic Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_name_ar" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الخدمة (عربي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('service_name_ar') is-invalid @enderror"
                                                id="service_name_ar" name="service_name_ar"
                                                value="{{ old('service_name_ar', $getRecord->service_name['ar'] ?? '') }}"
                                                placeholder="أدخل اسم الخدمة باللغة العربية">
                                            @error('service_name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- English Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_name_en" class="required">
                                                <i class="fas fa-tag mr-1"></i>
                                                اسم الخدمة (إنجليزي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('service_name_en') is-invalid @enderror"
                                                id="service_name_en" name="service_name_en"
                                                value="{{ old('service_name_en', $getRecord->service_name['en'] ?? '') }}"
                                                placeholder="Enter service name in English">
                                            @error('service_name_en')
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
                                                حالة الخدمة
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="0"
                                                    {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}>نشط
                                                </option>
                                                <option value="1"
                                                    {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}>غير
                                                    نشط</option>

                                                <option value="2"
                                                    {{ old('status', $getRecord->status) == '2' ? 'selected' : '' }}>محذوف
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Current Image Display -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>الصورة الحالية</label>
                                            <div class="current-image-container">
                                                @if ($getRecord->service_img)
                                                    <div class="current-image-wrapper" style="max-width: 200px;">
                                                        <div class="svg-container bg-light border rounded p-3 text-center">
                                                            <img src="{{ $getRecord->getServiceImage() }}"
                                                                alt="Current Service SVG"
                                                                class="img-fluid current-service-svg"
                                                                style="max-width: 100%; max-height: 150px;">
                                                        </div>
                                                        <div class="image-overlay mt-2">
                                                            <small class="text-muted">الصورة الحالية (SVG)</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="no-image-placeholder text-center p-4 bg-light border rounded">
                                                        <i class="fas fa-image fa-3x text-muted"></i>
                                                        <p class="text-muted mt-2 mb-0">لا توجد صورة</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SVG Image Upload -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="service_image">
                                                <i class="fas fa-image mr-1"></i>
                                                تحديث صورة الخدمة (SVG فقط)
                                            </label>
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('service_image') is-invalid @enderror"
                                                    id="service_image" name="service_image" accept=".svg">
                                                <label class="custom-file-label" for="service_image">
                                                    اختر ملف SVG جديد أو اتركه فارغاً للاحتفاظ بالصورة الحالية
                                                </label>
                                                @error('service_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                النوع المدعوم: SVG فقط | الحد الأقصى: 2 ميجابايت
                                            </div>

                                            <!-- SVG Preview -->
                                            <div id="svgPreview" style="display: none; margin-top: 15px;">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            معاينة الصورة الجديدة
                                                        </h6>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="svgPreviewContainer" class="bg-light border rounded p-3">
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                id="removeSvgPreview">
                                                                <i class="fas fa-times mr-1"></i>
                                                                إزالة الصورة الجديدة
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <span class="info-box-text">معلومات الخدمة</span>
                                                <span class="info-box-number">
                                                    تاريخ الإنشاء:
                                                    {{ $getRecord->created_at ? $getRecord->created_at->format('Y-m-d H:i') : 'غير محدد' }}
                                                </span>
                                                @if ($getRecord->updated_at && $getRecord->updated_at != $getRecord->created_at)
                                                    <div class="progress py-4">
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
                                            <span class="btn-text">تحديث الخدمة</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.service.list') }}"
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
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.service.list') }}");
            }



            // SVG preview
            $('body').on('change', '#service_image', function(e) {
                const file = e.target.files[0];
                const $label = $(this).siblings('.custom-file-label');

                if (!file) return removeSvgPreview();

                $label.addClass('selected').text(file.name);

                if (!file.name.toLowerCase().endsWith('.svg')) {
                    window.showNotification('error', ' نوع الملف يجب أن يكون SVG فقط');

                    return removeSvgPreview();
                }

                if (file.size > 2 * 1024 * 1024) {

                    window.showNotification('error', 'الحجم يجب أن يكون أقل من 2 ميجابايت');
                    return removeSvgPreview();
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const content = e.target.result;
                    if (!content.includes('<svg')) {


                        window.showNotification('error', 'ملف SVG غير صالح');
                        return removeSvgPreview();
                    }

                    const $svg = $('<div>').html(content).find('svg').first();
                    if ($svg.length === 0) return showNotification('error', 'SVG غير صالح');

                    $svg.find('script').remove();
                    $svg.css({
                        maxWidth: '200px',
                        maxHeight: '150px',
                        width: 'auto',
                        height: 'auto'
                    });

                    $('#svgPreviewContainer').html($svg);
                    $('#svgPreview').show();


                    window.showNotification('success', 'تم تحميل الصورة بنجاح');

                };



                reader.onerror = () => window.showNotification('error', 'حدث خطأ في قراءة الملف');
                reader.readAsText(file);
            });

            function removeSvgPreview() {
                $('#svgPreview').hide();
                $('#svgPreviewContainer').empty();
                $('#service_image').val('');
                $('.custom-file-label').removeClass('selected').text(
                    'اختر ملف SVG جديد أو اتركه فارغاً للاحتفاظ بالصورة الحالية');
            }

            $('body').on('click', '#removeSvgPreview', function(e) {
                e.preventDefault();
                removeSvgPreview();
            });

            // Clear validation styles
            $('body').on('input change', '#service_name_ar, #service_name_en, #status, #service_image', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '0') $(this).addClass('border-success');
                else if (this.value === '1') $(this).addClass('border-warning');
            });
            $('#status').trigger('change');



            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#editServiceForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.service.list') }}";
                }
            });


        });
    </script>
@endsection


@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#editServiceForm', function(e) {
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
                data: new FormData($form[0]),
                processData: false,
                contentType: false,
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
                    let message = 'حدث خطأ أثناء تحديث الخدمة';
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
            $text.html('تحديث الخدمة');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');
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


        // Display field-specific validation errors
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
