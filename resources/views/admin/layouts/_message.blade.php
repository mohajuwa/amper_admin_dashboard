{{-- Success Alert --}}
@if (session('success'))
    <script>
        $(document).ready(function() {
            if (typeof showSuccess === 'function') {
                showSuccess(@json(session('success')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('نجح') }}',
                    text: @json(session('success')),
                    confirmButtonText: '{{ __('موافق') }}',
                    timer: 3000
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.success(@json(session('success')));
            } else {
                alert(@json(session('success')));
            }
        });
    </script>
@endif

{{-- Error Alert --}}
@if (session('error'))
    <script>
        $(document).ready(function() {
            if (typeof showError === 'function') {
                showError(@json(session('error')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('خطأ') }}',
                    text: @json(session('error')),
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.error(@json(session('error')));
            } else {
                alert('{{ __('خطأ') }}: ' + @json(session('error')));
            }
        });
    </script>
@endif

{{-- Warning Alert --}}
@if (session('warning'))
    <script>
        $(document).ready(function() {
            if (typeof showWarning === 'function') {
                showWarning(@json(session('warning')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __('تحذير') }}',
                    text: @json(session('warning')),
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.warning(@json(session('warning')));
            } else {
                alert('{{ __('تحذير') }}: ' + @json(session('warning')));
            }
        });
    </script>
@endif

{{-- Info Alert --}}
@if (session('info'))
    <script>
        $(document).ready(function() {
            if (typeof showInfo === 'function') {
                showInfo(@json(session('info')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: '{{ __('معلومات') }}',
                    text: @json(session('info')),
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.info(@json(session('info')));
            } else {
                alert('{{ __('معلومات') }}: ' + @json(session('info')));
            }
        });
    </script>
@endif

{{-- Payment Error Alert --}}
@if (session('payment-error'))
    <script>
        $(document).ready(function() {
            if (typeof showPaymentError === 'function') {
                showPaymentError(@json(session('payment-error')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('خطأ في الدفع') }}',
                    text: @json(session('payment-error')),
                    confirmButtonText: '{{ __('موافق') }}',
                    showCancelButton: true,
                    cancelButtonText: '{{ __('إلغاء') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.error(@json(session('payment-error')), '{{ __('خطأ في الدفع') }}');
            } else {
                alert('{{ __('خطأ في الدفع') }}: ' + @json(session('payment-error')));
            }
        });
    </script>
@endif

{{-- Primary Alert --}}
@if (session('primary'))
    <script>
        $(document).ready(function() {
            if (typeof showPrimary === 'function') {
                showPrimary(@json(session('primary')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: '{{ __('إشعار') }}',
                    text: @json(session('primary')),
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.info(@json(session('primary')));
            } else {
                alert(@json(session('primary')));
            }
        });
    </script>
@endif

{{-- Secondary Alert --}}
@if (session('secondary'))
    <script>
        $(document).ready(function() {
            if (typeof showSecondary === 'function') {
                showSecondary(@json(session('secondary')));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'question',
                    title: '{{ __('إشعار ثانوي') }}',
                    text: @json(session('secondary')),
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.info(@json(session('secondary')));
            } else {
                alert(@json(session('secondary')));
            }
        });
    </script>
@endif

{{-- Validation Errors Alert --}}
@if (session('validation_errors'))
    <script>
        $(document).ready(function() {
            var errors = @json(session('validation_errors'));
            var errorMessage = '{{ __('يرجى إصلاح الأخطاء التالية') }}:\n';
            errors.forEach(function(error, index) {
                errorMessage += (index + 1) + '. ' + error + '\n';
            });

            if (typeof showValidationErrors === 'function') {
                showValidationErrors(errors);
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('أخطاء في التحقق') }}',
                    html: '<div style="text-align: right;"><ul>' +
                        errors.map(error => '<li>' + error + '</li>').join('') +
                        '</ul></div>',
                    confirmButtonText: '{{ __('موافق') }}'
                });
            } else if (typeof toastr !== 'undefined') {
                toastr.error(errorMessage, '{{ __('أخطاء في التحقق') }}');
            } else {
                alert(errorMessage);
            }
        });
    </script>
@endif

{{-- Original Bootstrap Alerts (as fallback or additional display) --}}
@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    <div class="alert-container mb-3">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('payment-error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-credit-card mr-2"></i>
                {{ session('payment-error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('primary'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <i class="fas fa-info mr-2"></i>
                {{ session('primary') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('secondary'))
            <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                <i class="fas fa-info mr-2"></i>
                {{ session('secondary') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
@endif

{{-- Validation Errors Bootstrap Alert --}}
@if ($errors->any() || session('validation_errors'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>{{ __('يرجى إصلاح الأخطاء التالية:') }}</strong>
        <ul class="mb-0 mt-2">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endif
            @if (session('validation_errors'))
                @foreach (session('validation_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endif
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('إغلاق') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
