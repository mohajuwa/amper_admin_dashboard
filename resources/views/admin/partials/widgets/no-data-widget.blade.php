{{-- No Data Widget --}}
@props([
    'message' => 'لا توجد بيانات متاحة حالياً.',
    'icon' => 'fas fa-info-circle',
    'colspan' => null,
    'wrapperClass' => 'text-center py-4',
    'iconClass' => 'text-muted mb-2',
    'textClass' => 'text-muted',
    'actionButton' => null
])

@if($colspan)
    <tr>
        <td colspan="{{ $colspan }}">
@endif

<div class="{{ $wrapperClass }}">
    <div class="{{ $iconClass }}">
        <i class="{{ $icon }}" style="font-size: 3rem;"></i>
    </div>
    <div class="{{ $textClass }}">
        <h5>{{ $message }}</h5>
    </div>
    
    @if($actionButton)
        <div class="mt-3">
            <a href="{{ $actionButton['url'] }}" class="btn btn-{{ $actionButton['color'] ?? 'primary' }} btn-sm">
                @if(isset($actionButton['icon']))
                    <i class="{{ $actionButton['icon'] }}"></i>
                @endif
                {{ $actionButton['text'] }}
            </a>
        </div>
    @endif
</div>

@if($colspan)
        </td>
    </tr>
@endif