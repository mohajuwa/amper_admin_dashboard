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
                        <li class="breadcrumb-item active">تعديل كود الخصم</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Coupon Information Cards -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>
                                معلومات كود الخصم
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Days Remaining -->
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">باقي على انتهاء الكود</span>
                                            <span class="info-box-number">
                                                @php
                                                    $expireDate = \Carbon\Carbon::parse($getRecord->coupon_expiredate);
                                                    $now = \Carbon\Carbon::now();
                                                    $daysRemaining = $now->diffInDays($expireDate, false);

                                                    if ($daysRemaining > 0) {
                                                        echo $daysRemaining . ' يوم';
                                                    } elseif ($daysRemaining == 0) {
                                                        echo 'ينتهي اليوم';
                                                    } else {
                                                        echo 'منتهي الصلاحية';
                                                    }
                                                @endphp
                                            </span>
                                            <div class="progress">
                                                @php
                                                    $totalDays = \Carbon\Carbon::parse(
                                                        $getRecord->created_at,
                                                    )->diffInDays($expireDate);
                                                    $progressPercentage =
                                                        $totalDays > 0
                                                            ? max(0, min(100, ($daysRemaining / $totalDays) * 100))
                                                            : 0;
                                                    $progressColor =
                                                        $daysRemaining > 7
                                                            ? 'bg-success'
                                                            : ($daysRemaining > 3
                                                                ? 'bg-warning'
                                                                : 'bg-danger');
                                                @endphp
                                                <div class="progress-bar {{ $progressColor }}"
                                                    style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                {{ $expireDate->format('Y-m-d') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Usage Count -->
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">تم استخدامه</span>
                                            <span class="info-box-number">
                                                @php
                                                    // Assuming you have a used_count field or relationship
                                                    // Replace this with your actual usage count logic
                                                    $usedCount = $getRecord->used_count ?? 0; // or count from orders table
                                                    $totalCount = $getRecord->coupon_count;
                                                    $remainingCount = $totalCount - $usedCount;
                                                @endphp
                                                {{ $usedCount }} مرة
                                            </span>
                                            <div class="progress">
                                                @php
                                                    $usagePercentage =
                                                        $totalCount > 0 ? ($usedCount / $totalCount) * 100 : 0;
                                                    $usageColor =
                                                        $usagePercentage < 50
                                                            ? 'bg-success'
                                                            : ($usagePercentage < 80
                                                                ? 'bg-warning'
                                                                : 'bg-danger');
                                                @endphp
                                                <div class="progress-bar {{ $usageColor }}"
                                                    style="width: {{ $usagePercentage }}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                متبقي {{ $remainingCount }} من {{ $totalCount }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Days Since Creation -->
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-calendar-plus"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">مر على إطلاقه</span>
                                            <span class="info-box-number">
                                                @php
                                                    $createdDate = \Carbon\Carbon::parse($getRecord->created_at);
                                                    $daysSinceCreation = $createdDate->diffInDays($now);

                                                    if ($daysSinceCreation == 0) {
                                                        echo 'اليوم';
                                                    } elseif ($daysSinceCreation == 1) {
                                                        echo 'يوم واحد';
                                                    } elseif ($daysSinceCreation == 2) {
                                                        echo 'يومان';
                                                    } elseif ($daysSinceCreation < 11) {
                                                        echo $daysSinceCreation . ' أيام';
                                                    } else {
                                                        echo $daysSinceCreation . ' يوم';
                                                    }
                                                @endphp
                                            </span>
                                            <div class="progress">
                                                @php
                                                    $totalLifeDays = $createdDate->diffInDays($expireDate);
                                                    $lifePercentage =
                                                        $totalLifeDays > 0
                                                            ? ($daysSinceCreation / $totalLifeDays) * 100
                                                            : 0;
                                                    $lifeColor =
                                                        $lifePercentage < 30
                                                            ? 'bg-info'
                                                            : ($lifePercentage < 70
                                                                ? 'bg-primary'
                                                                : 'bg-warning');
                                                @endphp
                                                <div class="progress-bar {{ $lifeColor }}"
                                                    style="width: {{ min(100, $lifePercentage) }}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                {{ $createdDate->format('Y-m-d') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Status Information -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5><i class="icon fas fa-info"></i> ملخص حالة الكود:</h5>
                                        <ul class="mb-0">
                                            <li>
                                                <strong>الحالة:</strong>
                                                @if ($getRecord->coupon_status == 0)
                                                    <span class="badge badge-success">نشط</span>
                                                @elseif($getRecord->coupon_status == 1)
                                                    <span class="badge badge-warning">غير نشط</span>
                                                @else
                                                    <span class="badge badge-danger">محذوف</span>
                                                @endif
                                            </li>
                                            <li>
                                                <strong>نسبة الخصم:</strong> {{ $getRecord->coupon_discount }}%
                                            </li>
                                            <li>
                                                <strong>الفعالية:</strong>
                                                @if ($daysRemaining > 0 && $remainingCount > 0 && $getRecord->coupon_status == 0)
                                                    <span class="text-success">فعال ومتاح للاستخدام</span>
                                                @elseif($daysRemaining <= 0)
                                                    <span class="text-danger">منتهي الصلاحية</span>
                                                @elseif($remainingCount <= 0)
                                                    <span class="text-warning">تم استنفاد عدد مرات الاستخدام</span>
                                                @elseif($getRecord->coupon_status != 1)
                                                    <span class="text-secondary">غير نشط</span>
                                                @else
                                                    <span class="text-info">متاح</span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-2"></i>
                                تعديل بيانات كود الخصم
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.discount-codes.list') }}" class="btn btn-tool"
                                    title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="editDiscountCodeForm"
                            action="{{ route('admin.discount-codes.update', $getRecord->coupon_id) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                                value="{{ old('coupon_name', $getRecord->coupon_name) }}"
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
                                                value="{{ old('coupon_count', $getRecord->coupon_count) }}"
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
                                                value="{{ old('coupon_discount', $getRecord->coupon_discount) }}"
                                                placeholder="أدخل نسبة الخصم" min="0" max="100"
                                                step="0.01">
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
                                                تاريخ ووقت انتهاء الصلاحية
                                            </label>
                                            <input type="datetime-local"
                                                class="form-control @error('coupon_expiredate') is-invalid @enderror"
                                                id="coupon_expiredate" name="coupon_expiredate"
                                                value="{{ old('coupon_expiredate', $getRecord->coupon_expiredate ? \Carbon\Carbon::parse($getRecord->coupon_expiredate)->format('Y-m-d\TH:i') : '') }}">
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
                                                <option value="0"
                                                    {{ old('status', $getRecord->coupon_status) == '0' ? 'selected' : '' }}>
                                                    نشط</option>
                                                <option value="1"
                                                    {{ old('status', $getRecord->coupon_status) == '1' ? 'selected' : '' }}>
                                                    غير نشط</option>
                                                <option value="2"
                                                    {{ old('status', $getRecord->coupon_status) == '2' ? 'selected' : '' }}>
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
                                            <i class="fas fa-save mr-2"></i>
                                            <span class="btn-text">تحديث كود الخصم</span>
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
            $('body').on('input change',
                '#coupon_name, #coupon_count, #coupon_discount, #coupon_expiredate, #status',
                function() {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                });

            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning border-danger');
                if (this.value === '0') $(this).addClass('border-success');
                else if (this.value === '1') $(this).addClass('border-warning');
                else if (this.value === '2') $(this).addClass('border-danger');
            });


            // Prevent form submission on Enter key press (except submit button)
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#editDiscountCodeForm').submit();
                }
                // Escape to go back to list
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.discount-codes.list') }}";
                }
            });

            // Discount percentage validation (0-100)
            $('body').on('input', '#coupon_discount', function() {
                let value = parseFloat($(this).val());
                if (value > 100) {
                    $(this).val(100);
                } else if (value < 0) {
                    $(this).val(0);
                }
            });

            // Usage count validation (minimum 1)
            $('body').on('input', '#coupon_count', function() {
                let value = parseInt($(this).val());
                if (value < 1) {
                    $(this).val(1);
                }
            });

            // Discount formatting on blur
            $('body').on('blur', '#coupon_discount', function() {
                let value = $(this).val();
                if (value && !isNaN(value)) {
                    $(this).val(parseFloat(value).toFixed(2));
                }
            });

            // Expire date validation (cannot be in the past)
            $('body').on('change', '#coupon_expiredate', function() {
                let selectedDate = new Date($(this).val());
                let today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    $(this).addClass('is-invalid');
                    if (!$(this).siblings('.invalid-feedback').length) {
                        $(this).after(
                            '<div class="invalid-feedback">تاريخ انتهاء الصلاحية لا يمكن أن يكون في الماضي</div>'
                        );
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }
            });
        });
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('submit', '#editDiscountCodeForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');

            // Disable submit button and show loading state
            $submitBtn.prop('disabled', true);
            $btnText.html('جاري التحديث...');
            $submitBtn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');

            // Fix datetime-local format before sending
            const expireDateInput = $('#coupon_expiredate');
            if (expireDateInput.val()) {
                const originalValue = expireDateInput.val();
                // Convert from "2024-12-25T14:30" to "2024-12-25 14:30:00"
                const formattedValue = originalValue.replace('T', ' ') + ':00';
                expireDateInput.val(formattedValue);
            }

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
                        // Restore original datetime-local format
                        if (expireDateInput.val()) {
                            expireDateInput.val(originalValue);
                        }
                    }
                },
                error: function(xhr) {
                    let message = 'حدث خطأ أثناء تحديث كود الخصم';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        displayErrors(xhr.responseJSON.errors);
                        message = 'يرجى تصحيح الأخطاء المذكورة';
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    window.showNotification('error', message);
                    resetSubmitButton($submitBtn, $btnText);

                    // Restore original datetime-local format on error
                    if (expireDateInput.val()) {
                        expireDateInput.val(originalValue);
                    }
                }
            });
        });

        function resetSubmitButton($btn, $text) {
            $btn.prop('disabled', false);
            $text.html('تحديث كود الخصم');
            $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');
        }

        function displayErrors(errors) {
            console.log('displayErrors called with:', errors);

            // Clear previous error states
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            // Display new errors
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

            // Scroll to first error field
            const firstErrorField = $('.is-invalid').first();
            if (firstErrorField.length) {
                $('html, body').animate({
                    scrollTop: firstErrorField.offset().top - 100
                }, 500);
                firstErrorField.focus();
            }
        }
    </script>
@endsection
