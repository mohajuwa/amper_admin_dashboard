@extends('admin.layouts.app')

@section('style')
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group span {
            font-weight: normal;
            color: darkslateblue;
            padding: 5px 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            display: inline-block;
            min-width: 100px;
        }

        .form-group label {
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 5px;
            display: block;
        }

        .language-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .language-section h5 {
            margin-bottom: 15px;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            font-family: monospace;
            font-size: 12px;
        }

        .api-section {
            border: 2px solid #28a745;
            background-color: #f8fff9;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Details</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Order Information - #{{ $getRecord->order_number ?? 'N/A' }}</h3>
                    <div class="float-end text-right">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
                            <i class="nav-icon fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Order Info -->
                        <div class="col-md-6">
                            <div class="language-section">
                                <h5><i class="fas fa-info-circle"></i> Basic Information</h5>

                                <div class="form-group">
                                    <label>Order ID:</label>
                                    <span>{{ $getRecord->order_id }}</span>
                                </div>

                                <div class="form-group">
                                    <label>Customer:</label>
                                    <span>{{ $getRecord->user_name ?? 'N/A' }}</span>
                                </div>

                                <div class="form-group">
                                    <label>User ID:</label>
                                    <span>{{ $getRecord->user_id ?? 'N/A' }}</span>
                                </div>

                                <div class="form-group">
                                    <label>Order Date:</label>
                                    <span>{{ $getRecord->order_date ? \Carbon\Carbon::parse($getRecord->order_date)->format('Y-m-d H:i:s') : 'N/A' }}</span>
                                </div>

                                <div class="form-group">
                                    <label>Year:</label>
                                    <span>{{ $getRecord->year ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Multilingual Fields -->
                        <div class="col-md-6">
                            <div class="language-section">
                                <h5><i class="fas fa-language"></i> Multilingual Information</h5>

                                <!-- Vendor Name -->
                                <div class="form-group">
                                    <label>Vendor Name:</label>
                                    <div>
                                        <strong>EN:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('vendor_name', 'en') ?: 'N/A' }}</span><br>
                                        <strong>AR:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('vendor_name', 'ar') ?: 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Vehicle Make -->
                                <div class="form-group">
                                    <label>Vehicle Make:</label>
                                    <div>
                                        <strong>EN:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('make_name', 'en') ?: 'N/A' }}</span><br>
                                        <strong>AR:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('make_name', 'ar') ?: 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Vehicle Model -->
                                <div class="form-group">
                                    <label>Vehicle Model:</label>
                                    <div>
                                        <strong>EN:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('model_name', 'en') ?: 'N/A' }}</span><br>
                                        <strong>AR:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('model_name', 'ar') ?: 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- License Plate -->
                                <div class="form-group">
                                    <label>License Plate:</label>
                                    <div>
                                        <strong>EN:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('license_plate_number', 'en') ?: 'N/A' }}</span><br>
                                        <strong>AR:</strong>
                                        <span>{{ $getRecord->getLocalizedValue('license_plate_number', 'ar') ?: 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services and Fault Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="language-section">
                                <h5><i class="fas fa-tools"></i> Services & Fault Information</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Fault Type -->
                                        <div class="form-group">
                                            <label>Fault Type:</label>
                                            <div>
                                                <strong>EN:</strong>
                                                <span>{{ $getRecord->getLocalizedValue('fault_type_name', 'en') ?: 'N/A' }}</span><br>
                                                <strong>AR:</strong> <span
                                                    class="text-primary">{{ $getRecord->getLocalizedValue('fault_type_name', 'ar') ?: 'N/A' }}</span>
                                            </div>
                                        </div>

                                        <!-- Sub-Services -->
                                        <div class="form-group">
                                            <label>Sub-Services:</label>
                                            <div>
                                                <strong>EN:</strong>
                                                <span>{{ $getRecord->getLocalizedValue('sub_service_names', 'en') ?: 'N/A' }}</span><br>
                                                <strong>AR:</strong>
                                                <span>{{ $getRecord->getLocalizedValue('sub_service_names', 'ar') ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Service Names -->
                                        <div class="form-group">
                                            <label>Service Names:</label>
                                            <div>
                                                <strong>EN:</strong>
                                                <span>{{ $getRecord->getLocalizedValue('service_names', 'en') ?: 'N/A' }}</span><br>
                                                <strong>AR:</strong>
                                                <span>{{ $getRecord->getLocalizedValue('service_names', 'ar') ?: 'N/A' }}</span>
                                            </div>
                                        </div>

                                        <!-- Services Total Price -->
                                        <div class="form-group">
                                            <label>Services Total Price:</label>
                                            <span>${{ number_format($getRecord->services_total_price ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="paymentDataToggle" style="color: #007bff; cursor: pointer;">
                                    <i class="fas fa-credit-card"></i> Toggle Payment Data
                                </label>
                                <div id="paymentDataSection" style="display: none;" class="language-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Method:</label>
                                                <span>{{ $getRecord->orders_paymentmethod ?? 'N/A' }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Delivery Price:</label>
                                                <span>${{ number_format($getRecord->orders_pricedelivery ?? 0, 2) }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Total Amount:</label>
                                                <span
                                                    class="text-success"><strong>${{ number_format($getRecord->total_amount ?? 0, 2) }}</strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Workshop Amount:</label>
                                                <span>${{ number_format($getRecord->workshop_amount ?? 0, 2) }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label>App Commission:</label>
                                                <span>${{ number_format($getRecord->app_commission ?? 0, 2) }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Status:</label>
                                                <span
                                                    class="badge badge-{{ $getRecord->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($getRecord->payment_status ?? 'pending') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update Form - Connected to your PHP API -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="language-section api-section">
                                <h5><i class="fas fa-api"></i> Update Status (PHP API Integration)</h5>
                                <p class="text-muted"><small>This form sends data to your PHP API:
                                        <code>modwir.com/haytham_store/admin/orders/prepare.php</code></small></p>

                                <!-- AJAX Form (Recommended) -->
                                <form id="ajaxStatusForm">
                                    <input type="hidden" name="orderId" value="{{ $getRecord->order_id }}">
                                    <input type="hidden" name="userId" value="{{ $getRecord->user_id ?? '' }}">

                                    <div class="row">
                                        {{-- Select to change status --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ajax_order_status_select">Change Status:</label>
                                                @php
                                                    $statuses = ['Pending', 'In Progress', 'Delivered', 'Completed', 'Cancelled'];
                                                @endphp
                                                <select name="orderStatus" id="ajax_order_status_select"
                                                    class="form-control" required>
                                                    @foreach ($statuses as $key => $status)
                                                        <option value="{{ $key }}" @if($getRecord->order_status == $key) selected
                                                        @endif>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Submit button --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <button type="button" id="updateStatusBtn" class="btn btn-success">
                                                    <i class="fas fa-sync"></i> Update Status (AJAX)
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Current status badge --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Current Status:</label><br>
                                                @php
                                                    $badgeClasses = [
                                                        0 => 'badge badge-warning',
                                                        1 => 'badge badge-primary',
                                                        2 => 'badge badge-info',
                                                        4 => 'badge badge-success',
                                                        5 => 'badge badge-danger',
                                                    ];
                                                    $currentBadgeClass = $badgeClasses[$getRecord->order_status] ?? 'badge badge-secondary';
                                                    $currentStatusText = $statuses[$getRecord->order_status] ?? 'Unknown';
                                                @endphp
                                                <span class="{{ $currentBadgeClass }} badge-lg">
                                                    {{ $currentStatusText }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                <!-- Status update result -->
                                <div id="statusUpdateResult" style="margin-top: 15px;"></div>

                                <hr style="margin: 20px 0;">

                                <!-- Alternative: Direct Form Submission -->
                                <h6>Alternative: Direct Form Submission</h6>
                                <p class="text-muted"><small>This will redirect to your PHP API page</small></p>
                                <form id="directStatusForm" method="POST"
                                    action="https://modwir.com/haytham_store/admin/orders/prepare.php" target="_blank">
                                    <input type="hidden" name="orderId" value="{{ $getRecord->order_id }}">
                                    <input type="hidden" name="userId" value="{{ $getRecord->user_id ?? '' }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="direct_order_status_select">Status:</label>
                                                <select name="orderStatus" id="direct_order_status_select"
                                                    class="form-control" required>
                                                    @foreach ($statuses as $key => $status)
                                                        <option value="{{ $key }}" @if($getRecord->order_status == $key) selected
                                                        @endif>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-external-link-alt"></i> Update Status (Direct)
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Information (remove in production) -->
                    @if(config('app.debug'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label id="debugToggle" style="color: #dc3545; cursor: pointer;">
                                        <i class="fas fa-bug"></i> Toggle Debug Info (Development Only)
                                    </label>
                                    <div id="debugSection" style="display: none;" class="debug-info">
                                        <h6>Data being sent to PHP API:</h6>
                                        <strong>orderId:</strong> <code>{{ $getRecord->order_id }}</code><br>
                                        <strong>userId:</strong> <code>{{ $getRecord->user_id ?? 'NULL' }}</code><br>
                                        <strong>Current orderStatus:</strong> <code>{{ $getRecord->order_status }}</code>
                                        ({{ $statuses[$getRecord->order_status] ?? 'Unknown' }})<br>

                                        <hr>
                                        <h6>Status Mapping (Integer Values):</h6>
                                        @foreach($statuses as $key => $name)
                                            <code>{{ $key }}</code> =
                                            {{ $name }}{{ $getRecord->order_status == $key ? ' ← Current' : '' }}<br>
                                        @endforeach

                                        <hr>
                                        <h6>Raw JSON Data (Direct from Database):</h6>
                                        @php $rawFields = $getRecord->getRawJsonFields(); @endphp
                                        <strong>Vendor Name:</strong> <code>{{ $rawFields['vendor_name'] ?? 'NULL' }}</code><br>
                                        <strong>Make Name:</strong> <code>{{ $rawFields['make_name'] ?? 'NULL' }}</code><br>
                                        <strong>Model Name:</strong> <code>{{ $rawFields['model_name'] ?? 'NULL' }}</code><br>
                                        <strong>Service Names:</strong>
                                        <code>{{ $rawFields['service_names'] ?? 'NULL' }}</code><br>
                                        <strong>Sub Service Names:</strong>
                                        <code>{{ $rawFields['sub_service_names'] ?? 'NULL' }}</code><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ==========================================================
            // PAYMENT DATA TOGGLE
            // ==========================================================
            const paymentToggle = document.getElementById('paymentDataToggle');
            const paymentSection = document.getElementById('paymentDataSection');

            if (paymentToggle && paymentSection) {
                paymentToggle.addEventListener('click', function () {
                    const isHidden = paymentSection.style.display === 'none';
                    paymentSection.style.display = isHidden ? 'block' : 'none';
                    paymentToggle.innerHTML = isHidden
                        ? '<i class="fas fa-credit-card"></i> Hide Payment Data'
                        : '<i class="fas fa-credit-card"></i> Toggle Payment Data';
                });
            }

            // ==========================================================
            // DEBUG TOGGLE (Development Only)
            // ==========================================================
            const debugToggle = document.getElementById('debugToggle');
            const debugSection = document.getElementById('debugSection');

            if (debugToggle && debugSection) {
                debugToggle.addEventListener('click', function () {
                    const isHidden = debugSection.style.display === 'none';
                    debugSection.style.display = isHidden ? 'block' : 'none';
                    debugToggle.innerHTML = isHidden
                        ? '<i class="fas fa-bug"></i> Hide Debug Info'
                        : '<i class="fas fa-bug"></i> Toggle Debug Info (Development Only)';
                });
            }

            // ==========================================================
            // DIRECT FORM SUBMISSION HANDLER
            // ==========================================================
            const directStatusForm = document.getElementById('directStatusForm');
            if (directStatusForm) {
                directStatusForm.addEventListener('submit', function (e) {
                    const select = document.getElementById('direct_order_status_select');
                    const selectedText = select.options[select.selectedIndex].text;

                    if (!confirm(`Are you sure you want to change the order status to "${selectedText}"? This will open in a new tab.`)) {
                        e.preventDefault();
                        return false;
                    }

                    // Show loading state
                    const submitBtn = directStatusForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Opening...';
                        submitBtn.disabled = true;

                        // Reset button after a delay
                        setTimeout(() => {
                            submitBtn.innerHTML = '<i class="fas fa-external-link-alt"></i> Update Status (Direct)';
                            submitBtn.disabled = false;
                        }, 3000);
                    }
                });
            }

            // ==========================================================
            // AJAX FORM HANDLER WITH DETAILED DEBUG
            // ==========================================================
            const updateStatusBtn = document.getElementById('updateStatusBtn');
            const ajaxStatusForm = document.getElementById('ajaxStatusForm');
            const statusUpdateResult = document.getElementById('statusUpdateResult');

            if (updateStatusBtn && ajaxStatusForm) {
                updateStatusBtn.addEventListener('click', function () {
                    const formData = new FormData(ajaxStatusForm);
                    const selectedStatus = document.getElementById('ajax_order_status_select');
                    const selectedText = selectedStatus.options[selectedStatus.selectedIndex].text;

                    if (!confirm(`Are you sure you want to change the order status to "${selectedText}"?`)) {
                        return;
                    }

                    // Show loading state
                    updateStatusBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                    updateStatusBtn.disabled = true;

                    // Clear previous results
                    statusUpdateResult.innerHTML = '';

                    // Debug: Log the data being sent
                    console.log('=== DEBUG: Form Data ===');
                    console.log('orderId:', formData.get('orderId'));
                    console.log('userId:', formData.get('userId'));
                    console.log('orderStatus:', formData.get('orderStatus'));
                    console.log('Selected Status Text:', selectedText);

                    // Convert FormData to URL-encoded string for PHP API
                    const params = new URLSearchParams();
                    for (let [key, value] of formData.entries()) {
                        params.append(key, value);
                    }

                    console.log('=== DEBUG: URL Params ===');
                    console.log(params.toString());

                    // Show loading message
                    statusUpdateResult.innerHTML = `
                                <div class="alert alert-info">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    Sending request to PHP API...
                                </div>
                            `;

                    // Send AJAX request to your PHP API
                    fetch('https://modwir.com/haytham_store/admin/orders/prepare.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: params.toString()
                    })
                        .then(response => {
                            console.log('=== DEBUG: Response Status ===');
                            console.log(`Status: ${response.status}`);
                            console.log(`Status Text: ${response.statusText}`);
                            console.log(`Content Type: ${response.headers.get('content-type')}`);
                            console.log(`URL: ${response.url}`);

                            // Always try to get response text for debugging
                            return response.text().then(text => {
                                console.log('=== DEBUG: Raw Response ===');
                                console.log(text);

                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${response.statusText}\n\nResponse: ${text}`);
                                }

                                return { text: text, status: response.status };
                            });
                        })
                        .then(responseData => {
                            console.log('=== DEBUG: Success Response ===');
                            console.log(responseData);

                            // Try to parse as JSON, fallback to text
                            let parsedData;
                            try {
                                parsedData = JSON.parse(responseData.text);
                                console.log('=== DEBUG: Parsed JSON ===');
                                console.log(parsedData);
                            } catch (e) {
                                console.log('=== DEBUG: Not JSON, treating as text ===');
                                parsedData = {
                                    status: 'success',
                                    message: responseData.text,
                                    raw_response: responseData.text
                                };
                            }

                            // Reset button state
                            updateStatusBtn.innerHTML = '<i class="fas fa-sync"></i> Update Status (AJAX)';
                            updateStatusBtn.disabled = false;

                            // Show success message
                            if (parsedData.status === 'success') {
                                statusUpdateResult.innerHTML = `
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> 
                                            <strong>Success!</strong><br>
                                            ${parsedData.message || 'Order status updated successfully'}
                                            ${parsedData.data ? `
                                                <br><small>
                                                    Order #${parsedData.data.order_number || parsedData.data.order_id} 
                                                    updated to <strong>${parsedData.data.status_name || selectedText}</strong>
                                                    ${parsedData.data.notification_sent ? ` (Notification: ${parsedData.data.notification_sent})` : ''}
                                                </small>
                                            ` : ''}
                                            <hr>
                                            <button type="button" class="btn btn-sm btn-success" onclick="window.location.reload()">
                                                <i class="fas fa-refresh"></i> Refresh Page
                                            </button>
                                        </div>
                                    `;
                            } else if (parsedData.status === 'error') {
                                // Handle error response from server
                                statusUpdateResult.innerHTML = `
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <strong>Server Error:</strong><br>
                                            ${parsedData.message || 'Unknown error occurred'}
                                            ${parsedData.details ? `<br><small>Details: ${JSON.stringify(parsedData.details)}</small>` : ''}
                                            <hr>
                                            <button type="button" class="btn btn-sm btn-info" onclick="showServerDebugInfo('${encodeURIComponent(JSON.stringify(parsedData))}')">
                                                Show Details
                                            </button>
                                        </div>
                                    `;
                            } else {
                                // Unknown response format
                                statusUpdateResult.innerHTML = `
                                        <div class="alert alert-warning">
                                            <i class="fas fa-question-circle"></i> 
                                            <strong>Unexpected Response:</strong><br>
                                            ${parsedData.message || responseData.text}
                                            <hr>
                                            <button type="button" class="btn btn-sm btn-info" onclick="showRawResponse('${encodeURIComponent(responseData.text)}')">
                                                Show Raw Response
                                            </button>
                                        </div>
                                    `;
                            }
                        })
                        .catch(error => {
                            console.error('=== DEBUG: Error Caught ===');
                            console.error(error);

                            // Reset button state
                            updateStatusBtn.innerHTML = '<i class="fas fa-sync"></i> Update Status (AJAX)';
                            updateStatusBtn.disabled = false;

                            // Show detailed error message
                            statusUpdateResult.innerHTML = `
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        <strong>Request Failed</strong><br>
                                        ${error.message}
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-sm btn-info" onclick="showDebugInfo()">
                                                    <i class="fas fa-bug"></i> Show Debug Info
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-sm btn-warning" onclick="testConnection()">
                                                    <i class="fas fa-flask"></i> Test Connection
                                                </button>
                                            </div>
                                        </div>
                                        <div id="debugInfo" style="display: none; margin-top: 15px;">
                                            <div class="card">
                                                <div class="card-header">Debug Information</div>
                                                <div class="card-body">
                                                    <h6>Request Details:</h6>
                                                    <code style="display: block; white-space: pre-wrap; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                URL: https://modwir.com/haytham_store/admin/orders/prepare.php
                Method: POST
                Content-Type: application/x-www-form-urlencoded

                Form Data:
                orderId: ${formData.get('orderId')}
                userId: ${formData.get('userId')}
                orderStatus: ${formData.get('orderStatus')} (${selectedText})

                Error: ${error.message}
                                                    </code>
                                                    <hr>
                                                    <h6>Troubleshooting Steps:</h6>
                                                    <ol>
                                                        <li>Check browser Network tab (F12) for actual server response</li>
                                                        <li>Click "Test Connection" to verify server is reachable</li>
                                                        <li>Try the "Direct Form Submission" as an alternative</li>
                                                        <li>Check server error logs if you have access</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                        });
                });

                // Add test connection button after the AJAX form
                const testConnectionBtn = document.createElement('button');
                testConnectionBtn.type = 'button';
                testConnectionBtn.className = 'btn btn-sm btn-outline-warning';
                testConnectionBtn.innerHTML = '<i class="fas fa-flask"></i> Test API Connection';
                testConnectionBtn.style.marginTop = '10px';
                testConnectionBtn.addEventListener('click', testConnection);

                // Insert test button after the AJAX form
                ajaxStatusForm.appendChild(testConnectionBtn);
            }
        });

        // ==========================================================
        // UTILITY FUNCTIONS
        // ==========================================================

        // Function to show/hide debug info
        function showDebugInfo() {
            const debugInfo = document.getElementById('debugInfo');
            if (debugInfo) {
                const isHidden = debugInfo.style.display === 'none';
                debugInfo.style.display = isHidden ? 'block' : 'none';
            }
        }

        // Function to show server debug info
        function showServerDebugInfo(encodedData) {
            try {
                const data = JSON.parse(decodeURIComponent(encodedData));
                const newWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
                newWindow.document.write(`
                            <html>
                                <head>
                                    <title>Server Debug Info</title>
                                    <style>
                                        body { font-family: Arial, sans-serif; padding: 20px; }
                                        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow: auto; }
                                        .error { color: #dc3545; }
                                        .success { color: #28a745; }
                                    </style>
                                </head>
                                <body>
                                    <h2>Server Response Details</h2>
                                    <div class="error">
                                        <h3>Status: ${data.status}</h3>
                                        <h4>Message: ${data.message}</h4>
                                    </div>
                                    <h3>Full Response:</h3>
                                    <pre>${JSON.stringify(data, null, 2)}</pre>
                                    <hr>
                                    <button onclick="window.close()" style="padding: 10px 20px;">Close</button>
                                </body>
                            </html>
                        `);
            } catch (e) {
                alert('Error parsing debug data: ' + e.message);
            }
        }

        // Function to show raw response
        function showRawResponse(encodedResponse) {
            const response = decodeURIComponent(encodedResponse);
            const newWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
            newWindow.document.write(`
                        <html>
                            <head>
                                <title>Raw Server Response</title>
                                <style>
                                    body { font-family: monospace; padding: 20px; }
                                    pre { background: #f8f9fa; padding: 15px; border-radius: 4px; white-space: pre-wrap; }
                                </style>
                            </head>
                            <body>
                                <h2>Raw Server Response</h2>
                                <pre>${response}</pre>
                                <hr>
                                <button onclick="window.close()" style="padding: 10px 20px;">Close</button>
                            </body>
                        </html>
                    `);
        }

        // Test connection function  
        function testConnection() {
            const testUrl = 'https://modwir.com/haytham_store/admin/orders/test.php';

            console.log('Testing connection to:', testUrl);

            // Show loading state
            const statusUpdateResult = document.getElementById('statusUpdateResult');
            if (statusUpdateResult) {
                statusUpdateResult.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-spinner fa-spin"></i> 
                                Testing connection to server...
                            </div>
                        `;
            }

            fetch(testUrl)
                .then(response => {
                    console.log('Test connection response status:', response.status);
                    return response.text();
                })
                .then(data => {
                    console.log('=== TEST CONNECTION RESULT ===');
                    console.log(data);

                    // Show results in a new window
                    const newWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
                    newWindow.document.write(`
                                <html>
                                    <head>
                                        <title>API Connection Test Results</title>
                                        <style>
                                            body { 
                                                font-family: monospace; 
                                                padding: 20px; 
                                                background: #f8f9fa; 
                                            }
                                            .container {
                                                background: white;
                                                padding: 20px;
                                                border-radius: 8px;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                            }
                                            pre { 
                                                background: #f8f9fa; 
                                                padding: 15px; 
                                                border-radius: 4px; 
                                                white-space: pre-wrap;
                                                border: 1px solid #dee2e6;
                                            }
                                            .success { color: #28a745; font-weight: bold; }
                                            .error { color: #dc3545; font-weight: bold; }
                                            .warning { color: #ffc107; font-weight: bold; }
                                            button {
                                                background: #007bff;
                                                color: white;
                                                border: none;
                                                padding: 10px 20px;
                                                border-radius: 4px;
                                                cursor: pointer;
                                                margin: 5px;
                                            }
                                            button:hover { background: #0056b3; }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="container">
                                            <h2>🔧 API Connection Test Results</h2>
                                            <p><strong>Test URL:</strong> ${testUrl}</p>
                                            <p><strong>Test Time:</strong> ${new Date().toLocaleString()}</p>
                                            <hr>
                                            <pre>${data}</pre>
                                            <hr>
                                            <button onclick="window.close()">Close Window</button>
                                            <button onclick="window.location.reload()">Refresh Test</button>
                                        </div>
                                    </body>
                                </html>
                            `);

                    // Update status result
                    if (statusUpdateResult) {
                        statusUpdateResult.innerHTML = `
                                    <div class="alert alert-info">
                                        <i class="fas fa-check-circle"></i> 
                                        Connection test completed. Check the popup window for results.
                                    </div>
                                `;
                    }
                })
                .catch(error => {
                    console.error('Test connection failed:', error);

                    if (statusUpdateResult) {
                        statusUpdateResult.innerHTML = `
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        <strong>Connection Test Failed:</strong><br>
                                        ${error.message}
                                        <hr>
                                        <small>This might indicate a server connectivity issue or CORS problem.</small>
                                    </div>
                                `;
                    }

                    // Also show in alert as backup
                    alert('Connection test failed: ' + error.message);
                });
        }

        // JSON Test function (for debug section)
        function testJsonDecoding() {
            const testData = {
                        @php $rawFields = $getRecord->getRawJsonFields(); @endphp
            vendor_name: {!! json_encode($rawFields['vendor_name'] ?? null) !!},
            make_name: {!! json_encode($rawFields['make_name'] ?? null) !!},
                service_names: {!! json_encode($rawFields['service_names'] ?? null) !!}
                    };

        let result = '<h6>JavaScript JSON Test Results:</h6>';

        Object.keys(testData).forEach(key => {
            const value = testData[key];
            result += `<div style="margin-bottom: 10px; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">`;
            result += `<strong>${key.replace('_', ' ').toUpperCase()}:</strong><br>`;
            result += `<small>Raw Value:</small> <code style="background: #f8f9fa; padding: 2px 4px;">${value || 'NULL'}</code><br>`;

            if (value) {
                try {
                    const parsed = JSON.parse(value);
                    result += `<small>Parsed Value:</small> <code style="background: #d4edda; padding: 2px 4px;">${JSON.stringify(parsed)}</code><br>`;
                    result += `<span class="badge badge-success">✓ Valid JSON</span>`;
                } catch (e) {
                    result += `<small>Parse Error:</small> <code style="background: #f8d7da; padding: 2px 4px;">${e.message}</code><br>`;
                    result += `<span class="badge badge-danger">✗ Invalid JSON</span>`;
                }
            } else {
                result += `<span class="badge badge-warning">⚠ No Data</span>`;
            }
            result += `</div>`;
        });

        const resultElement = document.getElementById('jsonTestResult');
        if (resultElement) {
            resultElement.innerHTML = result;
        }
                }

    </script>
@endsection