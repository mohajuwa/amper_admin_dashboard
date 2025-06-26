{{-- Action Buttons Component --}}
@props([
    'record',
    'actions' => [],
    'isMobile' => false,
    'idField' => 'id',
    'statusField' => 'status'
])

@php
    $recordId = $record->{$idField} ?? null;
    $recordStatus = $record->{$statusField} ?? null;
    $isDeleted = (int) $recordStatus === 2;
    
    // Default actions if none provided
    if (empty($actions)) {
        $actions = [
            'edit' => [
                'route' => 'edit',
                'icon' => 'fas fa-edit',
                'text' => 'تعديل',
                'class' => 'btn-edit',
                'color' => 'primary'
            ],
            'delete' => [
                'action' => 'delete',
                'icon' => $isDeleted ? 'fas fa-undo' : 'fas fa-trash',
                'text' => $isDeleted ? 'استعادة' : 'حذف',
                'class' => 'btnDelete ' . ($isDeleted ? 'btn-primary' : 'btn-delete'),
                'color' => $isDeleted ? 'primary' : 'danger',
                'data' => ['id' => $recordId, 'status' => $recordStatus]
            ]
        ];
    }
@endphp

@if($isMobile)
    {{-- Mobile Layout --}}
    @foreach($actions as $action)
        @if(isset($action['route']))
            <a href="{{ $action['route'] }}" 
               class="btn btn-{{ $action['color'] ?? 'primary' }} btn-sm mx-1" 
               style="min-width: 70px;">
                <i class="{{ $action['icon'] }}"></i> {{ $action['text'] }}
            </a>
        @else
            <a href="javascript:void(0)" 
               class="btn btn-{{ $action['color'] ?? 'danger' }} btn-sm mx-1 {{ $action['class'] ?? '' }}"
               @if(isset($action['data']))
                   @foreach($action['data'] as $key => $value)
                       data-{{ $key }}="{{ $value }}"
                   @endforeach
               @endif
               style="min-width: 70px;">
                <i class="{{ $action['icon'] }}"></i> {{ $action['text'] }}
            </a>
        @endif
    @endforeach
@else
    {{-- Desktop Layout --}}
    <div class="action-buttons d-flex align-items-center">
        @foreach($actions as $action)
            @if(isset($action['route']))
                <a href="{{ $action['route'] }}" 
                   class="btn-action {{ $action['class'] ?? 'btn-edit' }}" 
                   title="{{ $action['text'] }}">
                    <i class="{{ $action['icon'] }}"></i>
                </a>
            @else
                <a href="javascript:void(0)" 
                   class="btn-action {{ $action['class'] ?? 'btn-delete' }}"
                   @if(isset($action['data']))
                       @foreach($action['data'] as $key => $value)
                           data-{{ $key }}="{{ $value }}"
                       @endforeach
                   @endif
                   title="{{ $action['text'] }}">
                    <i class="{{ $action['icon'] }}"></i>
                </a>
            @endif
        @endforeach
    </div>
@endif