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
                        <li class="breadcrumb-item"><a href="{{ route('admin.discount-codes.list') }}">أكواد الخصم</a></li>
                        <li class="breadcrumb-item active">إضافة كود خصم جديد</li>
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
                                إضافة كود خصم جديد
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.discount-codes.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="addDiscountCodeForm" action="{{ route('admin.discount-codes.store') }}"
                            method="POST">
                            @csrf

                            <div class="card-body">
                                <!-- Coupon Name -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="coupon_name" class="required">
                                                <i class="fas fa-ticket-alt mr-1"></i>
                                                اسم كود الخصم
                                            </label>
                                            <input type="text"
                                                class="form-control @error('coupon_name') is-invalid @enderror"
                                                id="coupon_name" name="coupon_name"
                                                value="{{ old('coupon_name') }}"
                                                placeholder="أدخل اسم كود الخصم">
                                            @error('coupon_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Count and Discount Row -->
                                <div class="row">
                                    <!-- Usage Count -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="coupon_count" class="required">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                عدد مرات الاستخدام
                                            </label>
                                            <input type="number"
                                                class="form-control @error('coupon_count') is-invalid @enderror"
                                                id="coupon_count" name="coupon_count"
                                                value="{{ old('coupon_count') }}"
                                                placeholder="أدخل عدد مرات الاستخدام" min="1">
                                            @error('coupon_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Discount Percentage -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="coupon_discount" class="required">
                                                <i class="fas fa-percent mr-1"></i>
                                                نسبة الخصم
                                            </label>
                                            <input type="number"
                                                class="form-control @error('coupon_discount') is-invalid @enderror"
                                                id="coupon_discount" name="coupon_discount"
                                                value="{{ old('coupon_discount') }}"
                                                placeholder="أدخل نسبة الخصم" min="0" max="100" step="0.01">
                                            @error('coupon_discount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Expire Date and Status Row -->
                                <div class="row">
                                    <!-- Expire Date -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="coupon_expiredate" class="required">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                تاريخ انتهاء الصلاحية
                                            </label>
                                            <input type="date"
                                                class="form-control @error('coupon_expiredate') is-invalid @enderror"
                                                id="coupon_expiredate" name="coupon_expiredate"
                                                value="{{ old('coupon_expiredate') }}">
                                            @error('coupon_expiredate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">
                                                <i class="fas fa-toggle-on mr-1"></i>
                                                حالة كود الخصم
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status">
                                                <option value="">اختر الحالة</option>
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                                    نشط</option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                                    غير نشط</option>
                                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>
                                                    محذوف</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer with Action Buttons -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                            <i class="fas fa-plus mr-2"></i>
                                            <span class="btn-text">إضافة كود الخصم</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.discount-codes.list') }}"
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
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.discount-codes.list') }}");
            }

            // Clear validation styles
            $('body').on('input change', '#coupon_name, #coupon_count, #coupon_discount, #coupon_expiredate, #status', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-warning');
                else if (this.value === '2') $(this).addClass('border-danger');
            });

            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#addDiscountCodeForm').submit();
                }
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.discount-codes.list') }}";
                }
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            $('#coupon_expiredate').attr('min', today);

            // Discount formatting
            $('body').on('blur', '#coupon_discount', function() {
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
        $('body').on('submit', '#addDiscountCodeForm', function(e) {
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
                    let message = 'حدث خطأ أثناء إضافة كود الخصم';
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
            $text.html('إضافة كود الخصم');
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