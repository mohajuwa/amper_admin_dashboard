<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $header_title ?? 'لوحة التحكم' }} - {{ config('app.name', 'متجر إلكتروني') }}</title>

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Arabic Font Only -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/css/admin-design-system.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Toast Notifications CSS -->
    <link rel="stylesheet" href="{{ url('public/assets/css/notifications.css') }}">

    @stack('styles')
    @yield('style')


</head>

<body class="hold-transition sidebar-mini layout-fixed rtl">
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Confirm Modal Container -->
    <div class="custom-modal-overlay" id="modernConfirmModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <div class="custom-modal-icon">
                    <i class="fas fa-question"></i>
                </div>
                <div class="custom-modal-title" id="modernConfirmTitle">تأكيد العملية</div>
            </div>
            <div class="custom-modal-body">
                <p id="modernConfirmMessage">هل أنت متأكد من القيام بهذا الإجراء؟</p>
            </div>
            <div class="custom-modal-actions">
                <button id="modernConfirmCancel" class="btn btn-danger">إلغاء</button>
                <button id="modernConfirmOk" class="btn btn-success">موافق</button>
            </div>
        </div>
    </div>

    <div class="wrapper" id="app">
        @include('admin.layouts.header')

        <!-- Sidebar Overlay for Mobile/Tablet -->
        <div class="sidebar-overlay"></div>

        @include('admin.layouts.sidebar')

        <div class="content-wrapper">
            @include('admin.layouts._message')

            @yield('content')
        </div>

        @include('admin.layouts.footer')
    </div>

    <!-- Core Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.js') }}"></script>
    <script src="{{ url('public/assets/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Our fixed consolidated scripts file -->
    <script src="{{ url('public/assets/js/scripts.js') }}"></script>


    {{-- Enhanced Flash Messages Handler Script --}}
    <script>
        /**
         * Enhanced Flash Message Handler with Global showNotification
         * Works both with and without redirections
         */

        class FlashMessageHandler {
            constructor() {
                this.baseUrl = '/admin'; // Adjust based on your admin prefix
                this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                this.initializeGlobalFunctions();
            }

            /**
             * Initialize global notification functions
             */
            initializeGlobalFunctions() {
                // Make showNotification globally available
                window.showNotification = this.showNotification.bind(this);

                // Individual notification functions
                window.showSuccess = (message) => this.showNotification('success', message);
                window.showError = (message) => this.showNotification('error', message);
                window.showWarning = (message) => this.showNotification('warning', message);
                window.showInfo = (message) => this.showNotification('info', message);
                window.showPrimary = (message) => this.showNotification('primary', message);
                window.showSecondary = (message) => this.showNotification('secondary', message);
                window.showPaymentError = (message) => this.showNotification('payment-error', message);
                window.showValidationErrors = (errors) => this.showValidationErrors(errors);
                window.showCustomAlert = (message, type) => this.showNotification(type, message);
            }

            /**
             * Global showNotification function - works with or without redirect
             * @param {string} type - success, error, warning, info, payment-error, primary, secondary
             * @param {string} message - Message to display
             * @param {object} options - Additional options
             */
            showNotification(type, message, options = {}) {
                const titles = {
                    'success': 'نجح العمل',
                    'error': 'حدث خطأ',
                    'warning': 'تحذير',
                    'info': 'معلومات',
                    'payment-error': 'خطأ في الدفع',
                    'primary': 'إشعار',
                    'secondary': 'إشعار ثانوي'
                };

                const title = options.title || titles[type] || '';

                // Priority order: Custom toast > SweetAlert > Toastr > Native alert
                if (typeof window.showToast === 'function') {
                    window.showToast(type, title, message);
                } else if (typeof window.AlertHandler !== 'undefined') {
                    window.AlertHandler.show(type, message, title);
                } else if (typeof Swal !== 'undefined') {
                    const swalIcons = {
                        'success': 'success',
                        'error': 'error',
                        'warning': 'warning',
                        'info': 'info',
                        'payment-error': 'error',
                        'primary': 'info',
                        'secondary': 'question'
                    };

                    Swal.fire({
                        icon: swalIcons[type] || 'info',
                        title: title,
                        text: message,
                        confirmButtonText: 'موافق',
                        timer: type === 'success' ? (options.timer || 3000) : null,
                        showCloseButton: true,
                        allowOutsideClick: true
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr[type === 'payment-error' ? 'error' : type](message, title);
                } else {
                    alert((title ? title + ': ' : '') + message);
                }
            }

            /**
             * Show validation errors immediately
             * @param {array} errors 
             */
            showValidationErrors(errors) {
                const errorMessage = Array.isArray(errors) ? errors.join('\n') : errors;
                const title = 'أخطاء في التحقق';

                if (typeof window.showToast === 'function') {
                    window.showToast('error', title, errorMessage);
                } else if (typeof window.AlertHandler !== 'undefined') {
                    window.AlertHandler.show('error', errorMessage, title);
                } else if (typeof Swal !== 'undefined') {
                    const errorList = Array.isArray(errors) ? errors : [errors];
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        html: '<div style="text-align: right;"><ul>' +
                            errorList.map(error => '<li>' + error + '</li>').join('') +
                            '</ul></div>',
                        confirmButtonText: 'موافق',
                        showCloseButton: true
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.error('يرجى إصلاح الأخطاء التالية:\n' + errorMessage, title);
                } else {
                    alert(title + ':\n' + errorMessage);
                }
            }

            /**
             * Set flash message via AJAX route (for redirections)
             * @param {string} type - success, error, warning, info, payment-error, primary, secondary
             * @param {string} message - Message to display
             * @param {function} callback - Optional callback after setting message
             */
            async setFlashMessage(type, message, callback = null) {
                const routeMap = {
                    'success': '/set-flash-success',
                    'error': '/set-flash-error',
                    'warning': '/set-flash-warning',
                    'info': '/set-flash-info',
                    'payment-error': '/set-flash-payment-error',
                    'primary': '/set-flash-primary',
                    'secondary': '/set-flash-secondary'
                };

                const route = routeMap[type];
                if (!route) {
                    console.error('Invalid flash message type:', type);
                    return;
                }

                try {
                    const response = await fetch(this.baseUrl + route, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            message
                        })
                    });

                    const result = await response.json();

                    if (result.status === 'ok') {
                        // Show immediate feedback as well
                        this.showNotification(type, message);

                        if (callback) {
                            callback();
                        }
                    }
                } catch (error) {
                    console.error('Error setting flash message:', error);
                    // Fallback to immediate display
                    this.showNotification(type, message);
                }
            }

            /**
             * Set validation errors via AJAX route (for redirections)
             * @param {array} errors - Array of error messages
             * @param {function} callback - Optional callback
             */
            async setValidationErrors(errors, callback = null) {
                try {
                    const response = await fetch(this.baseUrl + '/set-validation-errors', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            errors
                        })
                    });

                    const result = await response.json();

                    if (result.status === 'ok') {
                        // Show immediate feedback
                        this.showValidationErrors(errors);

                        if (callback) {
                            callback();
                        }
                    }
                } catch (error) {
                    console.error('Error setting validation errors:', error);
                    // Fallback to immediate display
                    this.showValidationErrors(errors);
                }
            }

            /**
             * Redirect with flash message - stores in sessionStorage for next page
             * @param {string} message 
             * @param {string} type 
             * @param {string} url 
             */
            redirectWithFlash(message, type, url) {
                if (typeof(Storage) !== "undefined") {
                    sessionStorage.setItem('alertMessage', message);
                    sessionStorage.setItem('alertType', type);
                }
                window.location.href = url;
            }

            /**
             * Enhanced form submission with flash messages
             * @param {jQuery} $form - Form element
             * @param {object} options - Additional options
             */
            submitFormWithFlash($form, options = {}) {
                const $btn = $form.find('button[type="submit"]');
                const originalText = $btn.html();
                const loadingText = options.loadingText || '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...';

                // Disable button and show loading
                $btn.prop('disabled', true).html(loadingText);

                // Prepare form data
                let formData;
                if ($form.attr('enctype') === 'multipart/form-data') {
                    formData = new FormData($form[0]);
                } else {
                    formData = $form.serialize();
                }

                return $.ajax({
                    url: $form.attr('action') || window.location.href,
                    type: $form.attr('method') || 'POST',
                    data: formData,
                    contentType: $form.attr('enctype') === 'multipart/form-data' ? false :
                        'application/x-www-form-urlencoded; charset=UTF-8',
                    processData: $form.attr('enctype') === 'multipart/form-data' ? false : true,
                    success: (response) => {
                        // Handle success message
                        if (response.message) {
                            if (response.redirect) {
                                // Use redirect with flash for navigation
                                this.redirectWithFlash(response.message, response.type || 'success',
                                    response.redirect);
                            } else {
                                // Show immediate notification
                                this.showNotification(response.type || 'success', response.message);

                                if (options.resetForm !== false) {
                                    $form[0].reset();
                                }
                                $btn.prop('disabled', false).html(originalText);
                            }
                        } else if (response.redirect) {
                            // Direct redirect without message
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, options.redirectDelay || 500);
                        } else {
                            // Reset form and button
                            $btn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: (xhr) => {
                        $btn.prop('disabled', false).html(originalText);

                        const response = xhr.responseJSON;
                        let message = options.defaultErrorMessage || 'حدث خطأ أثناء المعالجة';

                        if (response) {
                            if (response.errors) {
                                if (Array.isArray(response.errors)) {
                                    this.showValidationErrors(response.errors);
                                    return;
                                } else if (typeof response.errors === 'object') {
                                    // Laravel validation errors format
                                    const errors = Object.values(response.errors).flat();
                                    this.showValidationErrors(errors);
                                    return;
                                }
                            } else if (response.message) {
                                message = response.message;
                            }
                        }

                        this.showNotification('error', message);
                    }
                });
            }
        }

        // Initialize global flash message handler
        window.FlashHandler = new FlashMessageHandler();

        // Enhanced jQuery integration
        $(document).ready(function() {
            // Auto-handle forms with data-flash attribute
            $(document).on('submit', 'form[data-flash="true"]', function(e) {
                e.preventDefault();

                const $form = $(this);
                const options = {
                    loadingText: $form.data('loading-text'),
                    defaultErrorMessage: $form.data('error-message'),
                    redirectDelay: parseInt($form.data('redirect-delay')) || 1500,
                    resetForm: $form.data('reset-form') !== false
                };

                window.FlashHandler.submitFormWithFlash($form, options);
            });

            // Handle existing flash messages from server
            @if (session('success'))
                window.showNotification('success', @json(session('success')));
            @endif

            @if (session('error'))
                window.showNotification('error', @json(session('error')));
            @endif

            @if (session('warning'))
                window.showNotification('warning', @json(session('warning')));
            @endif

            @if (session('info'))
                window.showNotification('info', @json(session('info')));
            @endif

            @if (session('payment-error'))
                window.showNotification('payment-error', @json(session('payment-error')));
            @endif

            @if (session('primary'))
                window.showNotification('primary', @json(session('primary')));
            @endif

            @if (session('secondary'))
                window.showNotification('secondary', @json(session('secondary')));
            @endif

            @if (session('validation_errors'))
                window.showValidationErrors(@json(session('validation_errors')));
            @endif

            @if ($errors->any())
                window.showValidationErrors(@json($errors->all()));
            @endif

            // Check for saved alerts from sessionStorage (after redirects)
            if (typeof(Storage) !== "undefined") {
                const msg = sessionStorage.getItem('alertMessage');
                const type = sessionStorage.getItem('alertType');
                if (msg && type) {
                    window.showNotification(type, msg);
                    sessionStorage.removeItem('alertMessage');
                    sessionStorage.removeItem('alertType');
                }
            }
        });

        // Utility functions for quick access (both immediate and with redirect)
        window.setFlashSuccess = (message, callback) => window.FlashHandler.setFlashMessage('success', message, callback);
        window.setFlashError = (message, callback) => window.FlashHandler.setFlashMessage('error', message, callback);
        window.setFlashWarning = (message, callback) => window.FlashHandler.setFlashMessage('warning', message, callback);
        window.setFlashInfo = (message, callback) => window.FlashHandler.setFlashMessage('info', message, callback);

        // Redirect with flash message helper
        window.redirectWithFlash = (message, type, url) => window.FlashHandler.redirectWithFlash(message, type, url);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('.select2').select2({
                dir: "rtl",
                width: '100%',
                placeholder: 'اختر من القائمة',
                allowClear: true
            });
        });
    </script>

    @stack('scripts')
    @yield('script')

</body>

</html>
