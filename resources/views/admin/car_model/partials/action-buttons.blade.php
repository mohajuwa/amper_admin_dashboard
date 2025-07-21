{{-- resources/views/admin/car_model/partials/action-buttons.blade.php --}}
@include('admin.partials.form-fields.table-action-buttons', [
    'record' => $record,
    'idField' => 'model_id',
    'statusField' => 'status',
    'editRoute' => route('admin.car_model.edit', $record->model_id),
])
