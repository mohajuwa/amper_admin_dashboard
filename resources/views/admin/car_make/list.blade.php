@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">قائمة ماركات السيارات</h1>
                <a href="{{ route('admin.car_make.create') }}" class="btn btn-sm btn-primary">إضافة ماركة جديدة</a>
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
                                <h3 class="card-title">البحث في ماركات السيارات</h3>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>المعرف</label>
                                            <input type="text" name="make_id" value="{{ Request::get('make_id') }}"
                                                class="form-control" placeholder="معرف الماركة">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>اسم الماركة</label>
                                            <input type="text" name="name" value="{{ Request::get('name') }}"
                                                class="form-control" placeholder="اسم الماركة">
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
                                                    {{ Request::get('status') == '0' ? 'selected' : '' }}>غير نشط
                                                </option>
                                                <option value="2"
                                                    {{ Request::get('status') == '2' ? 'selected' : '' }}>محذوف</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Popularity Level Filter --}}
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>مستوى الشهرة</label>
                                            <select name="popularity_level" class="form-control">
                                                <option value="">جميع المستويات</option>
                                                <option value="highest"
                                                    {{ Request::get('popularity_level') == 'highest' ? 'selected' : '' }}>
                                                    🏆 الاعلى شهرة (90+)
                                                </option>
                                                <option value="very_high"
                                                    {{ Request::get('popularity_level') == 'very_high' ? 'selected' : '' }}>
                                                    🥇 شهيرة جداً (80-89)
                                                </option>
                                                <option value="good"
                                                    {{ Request::get('popularity_level') == 'good' ? 'selected' : '' }}>
                                                    🥈 شهرة جيدة (70-79)
                                                </option>
                                                <option value="average"
                                                    {{ Request::get('popularity_level') == 'average' ? 'selected' : '' }}>
                                                    🥉 جيد (60-69)
                                                </option>
                                                <option value="acceptable"
                                                    {{ Request::get('popularity_level') == 'acceptable' ? 'selected' : '' }}>
                                                    📊 مقبول (40-59)
                                                </option>
                                                <option value="low"
                                                    {{ Request::get('popularity_level') == 'low' ? 'selected' : '' }}>
                                                    📉 ضعيف (1-39)
                                                </option>
                                                <option value="undefined"
                                                    {{ Request::get('popularity_level') == 'undefined' ? 'selected' : '' }}>
                                                    ❓ غير محدد (0)
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Custom Popularity Range --}}
                                    <div class="col-lg-4 col-md-3 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>شهرة من</label>
                                            <input type="number" name="popularity_min"
                                                value="{{ Request::get('popularity_min') }}" class="form-control"
                                                placeholder="0" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>شهرة إلى</label>
                                            <input type="number" name="popularity_max"
                                                value="{{ Request::get('popularity_max') }}" class="form-control"
                                                placeholder="100" min="0" max="100">
                                        </div>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.car_make.list') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Car Makes Table --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">قائمة ماركات السيارات (المجموع: {{ $getRecord->total() }})</h3>
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
                                                    <th>اسم الماركة</th>
                                                    <th>اسم الماركة إنجليزي</th>
                                                    <th>الحالة</th>
                                                    <th>مستوى الشهرة</th>
                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    @php
                                                        $name = 'غير متوفر';
                                                        $make_name_en = 'غير متوفر';

                                                        $rawName = $value->getAttributes()['name'];

                                                        if (is_string($rawName)) {
                                                            $decoded_name = json_decode($rawName, true);

                                                            if (is_array($decoded_name)) {
                                                                $name = $decoded_name['ar'] ?? 'غير متوفر';
                                                                $make_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                            } else {
                                                                $name = $rawName;
                                                            }
                                                        }
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $value->make_id }}</td>
                                                        <td>
                                                            @if ($value->getCarMakeLogo())
                                                                <img src="{{ $value->getCarMakeLogo() }}"
                                                                    style="width: 60px; height: 60px; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: 5px;"
                                                                    alt="صورة الماركة">
                                                            @else
                                                                <div
                                                                    style="width: 60px; height: 60px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $name }}</td>
                                                        <td>{{ $make_name_en }}</td>
                                                            <td>
                                                            @php $actualStatus = $value->getAttributes()['status']; @endphp
                                                            @if ($actualStatus == 1)
                                                                <span class="badge badge-success px-3 py-2">
                                                                    <i class="fas fa-check-circle mr-1"></i>نشط
                                                                </span>
                                                            @elseif ($actualStatus == 0)
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
                                                            @php
                                                                $popularity =
                                                                    $value->getAttributes()['popularity'] ?? 0;
                                                                $badgeClass = '';
                                                                $label = '';
                                                                $icon = '';

                                                                if ($popularity >= 90) {
                                                                    $badgeClass = 'badge-gradient-gold';
                                                                    $label = 'الاعلى شهرة';
                                                                    $icon = '🏆';
                                                                } elseif ($popularity >= 80) {
                                                                    $badgeClass = 'badge-gradient-purple';
                                                                    $label = 'شهيرة جداً';
                                                                    $icon = '🥇';
                                                                } elseif ($popularity >= 70) {
                                                                    $badgeClass = 'badge-gradient-blue';
                                                                    $label = 'شهرة جيدة';
                                                                    $icon = '🥈';
                                                                } elseif ($popularity >= 60) {
                                                                    $badgeClass = 'badge-gradient-green';
                                                                    $label = 'جيد';
                                                                    $icon = '🥉';
                                                                } elseif ($popularity >= 40) {
                                                                    $badgeClass = 'badge-gradient-orange';
                                                                    $label = 'مقبول';
                                                                    $icon = '📊';
                                                                } elseif ($popularity > 0) {
                                                                    $badgeClass = 'badge-gradient-red';
                                                                    $label = 'ضعيف';
                                                                    $icon = '📉';
                                                                } else {
                                                                    $badgeClass = 'badge-secondary';
                                                                    $label = 'غير محدد';
                                                                    $icon = '❓';
                                                                }
                                                            @endphp

                                                            <div class="popularity-wrapper">
                                                                <span class="badge {{ $badgeClass }} popularity-badge">
                                                                    <div class="popularity-content">
                                                                        <span
                                                                            class="popularity-icon">{{ $icon }}</span>
                                                                        <span
                                                                            class="popularity-score">{{ $popularity }}</span>
                                                                        <div class="popularity-progress">
                                                                            <div class="progress-bar"
                                                                                style="width: {{ $popularity }}%"></div>
                                                                        </div>
                                                                        <small
                                                                            class="popularity-label">{{ $label }}</small>
                                                                    </div>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('admin.car_make.edit', $value->make_id) }}"
                                                                    class="btn-action btn-edit" title="تعديل">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn-action btn-delete btnDelete"
                                                                    data-id="{{ $value->make_id }}" title="حذف">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-4">
                                                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">لا توجد ماركات سيارات متاحة حالياً.</p>
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
                                            $name = 'غير متوفر';
                                            $make_name_en = 'غير متوفر';

                                            $rawName = $value->getAttributes()['name'];

                                            if (is_string($rawName)) {
                                                $decoded_name = json_decode($rawName, true);

                                                if (is_array($decoded_name)) {
                                                    $name = $decoded_name['ar'] ?? 'غير متوفر';
                                                    $make_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                } else {
                                                    $name = $rawName;
                                                }
                                            }
                                        @endphp

                                        <div class="card mb-3 mx-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3 text-center">
                                                        @if ($value->getCarMakeLogo())
                                                            <img src="{{ $value->getCarMakeLogo() }}"
                                                                style="width: 50px; height: 50px; object-fit: contain; border-radius: 5px; background: #f8f9fa; padding: 3px;"
                                                                alt="صورة الماركة">
                                                        @else
                                                            <div
                                                                style="width: 50px; height: 50px; background: #e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="card-title mb-1">{{ $name }}</h6>
                                                        <p class="text-muted small mb-1">{{ $make_name_en }}</p>
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-hashtag"></i> {{ $value->make_id }}
                                                        </p>
                                                    </div>
                                                    <div class="col-3 text-right">
                                                        @php $actualStatus = $value->getAttributes()['status']; @endphp
                                                        @if ($actualStatus == 1)
                                                            <span class="badge badge-success d-block mb-1">
                                                                <i class="fas fa-check-circle"></i> نشط
                                                            </span>
                                                        @elseif ($actualStatus == 0)
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

                                                {{-- Mobile Popularity Display --}}
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        @php
                                                            $popularity = $value->getAttributes()['popularity'] ?? 0;
                                                            $badgeClass = '';
                                                            $label = '';
                                                            $icon = '';

                                                            if ($popularity >= 90) {
                                                                $badgeClass = 'badge-gradient-gold';
                                                                $label = 'الاعلى شهرة';
                                                                $icon = '🏆';
                                                            } elseif ($popularity >= 80) {
                                                                $badgeClass = 'badge-gradient-purple';
                                                                $label = 'شهيرة جداً';
                                                                $icon = '🥇';
                                                            } elseif ($popularity >= 70) {
                                                                $badgeClass = 'badge-gradient-blue';
                                                                $label = 'شهرة جيدة';
                                                                $icon = '🥈';
                                                            } elseif ($popularity >= 60) {
                                                                $badgeClass = 'badge-gradient-green';
                                                                $label = 'جيد';
                                                                $icon = '🥉';
                                                            } elseif ($popularity >= 40) {
                                                                $badgeClass = 'badge-gradient-orange';
                                                                $label = 'مقبول';
                                                                $icon = '📊';
                                                            } elseif ($popularity > 0) {
                                                                $badgeClass = 'badge-gradient-red';
                                                                $label = 'ضعيف';
                                                                $icon = '📉';
                                                            } else {
                                                                $badgeClass = 'badge-secondary';
                                                                $label = 'غير محدد';
                                                                $icon = '❓';
                                                            }
                                                        @endphp

                                                        <div class="popularity-mobile">
                                                            <span
                                                                class="badge {{ $badgeClass }} popularity-badge-mobile">
                                                                <span class="popularity-icon">{{ $icon }}</span>
                                                                <span class="popularity-score">{{ $popularity }}</span>
                                                                <span class="popularity-label">{{ $label }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="row mt-3">
                                                    <div class="col-12 text-center">
                                                        <a href="{{ route('admin.car_make.edit', $value->make_id) }}"
                                                            class="btn btn-primary btn-sm mx-1" style="min-width: 70px;">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                            class="btn btn-danger btn-sm mx-1 btnDelete"
                                                            data-id="{{ $value->make_id }}" style="min-width: 70px;">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد ماركات سيارات متاحة حالياً.</p>
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
            const confirmMessage = 'هل أنت متأكد من حذف هذه الماركة؟';

            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(confirmMessage, function() {
                    carMakeDelete(button);
                }, 'تأكيد العملية');
            } else {
                if (confirm(confirmMessage)) {
                    carMakeDelete(button);
                }
            }
        });

        function carMakeDelete(button) {
            const makeId = button.data('id');

            $.ajax({
                type: "POST",
                url: "{{ url('admin/car_makes/delete') }}/" + makeId,
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
                            showSuccess('تم حذف الماركة بنجاح');
                        } else {
                            alert('تم حذف الماركة بنجاح');
                        }
                    } else {
                        const errorMsg = 'حدث خطأ: ' + (data.message || 'فشل أثناء حذف الماركة');
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
