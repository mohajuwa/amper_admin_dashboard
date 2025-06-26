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
                        <li class="breadcrumb-item"><a href="{{ route('admin.customer.list') }}">العملاء</a></li>
                        <li class="breadcrumb-item active">إضافة عميل</li>
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
                                <i class="fas fa-user-plus mr-2"></i>
                                إضافة عميل جديد
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.customer.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="addCustomerForm" action="{{ route('admin.customer.store') }}" method="POST">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <!-- Full Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="full_name" class="required">
                                                <i class="fas fa-user mr-1"></i>
                                                الاسم الكامل
                                            </label>
                                            <input type="text"
                                                class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                                                name="full_name" value="{{ old('full_name') }}"
                                                placeholder="أدخل الاسم الكامل">
                                            @error('full_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="required">
                                                <i class="fas fa-phone mr-1"></i>
                                                رقم الهاتف
                                            </label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone" value="{{ old('phone') }}"
                                                placeholder="أدخل رقم الهاتف">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Verification Code -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="verfiycode">
                                                <i class="fas fa-key mr-1"></i>
                                                كود التحقق
                                            </label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('verfiycode') is-invalid @enderror"
                                                    id="verfiycode" name="verfiycode" min="0" max="9999"
                                                    value="{{ old('verfiycode') }}" placeholder="كود التحقق (4 أرقام)">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outlined-secondary"
                                                        id="generateCode" title="توليد كود عشوائي">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                                @error('verfiycode')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                كود مكون من 4 أرقام للتحقق من الهوية
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة العميل
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="active"
                                                    {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                                    نشط
                                                </option>
                                                <option value="inactive"
                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                    غير نشط
                                                </option>
                                                <option value="banned" {{ old('status') == 'banned' ? 'selected' : '' }}>
                                                    محظور
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Approval Status -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="approve" class="required">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                حالة الموافقة
                                            </label>
                                            <select class="form-control @error('approve') is-invalid @enderror"
                                                id="approve" name="approve">
                                                <option value="">اختر حالة الموافقة</option>
                                                <option value="1" {{ old('approve', '1') == '1' ? 'selected' : '' }}>
                                                    موافق
                                                </option>
                                                <option value="0" {{ old('approve') == '0' ? 'selected' : '' }}>
                                                    غير موافق
                                                </option>
                                            </select>
                                            @error('approve')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Instructions Card -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-success">
                                                <i class="fas fa-lightbulb"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">تعليمات إضافة العميل</span>
                                                <span class="info-box-number">
                                                    • تأكد من صحة رقم الهاتف قبل الحفظ
                                                    <br>• كود التحقق يمكن توليده تلقائياً أو إدخاله يدوياً
                                                    <br>• العميل الجديد سيكون نشطاً وموافق عليه افتراضياً
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                            <i class="fas fa-user-plus mr-2"></i>
                                            <span class="btn-text">إضافة العميل</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-lg ml-2"
                                            id="resetBtn">
                                            <i class="fas fa-undo mr-2"></i>
                                            إعادة تعيين
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.customer.list') }}"
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
        // Customer add script integrated with global flash handler
        $(document).ready(function() {
            // Redirect with flash message using global handler
            function redirectToList(message, type) {
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.customer.list') }}");
            }

            // Generate random verification code
            $('body').on('click', '#generateCode', function() {
                const randomCode = Math.floor(1000 + Math.random() * 9000);
                $('#verfiycode').val(randomCode);
                $(this).find('i').addClass('fa-spin');
                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                }, 500);
            });

            // Reset form
            $('body').on('click', '#resetBtn', function() {
                $('#addCustomerForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('#status').val('active').trigger('change');
                $('#approve').val('1').trigger('change');
                window.showNotification('info', 'تم إعادة تعيين النموذج');
            });


            // Clear validation styles
            $('body').on('input change', '#full_name, #phone, #verfiycode, #status, #approve', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === 'active') $(this).addClass('border-success');
                else if (this.value === 'inactive') $(this).addClass('border-warning');
                else if (this.value === 'banned') $(this).addClass('border-danger');
            });
            $('#status').trigger('change');

            // Approve border color
            $('body').on('change', '#approve', function() {
                $(this).removeClass('border-success border-warning');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-warning');
            });
            $('#approve').trigger('change');

            // Phone number formatting
            $('body').on('input', '#phone', function() {
                let value = $(this).val().replace(/[^\d+]/g, '');
                $(this).val(value);
            });
            $('#phone').trigger('change');

            // Verification code validation
            $('body').on('input', '#verfiycode', function() {
                let value = $(this).val();
                if (value.length > 4) {
                    $(this).val(value.slice(0, 4));
                }
                if (value < 0) {
                    $(this).val('');
                }
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) { // Ctrl+S
                    e.preventDefault();
                    $('#addCustomerForm').submit();
                }
                if (e.ctrlKey && e.which === 82) { // Ctrl+R
                    e.preventDefault();
                    $('#resetBtn').click();
                }
                if (e.which === 27) { // ESC
                    window.location.href = "{{ route('admin.customer.list') }}";
                }
            });

            // Prevent submit on Enter (except submit buttons)
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Auto-focus first input
            $('#full_name').focus();

            // Note: Global flash handler manages regular flash messages automatically
            // Validation errors are handled locally with field highlighting
        });
    </script>
@endsection
@section('scripts')
    <script type="text/javascript">
        // Submit form with custom validation error handling
        $('body').on('submit', '#addCustomerForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');

            // Handle button state
            $submitBtn.prop('disabled', true);
            $btnText.html('جاري الإضافة...');
            $submitBtn.find('i').removeClass('fa-user-plus').addClass('fa-spinner fa-spin');

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
                    // Debug: Log the full error response
                    console.log('Full XHR Response:', xhr);
                    console.log('Response JSON:', xhr.responseJSON);
                    console.log('Status:', xhr.status);

                    let message = 'حدث خطأ أثناء إضافة العميل';

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

        // Reset submit button helper
        function resetSubmitButton($btn, $text) {
            $btn.prop('disabled', false);
            $text.html('إضافة العميل');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-user-plus');
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
