{{-- Status Badge Component --}}
@props([
    'status' => null,
    'field' => null,
    'mappings' => null,
    'size' => 'normal'
])

@php
    // Default status mappings
    $defaultMappings = [
        1 => ['type' => 'success', 'text' => 'نشط', 'icon' => 'fas fa-check-circle'],
        0 => ['type' => 'secondary', 'text' => 'غير نشط', 'icon' => 'fas fa-pause-circle'],
        2 => ['type' => 'danger', 'text' => 'محذوف', 'icon' => 'fas fa-times-circle'],
        'active' => ['type' => 'success', 'text' => 'نشط', 'icon' => 'fas fa-check-circle'],
        'inactive' => ['type' => 'secondary', 'text' => 'غير نشط', 'icon' => 'fas fa-pause-circle'],
        'deleted' => ['type' => 'danger', 'text' => 'محذوف', 'icon' => 'fas fa-times-circle'],
        'expired' => ['type' => 'warning', 'text' => 'منتهي الصلاحية', 'icon' => 'fas fa-clock']
    ];
    
    $statusMappings = $mappings ?? $defaultMappings;
    
    // Auto-detect status field if field is not specified and status is an object
    if (is_object($status) && is_null($field)) {
        $possibleFields = ['status', 'model_status', 'discount_status', 'order_status', 'payment_status', 'delivery_status'];
        foreach ($possibleFields as $possibleField) {
            if (property_exists($status, $possibleField)) {
                $field = $possibleField;
                break;
            }
        }
        // Default to 'status' if no field found
        $field = $field ?? 'status';
    }
    
    $statusValue = is_object($status) ? ($status->{$field} ?? null) : $status;
@endphp

@if(!is_null($statusValue))

@include('admin.partials.components.badges.generic-badge', [
    'value' => $statusValue,
    'mappings' => $statusMappings,
    'size' => $size
])
@endif