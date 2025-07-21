@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="m-0">قائمة التجار</h1>
            <a href="{{ route('admin.vendor.create') }}" class="btn btn-sm btn-primary">إضافة تاجر جديد</a>
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
                            <h3 class="card-title">البحث في التجار</h3>
                        </div>
                        <div class="card-body" style="overflow:auto">
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 form-group">
                                    <label>المعرف</label>
                                    <input type="text" name="vendor_id" value="{{ Request::get('vendor_id') }}" class="form-control" placeholder="معرف التاجر">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 form-group">
                                    <label>اسم التاجر</label>
                                    <input type="text" name="vendor_name" value="{{ Request::get('vendor_name') }}" class="form-control" placeholder="اسم التاجر">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 form-group">
                                    <label>نوع التاجر</label>
                                    <select name="vendor_type" class="form-control">
                                        <option value="">الكل</option>
                                        <option value="workshop" {{ request('vendor_type') == 'workshop' ? 'selected' : '' }}>ورشة</option>
                                        <option value="tow_truck" {{ request('vendor_type') == 'tow_truck' ? 'selected' : '' }}>سطحة</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 form-group">
                                    <label>الحالة</label>
                                    <select name="status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>محذوف</option>
                                    </select>
                                </div>
                                   <div
                                        class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('admin.vendor.list') }}" class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Results --}}
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">قائمة التجار (المجموع: {{ $getRecord->total() }})</h3>
                    </div>
                    <div class="card-body p-0">

                        {{-- Desktop Table --}}
                        <div class="d-none d-lg-block">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead class="text-center bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم التاجر (عربي)</th>
                                            <th>اسم التاجر (إنجليزي)</th>
                                            <th>النوع</th>
                                            <th>المالك</th>
                                            <th>الهاتف</th>
                                            <th>الحالة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @forelse($getRecord as $value)
                                        <tr>
                                            <td>{{ $value->vendor_id }}</td>
                                            <td>{{ $value->vendor_name_ar }}</td>
                                            <td>{{ $value->vendor_name_en }}</td>
                                            <td>
                                                @if($value->vendor_type == 'workshop') <span class="badge badge-primary">ورشة</span>
                                                @elseif($value->vendor_type == 'tow_truck') <span class="badge badge-warning">سطحة</span>
                                                @endif
                                            </td>
                                            <td>{{ $value->owner_name_ar }}</td>
                                            <td>{{ $value->phone }}</td>
                                            <td>
                                                @if($value->status == 1) <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i>نشط</span>
                                                @elseif($value->status == 0) <span class="badge badge-secondary px-3 py-2"><i class="fas fa-pause-circle mr-1"></i>غير نشط</span>
                                                @elseif($value->status == 2) <span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i>محذوف</span>
                                                @endif
                                            </td>
                                            <td>
                                                @include('admin.vendor.partials.action-buttons', ['record' => $value])
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="8" class="text-center py-4"><p class="text-muted">لا توجد سجلات.</p></td></tr>
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
                                                <h5 class="card-title mb-1">{{ $value->vendor_name_ar }}</h5>
                                                <p class="text-muted small mb-1">{{ $value->owner_name_ar }}</p>
                                                <p class="text-info small mb-0">
                                                    <i class="fas fa-phone-alt fa-flip-horizontal"></i> {{ $value->phone }}
                                                </p>
                                            </div>
                                            <div class="col-4 text-right">
                                                @if($value->vendor_type == 'workshop')
                                                    <span class="badge badge-primary d-block mb-2">ورشة</span>
                                                @else
                                                    <span class="badge badge-warning d-block mb-2">سطحة</span>
                                                @endif

                                                @if ($value->status == 1) <span class="badge badge-success d-block"><i class="fas fa-check-circle"></i> نشط</span>
                                                @elseif ($value->status == 0) <span class="badge badge-secondary d-block"><i class="fas fa-pause-circle"></i> غير نشط</span>
                                                @else <span class="badge badge-danger d-block"><i class="fas fa-times-circle"></i> محذوف</span>
                                                @endif
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        @include('admin.vendor.partials.action-buttons', ['record' => $value])
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">لا يوجد تجار متاحين حالياً.</p>
                                </div>
                            @endforelse
                        </div>
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
</section>
@endsection

@section('script')
    {{-- You can create a scripts partial for vendors if needed --}}
    @include('admin.vendor.partials.scripts')
@endsection