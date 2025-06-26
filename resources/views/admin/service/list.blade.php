    @extends('admin.layouts.app')

    @section('content')
        <section class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h1 class="m-0">قائمة الخدمات</h1>
                    <a href="{{ route('admin.service.create') }}" class="btn btn-sm btn-primary">إضافة خدمة جديدة</a>
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
                                    <h3 class="card-title">البحث في الخدمات</h3>
                                </div>
                                <div class="card-body" style="overflow:auto">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>المعرف</label>
                                                <input type="text" name="service_id"
                                                    value="{{ Request::get('service_id') }}" class="form-control"
                                                    placeholder="معرف الخدمة">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>اسم الخدمة</label>
                                                <input type="text" name="service_name"
                                                    value="{{ Request::get('service_name') }}" class="form-control"
                                                    placeholder="اسم الخدمة">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>الحالة</label>
                                                <select name="status" class="form-control">
                                                    <option value="">جميع الحالات</option>
                                                    <option value="0"
                                                        {{ Request::get('status') == '0' ? 'selected' : '' }}>نشط</option>
                                                    <option value="1"
                                                        {{ Request::get('status') == '1' ? 'selected' : '' }}>غير نشط
                                                    </option>
                                                    <option value="2"
                                                        {{ Request::get('status') == '2' ? 'selected' : '' }}>محذوف</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>من تاريخ</label>
                                                <input type="date" name="from_date"
                                                    value="{{ Request::get('from_date') }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>إلى تاريخ</label>
                                                <input type="date" name="to_date" value="{{ Request::get('to_date') }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div
                                            class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                            <div class="form-group w-100">
                                                <button class="btn btn-info btn-block mb-2">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <a href="{{ route('admin.service.list') }}" class="btn btn-info btn-block">
                                                    <i class="fas fa-redo"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Service Table --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">قائمة الخدمات (المجموع: {{ $getRecord->total() }})</h3>
                            </div>
                            <div id="table-container">
                                <div class="card-body p-0">
                                    {{-- Desktop Table --}}
                                    <div class="d-none d-lg-block">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered mb-0">
                                                <thead class="text-center bg-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>الصورة</th>
                                                        <th>اسم الخدمة</th>
                                                        <th>اسم الخدمة إنجليزي</th>
                                                        <th>الحالة</th>
                                                        <th>تاريخ الإنشاء</th>
                                                        <th>الإجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                    @forelse ($getRecord as $value)
                                                        @php
                                                            $service_name = 'غير متوفر';
                                                            $service_name_en = 'غير متوفر';

                                                            $rawName = $value->getAttributes()['service_name'];

                                                            if (is_string($rawName)) {
                                                                $decoded_name = json_decode($rawName, true);

                                                                if (is_array($decoded_name)) {
                                                                    $service_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                                    $service_name_en =
                                                                        $decoded_name['en'] ?? 'غير متوفر';
                                                                } else {
                                                                    $service_name = $rawName;
                                                                }
                                                            }
                                                        @endphp

                                                        <tr>
                                                            <td>{{ $value->service_id }}</td>
                                                            <td>
                                                                @if ($value->getServiceImage())
                                                                    <img src="{{ $value->getServiceImage() }}"
                                                                        style="width: 60px; height: 60px; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: 5px;"
                                                                        alt="صورة الخدمة">
                                                                @else
                                                                    <div
                                                                        style="width: 60px; height: 60px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                                        <i class="fas fa-image text-muted"></i>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $service_name }}</td>
                                                            <td>{{ $service_name_en }}</td>
                                                            <td>
                                                                @php $actualStatus = $value->getAttributes()['status']; @endphp
                                                                @if ($actualStatus == 0)
                                                                    <span class="badge badge-success px-3 py-2">
                                                                        <i class="fas fa-check-circle mr-1"></i>نشط
                                                                    </span>
                                                                @elseif ($actualStatus == 1)
                                                                    <span class="badge badge-secondary px-3 py-2">
                                                                        <i class="fas fa-pause-circle mr-1"></i>غير نشط
                                                                    </span>
                                                                @elseif ($actualStatus == 2)
                                                                    <span class="badge badge-danger px-3 py-2">
                                                                        <i class="fas fa-times-circle mr-1"></i>محذوف
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-warning px-3 py-2">
                                                                        <i class="fas fa-question-circle mr-1"></i>غير معروف
                                                                    </span>
                                                                @endif
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
                                                                    <a href="{{ route('admin.service.edit', $value->service_id) }}"
                                                                        class="btn-action btn-edit" title="تعديل">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0)"
                                                                        class="btn-action btn-delete btnDelete"
                                                                        data-id="{{ $value->service_id }}" title="حذف">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center py-4">
                                                                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">لا توجد خدمات متاحة حالياً.</p>
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
                                            @php
                                                $service_name = 'غير متوفر';
                                                $service_name_en = 'غير متوفر';

                                                $rawName = $value->getAttributes()['service_name'];

                                                if (is_string($rawName)) {
                                                    $decoded_name = json_decode($rawName, true);

                                                    if (is_array($decoded_name)) {
                                                        $service_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                        $service_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                    } else {
                                                        $service_name = $rawName;
                                                    }
                                                }
                                            @endphp

                                            <div class="card mb-3 mx-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-3 text-center">
                                                            @if ($value->getServiceImage())
                                                                <img src="{{ $value->getServiceImage() }}"
                                                                    style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: 3px;"
                                                                    alt="صورة الخدمة">
                                                            @else
                                                                <div
                                                                    style="width: 50px; height: 50px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="card-title mb-1">{{ $service_name }}</h6>
                                                            <p class="text-muted small mb-1">{{ $service_name_en }}</p>
                                                            <p class="text-muted small mb-0">
                                                                <i class="fas fa-hashtag"></i> {{ $value->service_id }}
                                                            </p>
                                                        </div>
                                                        <div class="col-3 text-right">
                                                            @if ($actualStatus == 0)
                                                                <span class="badge badge-success d-block mb-1">
                                                                    <i class="fas fa-check-circle"></i> نشط
                                                                </span>
                                                            @elseif ($actualStatus == 1)
                                                                <span class="badge badge-secondary d-block mb-1">
                                                                    <i class="fas fa-pause-circle"></i> غير نشط
                                                                </span>
                                                            @elseif ($actualStatus == 2)
                                                                <span class="badge badge-danger d-block mb-1">
                                                                    <i class="fas fa-times-circle"></i> محذوف
                                                                </span>
                                                            @else
                                                                <span class="badge badge-warning d-block mb-1">
                                                                    <i class="fas fa-question-circle"></i> غير معروف
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar"></i>
                                                                {{ \Carbon\Carbon::parse($value->created_at)->locale('ar')->translatedFormat('j F Y') }}
                                                                {{ \Carbon\Carbon::parse($value->created_at)->format('g:i A') }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-12 text-center">
                                                            <a href="{{ route('admin.service.edit', $value->service_id) }}"
                                                                class="btn btn-primary btn-sm mx-1"
                                                                style="min-width: 70px;">
                                                                <i class="fas fa-edit"></i> تعديل
                                                            </a>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-danger btn-sm mx-1 btnDelete"
                                                                data-id="{{ $value->service_id }}"
                                                                style="min-width: 70px;">
                                                                <i class="fas fa-trash"></i> حذف
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-5">
                                                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد خدمات متاحة حالياً.</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-center">
                                            {!! $getRecord->appends(request()->except('page'))->links() !!}
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
                const confirmMessage = 'هل أنت متأكد من من الحذف؟';

                if (typeof showCustomConfirm === 'function') {
                    showCustomConfirm(confirmMessage, function() {
                        serviceDelete(button);
                    }, 'تأكيد العملية');
                } else {
                    if (confirm(confirmMessage)) {
                        serviceDelete(button);
                    }
                }
            });

            function serviceDelete(button) {
                const serviceId = button.data('id');

                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/services/delete') }}/" + serviceId,
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
                            $('#table-container').load(location.href + ' #table-container > *');
                            if (typeof showSuccess === 'function') {
                                showSuccess('تم حذف الخدمة بنجاح');
                            } else {
                                alert('تم حذف الخدمة بنجاح');
                            }
                        } else {
                            const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء حذف الخدمة');
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
                    }
                });
            }
        </script>
    @endsection
