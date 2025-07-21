{{-- `resources/views/admin/partials/components/tables/car-make-name-en-cell.blade.php`: --}}
@php
    $make_name_en = 'غير متوفر';
    $rawName = $record->getAttributes()['name'];
    
    if (is_string($rawName)) {
        $decoded_name = json_decode($rawName, true);
        if (is_array($decoded_name)) {
            $make_name_en = $decoded_name['en'] ?? 'غير متوفر';
        }
    }
@endphp
{{ $make_name_en }}