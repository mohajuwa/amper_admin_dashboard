{{-- Status Badge Component --}}
@props([
    'status',
    'field' => 'status',
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
    $statusValue = is_object($status) ? $status->{$field} : $status;
@endphp

@include('admin.partials.components.badges.generic-badge', [
    'value' => $statusValue,
    'mappings' => $statusMappings,
    'size' => $size
])