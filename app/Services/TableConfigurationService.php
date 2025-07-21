<?php 
// app/Services/TableConfigurationService.php

namespace App\Services;

class TableConfigurationService
{
    public static function getCarMakeConfig()
    {
        return [
            'pageConfig' => [
                'title' => 'قائمة ماركات السيارات',
                'createRoute' => route('admin.car_make.create'),
                'createButtonText' => 'إضافة ماركة جديدة',
                'searchFormView' => 'admin.car_make.partials.search-form',
                'scriptsView' => 'admin.car_make.partials.scripts',
                'view' => 'admin.partials.generic-list' // Default view
            ],
            'tableConfig' => [
                'title' => 'قائمة ماركات السيارات',
                'columns' => [
                    [
                        'header' => '#',
                        'field' => 'make_id',
                        'type' => 'text',
                    ],
                    [
                        'header' => 'الصورة',
                        'type' => 'component',
                        'component' => 'admin.car_make.partials.logo-image',
                    ],
                    [
                        'header' => 'اسم الماركة',
                        'type' => 'callback',
                        'callback' => function ($record) {
                            $nameData = app('App\Services\CarMakeNameService')->getNameData($record->getAttributes()['name']);
                            return $nameData['ar'];
                        }
                    ],
                    [
                        'header' => 'اسم الماركة إنجليزي',
                        'type' => 'callback',
                        'callback' => function ($record) {
                            $nameData = app('App\Services\CarMakeNameService')->getNameData($record->getAttributes()['name']);
                            return $nameData['en'];
                        }
                    ],
                    [
                        'header' => 'الحالة',
                        'type' => 'component',
                        'component' => 'admin.partials.status-badge',
                        'showInMobile' => true,
                    ],
                    [
                        'header' => 'مستوى الشهرة',
                        'type' => 'component',
                        'component' => 'admin.car_make.partials.popularity-badge',
                        'showInMobile' => true,
                        'data' => function ($record) {
                            return self::getPopularityData($record);
                        }
                    ],
                    [
                        'header' => 'الإجراء',
                        'type' => 'actions',
                        'component' => 'admin.car_make.partials.action-buttons',
                    ],
                ]
            ],
            'entityConfig' => [
                'icon' => 'fas fa-car',
                'emptyMessage' => 'لا توجد ماركات سيارات متاحة حالياً.',
                'titleField' => 'name',
                'mobileCardHeader' => 'admin.car_make.partials.mobile-card-header',
                'mobileCardBody' => 'admin.car_make.partials.mobile-card-body',
            ]
        ];
    }

    private static function getPopularityData($record)
    {
        $popularity = $record->popularity ?? 0;

        if ($popularity >= 80) {
            return [
                'popularity' => $popularity,
                'badgeClass' => 'badge-success',
                'icon' => '🔥',
                'label' => 'عالية جداً'
            ];
        } elseif ($popularity >= 60) {
            return [
                'popularity' => $popularity,
                'badgeClass' => 'badge-warning',
                'icon' => '⭐',
                'label' => 'عالية'
            ];
        } elseif ($popularity >= 40) {
            return [
                'popularity' => $popularity,
                'badgeClass' => 'badge-info',
                'icon' => '📈',
                'label' => 'متوسطة'
            ];
        } else {
            return [
                'popularity' => $popularity,
                'badgeClass' => 'badge-secondary',
                'icon' => '📉',
                'label' => 'منخفضة'
            ];
        }
    }

    public static function getCarModelConfig()
    {
        return [
            'pageConfig' => [
                'title' => 'إدارة موديلات السيارات',
                'createRoute' => route('admin.car_model.create'),
                'createButtonText' => 'إضافة موديل جديد',
                'searchFormView' => 'admin.car_model.partials.search-form',
                'scriptsView' => 'admin.car_model.partials.scripts',
                'view' => 'admin.partials.generic-list',
                'breadcrumb' => [
                    'الرئيسية' => route('admin.dashboard'),
                    'موديلات السيارات' => ''
                ]
            ],
            'tableConfig' => [
                'title' => 'قائمة موديلات السيارات',
                'columns' => [
                    [
                        'header' => 'ID',
                        'field' => 'model_id',
                        'type' => 'text',
                        'showInMobile' => false
                    ],
                    [
                        'header' => 'اسم الموديل',
                        'type' => 'callback',
                        'callback' => function ($record) {
                            return $record->getCarModelNameDisplayAttribute();
                        },
                        'showInMobile' => true
                    ],
                    [
                        'header' => 'الماركة',
                        'type' => 'callback',
                        'callback' => function ($record) {
                            return $record->carMake ? $record->carMake->getCarMakeNameDisplayAttribute() : 'غير محدد';
                        },
                        'showInMobile' => true
                    ],
                    [
                        'header' => 'السنة',
                        'field' => 'year',
                        'type' => 'text',
                        'showInMobile' => false
                    ],
                    [
                        'header' => 'الحالة',
                        'type' => 'callback',
                        'callback' => function ($record) {
                            return $record->status == 1 
                                ? '<span class="badge badge-success">نشط</span>' 
                                : '<span class="badge badge-secondary">غير نشط</span>';
                        },
                        'showInMobile' => true
                    ],
                    [
                        'header' => 'الإجراءات',
                        'type' => 'actions',
                        'component' => 'admin.car_model.partials.action-buttons'
                    ]
                ]
            ],
            'entityConfig' => [
                'name' => 'car_model',
                'singular' => 'موديل',
                'plural' => 'موديلات',
                'icon' => 'fas fa-car-side',
                'emptyMessage' => 'لا توجد موديلات متاحة حالياً.',
                'titleField' => 'name',
                'mobileCardHeader' => 'admin.car_model.partials.mobile-card-header',
                'mobileCardBody' => 'admin.car_model.partials.mobile-card-body',
                'routes' => [
                    'add' => 'admin.car_model.create',
                    'edit' => 'admin.car_model.edit',
                    'delete' => 'admin.car_model.delete'
                ]
            ]
        ];
    }
}