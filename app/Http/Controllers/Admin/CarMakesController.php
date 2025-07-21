<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarMakesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CarMakesController extends Controller
{
    private const UPLOAD_PATH = '/home/u798429560/domains/modwir.com/public_html/haytham_store/upload/cars/';
    private const STATUS_ACTIVE = 1;
    private const STATUS_INACTIVE = 0;
    private const STATUS_DELETED = 2;

    public function list()
    {
        return view('admin.car_make.list', [
            'header_title' => 'قائمة العلامات التجارية',
            'getRecord' => CarMakesModel::getRecord()
        ]);
    }

    public function add()
    {
        return view('admin.car_make.add', [
            'header_title' => 'إضافة علامة تجارية جديدة'
        ]);
    }

    public function edit($id)
    {
        $car_make = CarMakesModel::getSingle($id);

        if (!$car_make) {
            return redirect()->route('admin.car_make.list')
                ->with('error', 'العلامة التجارية غير موجودة');
        }

        return view('admin.car_make.edit', [
            'header_title' => 'تعديل العلامة التجارية',
            'getRecord' => $car_make
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateCarMakesData($request);

        try {
            $car_make = new CarMakesModel();
            $this->fillCarMakesData($car_make, $validatedData, $request);
            $car_make->save();

            return $this->respondSuccess($request, 'تم إنشاء العلامة التجارية بنجاح', $car_make);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء العلامة التجارية');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateCarMakesData($request);

        try {
            $car_make = CarMakesModel::getSingle($id);

            if (!$car_make) {
                return $this->respondNotFound($request, 'العلامة التجارية غير موجودة');
            }

            $this->fillCarMakesData($car_make, $validatedData, $request);
            $car_make->save();

            return $this->respondSuccess($request, 'تم تحديث العلامة التجارية بنجاح', $car_make);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث العلامة التجارية');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $car_make = CarMakesModel::getSingle($id);

            if (!$car_make) {
                return response()->json([
                    'success' => false,
                    'message' => 'العلامة التجارية غير موجودة'
                ], 404);
            }

            $car_make->update(['status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العلامة التجارية بنجاح',
                'redirect' => route('admin.car_make.list')
            ]);
        } catch (\Exception $e) {
            Log::error('CarMakesModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العلامة التجارية'
            ], 500);
        }
    }
    public function restore(Request $request, $id)
    {
        try {
            $discount_code = CarMakesModel::getSingle($id);

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
                'redirect' => route('admin.car_make.list')
            ]);
        } catch (\Exception $e) {
            Log::error('carMakesModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة  العنصر'
            ], 500);
        }
    }
    private function validateCarMakesData(Request $request)
    {
        return $request->validate([
            'make_name_ar' => 'required|string|max:255',
            'make_name_en' => 'required|string|max:255',
            'status' => 'required|in:0,1,2',
            'popularity' => 'nullable|integer|min:0|max:100',
            'make_logo' => 'nullable|file|mimes:jpeg,jpg,png,svg|max:5120',
        ], [
            'make_name_ar.required' => 'اسم العلامة التجارية باللغة العربية مطلوب',
            'make_name_en.required' => 'اسم العلامة التجارية باللغة الإنجليزية مطلوب',
            'status.required' => 'حالة العلامة التجارية مطلوبة',
            'popularity.integer' => 'مستوى الشهرة يجب أن يكون رقم',
            'popularity.min' => 'مستوى الشهرة يجب أن يكون أكبر من أو يساوي 0',
            'popularity.max' => 'مستوى الشهرة يجب أن يكون أقل من أو يساوي 100',
            'make_logo.mimes' => 'نوع الصورة يجب أن يكون: JPG, PNG, SVG فقط',
            'make_logo.max' => 'حجم الصورة يجب أن يكون أقل من 5 ميجابايت',
        ]);
    }

    private function fillCarMakesData($car_make, $validatedData, $request)
    {
        // Store car make name as array
        $car_make->name = [
            'ar' => trim($validatedData['make_name_ar']),
            'en' => trim($validatedData['make_name_en'])
        ];

        $car_make->status = (int) $validatedData['status'];
        $car_make->popularity = isset($validatedData['popularity']) ? (int) $validatedData['popularity'] : 0;

        // Handle logo upload with auto-generated name based on English name
        if ($request->hasFile('make_logo') && $request->file('make_logo')->isValid()) {
            // Delete old logo if updating
            if (isset($car_make->logo) && $car_make->logo) {
                $this->deleteImage($car_make->logo);
            }

            $car_make->logo = $this->uploadImage($request->file('make_logo'), $validatedData['make_name_en']);
        }
    }

    private function uploadImage($file, $englishName)
    {
        // Create slug from English name
        $slug = Str::slug($englishName, '-');

        // Get file extension
        $extension = $file->getClientOriginalExtension();

        // Generate filename: slug + timestamp + extension
        $fileName = $slug . '_' . time() . '.' . $extension;

        // Ensure upload directory exists
        if (!file_exists(self::UPLOAD_PATH)) {
            mkdir(self::UPLOAD_PATH, 0755, true);
        }

        // Move file
        $file->move(self::UPLOAD_PATH, $fileName);
        return $fileName;
    }

    private function deleteImage($fileName)
    {
        if ($fileName) {
            $imagePath = self::UPLOAD_PATH . $fileName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    private function respondSuccess($request, $message, $car_make)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.car_make.list'),
                'data' => [
                    'make_id' => $car_make->make_id,
                    'name' => $car_make->name,
                    'status' => $car_make->status,
                    'popularity' => $car_make->popularity
                ]
            ]);
        }

        return redirect()->route('admin.car_make.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.car_make.list')->with('error', $message);
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