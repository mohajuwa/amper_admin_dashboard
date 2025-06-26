{{-- Page Wrapper Layout --}}
@props([
    'title',
    'createRoute' => null,
    'createButtonText' => 'إضافة جديد',
    'breadcrumbs' => [],
    'showHeader' => true
])

@if($showHeader)
    @include('admin.partials.components.layouts.header-section', [
        'title' => $title,
        'createRoute' => $createRoute,
        'createButtonText' => $createButtonText,
        'breadcrumbs' => $breadcrumbs
    ])
@endif

@include('admin.partials.components.layouts.content-section')