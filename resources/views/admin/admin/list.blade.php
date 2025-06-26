@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">المدراء</h1>
                <a href="{{ route('admin.admin.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> إضافة مدير
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    {{-- نموذج البحث --}}
                    <form action="" method="get">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">بحث عن مدير</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="full_name">الاسم الكامل</label>
                                            <input type="text" name="full_name" placeholder="الاسم الكامل"
                                                value="{{ Request::get('full_name') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="email">البريد الإلكتروني</label>
                                            <input type="text" name="email" placeholder="البريد الإلكتروني"
                                                value="{{ Request::get('email') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="role">الدور</label>
                                            <select name="role" class="form-control">
                                                <option value="">الكل</option>
                                                <option value="super_admin" {{ Request::get('role') == 'super_admin' ? 'selected' : '' }}>إشراف عام</option>
                                                <option value="admin" {{ Request::get('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                                                <option value="moderator" {{ Request::get('role') == 'moderator' ? 'selected' : '' }}>مشرف</option>
                                                <option value="editor" {{ Request::get('role') == 'editor' ? 'selected' : '' }}>محرر</option>
                                                <option value="viewer" {{ Request::get('role') == 'viewer' ? 'selected' : '' }}>مشاهد</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="status">الحالة</label>
                                            <select name="status" class="form-control">
                                                <option value="">الكل</option>
                                                <option value="active" {{ Request::get('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ Request::get('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="from_date">من تاريخ</label>
                                            <input type="date" name="from_date" value="{{ Request::get('from_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                   <div class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i> 
                                            </button>
                                            <a href="{{ route('admin.admin.list') }}"
                                                class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- جدول المدراء --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                قائمة المدراء (الإجمالي: {{ $getRecord->total() }})
                            </h3>
                        </div>
                        <div id="table-container">

                            <div class="card-body p-0">
                                {{-- Desktop Table --}}
                                <div class="d-none d-lg-block">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead class="text-center bg-light">
                                                <tr>
                                                    <th>الاسم الكامل</th>
                                                    <th>البريد الإلكتروني</th>
                                                    <th>الدور</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    <tr>
                                                        <td>{{ $value->full_name }}</td>
                                                        <td>{{ $value->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                @if ($value->role == 'super_admin')
                                                                    إشراف عام
                                                                @elseif($value->role == 'admin')
                                                                    مدير
                                                                @elseif($value->role == 'moderator')
                                                                    مشرف
                                                                @elseif($value->role == 'editor')
                                                                    محرر
                                                                @elseif($value->role == 'viewer')
                                                                    مشاهد
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $value->status == 'active' ? 'success' : 'warning' }}">
                                                                {{ $value->status == 'active' ? 'نشط' : 'غير نشط' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="text-sm">
                                                                <div class="font-weight-bold">
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->locale('ar')->translatedFormat('j F Y') }}
                                                                </div>
                                                                <div class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->format('g:i A') }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ url('admin/administrators/' . $value->id . '/edit') }}"
                                                                    class="btn-action btn-edit" title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn-action btn-delete btnDelete"
                                                                    data-id="{{ $value->id }}" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4">
                                                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">لا توجد بيانات متاحة حالياً.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Mobile Cards --}}
                                <div class="d-lg-none">
                                    @forelse ($getRecord as $value)
                                        <div class="card mb-3 mx-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="card-title mb-1">{{ $value->full_name }}</h6>
                                                        <p class="text-muted small mb-1">
                                                            <i class="fas fa-envelope"></i> {{ $value->email }}
                                                        </p>
                                                        <p class="text-muted small mb-2">
                                                            الدور: 
                                                            @if ($value->role == 'super_admin')
                                                                إشراف عام
                                                            @elseif($value->role == 'admin')
                                                                مدير
                                                            @elseif($value->role == 'moderator')
                                                                مشرف
                                                            @elseif($value->role == 'editor')
                                                                محرر
                                                            @elseif($value->role == 'viewer')
                                                                مشاهد
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-right">
                                                        <span
                                                            class="badge bg-{{ $value->status == 'active' ? 'success' : 'warning' }} mb-1 d-block">
                                                            {{ $value->status == 'active' ? 'نشط' : 'غير نشط' }}
                                                        </span>
                                                        <span class="badge bg-info d-block">
                                                            @if ($value->role == 'super_admin')
                                                                إشراف عام
                                                            @elseif($value->role == 'admin')
                                                                مدير
                                                            @elseif($value->role == 'moderator')
                                                                مشرف
                                                            @elseif($value->role == 'editor')
                                                                محرر
                                                            @elseif($value->role == 'viewer')
                                                                مشاهد
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12 text-center">
                                                        <a href="{{ url('admin/administrators/' . $value->id . '/edit') }}"
                                                            class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm mx-1 btnDelete"
                                                            data-id="{{ $value->id }}" style="min-width: 70px;">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد بيانات متاحة حالياً.</p>
                                        </div>
                                    @endforelse
                                </div>

                                {{-- Pagination --}}
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        {{ $getRecord->appends(request()->except('page'))->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $('body').delegate('.btnDelete', 'click', function() {
            let button = $(this);
            const confirmMessage = 'هل أنت متأكد من حذف هذا المدير؟';

            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(confirmMessage, function() {
                    adminDelete(button);
                }, 'تأكيد العملية');
            } else {
                if (confirm(confirmMessage)) {
                    adminDelete(button);
                }
            }
        });

        function adminDelete(button) {
            const adminId = button.data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('admin/administrators/delete') }}/" + adminId,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function() {
                    button.prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(data) {
                    if (data.success) {
                        $('#table-container').load(location.href + ' #table-container > *');
                        if (typeof showSuccess === 'function') {
                            showSuccess('تم حذف المدير بنجاح');
                        } else {
                            alert('تم حذف المدير بنجاح');
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء حذف المدير');
                        if (typeof showError === 'function') {
                            showError(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    const errorMsg = 'حدث خطأ أثناء الاتصال بالخادم: ' + error;
                    if (typeof showError === 'function') {
                        showError(errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    </script>
@endsection