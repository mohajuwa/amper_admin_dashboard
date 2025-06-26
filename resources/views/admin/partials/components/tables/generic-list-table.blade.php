{{-- Generic List Table Component --}}
@props([
    'records',
    'columns' => [],
    'actions' => [],
    'tableId' => 'generic-table',
    'tableClass' => 'table table-striped table-bordered mb-0',
    'headerClass' => 'text-center bg-light',
    'bodyClass' => 'text-center',
    'noDataMessage' => 'لا توجد بيانات متاحة حالياً.',
    'noDataIcon' => 'fas fa-info-circle',
    'emptyColspan' => null,
    'showMobileCards' => true
])

<div class="card card-info">
    @if(isset($title))
        <div class="card-header">
            <h3 class="card-title">{{ $title }} (المجموع: {{ $records->total() }})</h3>
        </div>
    @endif
    
    <div id="{{ $tableId }}-container">
        <div class="card-body p-0">
            {{-- Desktop Table --}}
            <div class="d-none d-lg-block">
                <div class="table-responsive">
                    <table class="{{ $tableClass }}" id="{{ $tableId }}">
                        <thead class="{{ $headerClass }}">
                            <tr>
                                @foreach($columns as $column)
                                    <th>{{ $column['label'] ?? $column }}</th>
                                @endforeach
                                @if(!empty($actions))
                                    <th>الإجراء</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="{{ $bodyClass }}">
                            @forelse ($records as $record)
                                <tr>
                                    @foreach($columns as $key => $column)
                                        <td>
                                            @if(isset($column['component']))
                                                @include($column['component'], ['record' => $record, 'value' => $record->{$column['field'] ?? $key}])
                                            @elseif(isset($column['field']))
                                                {{ $record->{$column['field']} }}
                                            @else
                                                {{ $record->{$key} }}
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    @if(!empty($actions))
                                        <td>
                                            @include('admin.partials.components.buttons.action-buttons', [
                                                'record' => $record,
                                                'actions' => $actions
                                            ])
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                @include('admin.partials.widgets.no-data-widget', [
                                    'colspan' => $emptyColspan ?? (count($columns) + (!empty($actions) ? 1 : 0)),
                                    'icon' => $noDataIcon,
                                    'message' => $noDataMessage
                                ])
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Cards --}}
            @if($showMobileCards)
                @include('admin.partials.components.cards.mobile-record-card', [
                    'records' => $records,
                    'columns' => $columns,
                    'actions' => $actions,
                    'noDataIcon' => $noDataIcon,
                    'noDataMessage' => $noDataMessage
                ])
            @endif

            {{-- Pagination --}}
            @include('admin.partials.widgets.pagination-widget', ['records' => $records])
        </div>
    </div>
</div>