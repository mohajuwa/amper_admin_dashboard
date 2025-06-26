{{-- Generic Badge Component --}}
@props([
    'value',
    'type' => 'info',
    'icon' => null,
    'mappings' => [],
    'class' => '',
    'size' => 'normal'
])

@php
    $badgeClass = 'badge';
    $badgeText = $value;
    $badgeIcon = $icon;
    
    // Apply mappings if provided
    if (!empty($mappings) && isset($mappings[$value])) {
        $mapping = $mappings[$value];
        $type = $mapping['type'] ?? $type;
        $badgeText = $mapping['text'] ?? $badgeText;
        $badgeIcon = $mapping['icon'] ?? $badgeIcon;
    }
    
    // Size classes
    $sizeClass = match($size) {
        'sm' => 'badge-sm',
        'lg' => 'badge-lg px-3 py-2',
        default => 'px-3 py-2'
    };
    
    // Type classes
    $typeClass = match($type) {
        'success' => 'badge-success',
        'danger' => 'badge-danger',
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
        'light' => 'badge-light',
        'dark' => 'badge-dark',
        default => 'badge-info'
    };
    
    $finalClass = "{$badgeClass} {$typeClass} {$sizeClass} {$class}";
@endphp

<span class="{{ $finalClass }}">
    @if($badgeIcon)
        <i class="{{ $badgeIcon }} mr-1"></i>
    @endif
    {{ $badgeText }}
</span>