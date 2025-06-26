{{-- Responsive Data Table with Auto-Detection --}}
@props([
    'records',
    'config' => [],
    'tableId' => 'responsive-table'
])

@php
    // Auto-detect columns if not provided
    if (empty($config['columns']) && $records->count() > 0) {
        $firstRecord = $records->first();
        $config['columns'] = collect($firstRecord->getAttributes())->keys()->take(6)->mapWithKeys(function($key) {
            return [$key => ['label' => ucfirst(str_replace('_', ' ', $key)), 'field' => $key]];
        })->toArray();
    }
@endphp

@include('admin.partials.components.tables.generic-list-table', [
    'records' => $records,
    'columns' => $config['columns'] ?? [],
    'actions' => $config['actions'] ?? [],
    'tableId' => $tableId,
    'title' => $config['title'] ?? null,
    'noDataMessage' => $config['noDataMessage'] ?? 'لا توجد بيانات متاحة حالياً.',
    'noDataIcon' => $config['noDataIcon'] ?? 'fas fa-info-circle'
])