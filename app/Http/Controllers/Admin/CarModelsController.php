<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarModelsModel;
use App\Models\CarMakesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\GenericListController;
use App\Services\TableConfigurationService;

class CarModelsController extends Controller
{
        use GenericListController;
    private const STATUS_ACTIVE = 1;
    private const STATUS_INACTIVE = 0;
    private const STATUS_DELETED = 2;

    public function list()
    {
        return view('admin.car_model.list', [
            'header_title' => 'قائمة موديلات السيارات',
            'getRecord' => CarModelsModel::getRecord()
        ]);
    }

    public function add()
    {
        return view('admin.car_model.add', [
            'header_title' => 'إضافة موديل جديد',
            'carMakes' => CarMakesModel::getAllCarMakes(),
        ]);
    }

    public function edit($id)
    {
        $car_model = CarModelsModel::getSingle($id);

        if (!$car_model) {
            return redirect()->route('admin.car_model.list')
                ->with('error', 'الموديل غير موجود');
        }

        return view('admin.car_model.edit', [
            'header_title' => 'تعديل الموديل',
            'getRecord' => $car_model,
            'carMakes' => CarMakesModel::getAllCarMakes()
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateCarModelsData($request);

        try {
            $car_model = new CarModelsModel();
            $this->fillCarModelsData($car_model, $validatedData, $request);
            $car_model->save();

            return $this->respondSuccess($request, 'تم إنشاء الموديل بنجاح', $car_model);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء الموديل');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateCarModelsData($request);

        try {
            Log::info('بيانات التعديل:', $validatedData);

            $car_model = CarModelsModel::getSingle($id);
            Log::info('بعد التحقق: ', ['car_model' => $car_model]);

            if (!$car_model) {
                return $this->respondNotFound($request, 'الموديل غير موجود');
            }

            $this->fillCarModelsData($car_model, $validatedData, $request);
            $car_model->save();

            return $this->respondSuccess($request, 'تم تحديث الموديل بنجاح', $car_model);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث الموديل');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $car_model = CarModelsModel::getSingle($id);

            if (!$car_model) {
                return response()->json([
                    'success' => false,
                    'message' => 'الموديل غير موجود'
                ], 404);
            }

            $car_model->update(['status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الموديل بنجاح',
                'redirect' => route('admin.car_model.list')
            ]);
        } catch (\Exception $e) {
            Log::error('CarModelsModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الموديل'
            ], 500);
        }
    }
        public function restore(Request $request, $id)
    {
        try {
            $discount_code = CarModelsModel::getSingle($id);

            if (!$discount_code) {
                return response()->json([
                    'success' => false,
                    'message' => ' العنصر غير موجود'
                ], 404);
            }

            $discount_code->update(['status' => self::STATUS_ACTIVE]);

            return response()->json([
                'success' => true,
                'message' => 'تم استعادة  العنصر بنجاح',
                'redirect' => route('admin.car_model.list')
            ]);
        } catch (\Exception $e) {
            Log::error('carMakesModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة  العنصر'
            ], 500);
        }
    }
    public function getByMake(Request $request)
    {
        $makeId = $request->get('make_id');

        \Log::info('getByMake called with make_id: ' . $makeId);

        if (!$makeId) {
            return response()->json(['models' => []]);
        }

        try {
            // Make sure the table name and column names are correct
            $models = \DB::table('car_models') // Use direct DB query for debugging
                ->where('make_id', $makeId)
                ->where('status', 1)
                ->select('model_id', 'name')
                ->get();

            \Log::info('Raw query result: ' . $models->toJson());

            return response()->json([
                'success' => true,
                'models' => $models
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getByMake: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateCarModelsData(Request $request)
    {
        return $request->validate([
            'model_name_ar' => 'required|string|max:255',
            'model_name_en' => 'required|string|max:255',
            'make_id' => 'required|exists:car_makes,make_id', // Changed from car_make_id to make_id
            'status' => 'required|in:0,1',
        ], [
            'model_name_ar.required' => 'اسم الموديل باللغة العربية مطلوب',
            'model_name_en.required' => 'اسم الموديل باللغة الإنجليزية مطلوب',
            'make_id.required' => 'اختيار الماركة مطلوب', // Updated field name
            'make_id.exists' => 'الماركة المحددة غير موجودة', // Updated field name
            'status.required' => 'حالة الموديل مطلوبة',
        ]);
    }

    private function fillCarModelsData($car_model, $validatedData, $request)
    {
        // Store car model name as array (this will be handled by the setNameAttribute mutator)
        $car_model->name = [
            'ar' => trim($validatedData['model_name_ar']),
            'en' => trim($validatedData['model_name_en'])
        ];

        $car_model->make_id = (int) $validatedData['make_id']; // Changed from car_make_id to make_id
        $car_model->status = (int) $validatedData['status'];
    }

    private function respondSuccess($request, $message, $car_model)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.car_model.list'),
                'data' => [
                    'model_id' => $car_model->model_id,
                    'name' => $car_model->name,
                    'make_id' => $car_model->make_id, // Changed from car_make_id to make_id
                    'status' => $car_model->status,
                    'name_ar' => $car_model->getCarModelNameLang('ar'),
                    'name_en' => $car_model->getCarModelNameLang('en'),
                    'display_name' => $car_model->getCarModelNameDisplayAttribute()
                ]
            ]);
        }

        return redirect()->route('admin.car_model.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.car_model.list')->with('error', $message);
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