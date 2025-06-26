<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Alert System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            width: 100%;
        }

        /* Toast Styles */
        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
            padding: 20px;
            transform: translateX(450px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 5px solid #4CAF50;
            position: relative;
            overflow: hidden;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.hide {
            transform: translateX(450px);
            opacity: 0;
        }

        /* Toast Types */
        .toast.success {
            border-left-color: #4CAF50;
        }

        .toast.error {
            border-left-color: #f44336;
        }

        .toast.warning {
            border-left-color: #ff9800;
        }

        .toast.info {
            border-left-color: #2196F3;
        }

        /* Toast Header */
        .toast-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            margin-left: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            font-size: 12px;
        }

        .toast.success .toast-icon {
            background: #4CAF50;
        }

        .toast.error .toast-icon {
            background: #f44336;
        }

        .toast.warning .toast-icon {
            background: #ff9800;
        }

        .toast.info .toast-icon {
            background: #2196F3;
        }

        .toast-title {
            font-weight: 600;
            color: #333;
            font-size: 16px;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            color: #999;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            background: #f5f5f5;
            color: #666;
        }

        .toast-body {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Progress Bar */
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }

        .toast-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #45a049);
            width: 100%;
            transform: translateX(-100%);
            transition: transform linear;
        }

        .toast.error .toast-progress-bar {
            background: linear-gradient(90deg, #f44336, #d32f2f);
        }

        .toast.warning .toast-progress-bar {
            background: linear-gradient(90deg, #ff9800, #f57c00);
        }

        .toast.info .toast-progress-bar {
            background: linear-gradient(90deg, #2196F3, #1976D2);
        }

        /* Confirmation Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .modal-overlay.show .modal {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            background: #ff9800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-left: 15px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .modal-body {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 80px;
        }

        .btn-primary {
            background: #2196F3;
            color: white;
        }

        .btn-primary:hover {
            background: #1976D2;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-1px);
        }

        /* Demo Container */
        .demo-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .demo-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .demo-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .demo-btn {
            padding: 15px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
        }

        .demo-btn.success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .demo-btn.error {
            background: linear-gradient(135deg, #f44336, #d32f2f);
        }

        .demo-btn.warning {
            background: linear-gradient(135deg, #ff9800, #f57c00);
        }

        .demo-btn.info {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .toast-container {
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .toast {
                transform: translateY(-100px);
            }

            .toast.show {
                transform: translateY(0);
            }

            .toast.hide {
                transform: translateY(-100px);
            }
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-question"></i>
                </div>
                <div class="modal-title" id="confirmTitle">تأكيد العملية</div>
            </div>
            <div class="modal-body" id="confirmMessage">
                هل أنت متأكد من تنفيذ هذه العملية؟
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="confirmCancel">إلغاء</button>
                <button class="btn btn-primary" id="confirmOk">تأكيد</button>
            </div>
        </div>
    </div>

    <!-- Demo Container -->
    <div class="demo-container">
        <h1 class="demo-title">🎉 نظام التنبيهات الحديث</h1>

        <div class="demo-buttons">
            <button class="demo-btn success"
                onclick="showToast('success', 'نجح العمل', 'تم تحديد الإشعار كمقروء بنجاح')">
                إشعار نجاح
            </button>
            <button class="demo-btn error" onclick="showToast('error', 'فشل العملية', 'حدث خطأ أثناء تحديث الإشعار')">
                إشعار خطأ
            </button>
            <button class="demo-btn warning"
                onclick="showToast('warning', 'تحذير', 'انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.')">
                إشعار تحذير
            </button>
            <button class="demo-btn info" onclick="showToast('info', 'معلومات', 'تم تحديث البيانات بنجاح')">
                إشعار معلومات
            </button>
        </div>

        <button class="demo-btn warning"
            onclick="showConfirm('هل أنت متأكد من تحديد جميع الإشعارات كمقروءة؟', function() { showToast('success', 'تم', 'تم تحديد جميع الإشعارات كمقروءة'); })">
            تجربة نافذة التأكيد
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Modern Alert System
        class ModernAlert {
            constructor() {
                this.toastContainer = document.getElementById('toastContainer');
                this.confirmModal = document.getElementById('confirmModal');
                this.confirmCallback = null;
                this.setupConfirmModal();
            }

            // Show Toast Notification
            showToast(type = 'info', title = '', message = '', duration = 5000) {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;

                const icons = {
                    success: 'fas fa-check',
                    error: 'fas fa-times',
                    warning: 'fas fa-exclamation',
                    info: 'fas fa-info'
                };

                toast.innerHTML = `
                    <div class="toast-header">
                        <div class="toast-icon">
                            <i class="${icons[type]}"></i>
                        </div>
                        <div class="toast-title">${title}</div>
                        <button class="toast-close" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="toast-body">${message}</div>
                    <div class="toast-progress">
                        <div class="toast-progress-bar"></div>
                    </div>
                `;

                this.toastContainer.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                // Progress bar animation
                const progressBar = toast.querySelector('.toast-progress-bar');
                setTimeout(() => {
                    progressBar.style.transitionDuration = `${duration}ms`;
                    progressBar.style.transform = 'translateX(0)';
                }, 200);

                // Auto remove
                setTimeout(() => {
                    this.removeToast(toast);
                }, duration);

                return toast;
            }

            removeToast(toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 400);
            }

            // Show Confirmation Modal
            showConfirm(message, callback, title = 'تأكيد العملية') {
                document.getElementById('confirmTitle').textContent = title;
                document.getElementById('confirmMessage').textContent = message;
                this.confirmCallback = callback;
                this.confirmModal.classList.add('show');
            }

            setupConfirmModal() {
                document.getElementById('confirmOk').onclick = () => {
                    this.confirmModal.classList.remove('show');
                    if (this.confirmCallback) {
                        this.confirmCallback();
                        this.confirmCallback = null;
                    }
                };

                document.getElementById('confirmCancel').onclick = () => {
                    this.confirmModal.classList.remove('show');
                    this.confirmCallback = null;
                };

                this.confirmModal.onclick = (e) => {
                    if (e.target === this.confirmModal) {
                        this.confirmModal.classList.remove('show');
                        this.confirmCallback = null;
                    }
                };
            }
        }

        // Initialize the alert system
        const alert = new ModernAlert();

        // Global functions for easy use
        function showToast(type, title, message, duration = 5000) {
            alert.showToast(type, title, message, duration);
        }

        function showConfirm(message, callback, title = 'تأكيد العملية') {
            alert.showConfirm(message, callback, title);
        }

        // Laravel Integration Script
        // Replace this section in your Laravel blade file
        const laravelScript = `
            @section('script')
                <script type="text/javascript">
                    // تحديد الإشعار كمقروء
                    $('body').delegate('.mark-read', 'click', function() {
                        let notification_id = $(this).data('id');
                        let button = $(this);

                        $.ajax({
                            type: "POST",
                            url: "{{ url('admin/notifications/mark-read') }}/" + notification_id,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            dataType: "json",
                            success: function(data) {
                                if (data.success) {
                                    // تحديث مظهر الصف
                                    button.closest('tr').css('background-color', '');
                                    button.closest('tr').find('.font-weight-bold').removeClass('font-weight-bold')
                                        .addClass('font-weight-normal');

                                    // تحديث شارة الحالة
                                    button.closest('tr').find('.badge-warning').removeClass('badge-warning')
                                        .addClass('badge-success').text('مقروء');

                                    button.closest('tr').find('.btn-info.mark-read')
                                        .removeClass('btn-info mark-read')
                                        .addClass('btn-secondary view-details')
                                        .text('عرض');

                                    // عرض رسالة النجاح الحديثة
                                    showToast('success', 'نجح العمل', 'تم تحديد الإشعار كمقروء بنجاح');
                                } else {
                                    showToast('error', 'فشل العملية', 'حدث خطأ: ' + (data.message || 'حدث خطأ أثناء تحديث الإشعار'));
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('خطأ AJAX:', {
                                    status: status,
                                    error: error,
                                    response: xhr.responseText
                                });

                                // التعامل مع أخطاء التحقق في Laravel
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    showToast('error', 'خطأ في التحقق', Object.values(errors).flat().join(', '));
                                } else if (xhr.status === 419) {
                                    showToast('warning', 'انتهت الجلسة', 'انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.');
                                } else if (xhr.status === 404) {
                                    showToast('error', 'غير موجود', 'لم يتم العثور على الإشعار المطلوب.');
                                } else {
                                    showToast('error', 'خطأ في الخادم', 'حدث خطأ أثناء الاتصال بالخادم: ' + error);
                                }
                            }
                        });
                    });

                    // تحديد جميع الإشعارات كمقروءة
                    $('body').delegate('.mark-all-read', 'click', function() {
                        let button = $(this);

                        showConfirm('هل أنت متأكد من تحديد جميع الإشعارات كمقروءة؟', function() {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('admin.notifications.mark-all-read') }}",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                dataType: "json",
                                success: function(data) {
                                    if (data.success) {
                                        // تحديث جميع الصفوف
                                        $('tr').each(function() {
                                            $(this).css('background-color', '');
                                            $(this).find('.font-weight-bold').removeClass('font-weight-bold').addClass('font-weight-normal');
                                            $(this).find('.badge-warning').removeClass('badge-warning').addClass('badge-success').text('مقروء');
                                        });

                                        // إخفاء جميع أزرار "تحديد كمقروء"
                                        $('.mark-read').hide();

                                        showToast('success', 'تم بنجاح', 'تم تحديد جميع الإشعارات كمقروءة بنجاح (' + data.updated_count + ' إشعار)');
                                    } else {
                                        showToast('error', 'فشل العملية', 'حدث خطأ: ' + (data.message || 'حدث خطأ أثناء تحديث الإشعارات'));
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('خطأ AJAX:', xhr.responseText);
                                    showToast('error', 'خطأ في الخادم', 'حدث خطأ أثناء الاتصال بالخادم: ' + error);
                                }
                            });
                        });
                    });

                    // عرض تفاصيل الإشعار
                    $('body').delegate('.view-details', 'click', function() {
                        let title = $(this).data('title');
                        let body = $(this).data('body');
                        let date = $(this).data('date');

                        $('#modal-title').text(title);
                        $('#modal-body').text(body);
                        $('#modal-date').text(date);

                        $('#notificationModal').modal('show');
                    });

                    // تأكيد الحذف
                    $('body').delegate('.delete-notification', 'click', function(e) {
                        e.preventDefault();
                        let url = $(this).attr('href');

                        showConfirm('هل أنت متأكد من حذف هذا الإشعار؟', function() {
                            window.location.href = url;
                        });
                    });
    </script>
@endsection
`;

// Display the Laravel integration code
console.log("Laravel Integration Code:", laravelScript);
</script>
</body>

</html>
