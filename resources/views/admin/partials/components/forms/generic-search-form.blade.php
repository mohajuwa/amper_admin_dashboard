{{-- Generic Search Form --}}
@props([
    'fields' => [],
    'action' => '',
    'method' => 'get',
    'title' => 'البحث',
    'resetRoute' => null,
    'cardClass' => 'card card-info',
    'bodyClass' => 'card-body',
    'submitText' => 'بحث',
    'resetText' => 'إعادة تعيين'
])

<form action="{{ $action }}" method="{{ strtolower($method) }}">
    @if(strtolower($method) !== 'get')
        @csrf
    @endif
    
    <div class="{{ $cardClass }}">
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
        <div class="{{ $bodyClass }}">
            <div class="row">
                @foreach($fields as $field)
                    @include('admin.partials.form-fields.' . ($field['type'] ?? 'text-input'), $field)
                @endforeach
                
                {{-- Action Buttons --}}
                <div class="col-lg-1 col-md-4 col-sm-6 col-4 d-flex align-items-center justify-content-center">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-search"></i> {{ $submitText }}
                        </button>
                        @if($resetRoute)
                            <a href="{{ $resetRoute }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-redo"></i> {{ $resetText }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>