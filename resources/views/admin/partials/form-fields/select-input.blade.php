{{-- Enhanced Select Input --}}
@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'help' => null,
    'placeholder' => null,
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
        
        <select id="{{ $name }}"
                name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
                class="form-control @error($name) is-invalid @enderror"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($multiple) multiple @endif>
            
            @if($placeholder && !$multiple)
                <option value="">{{ $placeholder }}</option>
            @endif
            
            @foreach($options as $optionValue => $optionText)
                @if(is_array($optionText))
                    <optgroup label="{{ $optionValue }}">
                        @foreach($optionText as $subValue => $subText)
                            <option value="{{ $subValue }}" 
                                    {{ (($value ?? Request::get($name)) == $subValue) ? 'selected' : '' }}>
                                {{ $subText }}
                            </option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $optionValue }}" 
                            {{ (($value ?? Request::get($name)) == $optionValue) ? 'selected' : '' }}>
                        {{ $optionText }}
                    </option>
                @endif
            @endforeach
        </select>
        
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