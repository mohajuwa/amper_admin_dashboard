{{-- Header Section --}}
@props([
    'title',
    'createRoute' => null,
    'createButtonText' => 'إضافة جديد',
    'breadcrumbs' => []
])

<section class="content-header">
    <div class="container-fluid">
        {{-- Breadcrumbs --}}
        @if(!empty($breadcrumbs))
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $breadcrumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active">{{ $breadcrumb['text'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif
        
        {{-- Title and Action Button --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="m-0">{{ $title }}</h1>
            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> {{ $createButtonText }}
                </a>
            @endif
        </div>
    </div>
</section>