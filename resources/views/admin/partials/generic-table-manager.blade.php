{{-- resources/views/admin/partials/generic-table-manager.blade.php --}}

<script type="text/javascript">
    class GenericTableManager {
        constructor(options = {}) {
            this.options = {
                deleteButtonClass: '.btnDelete',
                tableContainerSelector: '#table-container',
                confirmMessage: 'هل أنت متأكد من الحذف؟',
                confirmRestoreMessage: 'هل أنت متأكد من الاستعادة؟',
                confirmTitle: 'تأكيد العملية',
                loadingText: '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...',
                defaultButtonText: '<i class="fas fa-trash"></i>',
                successMessage: 'تمت العملية بنجاح',
                errorPrefix: 'حدث خطأ: ',
                connectionErrorPrefix: 'حدث خطأ أثناء الاتصال بالخادم: ',
                deleteFailedMessage: 'فشل أثناء الحذف',
                baseDeleteUrl: null,
                baseRestoreUrl: null,
                csrfToken: "{{ csrf_token() }}",
                ...options
            };

            this.validateOptions();
            this.init();
        }

        validateOptions() {
            if (!this.options.baseDeleteUrl || !this.options.baseRestoreUrl) {
                throw new Error('baseDeleteUrl و baseRestoreUrl مطلوبان');
            }
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            $('body').on('click', this.options.deleteButtonClass, (e) => {
                const button = $(e.currentTarget);
                const status = Number(button.data('status'));

                if (status === 2) {
                    this.handleRestore(button);
                } else {
                    this.handleDelete(button);
                }
            });
        }

        handleDelete(button) {
            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(
                    this.options.confirmMessage,
                    () => this.sendRequest(button, 'delete'),
                    this.options.confirmTitle
                );
            } else {
                if (confirm(this.options.confirmMessage)) {
                    this.sendRequest(button, 'delete');
                }
            }
        }

        handleRestore(button) {
            if (typeof showCustomConfirm === 'function') {
                showCustomConfirm(
                    this.options.confirmRestoreMessage,
                    () => this.sendRequest(button, 'restore'),
                    this.options.confirmTitle
                );
            } else {
                if (confirm(this.options.confirmRestoreMessage)) {
                    this.sendRequest(button, 'restore');
                }
            }
        }

        sendRequest(button, action) {
            const recordId = button.data('id');
            const baseUrl = action === 'restore' ?
                this.options.baseRestoreUrl :
                this.options.baseDeleteUrl;

            const url = `${baseUrl}/${recordId}`;

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    _token: this.options.csrfToken
                },
                dataType: "json",
                beforeSend: () => {
                    this.setButtonLoading(button, true);
                },
                success: (data) => {
                    this.handleDeleteSuccess(data);
                },
                error: (xhr, status, error) => {
                    this.handleDeleteError(error);
                },
                complete: () => {
                    this.setButtonLoading(button, false);
                }
            });
        }

        setButtonLoading(button, isLoading) {
            if (isLoading) {
                button.prop('disabled', true)
                    .html(this.options.loadingText);
            } else {
                button.prop('disabled', false)
                    .html(this.options.defaultButtonText);
            }
        }

        handleDeleteSuccess(data) {
            if (data.success) {
                this.refreshTable();
                this.showMessage(this.options.successMessage, 'success');
            } else {
                const errorMsg = this.options.errorPrefix + (data.message || this.options.deleteFailedMessage);
                this.showMessage(errorMsg, 'error');
            }
        }

        handleDeleteError(error) {
            const errorMsg = this.options.connectionErrorPrefix + error;
            this.showMessage(errorMsg, 'error');
        }

        refreshTable() {
            $(this.options.tableContainerSelector).load(location.href +
                ` ${this.options.tableContainerSelector} > *`);
        }

        showMessage(message, type) {
            if (type === 'success' && typeof showSuccess === 'function') {
                showSuccess(message);
            } else if (type === 'error' && typeof showError === 'function') {
                showError(message);
            } else {
                alert(message);
            }
        }
    }

    window.GenericTableManager = GenericTableManager;
</script>
