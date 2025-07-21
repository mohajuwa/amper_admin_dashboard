{{-- resources/views/admin/car_make/partials/scripts.blade.php --}}

@include('admin.partials.scripts.generic-table-manager')
<script type="text/javascript">
    $(document).ready(() => {
        const carMakeManager = new GenericTableManager({
            baseDeleteUrl: "{{ url('admin/car_makes/delete') }}",
            baseRestoreUrl: "{{ url('admin/car_makes/restore') }}",
            deleteButtonClass: '.btnDelete',
            tableContainerSelector: '#table-container',
            confirmMessage: 'هل أنت متأكد من حذف هذه الماركة؟',
            confirmRestoreMessage: 'هل أنت متأكد من استعادة هذه الماركة؟',
            confirmTitle: 'تأكيد العملية',
            loadingText: '<i class="fas fa-spinner fa-spin"></i>  ...',
            defaultButtonText: '<i class="fas fa-trash"></i>',
            successMessage: 'تم حذف الماركة بنجاح',
            errorPrefix: 'حدث خطأ: ',
            connectionErrorPrefix: 'حدث خطأ أثناء الاتصال بالخادم: ',
            deleteFailedMessage: 'فشل أثناء حذف الماركة'
        });
    });
</script>