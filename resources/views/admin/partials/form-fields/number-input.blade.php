{{-- Number Input --}}
@props([
    'name',
    'label',
    'value' => null,
    'placeholder' => '',
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'min' => null,
    'max' => null,
    'step' => null,
    'help' => null,
    'icon' => null
])

<div class="{{ $colClass }}">
    <div class="form-group">
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
        
        @if($icon)
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="{{ $icon }}"></i></span>
                </div>
        @endif
        
        <input type="number" 
            id="{{ $name }}"
            name="{{ $name }}" 
            value="{{ $value ?? Request::get($name) }}"
            class="form-control @error($name) is-invalid @enderror" 
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            @if($step) step="{{ $step }}" @endif>
        
        @if($icon)
            </div>
        @endif
        
        @if($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endif
        
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>