
{{-- `resources/views/admin/partials/components/tables/car-make-name-cell.blade.php`: --}}
@php
    $name = 'غير متوفر';
    $rawName = $record->getAttributes()['name'];
    if (is_string($rawName)) {
        $decoded_name = json_decode($rawName, true);
        if (is_array($decoded_name)) {
            $name = $decoded_name['ar'] ?? 'غير متوفر';
        } else {
            $name = $rawName;
        }
    }
@endphp
{{ $name }}