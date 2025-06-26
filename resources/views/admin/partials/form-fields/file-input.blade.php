{{-- File Input --}}
@props([
    'name',
    'label',
    'colClass' => 'col-12',
    'required' => false,
    'disabled' => false,
    'accept' => null,
    'multiple' => false,
    'help' => null,
    'preview' => false,
    'currentFile' => null
])

<div class="{{ $colClass }}">
    <div class="form-group">
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
        
        <input type="file" 
            id="{{ $name }}"
            name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
            class="form-control-file @error($name) is-invalid @enderror"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($accept) accept="{{ $accept }}" @endif
            @if($multiple) multiple @endif
            @if($preview) onchange="previewFile(this)" @endif>
        
        @if($currentFile)
            <div class="mt-2">
                <small class="text-muted">الملف الحالي: {{ $currentFile }}</small>
            </div>
        @endif
        
        @if($preview)
            <div id="{{ $name }}_preview" class="mt-2" style="display: none;">
                <img id="{{ $name }}_preview_img" src="#" alt="معاينة" class="img-thumbnail" style="max-width: 200px;">
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