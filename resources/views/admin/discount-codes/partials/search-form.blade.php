{{-- resources/views/admin/discount-codes/partials/search-form.blade.php --}}
<form action="" method="get">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">البحث في أكواد الخصم</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @include('admin.partials.form-fields.number-input', [
                    'name'       => 'coupon_id',
                    'label'      => 'المعرّف',
                    'placeholder'=> 'معرّف الكود',
                    'colClass'   => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.text-input', [
                    'name'       => 'coupon_name',
                    'label'      => 'اسم الكود',
                    'placeholder'=> 'اسم كود الخصم',
                    'colClass'   => 'col-lg-3 col-md-4 col-sm-6 col-12',
                ])

                {{-- الحالة --}}
                @include('admin.partials.form-fields.select-input', [
                    'name'     => 'status',
                    'label'    => 'الحالة',
                    'options'  => [
                        ''          => 'جميع الحالات',
                        'active'    => 'نشط',
                        'expired'   => 'منتهي الصلاحية',
                        'inactive'  => 'غير نشط',
                        'deleted'   => 'محذوف',
                    ],
                    'colClass' => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name'       => 'discount_min',
                    'label'      => 'نسبة الخصم من',
                    'placeholder'=> '0',
                    'min'        => 0,
                    'max'        => 100,
                    'step'       => 0.1,
                    'colClass'   => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.number-input', [
                    'name'       => 'discount_max',
                    'label'      => 'نسبة الخصم إلى',
                    'placeholder'=> '100',
                    'min'        => 0,
                    'max'        => 100,
                    'step'       => 0.1,
                    'colClass'   => 'col-lg-2 col-md-4 col-sm-6 col-12',
                ])

                @include('admin.partials.form-fields.action-buttons', [
                    'searchRoute'=> null,
                    'resetRoute' => route('admin.discount-codes.list'),
                    'colClass'   => 'col-lg-1 col-md-4 col-sm-6 col-4',
                ])
            </div>
        </div>
    </div>
</form>
