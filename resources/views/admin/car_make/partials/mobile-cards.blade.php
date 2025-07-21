{{-- resources/views/admin/car_make/partials/mobile-cards.blade.php --}}
<div class="d-lg-none">
    @forelse ($records as $record)
        @php
            $nameData = app('App\Services\CarMakeNameService')->getNameData($record->getAttributes()['name']);
            $popularityData = app('App\Services\PopularityBadgeService')->getBadgeData($record->getAttributes()['popularity'] ?? 0);
        @endphp
        <div class="card mb-3 mx-3">
            <div class="card-body">
                @include('admin.car_make.partials.mobile-card-header', [
                    'record' => $record,
                    'nameData' => $nameData,
                ])

                @include('admin.car_make.partials.mobile-card-body', [
                    'popularityData' => $popularityData,
                ])

                @include('admin.car_make.partials.action-buttons', ['record' => $record])
            </div>
        </div>
    @empty
        @include('admin.partials.no-data', [
            'icon' => 'fas fa-car',
            'message' => 'لا توجد ماركات سيارات متاحة حالياً.',
            'wrapperClass' => 'text-center py-5',
        ])
    @endforelse
</div>