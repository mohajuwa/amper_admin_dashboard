{{-- resources/views/admin/car_make/partials/desktop-table.blade.php --}}
<div class="d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
            <thead class="text-center bg-light">
                <tr>
                    <th>#</th>
                    <th>الصورة</th>
                    <th>اسم الماركة</th>
                    <th>اسم الماركة إنجليزي</th>
                    <th>الحالة</th>
                    <th>مستوى الشهرة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($records as $record)
                    @php
                        $nameData = app('App\Services\CarMakeNameService')->getNameData(
                            $record->getAttributes()['name'],
                        );
                        $popularityData = app('App\Services\PopularityBadgeService')->getBadgeData(
                            $record->getAttributes()['popularity'] ?? 0,
                        );
                    @endphp
                    <tr>
                        <td>{{ $record->make_id }}</td>
                        <td>
                            @include('admin.car_make.partials.logo-image', ['record' => $record])
                        </td>
                        <td>{{ $nameData['ar'] }}</td>
                        <td>{{ $nameData['en'] }}</td>
                        <td>
                            @include('admin.car_make.partials.status-badge', [
                                'status' => $record->getAttributes()['status'],
                            ])
                        </td>
                        <td>
                            @include('admin.car_make.partials.popularity-badge', [
                                'popularityData' => $popularityData,
                            ])
                        </td>
                        <td>
                            @include('admin.car_make.partials.action-buttons', ['record' => $record])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.no-data', [
                        'colspan' => 7,
                        'icon' => 'fas fa-car',
                        'message' => 'لا توجد ماركات سيارات متاحة حالياً.',
                    ])
                @endforelse
            </tbody>
        </table>
    </div>
</div>
