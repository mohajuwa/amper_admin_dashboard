{{-- resources/views/admin/discount-codes/list.blade.php --}}


@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'قائمة أكواد الخصم',
        'createRoute' => route('admin.discount-codes.create'),
        'createButtonText' => 'إضافة كود خصم جديد',
    ])

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.discount-codes.partials.search-form')
                    @include('admin.discount-codes.partials.data-table', ['records' => $getRecord])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @include('admin.discount-codes.partials.scripts')
@endsection
