{{-- resources/views/admin/car_make/partials/mobile-card-header.blade.php --}}
@php
    $nameData = app('App\Services\CarMakeNameService')->getNameData($record->getAttributes()['name']);
@endphp

<div class="row">
    <div class="col-3 text-center">
        @include('admin.car_make.partials.logo-image', ['record' => $record, 'size' => 'small'])
    </div>
    <div class="col-6">
        <h6 class="card-title mb-1">{{ $nameData['ar'] }}</h6>
        <p class="text-muted small mb-1">{{ $nameData['en'] }}</p>
        <p class="text-muted small mb-0">
            <i class="fas fa-hashtag"></i> {{ $record->make_id }}
        </p>
    </div>
    <div class="col-3 text-right">
        @include('admin.car_make.partials.status-badge', [
            'status' => $record->getAttributes()['status'],
            'mobile' => true
        ])
    </div>
</div>