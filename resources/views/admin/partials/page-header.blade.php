{{-- resources/views/admin/partials/page-header.blade.php --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="m-0">{{ $title }}</h1>
            @if(isset($createRoute))
                <a href="{{ $createRoute }}" class="btn btn-sm btn-primary">
                    {{ $createButtonText ?? 'إضافة جديد' }}
                </a>
            @endif
        </div>
    </div>
</section>