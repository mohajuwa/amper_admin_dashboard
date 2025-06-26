{{-- Checkbox Input --}}
@props([
    'name',
    'label',
    'value' => 1,
    'checked' => false,
    'colClass' => 'col-12',
    'disabled' => false,
    'help' => null,
    'inline' => false
])

<div class="{{ $colClass }}">
    <div class="form-group">
        <div class="form-check {{ $inline ? 'form-check-inline' : '' }}">
            <input type="checkbox" 
                id="{{ $name }}"
                name="{{ $name }}" 
                value="{{ $value }}"
                class="form-check-input @error($name) is-invalid @enderror"
                @if($checked || old($name) || Request::get($name)) checked @endif
                @if($disabled) disabled @endif>
            
            <label class="form-check-label" for="{{ $name }}">
                {{ $label }}
            </label>
        </div>
        
        @if($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endif
        
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>