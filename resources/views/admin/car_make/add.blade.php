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
                        <li class="breadcrumb-item"><a href="{{ route('admin.car_make.list') }}">ماركات السيارات</a></li>
                        <li class="breadcrumb-item active">إضافة ماركة جديدة</li>
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
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus mr-2"></i>
                                إضافة ماركة سيارات جديدة
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.car_make.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="addCarMakeForm" action="{{ route('admin.car_make.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <!-- Arabic Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="make_name_ar" class="required">
                                                <i class="fas fa-car mr-1"></i>
                                                اسم الماركة (عربي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('make_name_ar') is-invalid @enderror"
                                                id="make_name_ar" name="make_name_ar"
                                                value="{{ old('make_name_ar') }}"
                                                placeholder="أدخل اسم الماركة باللغة العربية">
                                            @error('make_name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- English Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="make_name_en" class="required">
                                                <i class="fas fa-car mr-1"></i>
                                                اسم الماركة (إنجليزي)
                                            </label>
                                            <input type="text"
                                                class="form-control @error('make_name_en') is-invalid @enderror"
                                                id="make_name_en" name="make_name_en"
                                                value="{{ old('make_name_en') }}"
                                                placeholder="Enter car make name in English">
                                            @error('make_name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Status -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة الماركة
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>نشط
                                                </option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير نشط
                                                </option>
                                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>محذوف
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Popularity -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="popularity">
                                                <i class="fas fa-star mr-1"></i>
                                                مستوى الشهرة
                                            </label>
                                            <input type="number"
                                                class="form-control @error('popularity') is-invalid @enderror"
                                                id="popularity" name="popularity"
                                                value="{{ old('popularity', 0) }}"
                                                min="0" max="100" step="1"
                                                placeholder="0-100">
                                            @error('popularity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                أدخل رقم من 0 إلى 100 (0 = غير محدد، 100 = الأعلى شهرة)
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Popularity Preview -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>معاينة مستوى الشهرة</label>
                                            <div id="popularityPreview" class="p-3 bg-light border rounded text-center">
                                                <span class="badge badge-secondary popularity-badge-preview">
                                                    <span class="popularity-icon">❓</span>
                                                    <span class="popularity-score">0</span>
                                                    <span class="popularity-label">غير محدد</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo Upload -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="make_logo">
                                                <i class="fas fa-image mr-1"></i>
                                                شعار الماركة
                                            </label>
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('make_logo') is-invalid @enderror"
                                                    id="make_logo" name="make_logo" accept="image/*">
                                                <label class="custom-file-label" for="make_logo">
                                                    اختر شعار الماركة
                                                </label>
                                                @error('make_logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                الأنواع المدعومة: JPG, PNG, SVG | الحد الأقصى: 5 ميجابايت
                                            </div>

                                            <!-- Logo Preview -->
                                            <div id="logoPreview" style="display: none; margin-top: 15px;">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="card-title mb-0">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            معاينة الشعار
                                                        </h6>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="logoPreviewContainer" class="bg-light border rounded p-3">
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                id="removeLogoPreview">
                                                                <i class="fas fa-times mr-1"></i>
                                                                إزالة الشعار
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Car Make Information Tips -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h5><i class="icon fas fa-info-circle"></i> نصائح:</h5>
                                            <ul class="mb-0">
                                                <li>تأكد من إدخال اسم الماركة بكلا اللغتين العربية والإنجليزية</li>
                                                <li>اختر شعار عالي الجودة للحصول على أفضل عرض</li>
                                                <li>مستوى الشهرة يساعد في ترتيب الماركات (الأعلى شهرة تظهر أولاً)</li>
                                                <li>الحد الأقصى لحجم الشعار هو 5 ميجابايت</li>
                                                <li>يمكنك تغيير حالة الماركة ومستوى الشهرة لاحقاً من صفحة القائمة</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                            <i class="fas fa-plus mr-2"></i>
                                            <span class="btn-text">إضافة الماركة</span>
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg ml-2"
                                            id="resetBtn">
                                            <i class="fas fa-undo mr-2"></i>
                                            إعادة تعيين
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.car_make.list') }}"
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
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.car_make.list') }}");
            }

            // Popularity preview handler
            $('body').on('input', '#popularity', function() {
                const popularity = parseInt($(this).val()) || 0;
                updatePopularityPreview(popularity);
            });

            function updatePopularityPreview(popularity) {
                let badgeClass = '';
                let label = '';
                let icon = '';
                
                if (popularity >= 90) {
                    badgeClass = 'badge-gradient-gold';
                    label = 'الاعلى شهرة';
                    icon = '🏆';
                } else if (popularity >= 80) {
                    badgeClass = 'badge-gradient-purple';
                    label = 'شهيرة جداً';
                    icon = '🥇';
                } else if (popularity >= 70) {
                    badgeClass = 'badge-gradient-blue';
                    label = 'شهرة جيدة';
                    icon = '🥈';
                } else if (popularity >= 60) {
                    badgeClass = 'badge-gradient-green';
                    label = 'جيد';
                    icon = '🥉';
                } else if (popularity >= 40) {
                    badgeClass = 'badge-gradient-orange';
                    label = 'مقبول';
                    icon = '📊';
                } else if (popularity > 0) {
                    badgeClass = 'badge-gradient-red';
                    label = 'ضعيف';
                    icon = '📉';
                } else {
                    badgeClass = 'badge-secondary';
                    label = 'غير محدد';
                    icon = '❓';
                }

                $('#popularityPreview .popularity-badge-preview')
                    .removeClass('badge-gradient-gold badge-gradient-purple badge-gradient-blue badge-gradient-green badge-gradient-orange badge-gradient-red badge-secondary')
                    .addClass('badge ' + badgeClass)
                    .find('.popularity-icon').text(icon).end()
                    .find('.popularity-score').text(popularity).end()
                    .find('.popularity-label').text(label);
            }

            // Initialize popularity preview
            updatePopularityPreview(parseInt($('#popularity').val()) || 0);

            // Logo preview handler
            $('body').on('change', '#make_logo', function(e) {
                const file = e.target.files[0];
                const $label = $(this).siblings('.custom-file-label');

                if (!file) return removeLogoPreview();

                $label.addClass('selected').text(file.name);

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    window.showNotification('error', 'نوع الملف يجب أن يكون JPG, PNG, أو SVG فقط');
                    return removeLogoPreview();
                }

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    window.showNotification('error', 'الحجم يجب أن يكون أقل من 5 ميجابايت');
                    return removeLogoPreview();
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    let content;
                    
                    if (file.type === 'image/svg+xml') {
                        // Handle SVG
                        content = e.target.result;
                        if (!content.includes('<svg')) {
                            window.showNotification('error', 'ملف SVG غير صالح');
                            return removeLogoPreview();
                        }
                        const $svg = $('<div>').html(content).find('svg').first();
                        $svg.find('script').remove(); // Sanitize
                        $svg.css({
                            maxWidth: '200px',
                            maxHeight: '150px',
                            width: 'auto',
                            height: 'auto'
                        });
                        $('#logoPreviewContainer').html($svg);
                    } else {
                        // Handle regular images
                        const img = $('<img>').attr('src', e.target.result).css({
                            maxWidth: '200px',
                            maxHeight: '150px',
                            objectFit: 'contain',
                            borderRadius: '5px'
                        });
                        $('#logoPreviewContainer').html(img);
                    }

                    $('#logoPreview').show();
                    window.showNotification('success', 'تم تحميل الشعار بنجاح');
                };

                reader.onerror = () => window.showNotification('error', 'حدث خطأ في قراءة الملف');
                
                if (file.type === 'image/svg+xml') {
                    reader.readAsText(file);
                } else {
                    reader.readAsDataURL(file);
                }
            });

            // Remove logo preview
            function removeLogoPreview() {
                $('#logoPreview').hide();
                $('#logoPreviewContainer').empty();
                $('#make_logo').val('');
                $('.custom-file-label').removeClass('selected').text('اختر شعار الماركة');
            }

            $('body').on('click', '#removeLogoPreview', function(e) {
                e.preventDefault();
                removeLogoPreview();
            });

            // Form validation handling
            $('body').on('input change', '#make_name_ar, #make_name_en, #status, #popularity, #make_logo', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Status indicator
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-warning');
                else if (this.value === '2') $(this).addClass('border-danger');
            }).trigger('change');

            // Prevent form submit on Enter
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) { // Ctrl+S
                    e.preventDefault();
                    $('#addCarMakeForm').submit();
                }
                if (e.which === 27) { // Esc
                    window.location.href = "{{ route('admin.car_make.list') }}";
                }
            });

            // Reset form handler
            $('body').on('click', '#resetBtn', function(e) {
                e.preventDefault();
                document.getElementById('addCarMakeForm').reset();
                removeLogoPreview();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#status').trigger('change');
                updatePopularityPreview(0);
                window.showNotification('info', 'تم إعادة تعيين النموذج');
            });
        });
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#addCarMakeForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');

            // UI Feedback
            $submitBtn.prop('disabled', true);
            $btnText.html('جاري الإضافة...');
            $submitBtn.find('i').removeClass('fa-plus').addClass('fa-spinner fa-spin');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: new FormData($form[0]),
                processData: false,
                contentType: false,
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                success: function(response) {
                    if (response.success) {
                        redirectToList(response.message, 'success');
                    } else {
                        window.showNotification('error', response.message);
                        resetSubmitButton($submitBtn, $btnText);
                    }
                },
                error: function(xhr) {
                    let message = 'حدث خطأ أثناء إضافة الماركة';
                    if (xhr.status === 422) {
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

        // Reset button state
        function resetSubmitButton($btn, $text) {
            $btn.prop('disabled', false);
            $text.html('إضافة الماركة');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-plus');
        }

        // Show validation errors
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