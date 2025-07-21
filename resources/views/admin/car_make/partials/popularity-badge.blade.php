{{-- resources/views/admin/car_make/partials/popularity-badge.blade.php --}}
@php
    $mobile = $mobile ?? false;
    $badgeClass = $mobile ? 'popularity-badge-mobile' : 'popularity-badge';
@endphp

<div class="popularity-wrapper">
    <span class="badge {{ $popularityData['badgeClass'] }} {{ $badgeClass }}">
        @if ($mobile)
            <span class="popularity-icon">{{ $popularityData['icon'] }}</span>
            <span class="popularity-score">{{ $popularityData['popularity'] }}</span>
            <span class="popularity-label">{{ $popularityData['label'] }}</span>
        @else
            <div class="popularity-content">
                <span class="popularity-icon">{{ $popularityData['icon'] }}</span>
                <span class="popularity-score">{{ $popularityData['popularity'] }}</span>
                <div class="popularity-progress">
                    <div class="progress-bar" style="width: {{ $popularityData['popularity'] }}%"></div>
                </div>
                <small class="popularity-label">{{ $popularityData['label'] }}</small>
            </div>
        @endif
    </span>
</div>