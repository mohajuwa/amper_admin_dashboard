{{-- resources/views/admin/car_make/partials/action-buttons.blade.php --}}
@include('admin.partials.form-fields.table-action-buttons', [
    'record' => $record,
    'idField' => 'make_id',
    'statusField' => 'status',
    'editRoute' => route('admin.car_make.edit', $record->make_id),
])
