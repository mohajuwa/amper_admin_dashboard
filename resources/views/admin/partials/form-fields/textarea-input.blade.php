{{-- Textarea Input --}}
@props([
    'name',
    'label',
    'value' => null,
    'placeholder' => '',
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 3,
    'maxlength' => null,
    'help' => null
])

<div class="{{ $colClass }}">
    <div class="form-group">
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
        
        <textarea id="{{ $name }}"
                name="{{ $name }}" 
                class="form-control @error($name) is-invalid @enderror" 
                placeholder="{{ $placeholder }}"
                rows="{{ $rows }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                @if($maxlength) maxlength="{{ $maxlength }}" @endif>{{ $value ?? Request::get($name) }}</textarea>
        
        @if($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endif
        
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>