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
                        <li class="breadcrumb-item"><a href="{{ route('admin.admin.list') }}">المشرفين</a></li>
                        <li class="breadcrumb-item active">إضافة مشرف</li>
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
                                إضافة مشرف جديد
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.admin.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="addAdminForm" action="{{ route('admin.admin.store') }}" method="POST">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <!-- Full Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="required">
                                                <i class="fas fa-user mr-1"></i>
                                                الاسم الكامل
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}"
                                                placeholder="أدخل الاسم الكامل">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="required">
                                                <i class="fas fa-envelope mr-1"></i>
                                                البريد الإلكتروني
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}"
                                                placeholder="أدخل البريد الإلكتروني">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="required">
                                                <i class="fas fa-lock mr-1"></i>
                                                كلمة المرور
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" placeholder="أدخل كلمة المرور">

                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="required">
                                                <i class="fas fa-lock mr-1"></i>
                                                تأكيد كلمة المرور
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="أعد إدخال كلمة المرور">

                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة المشرف
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                    نشط
                                                </option>
                                                <option value="inactive"
                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                    غير نشط
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role" class="required">
                                                <i class="fas fa-user-tag mr-1"></i>
                                                الدور الوظيفي
                                            </label>
                                            <select class="form-control @error('role') is-invalid @enderror" id="role"
                                                name="role">
                                                <option value="">اختر الدور</option>
                                                <option value="super_admin"
                                                    {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                                                    مدير عام
                                                </option>
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                    مدير
                                                </option>
                                                <option value="moderator"
                                                    {{ old('role') == 'moderator' ? 'selected' : '' }}>
                                                    مشرف
                                                </option>
                                                <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>
                                                    محرر
                                                </option>
                                                <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>
                                                    مشاهد
                                                </option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Description Card -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon bg-success">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">وصف الأدوار</span>
                                                <div class="info-box-description">
                                                    <small>
                                                        <strong>مدير عام:</strong> جميع الصلاحيات<br>
                                                        <strong>مدير:</strong> إدارة المحتوى والمستخدمين<br>
                                                        <strong>مشرف:</strong> مراقبة المحتوى<br>
                                                        <strong>محرر:</strong> تحرير المحتوى<br>
                                                        <strong>مشاهد:</strong> عرض المحتوى فقط
                                                    </small>
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
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                            <i class="fas fa-plus mr-2"></i>
                                            <span class="btn-text">إضافة المشرف</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.admin.list') }}"
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
            // Show notification
            function showNotification(type, message) {
                if (typeof showCustomAlert === 'function') {
                    showCustomAlert(message, type);
                } else if (typeof showSuccess === 'function' && type === 'success') {
                    showSuccess(message);
                } else if (typeof showError === 'function' && type === 'error') {
                    showError(message);
                } else if (typeof toastr !== 'undefined') {
                    toastr[type](message);
                } else {
                    alert(message);
                }
            }

            // Redirect with flash message
            function redirectToList(message, type) {
                if (typeof(Storage) !== "undefined") {
                    sessionStorage.setItem('alertMessage', message);
                    sessionStorage.setItem('alertType', type);
                }
                window.location.href = "{{ route('admin.admin.list') }}";
            }

            // Submit form
            $('body').on('submit', '#addAdminForm', function(e) {
                e.preventDefault();

                // Password confirmation check
                const password = $('#password').val();
                const passwordConfirm = $('#password_confirmation').val();

                if (password !== passwordConfirm) {
                    showNotification('error', 'كلمة المرور وتأكيد كلمة المرور غير متطابقين');
                    $('#password_confirmation').addClass('is-invalid');
                    $('#password_confirmation').after(
                        '<div class="invalid-feedback">كلمة المرور وتأكيد كلمة المرور غير متطابقين</div>'
                        );
                    return;
                }

                const $form = $(this);
                const $submitBtn = $('#submitBtn');
                const $btnText = $submitBtn.find('.btn-text');

                $submitBtn.prop('disabled', true);
                $btnText.html('جاري الإضافة...');
                $submitBtn.find('i').removeClass('fa-plus').addClass('fa-spinner fa-spin');

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
                            showNotification('error', response.message);
                            resetSubmitButton($submitBtn, $btnText);
                        }
                    },
                    error: function(xhr) {
                        let message = 'حدث خطأ أثناء إضافة المشرف';
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            displayErrors(xhr.responseJSON.errors);
                            message = 'يرجى تصحيح الأخطاء المذكورة';
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }
                        showNotification('error', message);
                        resetSubmitButton($submitBtn, $btnText);
                    }
                });
            });

            function resetSubmitButton($btn, $text) {
                $btn.prop('disabled', false);
                $text.html('إضافة المشرف');
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

        
  
            // Clear validation styles
            $('body').on('input change', '#name, #email, #password, #password_confirmation, #status, #role',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                });

            // Real-time password confirmation check
            $('body').on('input', '#password_confirmation', function() {
                const password = $('#password').val();
                const passwordConfirm = $(this).val();

                if (passwordConfirm && password !== passwordConfirm) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                    $(this).after('<div class="invalid-feedback">كلمة المرور غير متطابقة</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning');
                if (this.value === 'active') $(this).addClass('border-success');
                else if (this.value === 'inactive') $(this).addClass('border-warning');
            });

            // Role border color
            $('body').on('change', '#role', function() {
                $(this).removeClass('border-primary border-info border-warning border-secondary');
                if (this.value === 'super_admin') $(this).addClass('border-primary');
                else if (this.value === 'admin') $(this).addClass('border-info');
                else if (this.value === 'moderator') $(this).addClass('border-warning');
                else if (this.value === 'editor' || this.value === 'viewer') $(this).addClass(
                    'border-secondary');
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#addAdminForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.admin.list') }}";
                }
            });

            // Show saved alerts
            if (typeof(Storage) !== "undefined") {
                const msg = sessionStorage.getItem('alertMessage');
                const type = sessionStorage.getItem('alertType');
                if (msg && type) {
                    showNotification(type, msg);
                    sessionStorage.removeItem('alertMessage');
                    sessionStorage.removeItem('alertType');
                }
            }

            // Prevent submit on Enter (except for submit button)
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Initialize border colors on page load
            $('#status').trigger('change');
            $('#role').trigger('change');
        });
    </script>
@endsection
