{{-- resources/views/admin/partials/status-badge.blade.php --}}
@php
    $mobile = $mobile ?? false;
    $blockClass = $mobile ? 'd-block mb-1' : '';
    $statusValue = $column['statusValue'] ?? data_get($record, $column['statusField'] ?? 'status');
    $statusConfig = $column['statusConfig'] ?? [
        1 => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'نشط'],
        0 => ['class' => 'secondary', 'icon' => 'pause-circle', 'text' => 'غير نشط'],
        2 => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'محذوف'],
    ];
    $defaultConfig = ['class' => 'warning', 'icon' => 'question-circle', 'text' => 'غير معروف'];
    $config = $statusConfig[$statusValue] ?? $defaultConfig;
@endphp

<span class="badge badge-{{ $config['class'] }} px-3 py-2 {{ $blockClass }}">
    <i class="fas fa-{{ $config['icon'] }} {{ $mobile ? '' : 'mr-1' }}"></i>{{ $config['text'] }}
</span>