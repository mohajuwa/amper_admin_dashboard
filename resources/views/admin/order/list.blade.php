@extends('admin.layouts.app') 

@section('content')
<style>
/* Custom CSS for responsive table */
.table-responsive-custom {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.table-responsive-custom::-webkit-scrollbar {
    height: 8px;
}

.table-responsive-custom::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive-custom::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-responsive-custom::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.table-responsive-custom table {
    min-width: 800px; /* Minimum width to ensure all columns are visible */
    margin-bottom: 0;
}

.table-responsive-custom .table th,
.table-responsive-custom .table td {
    white-space: nowrap;
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
}

/* Mobile specific adjustments */
@media (max-width: 768px) {
    .table-responsive-custom .table th,
    .table-responsive-custom .table td {
        padding: 0.5rem 0.3rem;
        font-size: 0.875rem;
    }
    
    .table-responsive-custom .table th:first-child,
    .table-responsive-custom .table td:first-child {
        padding-left: 0.75rem;
    }
    
    .table-responsive-custom .table th:last-child,
    .table-responsive-custom .table td:last-child {
        padding-right: 0.75rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25em 0.4em;
    }
}

/* Search form responsive adjustments */
@media (max-width: 768px) {
    .search-form .col-md-2,
    .search-form .col-md-3 {
        margin-bottom: 1rem;
    }
}

/* Pagination wrapper */
.pagination-wrapper {
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

@media (max-width: 576px) {
    .pagination-wrapper {
        padding: 0.5rem;
    }
    
    .pagination-wrapper .pagination {
        justify-content: center;
        margin-bottom: 0;
    }
}

/* Status badges responsive */
.status-badge {
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
}

@media (max-width: 576px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
}
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="m-0">قائمة الطلبات</h1>
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
                            <h3 class="card-title">البحث في الطلبات</h3>
                        </div>
                        <div class="card-body search-form">
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>المعرف</label>
                                    <input type="text" name="order_id" value="{{ Request::get('order_id') }}"
                                        class="form-control" placeholder="معرف الطلب">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>رقم الطلب</label>
                                    <input type="text" name="order_number" value="{{ Request::get('order_number') }}"
                                        class="form-control" placeholder="رقم الطلب">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>البائع</label>
                                    <input type="text" name="vendor_name" value="{{ Request::get('vendor_name') }}"
                                        class="form-control" placeholder="اسم البائع">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>طريقة الدفع</label>
                                    <select name="payment_method" class="form-control">
                                        <option value="">جميع طرق الدفع</option>
                                        <option value="0" {{ Request::get('payment_method') == '0' ? 'selected' : '' }}>نقداً</option>
                                        <option value="1" {{ Request::get('payment_method') == '1' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>حالة الطلب</label>
                                    <select name="order_status" class="form-control">
                                        <option value="">جميع الحالات</option>
                                        <option value="0" {{ Request::get('order_status') == '0' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="1" {{ Request::get('order_status') == '1' ? 'selected' : '' }}>قيد التنفيذ</option>
                                        <option value="2" {{ Request::get('order_status') == '2' ? 'selected' : '' }}>تم التسليم</option>
                                        <option value="4" {{ Request::get('order_status') == '4' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="5" {{ Request::get('order_status') == '5' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                </div>
                                </div>
                            <div class="row mt-2">

                            
                     
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12 ">
                                    <label>من تاريخ</label>
                                    <input type="date" name="from_date" value="{{ Request::get('from_date') }}"
                                        class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>إلى تاريخ</label>
                                    <input type="date" name="to_date" value="{{ Request::get('to_date') }}"
                                        class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>الحد الأدنى للمبلغ</label>
                                    <input type="number" name="min_amount" value="{{ Request::get('min_amount') }}"
                                        class="form-control" placeholder="0.00" step="0.01">
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                    <label>الحد الأقصى للمبلغ</label>
                                    <input type="number" name="max_amount" value="{{ Request::get('max_amount') }}"
                                        class="form-control" placeholder="0.00" step="0.01">
                                </div>


                             <div class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex m-2 align-items-center justify-content-center">
                                        <div class="form-group w-100">
                                            <button class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-search"></i> 
                                            </button>
                                            <a href="{{ route('admin.order.list') }}"
                                                class="btn btn-info btn-block">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                     
                        </div>
                    </div>
                </form>

                {{-- Order Table --}}
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">قائمة الطلبات (المجموع : {{ $getRecord->total() }}) </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive-custom">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الطلب</th>
                                        <th>البائع</th>
                                        <th>طريقة الدفع</th>
                                        <th>التاريخ</th>
                                        <th>المجموع</th>
                                        <th>الحالة</th>
                                        <th>الإجراء</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($getRecord as $value)
                                        @php
                                            $vendor_name = json_decode(optional($value->vendor)->vendor_name, true);
                                        @endphp
                                        <tr>
                                            <td>{{ $value->order_id }}</td>
                                            <td>{{ $value->order_number }}</td>
                                            <td>{{ $vendor_name['ar'] ?? 'غير متوفر' }}</td>

                                            <td>
                                                @if ($value->payment_methods == 0)
                                                    نقداً
                                                @else
                                                    بطاقة ائتمان
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    <div class="font-weight-bold">{{ \Carbon\Carbon::parse($value->order_date)->locale('ar')->translatedFormat('j F Y') }}</div>
                                                    <div class="text-muted">{{ \Carbon\Carbon::parse($value->order_date)->format('g:i A') }}</div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($value->total_amount, 2) }} ر.س</td>

                                            <td>
                                                @php
                                                    $statuses = [
                                                        0 => ['label' => 'قيد الانتظار', 'class' => 'badge badge-warning'],
                                                        1 => [
                                                            'label' => 'قيد التنفيذ',
                                                            'class' => 'badge badge-primary',
                                                        ],
                                                        2 => ['label' => 'تم التسليم', 'class' => 'badge badge-info'],
                                                        4 => ['label' => 'مكتمل', 'class' => 'badge badge-success'],
                                                        5 => ['label' => 'ملغي', 'class' => 'badge badge-danger'],
                                                    ];
                                                    $status = $statuses[$value['order_status']] ?? [
                                                        'label' => 'غير معروف',
                                                        'class' => 'badge badge-secondary',
                                                    ];
                                                @endphp
                                                <span class="status-badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                            </td>

                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.order.detail', $value->order_id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-wrapper">
                            <nav aria-label="الصفحات">
                                <ul class="pagination pagination-sm justify-content-center">
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
@endsection

@section('script')
    <script type="text/javascript">
        $('body').delegate('.changeStatus', 'change', function() {
            let status = $(this).val();
            let order_id = $(this).attr('id');
            $.ajax({
                type: "GET",
                url: "{{ route('admin.order.update-status', ':id') }}".replace(':id', order_id),
                data: {
                    status: status,
                    order_id: order_id
                },
                dataType: "json",
                success: function(data) {
                    alert(data.message);
                }
            });
        });
    </script>
@endsection