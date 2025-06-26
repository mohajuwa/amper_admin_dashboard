@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">قائمة الإشعارات</h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- Search Form --}}
                    <form action="" method="get">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">البحث في الإشعارات</h3>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>المعرف</label>
                                        <input type="text" name="notification_id"
                                            value="{{ Request::get('notification_id') }}" class="form-control"
                                            placeholder="معرف الإشعار">
                                    </div>
                                    <div class="col-md-3">
                                        <label>العنوان</label>
                                        <input type="text" name="title" value="{{ Request::get('title') }}"
                                            class="form-control" placeholder="عنوان الإشعار">
                                    </div>
                                    <div class="col-md-3">
                                        <label>حالة القراءة</label>
                                        <select name="read_status" class="form-control">
                                            <option value="">جميع الحالات</option>
                                            <option value="0"
                                                {{ Request::get('read_status') == '0' ? 'selected' : '' }}>غير مقروء
                                            </option>
                                            <option value="1"
                                                {{ Request::get('read_status') == '1' ? 'selected' : '' }}>مقروء</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>من تاريخ</label>
                                        <input type="date" name="from_date" value="{{ Request::get('from_date') }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label>إلى تاريخ</label>
                                        <input type="date" name="to_date" value="{{ Request::get('to_date') }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-9">
                                        <label>&nbsp;</label><br>
                                        <button class="btn btn-sm btn-info">بحث</button>
                                        <a href="{{ route('admin.notifications') }}" class="btn btn-sm btn-info">إعادة
                                            تعيين</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Notifications Table --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">قائمة الإشعارات (المجموع : {{ $getRecord->total() }}) </h3>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-success mark-all-read">
                                    <i class="fas fa-check-double"></i> تحديد الكل كمقروء
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0" style="overflow: auto">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>العنوان</th>
                                        <th>الرسالة</th>
                                        <th>التاريخ</th>
                                        <th>حالة القراءة</th>
                                        <th>الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($getRecord as $value)
                                        @php
                                            $title = is_array($value->notification_title)
                                                ? $value->notification_title['ar'] ??
                                                    ($value->notification_title['en'] ?? 'غير متوفر')
                                                : json_decode($value->notification_title, true)['ar'] ?? 'غير متوفر';

                                            $body = is_array($value->notification_body)
                                                ? $value->notification_body['ar'] ??
                                                    ($value->notification_body['en'] ?? 'غير متوفر')
                                                : json_decode($value->notification_body, true)['ar'] ?? 'غير متوفر';
                                        @endphp
                                        <tr
                                            style="{{ empty($value->notification_read) ? 'background-color: #f8f9fa;' : '' }}">
                                            <td>{{ $value->notification_id }}</td>
                                            <td>
                                                <div
                                                    class="font-weight-{{ empty($value->notification_read) ? 'bold' : 'normal' }}">
                                                    {{ $title }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;"
                                                    title="{{ $body }}">
                                                    {{ $body }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    <div class="font-weight-bold">
                                                        {{ \Carbon\Carbon::parse($value->notification_datetime)->locale('ar')->translatedFormat('j F Y') }}
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ \Carbon\Carbon::parse($value->notification_datetime)->format('g:i A') }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if (empty($value->notification_read))
                                                    <span class="badge badge-warning">غير مقروء</span>
                                                @else
                                                    <span class="badge badge-success">مقروء</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($value->notification_read))
                                                    <button class="btn btn-sm btn-success mark-read"
                                                        data-id="{{ $value->notification_id }}"
                                                        data-title="{{ $title }}" data-body="{{ $body }}"
                                                        data-date="{{ \Carbon\Carbon::parse($value->notification_datetime)->locale('ar')->translatedFormat('j F Y g:i A') }}">
                                                        <i class="fas fa-check"></i> تحديد كمقروء
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-info view-details"
                                                        data-title="{{ $title }}" data-body="{{ $body }}"
                                                        data-date="{{ \Carbon\Carbon::parse($value->notification_datetime)->locale('ar')->translatedFormat('j F Y g:i A') }}">
                                                        <i class="fas fa-eye"></i> عرض
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                                    <br>
                                                    لا توجد إشعارات متاحة
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="pagination-wrapper">
                                <nav aria-label="الصفحات">
                                    <ul class="pagination pagination-compact">
                                        {!! $getRecord->appends(request()->except('page'))->links() !!}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal for Notification Details - Fixed Full Screen Overlay --}}
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">تفاصيل الإشعار</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">العنوان:</label>
                        <p id="modal-title" class="mb-3"></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">الرسالة:</label>
                        <p id="modal-body" class="mb-3"></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">التاريخ:</label>
                        <p id="modal-date" class="mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        // تحديد الإشعار كمقروء
        $('body').delegate('.mark-read', 'click', function() {
            let notification_id = $(this).data('id');
            let button = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('admin/notifications/mark-read') }}/" + notification_id,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function() {
                    // Show loading state
                    button.prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(data) {
                    if (data.success) {
                        // تحديث مظهر الصف
                        button.closest('tr').css('background-color', '');
                        button.closest('tr').find('.font-weight-bold').removeClass('font-weight-bold')
                            .addClass('font-weight-normal');

                        // تحديث شارة الحالة
                        button.closest('tr').find('.badge-warning').removeClass('badge-warning')
                            .addClass('badge-success').text('مقروء');

                        button.closest('tr').find('.btn-success.mark-read')
                            .removeClass('btn-success mark-read')
                            .addClass('btn-info view-details')
                            .html('<i class="fas fa-eye"></i>')
                            .attr('title', 'عرض');

                        // عرض رسالة النجاح
                        if (typeof showToast === 'function') {
                            showToast('success', 'نجح العمل', 'تم تحديد الإشعار كمقروء بنجاح');
                        } else {
                            alert('تم تحديد الإشعار كمقروء بنجاح');
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('error', 'فشل العملية', 'حدث خطأ: ' + (data.message ||
                                'حدث خطأ أثناء تحديث الإشعار'));
                        } else {
                            alert('حدث خطأ: ' + (data.message || 'حدث خطأ أثناء تحديث الإشعار'));
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('خطأ AJAX:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });

                    let errorMessage = 'حدث خطأ أثناء الاتصال بالخادم';

                    // التعامل مع أخطاء مختلفة
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            errorMessage = Object.values(errors).flat().join(', ');
                        }
                        if (typeof showToast === 'function') {
                            showToast('error', 'خطأ في التحقق', errorMessage);
                        }
                    } else if (xhr.status === 419) {
                        errorMessage = 'انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.';
                        if (typeof showToast === 'function') {
                            showToast('warning', 'انتهت الجلسة', errorMessage);
                        }
                    } else if (xhr.status === 404) {
                        errorMessage = 'لم يتم العثور على الإشعار المطلوب.';
                        if (typeof showToast === 'function') {
                            showToast('error', 'غير موجود', errorMessage);
                        }
                    } else {
                        errorMessage = 'حدث خطأ أثناء الاتصال بالخادم: ' + error;
                        if (typeof showToast === 'function') {
                            showToast('error', 'خطأ في الخادم', errorMessage);
                        }
                    }

                    // Fallback to alert if showToast is not available
                    if (typeof showToast !== 'function') {
                        alert(errorMessage);
                    }
                },
                complete: function() {
                    // Restore button state
                    button.prop('disabled', false)
                        .html('<i class="fas fa-check"></i>');
                }
            });
        });

        // تحديد جميع الإشعارات كمقروءة
        $('body').delegate('.mark-all-read', 'click', function() {
            let button = $(this);
            const confirmMessage = 'هل أنت متأكد من تحديد جميع الإشعارات كمقروءة؟';

            // Use custom confirm if available, otherwise use browser confirm
            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(confirmMessage, function() {
                    markAllNotificationsRead(button);
                }, 'تأكيد العملية');
            } else {
                if (confirm(confirmMessage)) {
                    markAllNotificationsRead(button);
                }
            }
        });

        // Function to mark all notifications as read
        function markAllNotificationsRead(button) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.notifications.mark-all-read') }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...');
                },
                success: function(data) {
                    if (data.success) {
                        // تحديث جميع الصفوف
                        $('tr').each(function() {
                            $(this).css('background-color', '');
                            $(this).find('.font-weight-bold').removeClass('font-weight-bold')
                                .addClass('font-weight-normal');
                            $(this).find('.badge-warning').removeClass('badge-warning')
                                .addClass('badge-success').text('مقروء');
                        });

                        // إخفاء جميع أزرار "تحديد كمقروء" وتحويلها إلى "عرض"
                        $('.mark-read').removeClass('btn-success mark-read')
                            .addClass('btn-info view-details')
                            .html('<i class="fas fa-eye"></i>')
                            .attr('title', 'عرض');

                        const message = 'تم تحديد جميع الإشعارات كمقروءة بنجاح' +
                            (data.updated_count ? ' (' + data.updated_count + ' إشعار)' : '');

                        if (typeof showSuccess === 'function') {
                            showSuccess(message);
                        } else if (typeof showToast === 'function') {
                            showToast('success', 'تم بنجاح', message);
                        } else {
                            alert(message);
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'حدث خطأ أثناء تحديث الإشعارات');
                        if (typeof showError === 'function') {
                            showError(errorMsg);
                        } else if (typeof showToast === 'function') {
                            showToast('error', 'فشل العملية', errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('خطأ AJAX:', xhr.responseText);
                    const errorMsg = 'حدث خطأ أثناء الاتصال بالخادم: ' + error;

                    if (typeof showError === 'function') {
                        showError(errorMsg);
                    } else if (typeof showToast === 'function') {
                        showToast('error', 'خطأ في الخادم', errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    button.prop('disabled', false)
                        .html('<i class="fas fa-check-double"></i> تحديد جميع الإشعارات كمقروءة');
                }
            });
        }
        // عرض تفاصيل الإشعار
        $('body').delegate('.view-details', 'click', function() {
            let title = $(this).data('title');
            let body = $(this).data('body');
            let date = $(this).data('date');

            // Check if we have the required data
            if (!title && !body && !date) {
                // Try to get data from the row
                const row = $(this).closest('tr');
                title = row.find('td:eq(1)').text().trim(); // Assuming title is in second column
                body = row.find('td:eq(2)').text().trim(); // Assuming body is in third column
                date = row.find('td:eq(3)').text().trim(); // Assuming date is in fourth column
            }

            $('#modal-title').text(title || 'بدون عنوان');
            $('#modal-body').text(body || 'بدون محتوى');
            $('#modal-date').text(date || 'بدون تاريخ');

            // Use Bootstrap 5 modal if available, otherwise try Bootstrap 4
            const modalElement = document.getElementById('notificationModal');
            if (modalElement) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    // Bootstrap 5
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    // Bootstrap 4
                    $('#notificationModal').modal('show');
                } else {
                    console.error('Bootstrap modal not available');
                }
            } else {
                // Fallback: show in alert or create simple modal
                alert(`العنوان: ${title}\n\nالمحتوى: ${body}\n\nالتاريخ: ${date}`);
            }
        });

        // تأك
    </script>
@endsection
