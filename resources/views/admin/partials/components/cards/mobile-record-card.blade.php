{{-- Mobile Record Cards --}}
@props([
    'records',
    'columns' => [],
    'actions' => [],
    'noDataMessage' => 'لا توجد بيانات متاحة حالياً.',
    'noDataIcon' => 'fas fa-info-circle',
    'cardClass' => 'card mb-3 mx-3'
])

<div class="d-lg-none">
    @forelse ($records as $record)
        <div class="{{ $cardClass }}">
            <div class="card-body">
                {{-- Card Header with Primary Info --}}
                <div class="row mb-2">
                    @foreach(array_slice($columns, 0, 3) as $key => $column)
                        <div class="col-{{ 12 / min(3, count($columns)) }}">
                            @if(isset($column['component']))
                                @include($column['component'], ['record' => $record, 'value' => $record->{$column['field'] ?? $key}])
                            @else
                                <small class="text-muted">{{ $column['label'] ?? $key }}</small>
                                <div class="font-weight-bold">
                                    {{ $record->{$column['field'] ?? $key} }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Secondary Info --}}
                @if(count($columns) > 3)
                    <div class="row mb-2">
                        @foreach(array_slice($columns, 3) as $key => $column)
                            <div class="col-6">
                                <small class="text-muted">{{ $column['label'] ?? $key }}:</small>
                                @if(isset($column['component']))
                                    @include($column['component'], ['record' => $record, 'value' => $record->{$column['field'] ?? $key}])
                                @else
                                    <span class="ml-1">{{ $record->{$column['field'] ?? $key} }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                @if(!empty($actions))
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            @include('admin.partials.components.buttons.action-buttons', [
                                'record' => $record,
                                'actions' => $actions,
                                'isMobile' => true
                            ])
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        @include('admin.partials.widgets.no-data-widget', [
            'icon' => $noDataIcon,
            'message' => $noDataMessage,
            'wrapperClass' => 'text-center py-5'
        ])
    @endforelse
</div>