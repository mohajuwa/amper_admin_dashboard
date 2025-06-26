@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">قائمة الخدمات الفرعية</h1>
                <a href="{{ route('admin.sub_service.create') }}" class="btn btn-sm btn-primary">إضافة خدمة فرعية جديدة</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- Search SSF  --}}
                    <form action="" method="get">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">البحث في الخدمات الفرعية</h3>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>المعرف</label>
                                            <input type="text" name="sub_service_id"
                                                value="{{ Request::get('sub_service_id') }}" class="form-control"
                                                placeholder="معرف الخدمة الفرعية">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>اسم الخدمة الفرعية</label>
                                            <input type="text" name="sub_service_name"
                                                value="{{ Request::get('sub_service_name') }}" class="form-control"
                                                placeholder="اسم الخدمة الفرعية">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الخدمة الرئيسية</label>
                                            <select name="service_id" class="form-control">
                                                <option value="">جميع الخدمات</option>
                                                @foreach (\App\Models\Service::getAllServices() as $service)
                                                    @php
                                                        $serviceName = is_array($service->service_name)
                                                            ? $service->service_name['ar'] ??
                                                                $service->service_name['en']
                                                            : $service->service_name;

                                                        $serviceNameEn = is_array($service->service_name)
                                                            ? $service->service_name['en'] ??
                                                                $service->service_name['ar']
                                                            : $service->service_name;
                                                    @endphp
                                                    <option value="{{ $service->service_id }}"
                                                        {{ Request::get('service_id') == $service->service_id ? 'selected' : '' }}>
                                                        {{ $serviceName }} - {{ $serviceNameEn }}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الحالة</label>
                                            <select name="status" class="form-control">
                                                <option value="">جميع الحالات</option>
                                                <option value="1"
                                                    {{ Request::get('status') == '1' ? 'selected' : '' }}>نشط</option>
                                                <option value="0"
                                                    {{ Request::get('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>من تاريخ</label>
                                            <input type="date" name="from_date" value="{{ Request::get('from_date') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.sub_service.list') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Sub Service Table --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">قائمة الخدمات الفرعية (المجموع: {{ $getRecord->total() }})</h3>
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
                                                    <th>اسم الخدمة الفرعية</th>
                                                    <th>اسم الخدمة الفرعية إنجليزي</th>
                                                    <th>الخدمة الرئيسية</th>
                                                    <th>السعر</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    @php
                                                        $sub_service_name = 'غير متوفر';
                                                        $sub_service_name_en = 'غير متوفر';

                                                        $rawName = $value->getAttributes()['name'];

                                                        if (is_string($rawName)) {
                                                            $decoded_name = json_decode($rawName, true);

                                                            if (is_array($decoded_name)) {
                                                                $sub_service_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                                $sub_service_name_en =
                                                                    $decoded_name['en'] ?? 'غير متوفر';
                                                            } else {
                                                                $sub_service_name = $rawName;
                                                            }
                                                        }

                                                        // Get service name
                                                        $service_name = $value->service_name ?? 'غير متوفر';
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $value->sub_service_id }}</td>
                                                        <td>{{ $sub_service_name }}</td>
                                                        <td>{{ $sub_service_name_en }}</td>
                                                        <td>
                                                            @if ($service_name && $service_name != 'غير متوفر')
                                                                @php
                                                                    $serviceDisplayName = $service_name;
                                                                    if (is_string($service_name)) {
                                                                        $decoded_service = json_decode(
                                                                            $service_name,
                                                                            true,
                                                                        );
                                                                        if (is_array($decoded_service)) {
                                                                            $serviceDisplayName =
                                                                                $decoded_service['ar'] ??
                                                                                ($decoded_service['en'] ??
                                                                                    $service_name);
                                                                        }
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="badge badge-info">{{ $serviceDisplayName }}</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($value->price != 0)
                                                                <span
                                                                    class="badge badge-success">{{ number_format($value->price, 2) }}
                                                                    ر.س</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-warning">{{ number_format($value->price, 2) }}
                                                                    ر.س</span>
                                                            @endif
                                                        </td>
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
                                                                <a href="{{ route('admin.sub_service.edit', $value->sub_service_id) }}"
                                                                    class="btn-action btn-edit" title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn-action btn-delete btnDelete"
                                                                    data-id="{{ $value->sub_service_id }}" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4">
                                                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">لا توجد خدمات فرعية متاحة حالياً.</p>
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
                                            $sub_service_name = 'غير متوفر';
                                            $sub_service_name_en = 'غير متوفر';

                                            $rawName = $value->getAttributes()['name'];

                                            if (is_string($rawName)) {
                                                $decoded_name = json_decode($rawName, true);

                                                if (is_array($decoded_name)) {
                                                    $sub_service_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                    $sub_service_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                } else {
                                                    $sub_service_name = $rawName;
                                                }
                                            }

                                            // Get service name
                                            $service_name = $value->service_name ?? 'غير متوفر';
                                            $serviceDisplayName = $service_name;
                                            if (is_string($service_name)) {
                                                $decoded_service = json_decode($service_name, true);
                                                if (is_array($decoded_service)) {
                                                    $serviceDisplayName =
                                                        $decoded_service['ar'] ??
                                                        ($decoded_service['en'] ?? $service_name);
                                                }
                                            }
                                        @endphp

                                        <div class="card mb-3 mx-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-cog"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 class="card-title mb-1">{{ $sub_service_name }}</h6>
                                                        <p class="text-muted small mb-1">{{ $sub_service_name_en }}</p>
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-hashtag"></i> {{ $value->sub_service_id }}
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

                                                        <small
                                                            class="text-success font-weight-bold">{{ number_format($value->price, 2) }}
                                                            ر.س</small>
                                                    </div>
                                           
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <small class="text-info">
                                                            <i class="fas fa-layer-group"></i> {{ $serviceDisplayName }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
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
                                                        <a href="{{ route('admin.sub_service.edit', $value->sub_service_id) }}"
                                                            class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm mx-1 btnDelete"
                                                            data-id="{{ $value->sub_service_id }}"
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
                                            <p class="text-muted">لا توجد خدمات فرعية متاحة حالياً.</p>
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
            const confirmMessage = 'هل أنت متأكد من حذف هذه الخدمة الفرعية؟';

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
                url: "{{ url('admin/sub_services/delete') }}/" + subServiceId,
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
                            showSuccess('تم حذف الخدمة الفرعية بنجاح');
                        } else {
                            alert('تم حذف الخدمة الفرعية بنجاح');
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء حذف الخدمة الفرعية');
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
