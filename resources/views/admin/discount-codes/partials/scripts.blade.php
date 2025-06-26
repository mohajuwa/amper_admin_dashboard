{{-- resources/views/admin/discount-codes/partials/scripts.blade.php --}}


@include('admin.partials.scripts.generic-table-manager')
<script type="text/javascript">
    $(document).ready(() => {
        const discountCodeManager = new GenericTableManager({
            baseDeleteUrl: "{{ url('admin/discount-codes/delete') }}",
            baseRestoreUrl: "{{ url('admin/discount-codes/restore') }}",
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
            deleteFailedMessage: 'فشل أثناء الحذف'
        });
    });
</script>
