{{-- resources/views/admin/car_make/partials/search-form.blade.php --}}
<form action="" method="get">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">البحث في ماركات السيارات</h3>
        </div>
        <div class="card-body" style="overflow:auto">
            <div class="row">
                @include('admin.partials.form-fields.number-input', [
                    'name' => 'make_id',
                    'label' => 'المعرف',
                    'placeholder' => 'معرف الماركة',
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.text-input', [
                    'name' => 'name',
                    'label' => 'اسم الماركة',
                    'placeholder' => 'اسم الماركة',
                    'colClass' => 'col-lg-3 col-md-4 col-sm-6 col-12',
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
                    'value' => request('status', ''), // Default to active (1) instead of empty
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.select-input', [
                    'name' => 'popularity_level',
                    'label' => 'مستوى الشهرة',
                    'options' => [
                        '' => 'جميع المستويات',
                        'highest' => '🏆 الاعلى شهرة (90+)',
                        'very_high' => '🥇 شهيرة جداً (80-89)',
                        'good' => '🥈 شهرة جيدة (70-79)',
                        'average' => '🥉 جيد (60-69)',
                        'acceptable' => '📊 مقبول (40-59)',
                        'low' => '📉 ضعيف (1-39)',
                        'undefined' => '❓ غير محدد (0)',
                    ],
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name' => 'popularity_min',
                    'label' => 'شهرة من',
                    'placeholder' => '0',
                    'min' => 0,
                    'max' => 100,
                    'colClass' => 'col-lg-4 col-md-3 col-sm-6 col-6',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name' => 'popularity_max',
                    'label' => 'شهرة إلى',
                    'placeholder' => '100',
                    'min' => 0,
                    'max' => 100,
                    'colClass' => 'col-lg-4 col-md-3 col-sm-6 col-6',
                ])

                @include('admin.partials.form-fields.action-buttons', [
                    'searchRoute' => null,
                    'resetRoute' => route('admin.car_make.list'),
                    'colClass' => 'col-lg-1 col-md-4 col-sm-6 col-4',
                ])
            </div>
        </div>
    </div>
</form>
