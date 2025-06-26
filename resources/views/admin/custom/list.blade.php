@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">العملاء</h1>
                <a href="{{ route('admin.customer.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> إضافة عميل
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
                                <h3 class="card-title">بحث عن عميل</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="user_id">رقم العميل</label>
                                            <input type="text" name="user_id" placeholder="رقم العميل"
                                                value="{{ Request::get('user_id') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="name">الاسم</label>
                                            <input type="text" name="name" placeholder="الاسم"
                                                value="{{ Request::get('name') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="phone">رقم الهاتف</label>
                                            <input type="text" name="phone" placeholder="رقم الهاتف"
                                                value="{{ Request::get('phone') }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="from_date">من تاريخ</label>
                                            <input type="date" name="from_date" value="{{ Request::get('from_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="to_date">إلى تاريخ</label>
                                            <input type="date" name="to_date" value="{{ Request::get('to_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i> 
                                            </button>
                                            <a href="{{ route('admin.customer.list') }}"
                                                class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- جدول العملاء --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                قائمة العملاء (الإجمالي: {{ $getRecord->total() }})
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
                                                    <th>رقم العميل</th>
                                                    <th>الاسم</th>
                                                    <th>رقم الهاتف</th>
                                                    <th>كود التحقق</th>
                                                    <th>الحالة</th>
                                                    <th>الموافقة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    <tr>
                                                        <td>{{ $value->user_id }}</td>
                                                        <td>{{ $value->full_name }}</td>
                                                        <td>{{ $value->phone ?? '-' }}</td>
                                                        <td>{{ $value->verfiycode ?? '-' }}</td>
                                                        <td>
                                                            @php
                                                                $statusColor = [
                                                                    'active' => 'success',
                                                                    'inactive' => 'warning',
                                                                    'banned' => 'danger',
                                                                ];
                                                            @endphp
                                                            @if ($value->is_delete == 1)
                                                            <span
                                                                class="badge bg-secondary">
                                                              محذوف
                                                            </span>
                                                            @else
                                                            <span
                                                                class="badge bg-{{ $statusColor[$value->status] ?? 'secondary' }}">
                                                                {{ $value->status === 'active' ? 'نشط' : ($value->status === 'inactive' ? 'غير نشط' : 'محظور') }}
                                                            </span>
                                                            @endif
                                                            
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $value->approve ? 'success' : 'danger' }}">
                                                                {{ $value->approve ? 'مقبول' : 'مرفوض' }}
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
                                                                <a href="{{ url('admin/customers/' . $value->user_id . '/edit') }}"
                                                                    class="btn-action btn-edit" title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn-action btn-delete btnDelete"
                                                                    data-id="{{ $value->user_id }}" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4">
                                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
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
                                                        <p class="text-muted small mb-1">رقم العميل: {{ $value->user_id }}
                                                        </p>
                                                        <p class="text-muted small mb-2">
                                                            <i class="fas fa-phone"></i> {{ $value->phone ?? 'غير محدد' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-right">
                                                        @php
                                                            $statusColor = [
                                                                'active' => 'success',
                                                                'inactive' => 'warning',
                                                                'banned' => 'danger',
                                                            ];
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $statusColor[$value->status] ?? 'secondary' }} mb-1 d-block">
                                                            {{ $value->status === 'active' ? 'نشط' : ($value->status === 'inactive' ? 'غير نشط' : 'محظور') }}
                                                        </span>
                                                        <span
                                                            class="badge bg-{{ $value->approve ? 'success' : 'danger' }} d-block">
                                                            {{ $value->approve ? 'مقبول' : 'مرفوض' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-key"></i> كود التحقق:
                                                            {{ $value->verfiycode ?? '-' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-12 text-center">
                                                        <a href="{{ url('admin/customers/' . $value->user_id . '/edit') }}"
                                                            class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm mx-1 btnDelete"
                                                            data-id="{{ $value->user_id }}" style="min-width: 70px;">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
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
            const confirmMessage = 'هل أنت متأكد من حذف هذه العميل الفرعية؟';

            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(confirmMessage, function() {
                    subServiceDelete(button);
                }, 'تأكيد العملية');
            } else {
                if (confirm(confirmMessage)) {
                    subServiceDelete(button);
                }
            }
        });

        function subServiceDelete(button) {
            const subServiceId = button.data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('admin/customers/delete') }}/" + subServiceId,
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
                            showSuccess('تم حذف العميل  بنجاح');
                        } else {
                            alert('تم حذف العميل  بنجاح');
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء حذف العميل ');
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
