#!/usr/bin/env python3
import os

def create_directory_if_not_exists(path):
    """Create directory if it doesn't exist"""
    if not os.path.exists(path):
        os.makedirs(path)
        print(f"Created directory: {path}")

def write_file(filepath, content):
    """Write content to file"""
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Created file: {filepath}")

def generate_car_model_files():
    # Base paths
    base_path = "resources/views/admin/car_model"
    partials_path = f"{base_path}/partials"
    
    # Create directories
    create_directory_if_not_exists(base_path)
    create_directory_if_not_exists(partials_path)
    
    # File contents
    files = {
        f"{base_path}/list.blade.php": """{{-- resources/views/admin/car_model/list.blade.php --}}

@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.page-header', [
        'title' => $pageConfig['title'],
        'createRoute' => $pageConfig['createRoute'] ?? null,
        'createButtonText' => $pageConfig['createButtonText'] ?? null,
    ])

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    {{-- Search Form --}}
                    @if (isset($pageConfig['searchFormView']))
                        @include($pageConfig['searchFormView'], $searchFormData ?? [])
                    @endif

                    {{-- Data Table --}}
                    @include('admin.partials.generic-data-table', [
                        'records' => $records,
                        'tableConfig' => $tableConfig,
                        'entityConfig' => $entityConfig
                    ])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @if (isset($pageConfig['scriptsView']))
        @include($pageConfig['scriptsView'])
    @endif
@endsection""",

        f"{partials_path}/search-form.blade.php": """{{-- resources/views/admin/car_model/partials/search-form.blade.php --}}
<form action="" method="get">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">البحث في موديلات السيارات</h3>
        </div>
        <div class="card-body" style="overflow:auto">
            <div class="row">
                @include('admin.partials.form-fields.number-input', [
                    'name' => 'model_id',
                    'label' => 'المعرف',
                    'placeholder' => 'معرف الموديل',
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.text-input', [
                    'name' => 'name',
                    'label' => 'اسم الموديل',
                    'placeholder' => 'اسم الموديل',
                    'colClass' => 'col-lg-3 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.select-input', [
                    'name' => 'make_id',
                    'label' => 'الماركة',
                    'options' => $carMakes ?? [],
                    'colClass' => 'col-lg-3 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name' => 'year_from',
                    'label' => 'سنة من',
                    'placeholder' => 'سنة البداية',
                    'colClass' => 'col-lg-2 col-md-3 col-sm-6 col-6',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name' => 'year_to',
                    'label' => 'سنة إلى',
                    'placeholder' => 'سنة النهاية',
                    'colClass' => 'col-lg-2 col-md-3 col-sm-6 col-6',
                ])

                @include('admin.partials.form-fields.select-input', [
                    'name' => 'status',
                    'label' => 'الحالة',
                    'options' => [
                        '' => 'جميع الحالات',
                        '1' => 'نشط',
                        '0' => 'غير نشط',
                        '2' => 'محذوف',
                    ],
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.action-buttons', [
                    'searchRoute' => null,
                    'resetRoute' => route('admin.car_model.list'),
                    'colClass' => 'col-lg-1 col-md-4 col-sm-6 col-4',
                ])
            </div>
        </div>
    </div>
</form>""",

        f"{partials_path}/model-image.blade.php": """{{-- resources/views/admin/car_model/partials/model-image.blade.php --}}
@php
    $size = $size ?? 'normal';
    $dimensions = $size === 'small' ? '50px' : '60px';
    $padding = $size === 'small' ? '3px' : '5px';
@endphp

@if ($record->getCarModelImage())
    <img src="{{ $record->getCarModelImage() }}"
        style="width: {{ $dimensions }}; height: {{ $dimensions }}; object-fit: contain; border-radius: 5px; background: 
#f8f9fa; padding: {{ $padding }};"
        alt="صورة الموديل">
@else
    <div style="width: {{ $dimensions }}; height: {{ $dimensions }}; background: 
#e9ecef; border-radius: 5px; display: flex; align-items: center; justify-content: center; {{ $size === 'small' ? 'margin: 0 auto;' : '' }}">
        <i class="fas fa-car text-muted"></i>
    </div>
@endif""",

        f"{partials_path}/mobile-card-header.blade.php": """{{-- resources/views/admin/car_model/partials/mobile-card-header.blade.php --}}
<div class="row">
    <div class="col-3 text-center">
        @include('admin.car_model.partials.model-image', ['record' => $record, 'size' => 'small'])
    </div>
    <div class="col-6">
        <h6 class="card-title mb-1">{{ $record->name }}</h6>
        <p class="text-muted small mb-1">{{ $record->carMake->name ?? 'غير محدد' }}</p>
        <p class="text-muted small mb-0">
            <i class="fas fa-hashtag"></i> {{ $record->model_id }}
        </p>
    </div>
    <div class="col-3 text-right">
        @include('admin.partials.status-badge', [
            'column' => ['statusField' => 'status'],
            'record' => $record,
            'mobile' => true
        ])
    </div>
</div>""",

        f"{partials_path}/mobile-card-body.blade.php": """{{-- resources/views/admin/car_model/partials/mobile-card-body.blade.php --}}
<div class="row mt-2">
    <div class="col-6">
        <small class="text-muted">سنة الإنتاج:</small>
        <div class="font-weight-bold">{{ $record->year ?? 'غير محدد' }}</div>
    </div>
    <div class="col-6">
        <small class="text-muted">نوع الوقود:</small>
        <div class="font-weight-bold">{{ $record->fuel_type ?? 'غير محدد' }}</div>
    </div>
</div>""",

        f"{partials_path}/action-buttons.blade.php": """{{-- resources/views/admin/car_model/partials/action-buttons.blade.php --}}
<div class="btn-group" role="group">
    <a href="{{ route('admin.car_model.edit', $record->model_id) }}" 
       class="btn btn-warning btn-sm" 
       title="تعديل">
        <i class="fas fa-edit"></i>
    </a>
    
    <a href="{{ route('admin.car_model.view', $record->model_id) }}" 
       class="btn btn-info btn-sm" 
       title="عرض">
        <i class="fas fa-eye"></i>
    </a>
    
    <button type="button" 
            class="btn btn-danger btn-sm delete-btn" 
            data-id="{{ $record->model_id }}"
            data-url="{{ route('admin.car_model.delete', $record->model_id) }}"
            title="حذف">
        <i class="fas fa-trash"></i>
    </button>
</div>""",

        f"{partials_path}/scripts.blade.php": """{{-- resources/views/admin/car_model/partials/scripts.blade.php --}}
<script>
$(document).ready(function() {
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const url = $(this).data('url');
        
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف هذا الموديل نهائياً!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('تم الحذف!', response.message, 'success');
                            location.reload();
                        } else {
                            Swal.fire('خطأ!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('خطأ!', 'حدث خطأ أثناء الحذف', 'error');
                    }
                });
            }
        });
    });
});
</script>"""
    }
    
    # Write all files
    for filepath, content in files.items():
        write_file(filepath, content)
    
    # Generate controller configuration
    controller_config = """
// Add this to app/Services/TableConfigurationService.php

public static function getCarModelConfig()
{
    return [
        'pageConfig' => [
            'title' => 'قائمة موديلات السيارات',
            'createRoute' => 'admin.car_model.create',
            'createButtonText' => 'إضافة موديل جديد',
            'searchFormView' => 'admin.car_model.partials.search-form',
            'scriptsView' => 'admin.car_model.partials.scripts',
        ],
        'tableConfig' => [
            'title' => 'قائمة موديلات السيارات',
            'columns' => [
                [
                    'header' => '#',
                    'field' => 'model_id',
                    'type' => 'text',
                ],
                [
                    'header' => 'الصورة',
                    'type' => 'component',
                    'component' => 'admin.car_model.partials.model-image',
                ],
                [
                    'header' => 'اسم الموديل',
                    'field' => 'name',
                    'type' => 'text',
                    'showInMobile' => true,
                ],
                [
                    'header' => 'الماركة',
                    'type' => 'callback',
                    'callback' => function($record) {
                        return $record->carMake->name ?? 'غير محدد';
                    },
                    'showInMobile' => true,
                ],
                [
                    'header' => 'سنة الإنتاج',
                    'field' => 'year',
                    'type' => 'text',
                    'showInMobile' => true,
                ],
                [
                    'header' => 'نوع الوقود',
                    'field' => 'fuel_type',
                    'type' => 'text',
                    'showInMobile' => true,
                ],
                [
                    'header' => 'الحالة',
                    'type' => 'component',
                    'component' => 'admin.partials.status-badge',
                    'showInMobile' => true,
                ],
                [
                    'header' => 'الإجراء',
                    'type' => 'actions',
                    'component' => 'admin.car_model.partials.action-buttons',
                ],
            ]
        ],
        'entityConfig' => [
            'icon' => 'fas fa-car-side',
            'emptyMessage' => 'لا توجد موديلات سيارات متاحة حالياً.',
            'titleField' => 'name',
            'mobileCardHeader' => 'admin.car_model.partials.mobile-card-header',
            'mobileCardBody' => 'admin.car_model.partials.mobile-card-body',
        ]
    ];
}
"""
    
    # Generate controller method
    controller_method = """
// Add this to your CarModelController.php

use App\\Traits\\GenericListController;
use App\\Services\\TableConfigurationService;

class CarModelController extends Controller
{
    use GenericListController;

    public function list(Request $request)
    {
        $records = $this->getCarModelRecords($request);
        
        // Get car makes for search form
        $carMakes = CarMake::where('status', 1)
            ->pluck('name', 'make_id')
            ->prepend('جميع الماركات', '');
        
        $searchFormData = compact('carMakes');
        
        return $this->renderGenericList($records, 'getCarModelConfig', compact('searchFormData'));
    }

    private function getCarModelConfig()
    {
        return TableConfigurationService::getCarModelConfig();
    }

    private function getCarModelRecords(Request $request)
    {
        $query = CarModel::with('carMake');

        // Apply filters
        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('make_id')) {
            $query->where('make_id', $request->make_id);
        }

        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('name')
                    ->paginate(50)
                    ->appends($request->all());
    }
}
"""
    
    print("\n" + "="*50)
    print("Car Model files generated successfully!")
    print("="*50)
    print("\nController Configuration:")
    print(controller_config)
    print("\nController Method:")
    print(controller_method)
    print("\n" + "="*50)
    print("Next steps:")
    print("1. Add the getCarModelConfig() method to TableConfigurationService")
    print("2. Update your CarModelController with the new list method")
    print("3. Make sure your CarModel model has the getCarModelImage() method")
    print("4. Ensure CarModel has a carMake relationship")
    print("="*50)

if __name__ == "__main__":
    generate_car_model_files()