{{-- resources/views/admin/partials/generic-data-table.blade.php --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $pageConfig['title'] }}</h3>
    </div>
    <div class="card-body">
        @if ($records->count() > 0)
            {{-- Desktop Table --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            @foreach ($tableConfig['columns'] as $column)
                                <th>{{ $column['header'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                @foreach ($tableConfig['columns'] as $column)
                                    <td>
                                        @if ($column['type'] === 'text')
                                            @php
                                                $value = $record->{$column['field']};
                                                if (is_array($value)) {
                                                    $value = $value['ar'] ?? ($value['en'] ?? implode(', ', $value));
                                                }
                                            @endphp
                                            {{ $value }}
                                        @elseif($column['type'] === 'callback')
                                            {!! $column['callback']($record) !!}
                                        @elseif($column['type'] === 'component')
                                            @if (isset($column['data']))
                                                @include($column['component'], [
                                                    'popularityData' => $column['data']($record),
                                                    'record' => $record
                                                ])
                                            @else
                                                @include($column['component'], ['record' => $record])
                                            @endif
                                        @elseif($column['type'] === 'actions')
                                            @include($column['component'], ['record' => $record])
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-block d-md-none">
                @foreach ($records as $record)
                    <div class="card mb-3">
                        @if (isset($entityConfig['mobileCardHeader']))
                            @include($entityConfig['mobileCardHeader'], ['record' => $record])
                        @endif

                        <div class="card-body">
                            @if (isset($entityConfig['mobileCardBody']))
                                @include($entityConfig['mobileCardBody'], ['record' => $record])
                            @else
                                @foreach ($tableConfig['columns'] as $column)
                                    @if (isset($column['showInMobile']) && $column['showInMobile'])
                                        <div class="mb-2">
                                            <strong>{{ $column['header'] }}:</strong>
                                            @if ($column['type'] === 'text')
                                                @php
                                                    $value = $record->{$column['field']};
                                                    if (is_array($value)) {
                                                        $value = $value['ar'] ?? ($value['en'] ?? implode(', ', $value));
                                                    }
                                                @endphp
                                                {{ $value }}
                                            @elseif ($column['type'] === 'component')
                                                @if (isset($column['data']))
                                                    @include($column['component'], [
                                                        'popularityData' => $column['data']($record),
                                                        'record' => $record,
                                                        'mobile' => true,
                                                    ])
                                                @else
                                                    @include($column['component'], [
                                                        'record' => $record,
                                                        'mobile' => true,
                                                    ])
                                                @endif
                                            @elseif($column['type'] === 'callback')
                                                {!! $column['callback']($record) !!}
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if(method_exists($records, 'links'))
                <div class="d-flex justify-content-center">
                    {{ $records->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="{{ $entityConfig['icon'] }} fa-3x text-muted mb-3"></i>
                <p class="text-muted">{{ $entityConfig['emptyMessage'] }}</p>
            </div>
        @endif
    </div>
</div>