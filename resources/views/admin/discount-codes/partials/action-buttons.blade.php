{{-- resources/views/admin/discount-codes/partials/action-buttons.blade.php --}}
@include('admin.partials.form-fields.table-action-buttons', [
    'record' => $record,
    'idField' => 'coupon_id',
    'statusField' => 'coupon_status',
    'editRoute' => route('admin.discount-codes.edit', $record->coupon_id),
])
