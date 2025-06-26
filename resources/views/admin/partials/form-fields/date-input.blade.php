{{-- Date Input --}}
@props([
    'name',
    'label',
    'value' => null,
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'min' => null,
    'max' => null,
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
        
        <input type="date" 
               id="{{ $name }}"
               name="{{ $name }}" 
               value="{{ $value ?? Request::get($name) }}"
               class="form-control @error($name) is-invalid @enderror"
               @if($required) required @endif
               @if($disabled) disabled @endif
               @if($min) min="{{ $min }}" @endif
               @if($max) max="{{ $max }}" @endif>
                                
                                @if($help)
                                    <small class="form-text text-muted">{{ $help }}</small>
                                @endif
                                
                                @error($name)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>