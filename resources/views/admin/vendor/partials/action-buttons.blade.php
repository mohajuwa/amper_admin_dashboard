{{-- resources/views/admin/vendor/partials/action-buttons.blade.php --}}
@include('admin.partials.form-fields.table-action-buttons', [
    'record' => $record,
    'idField' => 'vendor_id',
    'statusField' => 'status',
    'editRoute' => route('admin.vendor.edit', $record->vendor_id),
])
