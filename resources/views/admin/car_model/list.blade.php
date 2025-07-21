@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="m-0">قائمة الموديل </h1>
                <a href="{{ route('admin.car_model.create') }}" class="btn btn-sm btn-primary">إضافة موديل جديد</a>
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
                                <h3 class="card-title">البحث في الموديل </h3>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>المعرف</label>
                                            <input type="text" name="model_id" value="{{ Request::get('model_id') }}"
                                                class="form-control" placeholder="معرف الموديل ">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>اسم الموديل </label>
                                            <input type="text" name="car_model_name"
                                                value="{{ Request::get('car_model_name') }}" class="form-control"
                                                placeholder="اسم الموديل ">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>الموديل الرئيسية</label>
                                            <select name="make_id" class="select2 form-control ">
                                                <option value="">جميع الماركات</option>
                                                @foreach (\App\Models\CarMakesModel::getAllCarMakes() as $car_make)
                                                    @php
                                                        $car_makeName = is_array($car_make->name)
                                                            ? $car_make->name['ar'] ?? $car_make->name['en']
                                                            : $car_make->name;

                                                        $car_makeNameEn = is_array($car_make->name)
                                                            ? $car_make->name['en'] ?? $car_make->name['ar']
                                                            : $car_make->name;
                                                    @endphp
                                                    <option value="{{ $car_make->make_id }}"
                                                        {{ Request::get('make_id') == $car_make->make_id ? 'selected' : '' }}>
                                                        {{ $car_makeName }} - {{ $car_makeNameEn }}
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
                                                    {{ Request::get('status') == '0' ? 'selected' : '' }}>غير نشط
                                                </option>
                                                <option value="2"
                                                    {{ Request::get('status') == '2' ? 'selected' : '' }}>محذوف</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.car_model.list') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Sub CarMakesModel Table --}}
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">قائمة الموديل (المجموع: {{ $getRecord->total() }})</h3>
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
                                                    <th>اسم الموديل </th>
                                                    <th>اسم الموديل إنجليزي</th>
                                                    <th>الشركة المصنعة</th>
                                                    <th>الحالة</th>

                                                    <th>الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($getRecord as $value)
                                                    @php
                                                        $car_model_name = 'غير متوفر';
                                                        $car_model_name_en = 'غير متوفر';

                                                        $rawName = $value->getAttributes()['name'];

                                                        if (is_string($rawName)) {
                                                            $decoded_name = json_decode($rawName, true);

                                                            if (is_array($decoded_name)) {
                                                                $car_model_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                                $car_model_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                            } else {
                                                                $car_model_name = $rawName;
                                                            }
                                                        }

                                                        // Get car_make name
                                                        $name = $value->car_make_name ?? 'غير متوفر';
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $value->model_id }}</td>
                                                        <td>{{ $car_model_name }}</td>
                                                        <td>{{ $car_model_name_en }}</td>
                                                        <td class="text-center">

                                                            @php
                                                                $carMakeDisplayName = $name;
                                                                if (is_string($name)) {
                                                                    $decoded_carMake = json_decode($name, true);
                                                                    if (is_array($decoded_carMake)) {
                                                                        $carMakeDisplayName =
                                                                            $decoded_carMake['ar'] ??
                                                                            ($decoded_carMake['en'] ?? $name);
                                                                    }
                                                                }
                                                            @endphp

                                                            @if (!empty($value->carMake?->getCarMakeLogo()))
                                                                <img src="{{ $value->carMake?->getCarMakeLogo() }}"
                                                                    style="height: 30px;" />

                                                                <div class="small mt-1">
                                                                    {{ $carMakeDisplayName }}
                                                                </div>
                                                            @else
                                                                {{ $carMakeDisplayName ?? 'غير محدد' }}
                                                            @endif
                                                        </td>

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
                                                            @include(
                                                                'admin.car_model.partials.action-buttons',
                                                                ['record' => $value]
                                                            )
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4">
                                                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                                            <p class="text-muted">لا توجد موديل متاحة حالياً.</p>
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
                                            $car_model_name = 'غير متوفر';
                                            $car_model_name_en = 'غير متوفر';

                                            $rawName = $value->getAttributes()['name'];

                                            if (is_string($rawName)) {
                                                $decoded_name = json_decode($rawName, true);

                                                if (is_array($decoded_name)) {
                                                    $car_model_name = $decoded_name['ar'] ?? 'غير متوفر';
                                                    $car_model_name_en = $decoded_name['en'] ?? 'غير متوفر';
                                                } else {
                                                    $car_model_name = $rawName;
                                                }
                                            }

                                            // Get car_make name
                                            $name = $value->name ?? 'غير متوفر';
                                            $carMakeDisplayName = $name;
                                            if (is_string($name)) {
                                                $decoded_carMake = json_decode($name, true);
                                                if (is_array($decoded_carMake)) {
                                                    $carMakeDisplayName =
                                                        $decoded_carMake['ar'] ?? ($decoded_carMake['en'] ?? $name);
                                                }
                                            }
                                        @endphp

                                        <div class="card mb-3 mx-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-2 text-center">
                                                        <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center border"
                                                            style="width: 40px; height: 40px; overflow: hidden;">
                                                            @if ($value->carMake && $value->carMake->getCarMakeLogo())
                                                                <img src="{{ $value->carMake->getCarMakeLogo() }}"
                                                                    alt="logo"
                                                                    style="max-width: 100%; max-height: 100%;">
                                                            @else
                                                                <i class="fas fa-car text-secondary"></i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-7">
                                                        <h6 class="card-title mb-1">{{ $car_model_name }}</h6>
                                                        <p class="text-title  mb-1">{{ $car_model_name_en }}</p>
                                                        <p class="text-muted small mb-0">
                                                            <i class="fas fa-hashtag"></i> {{ $value->model_id }}
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

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <small class="text-info">
                                                            <i class="fas fa-layer-group"></i> {{ $carMakeDisplayName }}
                                                        </small>
                                                    </div>
                                                </div>


                                                @include('admin.car_model.partials.action-buttons', [
                                                    'record' => $value,
                                                ])

                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد موديل متاحة حالياً.</p>
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
    @include('admin.car_model.partials.scripts')
 
@endsection
