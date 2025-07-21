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
                        <li class="breadcrumb-item active">تعديل بيانات التاجر</li>
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
                                <i class="fas fa-edit mr-2"></i>
                                تعديل بيانات التاجر
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.vendor.list') }}" class="btn btn-tool" title="العودة للقائمة">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>

                        <form id="editVendorForm" action="{{ route('admin.vendor.update', $getRecord->vendor_id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="row">
                                    {{-- Vendor Name AR --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name_ar" class="required">اسم التاجر (عربي)</label>
                                            <input type="text" class="form-control @error('vendor_name_ar') is-invalid @enderror" id="vendor_name_ar" name="vendor_name_ar" value="{{ old('vendor_name_ar', $getRecord->vendor_name_ar) }}">
                                            @error('vendor_name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Vendor Name EN --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name_en" class="required">اسم التاجر (إنجليزي)</label>
                                            <input type="text" class="form-control @error('vendor_name_en') is-invalid @enderror" id="vendor_name_en" name="vendor_name_en" value="{{ old('vendor_name_en', $getRecord->vendor_name_en) }}">
                                            @error('vendor_name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Owner Name AR --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="owner_name_ar" class="required">اسم المالك (عربي)</label>
                                            <input type="text" class="form-control @error('owner_name_ar') is-invalid @enderror" id="owner_name_ar" name="owner_name_ar" value="{{ old('owner_name_ar', $getRecord->owner_name_ar) }}">
                                            @error('owner_name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Owner Name EN --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="owner_name_en" class="required">اسم المالك (إنجليزي)</label>
                                            <input type="text" class="form-control @error('owner_name_en') is-invalid @enderror" id="owner_name_en" name="owner_name_en" value="{{ old('owner_name_en', $getRecord->owner_name_en) }}">
                                            @error('owner_name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Phone (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="required">رقم الهاتف</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $getRecord->phone) }}">
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Vendor Type --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_type" class="required">نوع التاجر</label>
                                            <select class="form-control @error('vendor_type') is-invalid @enderror" id="vendor_type" name="vendor_type">
                                                <option value="workshop" {{ old('vendor_type', $getRecord->vendor_type) == 'workshop' ? 'selected' : '' }}>ورشة</option>
                                                <option value="tow_truck" {{ old('vendor_type', $getRecord->vendor_type) == 'tow_truck' ? 'selected' : '' }}>سطحة</option>
                                            </select>
                                            @error('vendor_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Password (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="اتركه فارغاً لعدم التغيير">
                                            <small class="form-text text-muted">أدخل كلمة مرور جديدة فقط إذا كنت تريد تغيير الحالية.</small>
                                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Password Confirmation (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="أعد إدخال كلمة المرور الجديدة">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Address (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">العنوان</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $getRecord->address) }}</textarea>
                                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="required">حالة التاجر</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                <option value="1" {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}>نشط</option>
                                                <option value="0" {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}>غير نشط</option>
                                                <option value="2" {{ old('status', $getRecord->status) == '2' ? 'selected' : '' }}>محذوف</option>
                                            </select>
                                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Description AR (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description_ar">الوصف (عربي)</label>
                                            <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $getRecord->description['ar'] ?? '') }}</textarea>
                                            @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    {{-- Description EN (ADDED) --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description_en">الوصف (إنجليزي)</label>
                                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $getRecord->description['en'] ?? '') }}</textarea>
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
                                            <span class="btn-text">تحديث البيانات</span>
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

@section('script')
    {{-- This section is for UI interactions --}}
    <script type="text/javascript">
        $(document).ready(function() {
            function redirectToList(message, type) {
                // This assumes you have a global FlashHandler object for sweet alerts
                window.FlashHandler.redirectWithFlash(message, type, "{{ route('admin.vendor.list') }}");
            }

            // Clear validation styles for all vendor form fields
            const fieldsToWatch =
                '#vendor_name_ar, #vendor_name_en, #owner_name_ar, #owner_name_en, #phone, #vendor_type, #status, #password';
            $('body').on('input change', fieldsToWatch, function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Status border color
            $('body').on('change', '#status', function() {
                $(this).removeClass('border-success border-secondary border-danger');
                if (this.value === '1') $(this).addClass('border-success');
                else if (this.value === '0') $(this).addClass('border-secondary');
                else if (this.value === '2') $(this).addClass('border-danger');
            }).change();

            // Prevent form submission on Enter key press in inputs
            $('body').on('keypress', 'input:not([type="submit"])', function(e) {
                if (e.which === 13) e.preventDefault();
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl+S to save
                if (e.ctrlKey && e.which === 83) {
                    e.preventDefault();
                    $('#editVendorForm').submit();
                }
                // Esc to go back
                if (e.which === 27) {
                    window.location.href = "{{ route('admin.vendor.list') }}";
                }
            });
        });
    </script>
@endsection

@section('scripts')
    {{-- This section is for the AJAX form submission --}}
    <script type="text/javascript">
        $('body').on('submit', '#editVendorForm', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');
            const originalText = 'تحديث البيانات';
            const updatingText = 'جاري التحديث...';

            $submitBtn.prop('disabled', true);
            $btnText.html(updatingText);
            $submitBtn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(), // .serialize() is fine since there are no file inputs
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        redirectToList(response.message, 'success');
                    } else {
                        // Assumes a global function like window.showNotification
                        window.showNotification('error', response.message);
                        resetSubmitButton();
                    }
                },
                error: function(xhr) {
                    let message = 'حدث خطأ أثناء تحديث البيانات';
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
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                for (const field in errors) {
                    const fieldName = field.replace(/\./g, '_');
                    const $field = $(`#${fieldName}, [name="${field}"]`).first();
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $field.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            }
        });
    </script>
@endsection
