@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $header_title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vendor.list') }}">التجار</a></li>
                        <li class="breadcrumb-item active">إضافة تاجر جديد</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus mr-2"></i>
                                إضافة تاجر جديد
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.vendor.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <form id="addVendorForm" action="{{ route('admin.vendor.store') }}" method="POST">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name_ar" class="required">اسم التاجر (عربي)</label>
                                            <input type="text" class="form-control @error('vendor_name_ar') is-invalid @enderror" id="vendor_name_ar" name="vendor_name_ar" value="{{ old('vendor_name_ar') }}" placeholder="أدخل اسم التاجر باللغة العربية">
                                            @error('vendor_name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name_en" class="required">اسم التاجر (إنجليزي)</label>
                                            <input type="text" class="form-control @error('vendor_name_en') is-invalid @enderror" id="vendor_name_en" name="vendor_name_en" value="{{ old('vendor_name_en') }}" placeholder="Enter vendor name in English">
                                            @error('vendor_name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="owner_name_ar" class="required">اسم المالك (عربي)</label>
                                            <input type="text" class="form-control @error('owner_name_ar') is-invalid @enderror" id="owner_name_ar" name="owner_name_ar" value="{{ old('owner_name_ar') }}" placeholder="أدخل اسم المالك باللغة العربية">
                                            @error('owner_name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="owner_name_en" class="required">اسم المالك (إنجليزي)</label>
                                            <input type="text" class="form-control @error('owner_name_en') is-invalid @enderror" id="owner_name_en" name="owner_name_en" value="{{ old('owner_name_en') }}" placeholder="Enter owner name in English">
                                            @error('owner_name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="required">رقم الهاتف</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="أدخل رقم الهاتف">
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_type" class="required">نوع التاجر</label>
                                            <select class="form-control @error('vendor_type') is-invalid @enderror" id="vendor_type" name="vendor_type">
                                                <option value="">اختر النوع</option>
                                                <option value="workshop" {{ old('vendor_type') == 'workshop' ? 'selected' : '' }}>ورشة</option>
                                                <option value="tow_truck" {{ old('vendor_type') == 'tow_truck' ? 'selected' : '' }}>سطحة</option>
                                            </select>
                                            @error('vendor_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="required">كلمة المرور</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="أدخل كلمة المرور">
                                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="required">تأكيد كلمة المرور</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="أعد إدخال كلمة المرور">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">العنوان</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="أدخل العنوان التفصيلي">{{ old('address') }}</textarea>
                                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">الحالة</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description_ar">الوصف (عربي)</label>
                                            <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3" placeholder="أدخل وصفًا للتاجر باللغة العربية">{{ old('description_ar') }}</textarea>
                                            @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description_en">الوصف (إنجليزي)</label>
                                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3" placeholder="Enter vendor description in English">{{ old('description_en') }}</textarea>
                                            @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                            <i class="fas fa-save mr-2"></i>
                                            <span class="btn-text">حفظ التاجر</span>
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ route('admin.vendor.list') }}" class="btn btn-outline-secondary btn-lg">
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

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Define all vendor form fields
            const vendorFields = '#vendor_name_ar, #vendor_name_en, #owner_name_ar, #owner_name_en, #phone, #vendor_type, #password, #address, #status, #description_ar, #description_en';

            function redirectToList(message, type) {
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.vendor.list') }}");
            }

            // Clear validation styles on input/change
            $('body').on('input change', vendorFields, function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
             $('body').on('input', '#password_confirmation', function() {
                $('#password').removeClass('is-invalid').next('.invalid-feedback').remove();
            });

            // Handle status select color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-warning');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-warning');
            }).trigger('change');
            
            // AJAX form submission
            $('body').on('submit', '#addVendorForm', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $submitBtn = $('#submitBtn');
                const $btnText = $submitBtn.find('.btn-text');
                const originalText = 'حفظ التاجر';
                const loadingText = 'جاري الحفظ...';

                $submitBtn.prop('disabled', true);
                $btnText.html(loadingText);
                $submitBtn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.success) {
                            redirectToList(response.message, 'success');
                        } else {
                            window.showNotification('error', response.message);
                            resetSubmitButton();
                        }
                    },
                    error: function(xhr) {
                        let message = 'حدث خطأ أثناء إضافة التاجر';
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            displayErrors(xhr.responseJSON.errors);
                            message = 'يرجى تصحيح الأخطاء المذكورة';
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }
                        window.showNotification('error', message);
                        resetSubmitButton();
                    }
                });

                function resetSubmitButton() {
                    $submitBtn.prop('disabled', false);
                    $btnText.html(originalText);
                    $submitBtn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');
                }

                function displayErrors(errors) {
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    for (const field in errors) {
                        const fieldName = field.replace(/\./g, '_');
                        // Handle password confirmation error specifically
                        const $field = (fieldName === 'password' && errors[field][0].includes('confirmation')) 
                                     ? $('#password_confirmation') 
                                     : $(`#${fieldName}, [name="${field}"]`).first();
                        
                        if ($field.length) {
                            $field.addClass('is-invalid');
                            // Create a new div for the error message
                            const errorDiv = `<div class="invalid-feedback">${errors[field][0]}</div>`;
                            // Append the error message after the field
                            $field.after(errorDiv);
                        }
                    }
                }
            });

             // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#addVendorForm').submit();
                }
                // Esc to go back
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.vendor.list') }}";
                }
            });
        });
    </script>
@endsection