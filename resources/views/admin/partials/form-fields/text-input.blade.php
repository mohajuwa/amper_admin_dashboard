{{-- Enhanced Text Input --}}
@props([
    'name',
    'label',
    'value' => null,
    'placeholder' => '',
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'maxlength' => null,
    'pattern' => null,
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
        
        <input type="text" 
               id="{{ $name }}"
               name="{{ $name }}" 
               value="{{ $value ?? Request::get($name) }}"
               class="form-control @error($name) is-invalid @enderror" 
               placeholder="{{ $placeholder }}"
               @if($required) required @endif
               @if($disabled) disabled @endif
               @if($readonly) readonly @endif
               @if($maxlength) maxlength="{{ $maxlength }}" @endif
               @if($pattern) pattern="{{ $pattern }}" @endif>
        
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