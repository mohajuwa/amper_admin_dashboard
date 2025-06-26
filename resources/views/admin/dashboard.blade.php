@extends('admin.layouts.app')

@section('style')
 
@endsection

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>{{ __('لوحة التحكم') }}</h1>
                <p class="dashboard-subtitle mb-0">{{ __('مرحباً بك في نظام إدارة المتجر الإلكتروني') }}</p>
            </div>
           
        </div>
    </div>


    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="stats-card danger">
                <div class="stats-icon danger">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="stats-number rtl-number">{{ number_format($TotalOrders) }}</h3>
                <p class="stats-label">{{ __('إجمالي الطلبات') }}</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stats-card info">
                <div class="stats-icon info">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3 class="stats-number rtl-number">{{ number_format($TodayTotalOrders) }}</h3>
                <p class="stats-label">{{ __('طلبات اليوم') }}</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3 class="stats-number rtl-number">ر.س{{ number_format($TotalAmount, 2) }}</h3>
                <p class="stats-label">{{ __('إجمالي المبيعات') }}</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stats-card primary">
                <div class="stats-icon primary">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="stats-number rtl-number">ر.س{{ number_format($TodayTotalAmount, 2) }}</h3>
                <p class="stats-label">{{ __('مبيعات اليوم') }}</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stats-card warning">
                <div class="stats-icon warning">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="stats-number rtl-number">{{ number_format($TotalCustomers) }}</h3>
                <p class="stats-label">{{ __('إجمالي العملاء') }}</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stats-card dark">
                <div class="stats-icon dark">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 class="stats-number rtl-number">{{ number_format($TodayTotalCustomers) }}</h3>
                <p class="stats-label">{{ __('عملاء جدد اليوم') }}</p>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">{{ __('تقرير المبيعات الشهرية') }}</h3>
                    <select class="form-control year-selector changheYear">
                        @for ($i = 2022; $i <= date('Y'); $i++)
                            <option {{ $year == $i ? 'selected' : '' }} value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="chart-summary">
                    <h2 class="chart-summary-amount rtl-number">ر.س{{ number_format($totalAmount, 2) }}</h2>
                    <p class="chart-summary-label">{{ __('إجمالي المبيعات للعام') }} {{ $year }}</p>
                </div>

              

                <div class="position-relative">
                    <canvas id="sales-chart-order" height="300"></canvas>
                </div>

                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #007bff;"></div>
                        <span>{{ __('الطلبات') }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #6c757d;"></div>
                        <span>{{ __('العملاء') }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #dc3545;"></div>
                        <span>{{ __('المبيعات') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Orders -->
    <div class="row">
        <div class="col-12">
            <div class="orders-table-card">
                <div class="orders-table-header">
                    <h3 class="orders-table-title">{{ __('أحدث الطلبات') }}</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('رقم الطلب') }}</th>
                                <th>{{ __('رقم المرجع') }}</th>
                                <th>{{ __('تاريخ الطلب') }}</th>
                                <th>{{ __('المبلغ الإجمالي') }}</th>
                                <th>{{ __('العمليات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($LatestOrders as $order)
                                <tr>
                                    <td>
                                        <span class="order-id rtl-number">#{{ $order->order_id }}</span>
                                    </td>
                                    <td>
                                        <span class="order-number rtl-number">{{ $order->order_number }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ date('d/m/Y', strtotime($order->order_date)) }}</div>
                                            <small class="text-muted">{{ date('h:i A', strtotime($order->order_date)) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="order-amount rtl-number">ر.س{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.order.detail', $order->order_id) }}"
                                            class="btn btn-view btn-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ __('عرض') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p>{{ __('لا توجد طلبات حتى الآن') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('public/assets/dist/js/pages/dashboard3.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Year selector functionality
            $('.changheYear').change(function () {
                var year = $(this).val();
                window.location.href = "{{ url('admin/dashboard?year=') }}" + year;
            });

            // Check if chart canvas exists
            var chartCanvas = document.getElementById('sales-chart-order');
            if (!chartCanvas) {
                console.error('Chart canvas not found');
                return;
            }

            // Get context
            var ctx = chartCanvas.getContext('2d');

            // Prepare data arrays with proper validation
            var rawOrdersData = @json($getTotalOrdersMonth ?? []);
            var rawCustomersData = @json($getTotalCustomersMonth ?? []);
            var rawAmountData = @json($getTotalAmountMonth ?? []);

            // Function to sanitize and validate data
            function sanitizeData(data, defaultLength = 12) {
                // Ensure data is an array
                if (!Array.isArray(data)) {
                    data = [];
                }

                // Convert all values to numbers and handle nulls/undefined
                var sanitized = data.map(function (value, index) {
                    var num = parseFloat(value);
                    return isNaN(num) ? 0 : num;
                });

                // Ensure array has exactly 12 elements
                while (sanitized.length < defaultLength) {
                    sanitized.push(0);
                }

                // Trim to exactly 12 elements if longer
                if (sanitized.length > defaultLength) {
                    sanitized = sanitized.slice(0, defaultLength);
                }

                return sanitized;
            }

            var ordersData = sanitizeData(rawOrdersData);
            var customersData = sanitizeData(rawCustomersData);
            var amountData = sanitizeData(rawAmountData);

            // Create gradient backgrounds
            var gradientOrders = ctx.createLinearGradient(0, 0, 0, 300);
            gradientOrders.addColorStop(0, 'rgba(0, 123, 255, 0.8)');
            gradientOrders.addColorStop(1, 'rgba(0, 123, 255, 0.1)');

            var gradientCustomers = ctx.createLinearGradient(0, 0, 0, 300);
            gradientCustomers.addColorStop(0, 'rgba(108, 117, 125, 0.8)');
            gradientCustomers.addColorStop(1, 'rgba(108, 117, 125, 0.1)');

            // Create the chart with error handling
            try {
                var salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            '{{ __("يناير") }}', '{{ __("فبراير") }}', '{{ __("مارس") }}', '{{ __("أبريل") }}',
                            '{{ __("مايو") }}', '{{ __("يونيو") }}', '{{ __("يوليو") }}', '{{ __("أغسطس") }}',
                            '{{ __("سبتمبر") }}', '{{ __("أكتوبر") }}', '{{ __("نوفمبر") }}', '{{ __("ديسمبر") }}'
                        ],
                        datasets: [
                            {
                                label: '{{ __("الطلبات") }}',
                                data: ordersData,
                                backgroundColor: gradientOrders,
                                borderColor: '#007bff',
                                borderWidth: 3,
                                pointBackgroundColor: '#007bff',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: '{{ __("العملاء") }}',
                                data: customersData,
                                backgroundColor: gradientCustomers,
                                borderColor: '#6c757d',
                                borderWidth: 3,
                                pointBackgroundColor: '#6c757d',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: '{{ __("المبيعات") }} (ر.س)',
                                data: amountData,
                                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                borderColor: '#dc3545',
                                borderWidth: 3,
                                pointBackgroundColor: '#dc3545',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                fill: false,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 10,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y;

                                        if (context.datasetIndex === 2) {
                                            return label + ': ر.س' + value.toLocaleString();
                                        }

                                        return label + ': ' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12
                                    },
                                    callback: function (value) {
                                        return value >= 1000 ? (value / 1000) + 'k' : value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        hover: {
                            animationDuration: 200
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeInOutQuart'
                        }
                    }
                });

            } catch (error) {
                console.error('Error creating chart:', error);
                var chartContainer = document.getElementById('sales-chart-order').parentElement;
                chartContainer.innerHTML = '<div style="text-align: center; padding: 50px; color: #666;"><i class="fas fa-chart-line fa-3x mb-3"></i><br>حدث خطأ في تحميل الرسم البياني</div>';
            }
        });
    </script>
@endsection