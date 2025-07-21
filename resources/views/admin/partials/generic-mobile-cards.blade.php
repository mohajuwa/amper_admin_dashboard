{{-- resources/views/admin/partials/generic-mobile-cards.blade.php --}}
<div class="d-lg-none">
    @forelse ($records as $record)
        <div class="card mb-3 mx-3">
            <div class="card-body">
                {{-- Mobile Card Header --}}
                @if (isset($entityConfig['mobileCardHeader']))
                    @include($entityConfig['mobileCardHeader'], [
                        'record' => $record,
                        'entityConfig' => $entityConfig
                    ])
                @else
                    {{-- Default mobile header --}}
                    <div class="row">
                        <div class="col-12">
                            <h6 class="card-title mb-1">
                                @if (isset($entityConfig['titleField']))
                                    {{ data_get($record, $entityConfig['titleField']) }}
                                @else
                                    {{ $record->name ?? $record->title ?? '#' . ($record->id ?? '') }}
                                @endif
                            </h6>
                        </div>
                    </div>
                @endif

                {{-- Mobile Card Body --}}
                @if (isset($entityConfig['mobileCardBody']))
                    @include($entityConfig['mobileCardBody'], [
                        'record' => $record,
                        'entityConfig' => $entityConfig
                    ])
                @else
                    {{-- Default mobile body showing all mobile fields --}}
                    @foreach ($tableConfig['columns'] as $column)
                        @if (isset($column['showInMobile']) && $column['showInMobile'])
                            <div class="row mt-2">
                                <div class="col-6">
                                    <strong>{{ $column['header'] }}:</strong>
                                </div>
                                <div class="col-6">
                                    @if (isset($column['component']))
                                        @include($column['component'], [
                                            'record' => $record,
                                            'column' => $column,
                                            'entityConfig' => $entityConfig,
                                            'mobile' => true
                                        ])
                                    @elseif (isset($column['field']))
                                        @php
                                            $value = data_get($record, $column['field']);
                                            if (isset($column['processor']) && $column['processor']) {
                                                $processorClass = $column['processor'];
                                                $value = app($processorClass)->process($value, $record);
                                            }
                                        @endphp
                                        {{ $value }}
                                    @elseif (isset($column['callback']))
                                        {!! $column['callback']($record, true) !!}
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                {{-- Action Buttons --}}
                @foreach ($tableConfig['columns'] as $column)
                    @if ($column['type'] === 'actions')
                        <div class="mt-3">
                            @include($column['component'], [
                                'record' => $record,
                                'column' => $column,
                                'entityConfig' => $entityConfig
                            ])
                        </div>
                        @break
                    @endif
                @endforeach
            </div>
        </div>
    @empty
        @include('admin.partials.no-data', [
            'icon' => $entityConfig['icon'] ?? 'fas fa-list',
            'message' => $entityConfig['emptyMessage'] ?? 'لا توجد بيانات متاحة حالياً.',
            'wrapperClass' => 'text-center py-5',
        ])
    @endforelse
</div>