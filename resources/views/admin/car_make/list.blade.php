{{-- resources/views/admin/car_make/list.blade.php --}}

@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'قائمة ماركات السيارات',
        'createRoute' => route('admin.car_make.create'),
        'createButtonText' => 'إضافة ماركة جديدة',
    ])

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.car_make.partials.search-form')
                    @include('admin.car_make.partials.data-table', ['records' => $getRecord])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.car_make.partials.scripts')
@endsection