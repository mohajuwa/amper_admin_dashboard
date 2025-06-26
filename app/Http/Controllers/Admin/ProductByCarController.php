<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarMakesModel;
use App\Models\CarModelsModel;
use App\Models\ProductByCar;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductByCarController extends Controller
{
    public function list()
    {
        return view('admin.product_by_car.list', [
            'header_title' => 'المنتجات حسب السيارة',
            'getServices' => Service::getAllServices(),
            'getCarMakes' => CarMakesModel::getAllCarMakes(), // Fixed: was getting services instead of car makes
            'getRecord' => ProductByCar::getRecord()

        ]);
    }

    public function add()
    {
        return view('admin.product_by_car.add', [
            'header_title' => 'إضافة منتج حسب السيارة',
            'getServices' => Service::getAllServices(),
            'getCarMakes' => CarMakesModel::getAllCarMakes(),
        ]);
    }

    public function edit($id)
    {
        $productByCar = ProductByCar::getSingle($id);

        if (!$productByCar) {
            return redirect()->route('admin.product_by_car.list')
                ->with('error', 'المنتج غير موجود');
        }

        // Get car models for the selected make (if carModel relationship exists)
        $carModels = [];
        if ($productByCar->carModel && $productByCar->carModel->make_id) {
            $carModels = CarModelsModel::where('make_id', $productByCar->carModel->make_id)
                ->where('status', 0) // assuming 0 is active status
                ->get();
        }

        return view('admin.product_by_car.edit', [
            'header_title' => 'تعديل المنتج حسب السيارة',
            'getRecord' => $productByCar,
            'getServices' => Service::getAllServices(),
            'getCarMakes' => CarMakesModel::getAllCarMakes(),
            'getCarModels' => $carModels,
            'getSubServices' => SubService::getRecordByService($productByCar->service_id)
        ]);
    }
    public function insert(Request $request)
    {
        $validatedData = $this->validateProductByCarData($request);

        try {
            $productByCar = ProductByCar::create($validatedData);
            return $this->respondSuccess($request, 'تم إنشاء المنتج بنجاح', $productByCar);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء المنتج');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateProductByCarData($request);

        try {
            $productByCar = ProductByCar::getSingle($id);

            if (!$productByCar) {
                return $this->respondNotFound($request, 'المنتج غير موجود');
            }

            $productByCar->update($validatedData);
            return $this->respondSuccess($request, 'تم تحديث المنتج بنجاح', $productByCar);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث المنتج');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $productByCar = ProductByCar::getSingle($id);

            if (!$productByCar) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود'
                ], 404);
            }

            $productByCar->update(['status' => ProductByCar::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('ProductByCar deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج'
            ], 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:0,1,2']);

        try {
            $productByCar = ProductByCar::getSingle($id);

            if (!$productByCar) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود'
                ], 404);
            }

            $productByCar->update(['status' => (int) $request->status]);

            $statusText = [
                ProductByCar::STATUS_ACTIVE => 'نشط',
                ProductByCar::STATUS_INACTIVE => 'غير نشط',
                ProductByCar::STATUS_DELETED => 'محذوف'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة المنتج إلى: ' . $statusText[$request->status],
                'data' => [
                    'product_id' => $productByCar->product_id,
                    'status' => $productByCar->status,
                    'status_text' => $statusText[$request->status]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('ProductByCar status change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة المنتج'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:product_by_car,product_id'
        ]);

        try {
            $statusMap = [
                'activate' => ProductByCar::STATUS_ACTIVE,
                'deactivate' => ProductByCar::STATUS_INACTIVE,
                'delete' => ProductByCar::STATUS_DELETED
            ];

            $count = ProductByCar::whereIn('product_id', $request->ids)
                ->update(['status' => $statusMap[$request->action]]);

            $messages = [
                'activate' => "تم تفعيل {$count} منتج بنجاح",
                'deactivate' => "تم إلغاء تفعيل {$count} منتج بنجاح",
                'delete' => "تم حذف {$count} منتج بنجاح"
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$request->action]
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk action error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية'
            ], 500);
        }
    }

    /**
     * Get car models based on selected make (AJAX)
     */
    public function getCarModels(Request $request)
    {
        $makeId = $request->get('make_id');

        // Add logging for debugging
        \Log::info('getCarModels called with make_id: ' . $makeId);

        if (!$makeId) {
            \Log::info('No make_id provided');
            return response()->json(['models' => []]);
        }

        try {
            // Get models for the selected make
            $models = CarModelsModel::where('make_id', $makeId)
                ->where('status', 1) // Active models only
                ->get(['model_id', 'name']); // Make sure these column names are correct

            \Log::info('Found models count: ' . $models->count());
            \Log::info('Models data: ' . $models->toJson());

            return response()->json([
                'success' => true,
                'models' => $models
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching car models: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch models',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sub-services based on selected service (AJAX)
     */
    public function getSubServices(Request $request)
    {
        $serviceId = $request->get('service_id');

        if (!$serviceId) {
            return response()->json(['sub_services' => []]);
        }

        try {
            // Get sub-services for the selected service
            $subServices = SubService::where('service_id', $serviceId)
                ->where('status', 0) // Active sub-services only
                ->get(['sub_service_id', 'name']);

            return response()->json(['sub_services' => $subServices]);
        } catch (\Exception $e) {
            Log::error('Error fetching sub-services: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch sub-services'], 500);
        }
    }

    private function validateProductByCarData(Request $request)
    {
        return $request->validate([
            'make_id' => 'required|exists:car_makes,make_id',
            'model_id' => 'required|exists:car_models,model_id',
            'service_id' => 'required|exists:services,service_id',
            'sub_service_id' => 'required|exists:sub_services,sub_service_id',
            'year' => 'required|integer|min:1900|max:2030',
            'status' => 'required|in:0,1,2'
        ], [
            'make_id.required' => 'ماركة السيارة مطلوبة',
            'make_id.exists' => 'ماركة السيارة غير موجودة',
            'model_id.required' => 'طراز السيارة مطلوب',
            'model_id.exists' => 'طراز السيارة غير موجود',
            'service_id.required' => 'الخدمة مطلوبة',
            'service_id.exists' => 'الخدمة غير موجودة',
            'sub_service_id.required' => 'الخدمة الفرعية مطلوبة',
            'sub_service_id.exists' => 'الخدمة الفرعية غير موجودة',
            'year.required' => 'يجب اختيار سنة صنع السيارة',
            'year.integer' => 'السنة يجب أن تكون رقم',
            'year.min' => 'السنة يجب أن تكون أكبر من 1900',
            'year.max' => 'السنة يجب أن تكون أصغر من 2030',
            'status.required' => 'حالة المنتج مطلوبة',
            'status.in' => 'حالة المنتج غير صالحة'
        ]);
    }

    private function respondSuccess($request, $message, $productByCar)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.product_by_car.list'),
                'data' => [
                    'product_id' => $productByCar->product_id,
                    'make_id' => $productByCar->make_id ?? null,
                    'model_id' => $productByCar->model_id,
                    'service_id' => $productByCar->service_id,
                    'sub_service_id' => $productByCar->sub_service_id,
                    'year' => $productByCar->year,
                    'status' => $productByCar->status
                ]
            ]);
        }

        return redirect()->route('admin.product_by_car.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.product_by_car.list')->with('error', $message);
    }

    private function handleError($request, $exception, $message)
    {
        Log::error($message . ': ' . $exception->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }
}