{{-- Loading Spinner Widget --}}
@props([
    'size' => 'md',
    'color' => 'primary',
    'text' => 'جاري التحميل...',
    'showText' => true,
    'centered' => true,
    'overlay' => false
])

@php
    $sizeClass = match($size) {
        'sm' => 'spinner-border-sm',
        'lg' => 'spinner-border-lg',
        default => ''
    };
    
    $colorClass = "text-{$color}";
    $wrapperClass = $centered ? 'text-center' : '';
    
    if ($overlay) {
        $wrapperClass .= ' loading-overlay position-fixed w-100 h-100 d-flex align-items-center justify-content-center';
    }
@endphp

<div class="{{ $wrapperClass }}" {{ $overlay ? 'style=background-color:rgba(255,255,255,0.8);z-index:9999;top:0;left:0;' : '' }}>
    <div class="spinner-border {{ $sizeClass }} {{ $colorClass }}" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if($showText)
        <div class="mt-2">
            <span class="text-muted">{{ $text }}</span>
        </div>
    @endif
</div>