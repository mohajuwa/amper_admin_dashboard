{{-- resources/views/admin/partials/generic-desktop-table.blade.php --}}
<div class="d-none d-lg-block">
    <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
            <thead class="text-center bg-light">
                <tr>
                    @foreach ($tableConfig['columns'] as $column)
                        <th>{{ $column['header'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($records as $record)
                    <tr>
                        @foreach ($tableConfig['columns'] as $column)
                            <td>
                                @if (isset($column['component']))
                                    @include($column['component'], [
                                        'record' => $record,
                                        'column' => $column,
                                        'entityConfig' => $entityConfig
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
                                    {!! $column['callback']($record) !!}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    @include('admin.partials.no-data', [
                        'colspan' => count($tableConfig['columns']),
                        'icon' => $entityConfig['icon'] ?? 'fas fa-list',
                        'message' => $entityConfig['emptyMessage'] ?? 'لا توجد بيانات متاحة حالياً.',
                    ])
                @endforelse
            </tbody>
        </table>
    </div>
</div>