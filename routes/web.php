<?php
// routes/web.php - Admin Panel Routes Only
?>
<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ProductByCarController;
use App\Http\Controllers\Admin\CarMakesController;
use App\Http\Controllers\Admin\CarModelsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubServiceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AuthController::class, 'login_admin'])->name('login');
    Route::post('/', [AuthController::class, 'auth_login_admin'])->name('authenticate');
    Route::get('logout', [AuthController::class, 'logout_admin'])->name('logout');

    // Admin forgot password
    Route::get('forgot-password', [AuthController::class, 'admin_forgot_password'])->name('forgot.password');
    Route::post('forgot-password', [AuthController::class, 'admin_send_reset'])->name('send.reset');
    Route::get('reset/{token}', [AuthController::class, 'admin_reset'])->name('reset');
    Route::post('reset/{token}', [AuthController::class, 'admin_auth_reset'])->name('auth.reset');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {




    Route::post('/set-flash-success', function (Request $request) {
        session()->flash('success', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashSuccess');

    // Error Message Route
    Route::post('/set-flash-error', function (Request $request) {
        session()->flash('error', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashError');

    // Warning Message Route
    Route::post('/set-flash-warning', function (Request $request) {
        session()->flash('warning', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashWarning');

    // Info Message Route
    Route::post('/set-flash-info', function (Request $request) {
        session()->flash('info', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashInfo');

    // Payment Error Route
    Route::post('/set-flash-payment-error', function (Request $request) {
        session()->flash('payment-error', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashPaymentError');

    // Primary Message Route
    Route::post('/set-flash-primary', function (Request $request) {
        session()->flash('primary', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashPrimary');

    // Secondary Message Route
    Route::post('/set-flash-secondary', function (Request $request) {
        session()->flash('secondary', $request->message);
        return response()->json(['status' => 'ok']);
    })->name('setFlashSecondary');

    // Validation Errors Route
    Route::post('/set-validation-errors', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'errors' => 'required|array',
            'errors.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
        }

        $errors = $request->errors;
        session()->flash('validation_errors', $errors);
        return response()->json(['status' => 'ok']);
    })->name('setValidationErrors');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Analytics & Reports
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');

    // Notifications
    Route::get('notification', [PageController::class, 'notification'])->name('notifications');
    Route::post('notifications/mark-read/{id}', [PageController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [PageController::class, 'markAllAsRead'])->name('notifications.mark-all-read');





    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    // Administrators
    Route::prefix('administrators')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'list'])->name('list');
        Route::get('create', [AdminController::class, 'add'])->name('create');
        Route::post('store', [AdminController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('{id}', [AdminController::class, 'update'])->name('update');
        Route::post('delete/{id}', [AdminController::class, 'delete'])->name('delete');
        Route::patch('{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Customers
    Route::prefix('customers')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'customerList'])->name('list');
        Route::get('create', [CustomerController::class, 'customerAdd'])->name('create');
        Route::post('store', [CustomerController::class, 'customerInsert'])->name('store');
        Route::get('{id}/edit', [CustomerController::class, 'customerEdit'])->name('edit');
        Route::put('{id}', [CustomerController::class, 'customerUpdate'])->name('update');
        Route::post('delete/{id}', [CustomerController::class, 'customerDelete'])->name('delete');
        Route::patch('{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('export', [CustomerController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | E-commerce Management
    |--------------------------------------------------------------------------
    */
    // Orders
    Route::prefix('orders')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'list'])->name('list');
        Route::get('{id}', [OrderController::class, 'orderDetail'])->name('detail');
        Route::get('{id}/debug', [OrderController::class, 'debugOrder'])->name('debug');
        Route::patch('{id}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::get('{id}/invoice', [OrderController::class, 'generateInvoice'])->name('invoice');
        Route::get('export', [OrderController::class, 'export'])->name('export');
        Route::get('statistics', [OrderController::class, 'statistics'])->name('statistics');
    });

    /*
    |--------------------------------------------------------------------------
    | Product Management
    |--------------------------------------------------------------------------
    *///
    // Car Makes
    Route::prefix('car_makes')->name('car_make.')->group(function () {
        Route::get('/', [CarMakesController::class, 'list'])->name('list');
        Route::get('create', [CarMakesController::class, 'add'])->name('create');
        Route::post('store', [CarMakesController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [CarMakesController::class, 'edit'])->name('edit');
        Route::put('{id}', [CarMakesController::class, 'update'])->name('update');
        Route::post('delete/{id}', [CarMakesController::class, 'delete'])->name('delete');

    });

    // Car Models
    Route::prefix('car_models')->name('car_model.')->group(function () {
        Route::get('/', [CarModelsController::class, 'list'])->name('list');
        Route::get('create', [CarModelsController::class, 'add'])->name('create');
        Route::post('store', [CarModelsController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [CarModelsController::class, 'edit'])->name('edit');
        Route::put('{id}', [CarModelsController::class, 'update'])->name('update');
        Route::post('delete/{id}', [CarModelsController::class, 'delete'])->name('delete');

    });


    // Services
    Route::prefix('services')->name('service.')->group(function () {
        Route::get('/', [ServiceController::class, 'list'])->name('list');
        Route::get('create', [ServiceController::class, 'add'])->name('create');
        Route::post('store', [ServiceController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('{id}', [ServiceController::class, 'update'])->name('update');
        Route::patch('{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('reorder', [ServiceController::class, 'reorder'])->name('reorder');
        Route::post('delete/{id}', [ServiceController::class, 'delete'])->name('delete');
        Route::post('change-status/{id}', [ServiceController::class, 'changeStatus'])->name('change-status');
        Route::post('restore/{id}', [ServiceController::class, 'restore'])->name('restore');
        Route::get('stats', [ServiceController::class, 'getStats'])->name('stats');
        Route::post('bulk-action', [ServiceController::class, 'bulkAction'])->name('bulk-action');
    });

    /*
    |--------------------------------------------------------------------------
    | AJAX API Routes (for dynamic data loading)
    |--------------------------------------------------------------------------
    */
    // Get sub-services by service ID
    Route::get('ajax/sub-services', [SubServiceController::class, 'getSubServices'])->name('ajax.sub_services');

    // Get car models by make ID  
    Route::get('ajax/car-models', [CarModelsController::class, 'getByMake'])->name('ajax.car_models');

    // Get products by car model
    Route::get('ajax/products-by-model', [ProductByCarController::class, 'getByModel'])->name('ajax.products_by_model');
    Route::get('/get-car-models', [ProductByCarController::class, 'getCarModels'])->name('get_car_models');

    // Update your existing Sub Services routes section:
// Sub Services
    Route::prefix('sub_services')->name('sub_service.')->group(function () {
        Route::get('/', [SubServiceController::class, 'list'])->name('list');
        Route::get('create', [SubServiceController::class, 'add'])->name('create');
        Route::post('store', [SubServiceController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [SubServiceController::class, 'edit'])->name('edit');
        Route::put('{id}', [SubServiceController::class, 'update'])->name('update');
        Route::post('delete/{id}', [SubServiceController::class, 'delete'])->name('delete');
        Route::patch('{id}/toggle-status', [SubServiceController::class, 'toggleStatus'])->name('toggle-status');
        // Remove the old get-by-service route as we have a better AJAX route above
    });


    // Product by Car Routes
    Route::prefix('product-by-car')->name('product_by_car.')->group(function () {
        Route::get('/', [ProductByCarController::class, 'list'])->name('list');
        Route::get('create', [ProductByCarController::class, 'add'])->name('create');
        Route::post('store', [ProductByCarController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [ProductByCarController::class, 'edit'])->name('edit');
        Route::put('{id}', [ProductByCarController::class, 'update'])->name('update');
        Route::post('delete/{id}', [ProductByCarController::class, 'delete'])->name('delete');
        Route::patch('{id}/toggle-status', [ProductByCarController::class, 'changeStatus'])->name('toggle-status');
        Route::post('get-by-model', [ProductByCarController::class, 'getProductsByModel'])->name('get-by-model');
        Route::post('bulk-action', [ProductByCarController::class, 'bulkAction'])->name('bulk_action');
        Route::get('stats', [ProductByCarController::class, 'getStats'])->name('stats');
    });



    /*
    |--------------------------------------------------------------------------
    | Marketing
    |--------------------------------------------------------------------------
    */
    // Discount Codes
    Route::prefix('discount-codes')->name('discount-codes.')->group(function () {
        Route::get('/', [DiscountCodeController::class, 'list'])->name('list');
        Route::get('create', [DiscountCodeController::class, 'add'])->name('create');
        Route::post('store', [DiscountCodeController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [DiscountCodeController::class, 'edit'])->name('edit');
        Route::put('{id}', [DiscountCodeController::class, 'update'])->name('update');
        Route::post('delete/{id}', [DiscountCodeController::class, 'delete'])->name('delete');
        Route::post('restore/{id}', [DiscountCodeController::class, 'restore'])->name('restore');
        Route::patch('{id}/toggle-status', [DiscountCodeController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('{id}/usage-report', [DiscountCodeController::class, 'usageReport'])->name('usage-report');
    });



    /*
    |--------------------------------------------------------------------------
    | System Management
    |--------------------------------------------------------------------------
    */
    // Shipping Charges
    Route::prefix('shipping-charges')->name('shipping_charge.')->group(function () {
        Route::get('/', [ShippingChargeController::class, 'list'])->name('list');
        Route::get('create', [ShippingChargeController::class, 'add'])->name('create');
        Route::post('store', [ShippingChargeController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [ShippingChargeController::class, 'edit'])->name('edit');
        Route::put('{id}', [ShippingChargeController::class, 'update'])->name('update');
        Route::delete('{id}', [ShippingChargeController::class, 'delete'])->name('delete');
        Route::patch('{id}/toggle-status', [ShippingChargeController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Partners
    Route::prefix('partners')->name('partner.')->group(function () {
        Route::get('/', [PartnerController::class, 'list'])->name('list');
        Route::get('create', [PartnerController::class, 'add'])->name('create');
        Route::post('store', [PartnerController::class, 'insert'])->name('store');
        Route::get('{id}/edit', [PartnerController::class, 'edit'])->name('edit');
        Route::put('{id}', [PartnerController::class, 'update'])->name('update');
        Route::delete('{id}', [PartnerController::class, 'delete'])->name('delete');
        Route::patch('{id}/toggle-status', [PartnerController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('reorder', [PartnerController::class, 'reorder'])->name('reorder');
    });

    // Contact Messages
    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('/', [PageController::class, 'contactUs'])->name('list');
        Route::get('{id}', [PageController::class, 'contactUsView'])->name('view');
        Route::delete('{id}', [PageController::class, 'contactUsDelete'])->name('delete');
        Route::patch('{id}/mark-read', [PageController::class, 'markContactAsRead'])->name('mark-read');
        Route::post('bulk-delete', [PageController::class, 'bulkDeleteContacts'])->name('bulk-delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('system', [PageController::class, 'systemSettings'])->name('system');
        Route::put('system', [PageController::class, 'updateSystemSettings'])->name('system.update');

        Route::get('homepage', [PageController::class, 'homeSettings'])->name('homepage');
        Route::put('homepage', [PageController::class, 'updateHomeSettings'])->name('homepage.update');

        Route::get('email', [PageController::class, 'smtpSettings'])->name('email');
        Route::put('email', [PageController::class, 'updateSmtpSettings'])->name('email.update');
        Route::post('email/test', [PageController::class, 'testEmailSettings'])->name('email.test');

        Route::get('payment', [PageController::class, 'paymentSettings'])->name('payment');
        Route::put('payment', [PageController::class, 'updatePaymentSettings'])->name('payment.update');

        Route::get('cache', [PageController::class, 'cacheSettings'])->name('cache');
        Route::post('cache/clear', [PageController::class, 'clearCache'])->name('cache.clear');

        Route::get('backup', [PageController::class, 'backupSettings'])->name('backup');
        Route::post('backup/create', [PageController::class, 'createBackup'])->name('backup.create');
        Route::get('backup/download/{file}', [PageController::class, 'downloadBackup'])->name('backup.download');
    });

    // Maintenance Mode
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [PageController::class, 'maintenanceMode'])->name('index');
        Route::post('toggle', [PageController::class, 'toggleMaintenance'])->name('toggle');
    });
});